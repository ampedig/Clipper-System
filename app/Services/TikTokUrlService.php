<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TikTokUrlService
{
    /**
     * Resolve short URL and extract TikTok Video ID.
     *
     * @param string $url
     * @return string|null
     */
    public function extractVideoId(string $url): ?string
    {
        // If it's a short vt.tiktok.com URL, we need to resolve the redirect first
        if (str_contains($url, 'vt.tiktok.com') || str_contains($url, 'vm.tiktok.com')) {
            $url = $this->resolveRedirect($url);
        }

        // Regex to find the video ID (19 or more digits) after /video/
        if (preg_match('/\/video\/(\d+)/', $url, $matches)) {
            return $matches[1];
        }
        
        // Some mobile shares might use /v/ format
        if (preg_match('/\/v\/(\d+)/', $url, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Follow HTTP redirects to get the final long URL.
     *
     * @param string $url
     * @return string
     */
    protected function resolveRedirect(string $url): string
    {
        try {
            // We use withoutRedirecting() to catch the 301/302 Location header
            $response = Http::withoutRedirecting()
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'
                ])
                ->get($url);

            if ($response->status() >= 300 && $response->status() < 400 && $response->hasHeader('Location')) {
                return $response->header('Location');
            }

            return $url;
        } catch (\Exception $e) {
            // If it fails, just return the original URL
            return $url;
        }
    }
}
