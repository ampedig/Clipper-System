<?php

$url = 'https://www.tiktok.com/@scout2015/video/6718335390845095173';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)');
$response = curl_exec($ch);
file_put_contents('scratch_tiktok_html.html', $response);
echo "Dumped HTML to scratch_tiktok_html.html\n";
