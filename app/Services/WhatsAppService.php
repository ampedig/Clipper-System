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
     * Mulai sesi koneksi socket perangkat WhatsApp di AMBLAST (QR Code atau Pairing Code).
     *
     * @param  string  $method  Metode koneksi ('qr' atau 'pairing')
     * @param  string|null  $phoneNumber  Nomor WhatsApp jika metode pairing
     * @return array{
     *     success: bool,
     *     status: string,
     *     method?: string,
     *     qr_code?: string|null,
     *     qr_raw?: string|null,
     *     pairing_code?: string|null,
     *     phone_number?: string|null,
     *     message: string
     * }
     */
    public function connectDevice(string $method = 'qr', ?string $phoneNumber = null): array
    {
        if (empty($this->apiKey)) {
            return [
                'success' => false,
                'status' => 'unconfigured',
                'message' => 'API Key WhatsApp belum dikonfigurasi.',
            ];
        }

        $method = strtolower(trim($method)) === 'pairing' ? 'pairing' : 'qr';
        $payload = [
            'apiKey' => $this->apiKey,
            'method' => $method,
        ];

        if ($method === 'pairing') {
            $cleanPhone = preg_replace('/\D/', '', (string) $phoneNumber);
            if (empty($cleanPhone)) {
                return [
                    'success' => false,
                    'status' => 'error',
                    'message' => 'Nomor WhatsApp wajib diisi untuk metode pairing code.',
                ];
            }
            $payload['phone_number'] = $cleanPhone;
        }

        $url = self::BASE_URL.'/api/device/connect';

        try {
            $response = Http::timeout(12)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'x-api-key' => $this->apiKey,
                ])
                ->post($url, $payload);

            $json = $response->json();

            if ($response->successful() && ! empty($json['success'])) {
                $status = strtolower((string) ($json['status'] ?? 'connecting'));

                return [
                    'success' => true,
                    'status' => $status,
                    'method' => $json['method'] ?? $method,
                    'message' => $json['message'] ?? 'Sesi koneksi perangkat berhasil diinisialisasi.',
                    'qr_code' => $json['qr_code'] ?? null,
                    'qr_raw' => $json['qr_raw'] ?? null,
                    'pairing_code' => $json['pairing_code'] ?? null,
                    'phone_number' => $json['phone_number'] ?? ($payload['phone_number'] ?? null),
                ];
            }

            $errorMessage = $json['message'] ?? 'Gagal menghubungkan device (HTTP '.$response->status().').';

            return [
                'success' => false,
                'status' => 'error',
                'message' => $errorMessage,
            ];
        } catch (Throwable $e) {
            Log::warning('WhatsAppService: Gagal menghubungkan perangkat ke API gateway AMBLAST.', [
                'error' => $e->getMessage(),
                'url' => $url,
                'method' => $method,
            ]);

            return [
                'success' => false,
                'status' => 'error',
                'message' => 'Gagal terhubung ke server WhatsApp Gateway: '.$e->getMessage(),
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

    /**
     * Static shortcut helper untuk memulai sesi koneksi device secara instan.
     */
    public static function connect(string $method = 'qr', ?string $phoneNumber = null, ?string $apiKey = null): array
    {
        return (new self($apiKey))->connectDevice($method, $phoneNumber);
    }
}
