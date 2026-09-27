<?php

use App\Services\TikTokScraperService;
use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$scraper = new TikTokScraperService;
$stats = $scraper->getVideoStats('https://www.tiktok.com/@andeska.etsf/video/7685390437162487047');
var_dump($stats);
