<?php

namespace App\Jobs;

use App\Models\ClipSubmission;
use App\Services\TikTokScraperService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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

        if (!$stats) {
            // Jika scraper gagal (return null), lemparkan exception agar di-retry oleh Queue
            throw new \Exception("Gagal mengambil data views untuk submission ID: {$this->submission->id}");
        }

        $newViews = $stats['views'];

        // Ambil pengaturan campaign
        $threshold = $campaign->view_threshold;
        $commissionPerThreshold = $campaign->commission_amount;
        $maxPayout = $campaign->view_max;

        // Hitung komisi baru berdasarkan kelipatan threshold
        // Contoh: Threshold 1000, newViews 2700. Kelipatan = 2. 
        // credited_views akan menjadi 2 * 1000 = 2000.
        // Komisi baru = 2 * commissionPerThreshold = Rp10.000.
        
        $multiples = floor($newViews / $threshold);
        $newCreditedViews = $multiples * $threshold;
        $newEarned = $multiples * $commissionPerThreshold;

        // Pastikan kita tidak melebihi batas maksimal komisi per video (view_max)
        if ($maxPayout && $newEarned > $maxPayout) {
            $newEarned = $maxPayout;
            // credited_views maksimal
            $newCreditedViews = floor($maxPayout / $commissionPerThreshold) * $threshold;
        }

        $this->submission->update([
            'current_views' => $newViews,
            'credited_views' => max($this->submission->credited_views, $newCreditedViews),
            'total_earned' => max($this->submission->total_earned, $newEarned),
        ]);
    }
}
