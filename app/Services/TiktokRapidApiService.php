<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TiktokRapidApiService
{
    public const HOST = 'tiktok-api23.p.rapidapi.com';

    public const BASE_URL = 'https://tiktok-api23.p.rapidapi.com';

    /**
     * Inisialisasi API key RapidAPI TikTok.
     */
    public function __construct(
        protected ?string $apiKey = null
    ) {
        $this->apiKey = $apiKey ?? config('services.tiktok_rapidapi.key');
    }

    /**
     * Membersihkan username TikTok dari karakter '@' dan whitespace.
     */
    public function cleanUsername(string $username): string
    {
        return trim(ltrim(trim($username), '@'));
    }

    /**
     * Mengambil detail profil dan statistik akun TikTok dari RapidAPI.
     *
     * @param  string  $username  Unique ID / Username TikTok
     * @return array<string, mixed>|null
     */
    public function getUserInfo(string $username): ?array
    {
        $cleanUsername = $this->cleanUsername($username);

        if (empty($cleanUsername)) {
            return null;
        }

        if (empty($this->apiKey)) {
            Log::warning('TikTok RapidAPI Warning: RAPIDAPI_KEY belum dikonfigurasi di file .env.');

            return null;
        }

        try {
            $response = Http::withHeaders([
                'x-rapidapi-key' => $this->apiKey,
                'x-rapidapi-host' => self::HOST,
            ])
                ->timeout(15)
                ->get(self::BASE_URL.'/api/user/info', [
                    'uniqueId' => $cleanUsername,
                ]);

            if ($response->successful()) {
                $data = $response->json();

                // Pastikan struktur response memiliki userInfo.user sesuai dokumentasi API
                if (is_array($data) && isset($data['userInfo']['user'])) {
                    return $data;
                }

                Log::warning('TikTok RapidAPI Warning: Format response tidak sesuai atau akun tidak ditemukan.', [
                    'username' => $cleanUsername,
                    'response' => $data,
                ]);

                return null;
            }

            Log::error('TikTok RapidAPI Request Failed', [
                'username' => $cleanUsername,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        } catch (Exception $e) {
            Log::error('TikTok RapidAPI Exception: '.$e->getMessage(), [
                'username' => $cleanUsername,
            ]);

            return null;
        }
    }

    /**
     * Memverifikasi keberadaan kode 6 digit di dalam bio (signature) profil TikTok pengguna.
     *
     * @param  string  $username  Username akun TikTok
     * @param  string  $verificationCode  Kode verifikasi 6 digit yang wajib dipasang di bio
     * @return array{
     *     status: 'success'|'code_not_found'|'user_not_found'|'api_error',
     *     verified: bool,
     *     message: string,
     *     user_data: array{
     *         nickname: string,
     *         avatar_url: ?string,
     *         sec_uid: ?string,
     *         bio: string
     *     }|null
     * }
     */
    public function verifyBioCode(string $username, string $verificationCode): array
    {
        $cleanUsername = $this->cleanUsername($username);
        $cleanCode = trim($verificationCode);

        $apiResult = $this->getUserInfo($cleanUsername);

        // Jika API gagal dihubungi atau mengembalikan response non-200
        if ($apiResult === null) {
            return [
                'status' => 'api_error',
                'verified' => false,
                'message' => 'Gagal terhubung ke TikTok API. Pastikan koneksi dan API Key valid.',
                'user_data' => null,
            ];
        }

        $userInfo = $apiResult['userInfo']['user'] ?? null;

        // Jika user tidak ditemukan
        if (! is_array($userInfo)) {
            return [
                'status' => 'user_not_found',
                'verified' => false,
                'message' => "Akun TikTok @{$cleanUsername} tidak ditemukan. Silakan periksa kembali penulisan username Anda.",
                'user_data' => null,
            ];
        }

        $bio = $userInfo['signature'] ?? '';

        // Cek apakah kode verifikasi tercantum di dalam teks bio profil
        if (! str_contains($bio, $cleanCode)) {
            return [
                'status' => 'code_not_found',
                'verified' => false,
                'message' => "Kode verifikasi [{$cleanCode}] tidak ditemukan pada bio profil @{$cleanUsername}. Pastikan Anda sudah menyimpan bio TikTok Anda dan coba lagi.",
                'user_data' => null,
            ];
        }

        return [
            'status' => 'success',
            'verified' => true,
            'message' => "Akun TikTok @{$cleanUsername} berhasil diverifikasi!",
            'user_data' => [
                'nickname' => $userInfo['nickname'] ?? $cleanUsername,
                'avatar_url' => $userInfo['avatarLarger'] ?? $userInfo['avatarMedium'] ?? $userInfo['avatarThumb'] ?? null,
                'sec_uid' => $userInfo['secUid'] ?? null,
                'bio' => $bio,
            ],
        ];
    }
}

/**
 * Alias class untuk kompatibilitas penamaan jamak (TiktokRapidApiServices).
 */
class TiktokRapidApiServices extends TiktokRapidApiService {}
