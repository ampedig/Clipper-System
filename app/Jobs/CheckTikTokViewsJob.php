<?php

namespace App\Jobs;

use App\Models\ClipSubmission;
use App\Services\ClipViewsSyncService;
use Exception;
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
    public function handle(ClipViewsSyncService $syncService): void
    {
        $result = $syncService->sync($this->submission);

        if (! $result['success']) {
            // Jika kegagalan karena jaringan/scraper TikTok, lempar exception agar antrean me-retry
            if (str_contains($result['message'], 'TikTok')) {
                throw new Exception("Gagal mengambil data views untuk submission ID: {$this->submission->id} - {$result['message']}");
            }
        }
    }
}
