<?php

namespace App\Console\Commands;

use App\Jobs\CheckTikTokViewsJob;
use App\Models\ClipSubmission;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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

        $submissions = ClipSubmission::whereIn('status', ['active', 'approved'])
            ->whereHas('clipCampaign', function ($query) {
                $query->where('status', 'active')
                    ->where(function ($q) {
                        $q->whereNull('start_at')
                            ->orWhere('start_at', '<=', now());
                    })
                    ->where(function ($q) {
                        $q->whereNull('end_at')
                            ->orWhere('end_at', '>=', now());
                    });
            })->get();

        if ($submissions->isEmpty()) {
            $this->info('Tidak ada submission aktif/approved untuk dicek.');
            Log::info('CheckTikTokViewsCommand: Tidak ada submission aktif/approved untuk dicek.');

            return static::SUCCESS;
        }

        $this->info("Memproses {$submissions->count()} submission(s)...");
        Log::info("CheckTikTokViewsCommand: Memulai penjadwalan untuk {$submissions->count()} submission(s)...");

        $cumulativeDelay = 0;

        foreach ($submissions as $index => $submission) {
            // Gunakan jeda acak antara 12 hingga 25 detik per video 
            // agar request tidak berpola tetap dan lebih aman dari blokir TikTok (Akamai/Cloudflare)
            $cumulativeDelay += rand(12, 25);
            
            CheckTikTokViewsJob::dispatch($submission)
                ->delay(now()->addSeconds($cumulativeDelay));

            Log::info("CheckTikTokViewsCommand: Dispatched Job untuk Submission ID {$submission->id} dengan delay {$cumulativeDelay} detik.");
        }

        $this->info("Semua job telah berhasil masuk ke antrian (queue) dengan total sebaran waktu {$cumulativeDelay} detik.");
        Log::info('CheckTikTokViewsCommand: Semua job telah selesai dijadwalkan ke queue.');

        return static::SUCCESS;
    }
}
