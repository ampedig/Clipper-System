<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$scraper = new \App\Services\TikTokScraperService();
$stats = $scraper->getVideoStats('https://www.tiktok.com/@andeska.etsf/video/7685390437162487047');
var_dump($stats);
