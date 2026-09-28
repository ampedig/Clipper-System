<?php

use App\Models\ClipCampaign;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Scheduler untuk mengecek views TikTok setiap jam 12:10 malam
Schedule::command('clip:check-views')->dailyAt('00:10')->withoutOverlapping();

// Scheduler untuk mengirim rekap harian ke Telegram setiap jam 23:55
Schedule::command('app:daily-recap')->dailyAt('23:55')->withoutOverlapping();

// Auto-update status campaign (Upcoming -> Active, Active -> Completed)
Artisan::command('clip:update-campaign-statuses', function () {
    $now = now();
    $activated = ClipCampaign::where('status', 'upcoming')
        ->where('start_at', '<=', $now)
        ->update(['status' => 'active']);

    $completed = ClipCampaign::where('status', 'active')
        ->whereNotNull('end_at')
        ->where('end_at', '<', $now)
        ->update(['status' => 'completed']);

    $this->info("Updated {$activated} campaign(s) to active, {$completed} campaign(s) to completed.");
})->purpose('Auto update campaign statuses based on dates');

Schedule::command('clip:update-campaign-statuses')->dailyAt('00:01')->withoutOverlapping();
Schedule::command('clip:update-campaign-statuses')->dailyAt('05:00')->withoutOverlapping();
