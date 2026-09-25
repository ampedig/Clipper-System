<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class TikTokScraperService
{
    /**
     * Get video statistics using yt-dlp.
     */
    public function getVideoStats(string $url): ?array
    {
        // YTDLP_PATH diperlukan di Windows karena PHP menjalankan process dalam konteks CMD
        // yang tidak mewarisi PATH dari bash/PowerShell. Di Linux/VPS cukup set ke 'yt-dlp'.
        $ytdlpBin = config('services.ytdlp.path', 'yt-dlp');

        $process = new Process([
            $ytdlpBin,
            '--impersonate', 'chrome',
            '--dump-json',
            $url,
        ]);

        // Timeout 30 detik untuk berjaga-jaga jika koneksi lambat
        $process->setTimeout(30);

        try {
            $process->mustRun();

            $output = $process->getOutput();
            $data = json_decode($output, true);

            if (json_last_error() === JSON_ERROR_NONE && isset($data['view_count'])) {
                return [
                    'views'    => $data['view_count'] ?? 0,
                    'likes'    => $data['like_count'] ?? 0,
                    'comments' => $data['comment_count'] ?? 0,
                    'uploader' => $data['uploader'] ?? null,
                ];
            }

            Log::error('TikTok Scraper Error: Format JSON tidak valid atau view_count tidak ditemukan.', ['url' => $url]);

            return null;

        } catch (ProcessFailedException $e) {
            Log::error('TikTok Scraper Failed: '.$e->getMessage(), ['url' => $url]);

            return null;
        } catch (\Exception $e) {
            Log::error('TikTok Scraper Exception: '.$e->getMessage(), ['url' => $url]);

            return null;
        }
    }
}
