<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    /**
     * Send a message to the configured Telegram chat/topic.
     *
     * @param  string  $text  The HTML formatted message.
     * @param  int|null  $threadId  The message_thread_id (topic).
     * @return bool True if successful, false otherwise.
     */
    public static function sendMessage(string $text, ?int $threadId = null): bool
    {
        $token = config('telegram.bot_token');
        $chatId = config('telegram.chat_id');

        if (! $token || ! $chatId) {
            Log::warning('TelegramService: Missing bot_token or chat_id in configuration.');

            return false;
        }

        $url = "https://api.telegram.org/bot{$token}/sendMessage";

        $payload = [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'html',
        ];

        if ($threadId) {
            $payload['message_thread_id'] = $threadId;
        }

        try {
            $response = Http::post($url, $payload);

            if ($response->successful()) {
                return true;
            }

            Log::error('TelegramService: Failed to send message.', [
                'status' => $response->status(),
                'body' => $response->body(),
                'payload' => $payload,
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('TelegramService: Exception caught while sending message.', [
                'message' => $e->getMessage(),
                'payload' => $payload,
            ]);

            return false;
        }
    }
}
