<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\UserTiktokAccount;
use App\Services\TiktokRapidApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TiktokAccountController extends Controller
{
    /**
     * Menampilkan daftar akun TikTok milik clipper dan status verifikasinya.
     */
    public function index(): View
    {
        $user = auth()->user();
        $accounts = $user->tiktokAccounts()->latest()->get();

        $maxAccounts = (int) Setting::where('key', 'max_tiktok_akun')->value('value') ?: 10;
        $totalAccounts = $accounts->count();
        $verifiedAccounts = $accounts->where('is_verified', true)->count();
        $canAddMore = $totalAccounts < $maxAccounts;

        return view('app.profile.tiktok', compact(
            'accounts',
            'maxAccounts',
            'totalAccounts',
            'verifiedAccounts',
            'canAddMore'
        ));
    }

    /**
     * Mendaftarkan akun TikTok baru untuk user dan men-generate kode verifikasi bio 6 digit.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $user = auth()->user();
        $maxAccounts = (int) Setting::where('key', 'max_tiktok_akun')->value('value') ?: 10;

        // Validasi kuota akun maksimum user
        if ($user->tiktokAccounts()->count() >= $maxAccounts) {
            $msg = "Anda telah mencapai batas maksimal ({$maxAccounts}) akun TikTok.";

            return $request->wantsJson()
                ? response()->json(['success' => false, 'message' => $msg], 422)
                : back()->with('error', $msg);
        }

        // Bersihkan input username dari karakter @ dan whitespace
        $rawUsername = (string) $request->input('username', '');
        $cleanedUsername = trim(ltrim(trim($rawUsername), '@'));

        $request->merge(['username' => $cleanedUsername]);

        $validated = $request->validate([
            'username' => [
                'required',
                'string',
                'min:2',
                'max:50',
                'regex:/^[a-zA-Z0-9_.]+$/',
                'unique:user_tiktok_accounts,username',
            ],
        ], [
            'username.required' => 'Username TikTok wajib diisi.',
            'username.regex' => 'Format username TikTok tidak valid (hanya huruf, angka, titik, dan garis bawah).',
            'username.unique' => "Akun TikTok @{$cleanedUsername} sudah terdaftar di sistem AZCLIP oleh pengguna lain.",
        ]);

        // Generate 6 digit angka random unik untuk kode verifikasi bio
        $verificationCode = (string) random_int(100000, 999999);

        $account = $user->tiktokAccounts()->create([
            'username' => $validated['username'],
            'verification_code' => $verificationCode,
            'is_verified' => false,
        ]);

        $successMsg = "Akun @{$account->username} berhasil ditambahkan! Silakan tempelkan kode verifikasi di bio TikTok Anda.";

        return $request->wantsJson()
            ? response()->json([
                'success' => true,
                'message' => $successMsg,
                'account' => $account,
            ])
            : back()->with('success', $successMsg);
    }

    /**
     * Menghubungi RapidAPI untuk mengecek apakah kode verifikasi ada di bio profil TikTok.
     */
    public function verify(Request $request, UserTiktokAccount $account, TiktokRapidApiService $service): JsonResponse|RedirectResponse
    {
        // Pastikan hanya pemilik akun yang berhak memicu verifikasi
        if ($account->user_id !== auth()->id()) {
            abort(403, 'Akses tidak diizinkan.');
        }

        if ($account->is_verified) {
            $alreadyMsg = 'Akun TikTok ini sudah terverifikasi sebelumnya.';

            return $request->wantsJson()
                ? response()->json(['success' => true, 'message' => $alreadyMsg])
                : back()->with('info', $alreadyMsg);
        }

        // Panggil service untuk mencocokkan kode di bio profil TikTok
        $verification = $service->verifyBioCode($account->username, (string) $account->verification_code);

        if (! $verification['verified']) {
            return $request->wantsJson()
                ? response()->json([
                    'success' => false,
                    'status' => $verification['status'],
                    'message' => $verification['message'],
                ], 422)
                : back()->with('error', $verification['message']);
        }

        // Update data akun setelah verifikasi berhasil
        $userData = $verification['user_data'] ?? [];

        $account->update([
            'is_verified' => true,
            'verified_at' => now(),
            'nickname' => $userData['nickname'] ?? $account->username,
            'avatar_url' => $userData['avatar_url'] ?? null,
        ]);

        $successMsg = "Selamat! Akun TikTok @{$account->username} berhasil diverifikasi.";

        return $request->wantsJson()
            ? response()->json([
                'success' => true,
                'message' => $successMsg,
                'account' => $account,
            ])
            : back()->with('success', $successMsg);
    }

    /**
     * Menghapus akun TikTok dari daftar clipper.
     */
    public function destroy(Request $request, UserTiktokAccount $account): JsonResponse|RedirectResponse
    {
        // Pastikan hanya pemilik akun yang berhak menghapus
        if ($account->user_id !== auth()->id()) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $username = $account->username;
        $account->delete();

        $successMsg = "Akun TikTok @{$username} berhasil dihapus dari daftar Anda.";

        return $request->wantsJson()
            ? response()->json(['success' => true, 'message' => $successMsg])
            : back()->with('success', $successMsg);
    }
}
