<?php

use App\Jobs\CheckTikTokViewsJob;
use App\Models\ClipSubmission;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Scheduler untuk mengecek views TikTok setiap jam 12 malam
Schedule::call(function () {
    // Ambil semua submission yang status campaign-nya aktif dan belum berakhir
    $submissions = ClipSubmission::whereHas('clipCampaign', function ($query) {
        $query->where('status', 'active')
              ->where(function ($q) {
                  $q->whereNull('end_at')
                    ->orWhere('end_at', '>=', now());
              });
    })->get();

    foreach ($submissions as $index => $submission) {
        // Dispatch job dengan delay 10 detik antar setiap video agar IP aman
        CheckTikTokViewsJob::dispatch($submission)
            ->delay(now()->addSeconds($index * 10));
    }
})->dailyAt('00:00')->name('check-tiktok-views')->withoutOverlapping();
