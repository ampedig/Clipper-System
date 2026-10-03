<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TikTokUrlService
{
    /**
     * Resolve short URL and extract TikTok Content/Video/Photo ID.
     */
    public function extractVideoId(string $url): ?string
    {
        $resolvedUrl = $this->resolveIfShortUrl($url);

        // Regex to find ID (19 or more digits) after /video/, /photo/, or /v/
        if (preg_match('/\/(?:video|photo|v)\/(\d+)/', $resolvedUrl, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Extract author username handle from TikTok URL.
     */
    public function extractUsername(string $url): ?string
    {
        $resolvedUrl = $this->resolveIfShortUrl($url);

        if (preg_match('/@([a-zA-Z0-9_.]+)/', $resolvedUrl, $matches)) {
            return strtolower($matches[1]);
        }

        return null;
    }

    /**
     * Resolve short URL and extract content metadata (canonical URL, video_id, author username).
     *
     * @return array{url: string, video_id: ?string, username: ?string}
     */
    public function parseVideo(string $url): array
    {
        $resolvedUrl = $this->resolveIfShortUrl($url);

        $videoId = null;
        if (preg_match('/\/(?:video|photo|v)\/(\d+)/', $resolvedUrl, $matches)) {
            $videoId = $matches[1];
        }

        $username = null;
        if (preg_match('/@([a-zA-Z0-9_.]+)/', $resolvedUrl, $matches)) {
            $username = strtolower($matches[1]);
        }

        return [
            'url' => $resolvedUrl,
            'video_id' => $videoId,
            'username' => $username,
        ];
    }

    /**
     * Check if the given URL is a short TikTok URL and resolve its redirect destination.
     */
    public function resolveIfShortUrl(string $url): string
    {
        if (str_contains($url, 'vt.tiktok.com') || str_contains($url, 'vm.tiktok.com')) {
            return $this->resolveRedirect($url);
        }

        return $url;
    }

    /**
     * Follow HTTP redirects to get the final long URL.
     */
    protected function resolveRedirect(string $url): string
    {
        try {
            // We use withoutRedirecting() to catch the 301/302 Location header
            $response = Http::withoutRedirecting()
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
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
