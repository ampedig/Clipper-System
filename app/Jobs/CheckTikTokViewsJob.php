<?php

namespace App\Jobs;

use App\Models\ClipSubmission;
use App\Models\User;
use App\Services\TikTokScraperService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class CheckTikTokViewsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Jumlah maksimal percobaan ulang jika gagal.
     *
     * @var int
     */
    public $tries = 3;

    protected $submission;

    /**
     * Create a new job instance.
     */
    public function __construct(ClipSubmission $submission)
    {
        $this->submission = $submission;
    }

    /**
     * Execute the job.
     */
    public function handle(TikTokScraperService $scraperService): void
    {
        // Pastikan campaign masih aktif, jika tidak, batalkan proses
        $campaign = $this->submission->clipCampaign;
        if ($campaign->status->value !== 'active' || ($campaign->end_at && $campaign->end_at->isPast())) {
            return;
        }

        // Jalankan Scraper
        $stats = $scraperService->getVideoStats($this->submission->submitted_url);

        if (! $stats) {
            // Jika scraper gagal (return null), lemparkan exception agar di-retry oleh Queue
            throw new \Exception("Gagal mengambil data views untuk submission ID: {$this->submission->id}");
        }

        $newCurrentViews = $stats['views'];

        // 1. Tentukan Effective Views (capping jika ada view_max)
        $effectiveViews = $campaign->view_max
            ? min($newCurrentViews, $campaign->view_max)
            : $newCurrentViews;

        // 2. Hitung total views yang berhak dicairkan (sepanjang waktu)
        $eligibleCreditedViews = floor($effectiveViews / $campaign->view_threshold) * $campaign->view_threshold;

        // 3. Hitung selisih views yang belum pernah dibayar
        $deltaViews = $eligibleCreditedViews - $this->submission->credited_views;

        // 4. Guard Clause: Jika tidak ada kelipatan threshold baru yang tercapai
        if ($deltaViews <= 0) {
            $this->submission->update(['current_views' => $newCurrentViews]);

            return;
        }

        // 5. Hitung Nominal Komisi untuk delta views ini
        $earnedAmount = ($deltaViews / $campaign->view_threshold) * $campaign->commission_amount;

        // 6. DB Transaction untuk konsistensi finansial
        DB::transaction(function () use ($newCurrentViews, $eligibleCreditedViews, $deltaViews, $earnedAmount, $campaign) {
            // Lock record user untuk mencegah race condition
            $user = User::where('id', $this->submission->user_id)->lockForUpdate()->first();

            // Insert catatan mutasi dompet
            $user->walletTransactions()->create([
                'type' => 'credit',
                'amount' => $earnedAmount,
                'balance_before' => $user->balance,
                'balance_after' => $user->balance + $earnedAmount,
                'notes' => 'Komisi +'.number_format($deltaViews, 0, ',', '.')." views - {$campaign->title}",
            ]);

            // Update saldo user
            $user->increment('balance', $earnedAmount);

            // Update status submission
            $this->submission->update([
                'current_views' => $newCurrentViews,
                'credited_views' => $eligibleCreditedViews,
                'total_earned' => $this->submission->total_earned + $earnedAmount,
            ]);

            // Kirim notifikasi Telegram
            $pesan = "💰 <b>Komisi Baru Cair!</b>\n"
                   ."User: {$user->name}\n"
                   .'Nominal: <b>Rp'.number_format($earnedAmount, 0, ',', '.')."</b>\n"
                   ."Klip ID: #{$this->submission->id} ({$campaign->title})";

            SendTelegramMessageJob::dispatch($pesan, config('telegram.topics.commission'));
        });
    }
}
