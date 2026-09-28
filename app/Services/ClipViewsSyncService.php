<?php

namespace App\Services;

use App\Enums\CampaignStatus;
use App\Jobs\SendTelegramMessageJob;
use App\Models\ClipSubmission;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ClipViewsSyncService
{
    /**
     * Injeksi dependency TikTokScraperService.
     */
    public function __construct(
        protected TikTokScraperService $scraperService
    ) {}

    /**
     * Melakukan sinkronisasi views TikTok dan menghitung komisi secara otomatis.
     *
     * @return array{success: bool, message: string, current_views?: int, credited_views?: int, delta_views?: int, earned_now?: int, total_earned?: int}
     */
    public function sync(ClipSubmission $submission): array
    {
        Log::info("ClipViewsSyncService: Memulai pengecekan views untuk Submission ID {$submission->id}...");

        // 1. Guard Clause: Pastikan status submission aktif
        if (! in_array($submission->status, ['active', 'approved'], true)) {
            Log::warning("ClipViewsSyncService: Dibatalkan (Status {$submission->status}) - Submission ID {$submission->id}.");

            return [
                'success' => false,
                'message' => 'Pengecekan views hanya dapat dilakukan pada pengajuan yang berstatus aktif.',
            ];
        }

        // 2. Guard Clause: Pastikan campaign valid, aktif, sudah dimulai, dan belum berakhir
        $campaign = $submission->clipCampaign;
        if (! $campaign
            || $campaign->status !== CampaignStatus::Active
            || ($campaign->end_at && $campaign->end_at->isPast())
            || ($campaign->start_at && $campaign->start_at->isFuture())
        ) {
            Log::warning("ClipViewsSyncService: Dibatalkan (Campaign tidak valid/di luar masa tayang) - Submission ID {$submission->id}.");

            return [
                'success' => false,
                'message' => 'Campaign untuk submission ini sudah tidak aktif atau di luar masa tayang.',
            ];
        }

        // 3. Scraping data penayangan video TikTok
        $stats = $this->scraperService->getVideoStats($submission->submitted_url);
        if (! $stats) {
            Log::error("ClipViewsSyncService: Gagal scraping TikTok URL {$submission->submitted_url} - Submission ID {$submission->id}.");

            return [
                'success' => false,
                'message' => 'Gagal mengambil data views dari TikTok. Pastikan video berstatus publik dan tautan dapat diakses.',
            ];
        }

        $newCurrentViews = (int) ($stats['views'] ?? 0);
        Log::info("ClipViewsSyncService: Views ditemukan = {$newCurrentViews} untuk Submission ID {$submission->id}.");

        // 4. Kalkulasi Effective Views dengan batas atas capping (view_max) jika ditentukan
        $effectiveViews = $campaign->view_max
            ? min($newCurrentViews, (int) $campaign->view_max)
            : $newCurrentViews;

        // Cek apakah mencapai view_max, jika ya set flag completed
        $isCompleted = false;
        if ($campaign->view_max && $effectiveViews >= (int) $campaign->view_max) {
            $isCompleted = true;
            Log::info("ClipViewsSyncService: Target view_max tercapai ({$effectiveViews}) untuk Submission ID {$submission->id}.");
        }

        // 5. Kalkulasi views yang berhak dicairkan berdasarkan kelipatan threshold
        $threshold = max(1, (int) $campaign->view_threshold);
        $eligibleCreditedViews = (int) (floor($effectiveViews / $threshold) * $threshold);

        // 6. Hitung selisih views yang belum pernah dikreditkan sebelumnya
        $deltaViews = $eligibleCreditedViews - (int) $submission->credited_views;

        // 7. Jika tidak ada kelipatan threshold baru yang tercapai, cukup perbarui current_views
        if ($deltaViews <= 0) {
            $updateData = ['current_views' => $newCurrentViews];
            if ($isCompleted) {
                $updateData['status'] = 'completed';
            }
            $submission->update($updateData);

            Log::info("ClipViewsSyncService: Selesai tanpa komisi baru untuk Submission ID {$submission->id}.");

            return [
                'success' => true,
                'message' => 'Data views berhasil disinkronisasi. Belum ada penambahan komisi baru.',
                'current_views' => $newCurrentViews,
                'credited_views' => (int) $submission->credited_views,
                'delta_views' => 0,
                'earned_now' => 0,
                'total_earned' => (int) $submission->total_earned,
            ];
        }

        // 8. Hitung nominal komisi baru (strictly whole Rupiah integer)
        $commissionRate = (int) $campaign->commission_amount;
        $earnedAmount = (int) (($deltaViews / $threshold) * $commissionRate);

        // 9. Eksekusi database transaction dengan lock row user untuk konsistensi finansial
        DB::transaction(function () use ($submission, $newCurrentViews, $eligibleCreditedViews, $deltaViews, $earnedAmount, $campaign, $isCompleted): void {
            $user = User::where('id', $submission->user_id)->lockForUpdate()->first();

            if (! $user) {
                return;
            }

            // Catat riwayat mutasi dompet kredit
            $user->walletTransactions()->create([
                'type' => 'credit',
                'amount' => $earnedAmount,
                'balance_before' => (int) $user->balance,
                'balance_after' => (int) ($user->balance + $earnedAmount),
                'notes' => 'Komisi +'.number_format($deltaViews, 0, ',', '.')." views - {$campaign->title}",
            ]);

            // Update saldo user
            $user->increment('balance', $earnedAmount);

            // Update data metrik submission
            $updateData = [
                'current_views' => $newCurrentViews,
                'credited_views' => $eligibleCreditedViews,
                'total_earned' => (int) ($submission->total_earned + $earnedAmount),
            ];

            if ($isCompleted) {
                $updateData['status'] = 'completed';
            }

            $submission->update($updateData);

            // Kirim notifikasi pencairan komisi ke topic Telegram
            $formattedAmount = number_format($earnedAmount, 0, ',', '.');
            $formattedViews = number_format($deltaViews, 0, ',', '.');
            $formattedBalance = number_format((int) $user->balance, 0, ',', '.');

            $pesan = "💸 <b>KOMISI BERHASIL DICAIRKAN</b> 💸\n"
                   ."━━━━━━━━━━━━━━━━━━━━\n"
                   ."👤 <b>User:</b> {$user->name}\n"
                   ."💰 <b>Nominal:</b> <b>Rp{$formattedAmount}</b>\n"
                   ."📈 <b>Penambahan Views:</b> +{$formattedViews} views\n"
                   ."🎬 <b>Campaign:</b> {$campaign->title} (Klip #{$submission->id})\n"
                   ."💳 <b>Saldo Dompet:</b> Rp{$formattedBalance}";

            SendTelegramMessageJob::dispatch($pesan, config('telegram.topics.commission'));
        });

        $submission->refresh();

        Log::info("ClipViewsSyncService: Selesai dengan pencairan Rp{$earnedAmount} untuk Submission ID {$submission->id}.");

        return [
            'success' => true,
            'message' => 'Views berhasil disinkronisasi dan komisi Rp'.number_format($earnedAmount, 0, ',', '.').' berhasil dicairkan.',
            'current_views' => (int) $submission->current_views,
            'credited_views' => (int) $submission->credited_views,
            'delta_views' => $deltaViews,
            'earned_now' => $earnedAmount,
            'total_earned' => (int) $submission->total_earned,
        ];
    }
}
