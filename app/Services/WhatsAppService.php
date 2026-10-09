<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class WhatsAppService
{
    /**
     * Endpoint utama server AMBLAST WhatsApp Gateway.
     */
    public const BASE_URL = 'https://amblast.ampedig.id';

    /**
     * API Key perangkat WhatsApp AMBLAST yang aktif.
     */
    protected ?string $apiKey;

    /**
     * Constructor: Selalu dijalankan pertama kali sebelum method lainnya dipanggil.
     * Menginisialisasi API Key dari parameter atau dari pengaturan database.
     */
    public function __construct(?string $apiKey = null)
    {
        $this->apiKey = $apiKey !== null ? trim($apiKey) : $this->resolveStoredApiKey();
    }

    /**
     * Ambil API Key yang tersimpan di tabel settings database.
     */
    protected function resolveStoredApiKey(): ?string
    {
        $key = Setting::where('key', 'apikey_whatsapp')->value('value');

        return ! empty($key) ? trim($key) : null;
    }

    /**
     * Dapatkan API Key yang sedang aktif pada instance service.
     */
    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    /**
     * Periksa status koneksi socket perangkat WhatsApp di AMBLAST.
     *
     * @return array{
     *     success: bool,
     *     connected: bool,
     *     status: string,
     *     message: string,
     *     data: array<string, mixed>|null
     * }
     */
    public function checkStatus(): array
    {
        if (empty($this->apiKey)) {
            return [
                'success' => false,
                'connected' => false,
                'status' => 'unconfigured',
                'message' => 'API Key WhatsApp belum dikonfigurasi.',
                'data' => null,
            ];
        }

        $url = self::BASE_URL.'/api/device/status';

        try {
            $response = Http::timeout(8)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'x-api-key' => $this->apiKey,
                ])
                ->post($url, [
                    'apiKey' => $this->apiKey,
                ]);

            $json = $response->json();

            if ($response->successful() && ! empty($json['success']) && isset($json['data'])) {
                $device = $json['data'];
                $deviceStatus = strtolower((string) ($device['status'] ?? 'disconnected'));

                return [
                    'success' => true,
                    'connected' => $deviceStatus === 'connected',
                    'status' => $deviceStatus,
                    'message' => $json['message'] ?? 'Status device berhasil diambil.',
                    'data' => $device,
                ];
            }

            $errorMessage = $json['message'] ?? 'Gagal memeriksa status device (HTTP '.$response->status().').';

            return [
                'success' => false,
                'connected' => false,
                'status' => 'error',
                'message' => $errorMessage,
                'data' => null,
            ];
        } catch (Throwable $e) {
            Log::warning('WhatsAppService: Gagal terhubung ke API gateway AMBLAST.', [
                'error' => $e->getMessage(),
                'url' => $url,
            ]);

            return [
                'success' => false,
                'connected' => false,
                'status' => 'error',
                'message' => 'Gagal terhubung ke server WhatsApp Gateway: '.$e->getMessage(),
                'data' => null,
            ];
        }
    }

    /**
     * Static shortcut helper untuk mengecek status device secara instan.
     */
    public static function checkDeviceStatus(?string $apiKey = null): array
    {
        return (new self($apiKey))->checkStatus();
    }
}
