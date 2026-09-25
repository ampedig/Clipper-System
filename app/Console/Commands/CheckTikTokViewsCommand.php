<?php

namespace App\Console\Commands;

use App\Jobs\CheckTikTokViewsJob;
use App\Models\ClipSubmission;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('clip:check-views {--submission= : ID submission spesifik (opsional)}')]
#[Description('Mengecek dan memperbarui views TikTok untuk semua submission atau spesifik ID')]
class CheckTikTokViewsCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $submissionId = $this->option('submission');

        if ($submissionId) {
            $submission = ClipSubmission::find($submissionId);

            if (! $submission) {
                $this->error("Submission dengan ID {$submissionId} tidak ditemukan.");

                return static::FAILURE;
            }

            $this->info("Menjalankan sinkronisasi views untuk submission ID {$submissionId}...");
            CheckTikTokViewsJob::dispatchSync($submission);
            $this->info('Selesai!');

            return static::SUCCESS;
        }

        $submissions = ClipSubmission::whereHas('clipCampaign', function ($query) {
            $query->where('status', 'active')
                ->where(function ($q) {
                    $q->whereNull('end_at')
                        ->orWhere('end_at', '>=', now());
                });
        })->get();

        if ($submissions->isEmpty()) {
            $this->info('Tidak ada submission aktif untuk dicek.');

            return static::SUCCESS;
        }

        $this->info("Memproses {$submissions->count()} submission(s)...");

        foreach ($submissions as $index => $submission) {
            CheckTikTokViewsJob::dispatch($submission)
                ->delay(now()->addSeconds($index * 10));
        }

        $this->info('Semua job telah berhasil masuk ke antrian (queue).');

        return static::SUCCESS;
    }
}
