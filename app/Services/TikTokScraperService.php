<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class TikTokScraperService
{
    /**
     * Get video statistics using yt-dlp.
     *
     * @param string $url
     * @return array|null
     */
    public function getVideoStats(string $url): ?array
    {
        // Gunakan yt-dlp untuk mengambil dump-json
        // Pastikan yt-dlp terinstall di server dan path-nya dikenali
        $process = new Process(['yt-dlp', '--dump-json', $url]);
        
        // Timeout 30 detik untuk berjaga-jaga jika koneksi lambat
        $process->setTimeout(30);

        try {
            $process->mustRun();
            
            $output = $process->getOutput();
            $data = json_decode($output, true);

            if (json_last_error() === JSON_ERROR_NONE && isset($data['view_count'])) {
                return [
                    'views' => $data['view_count'] ?? 0,
                    'likes' => $data['like_count'] ?? 0,
                    'comments' => $data['comment_count'] ?? 0,
                    'uploader' => $data['uploader'] ?? null,
                ];
            }
            
            Log::error("TikTok Scraper Error: Format JSON tidak valid atau view_count tidak ditemukan.", ['url' => $url]);
            return null;

        } catch (ProcessFailedException $e) {
            Log::error("TikTok Scraper Failed: " . $e->getMessage(), ['url' => $url]);
            return null;
        } catch (\Exception $e) {
            Log::error("TikTok Scraper Exception: " . $e->getMessage(), ['url' => $url]);
            return null;
        }
    }
}
