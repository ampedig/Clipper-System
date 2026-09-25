<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Scheduler untuk mengecek views TikTok setiap jam 12 malam
Schedule::command('clip:check-views')->dailyAt('00:00')->withoutOverlapping();
