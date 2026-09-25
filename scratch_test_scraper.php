<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\ClipSubmission;
use App\Jobs\CheckTikTokViewsJob;
use App\Services\TikTokScraperService;

echo "Starting Scraper Test...\n";

$submissions = ClipSubmission::all();

if ($submissions->isEmpty()) {
    echo "Tidak ada data di tabel clip_submissions.\n";
    exit;
}

$scraper = new TikTokScraperService();

foreach ($submissions as $submission) {
    echo "Mengecek Submission ID: {$submission->id} | URL: {$submission->submitted_url}\n";
    
    try {
        $stats = $scraper->getVideoStats($submission->submitted_url);
        echo "Scraped Stats: \n";
        print_r($stats);

        $campaign = $submission->clipCampaign;
        $campaign->status = \App\Enums\CampaignStatus::from('active');
        $campaign->end_at = now()->addDays(10);
        $campaign->save();

        $job = new CheckTikTokViewsJob($submission);
        $job->handle($scraper);
        
        $submission->refresh();
        
        echo "✅ Berhasil!\n";
        echo "   - Current Views : " . number_format($submission->current_views) . "\n";
        echo "   - Credited Views: " . number_format($submission->credited_views) . "\n";
        echo "   - Total Earned  : Rp " . number_format($submission->total_earned) . "\n";
        echo "--------------------------------------------------\n";
    } catch (\Exception $e) {
        echo "❌ Gagal: " . $e->getMessage() . "\n";
    }
}

echo "Test Selesai.\n";
