<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\WithdrawChannel;
use App\Services\WhatsAppService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman akun dan profil clipper untuk end-user.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $totalSubmissions = $user->clipSubmissions()->count();
        $approvedSubmissions = $user->clipSubmissions()->whereIn('status', ['approved', 'active', 'completed'])->count();

        return view('app.profile.index', [
            'user' => $user,
            'totalSubmissions' => $totalSubmissions,
            'approvedSubmissions' => $approvedSubmissions,
        ]);
    }

    /**
     * Menampilkan form edit profil user.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        // Format nomor whatsapp tanpa awalan +62, 62, atau 0 untuk tampilan input
        $formattedWa = '';
        if ($user && $user->whatsapp) {
            $formattedWa = preg_replace('/^(?:\+62|62|0)/', '', $user->whatsapp);
        }

        return view('app.profile.edit', [
            'user' => $user,
            'formattedWa' => $formattedWa,
        ]);
    }

    /**
     * Menyimpan pembaruan data profil user.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:255'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.min' => 'Nama lengkap minimal :min karakter.',
        ]);

        $user = $request->user();
        if (! $user) {
            return redirect()->route('login');
        }

        $user->update([
            'name' => $validated['name'],
        ]);

        return redirect()->route('app.profile')->with('status', 'profile-updated');
    }

    /**
     * Menampilkan halaman syarat dan kebijakan layanan AZCLIP.
     */
    public function policy(): View
    {
        return view('app.profile.policy');
    }

    /**
     * Menampilkan halaman pusat bantuan dan kontak customer service.
     */
    public function help(): View
    {
        $settings = Setting::pluck('value', 'key')->toArray();

        if (! empty($settings['cs_whatsapp'])) {
            $settings['cs_whatsapp'] = preg_replace('/^(?:\+62|62|0)/', '', $settings['cs_whatsapp']);
        }

        if (! empty($settings['cs_telegram'])) {
            $settings['cs_telegram'] = ltrim($settings['cs_telegram'], '@');
        }

        return view('app.profile.help', compact('settings'));
    }

    /**
     * Menampilkan form pengaturan rekening pencairan dana.
     */
    public function rekening(Request $request): View
    {
        $user = $request->user()->load('withdrawChannel');
        $channels = WithdrawChannel::where('is_active', true)->orderBy('name')->get();

        return view('app.profile.rekening', [
            'user' => $user,
            'channels' => $channels,
        ]);
    }

    /**
     * Kirim kode OTP WhatsApp untuk verifikasi perubahan rekening.
     */
    public function sendRekeningOtp(Request $request): JsonResponse
    {
        $user = $request->user();
        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi login telah berakhir. Silakan login kembali.',
            ], 401);
        }

        if (empty($user->whatsapp)) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor WhatsApp belum terdaftar di profil Anda. Silakan lengkapi nomor WhatsApp terlebih dahulu.',
            ], 422);
        }

        $validated = $request->validate([
            'withdraw_channel_id' => ['required', 'exists:withdraw_channels,id'],
            'account_number' => ['required', 'numeric', 'digits_between:5,30'],
            'account_name' => ['required', 'string', 'min:2', 'max:255'],
        ], [
            'withdraw_channel_id.required' => 'Silakan pilih bank atau e-wallet tujuan pencairan.',
            'withdraw_channel_id.exists' => 'Bank atau e-wallet yang dipilih tidak valid.',
            'account_number.required' => 'Nomor rekening wajib diisi.',
            'account_number.numeric' => 'Nomor rekening harus berupa angka.',
            'account_number.digits_between' => 'Nomor rekening harus antara :min dan :max digit.',
            'account_name.required' => 'Nama pemilik rekening wajib diisi.',
            'account_name.min' => 'Nama pemilik rekening minimal :min karakter.',
        ]);

        $cooldownKey = "rekening_otp_cooldown:{$user->id}";
        if (Cache::has($cooldownKey)) {
            return response()->json([
                'success' => false,
                'message' => 'Mohon tunggu 60 detik sebelum meminta kode OTP kembali.',
            ], 429);
        }

        // Generate 6 digit random numeric OTP
        $otp = str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        // Simpan data rekening pending dan kode OTP ke Cache selama 5 menit
        $cacheKey = "rekening_otp:{$user->id}";
        Cache::put($cacheKey, [
            'otp' => $otp,
            'data' => $validated,
        ], now()->addMinutes(5));

        // Set cooldown 60 detik
        Cache::put($cooldownKey, true, now()->addSeconds(60));

        // Kirim OTP via Queue Job di background (non-blocking, respons instan ke browser)
        WhatsAppService::dispatchRekeningOtp($user->whatsapp, $otp, $user->name);

        // Mask nomor WhatsApp (contoh: 0812****7890)
        $rawWa = (string) $user->whatsapp;
        $maskedWa = strlen($rawWa) > 7
            ? substr($rawWa, 0, 4).'••••'.substr($rawWa, -4)
            : $rawWa;

        return response()->json([
            'success' => true,
            'message' => 'Kode OTP berhasil dikirim ke nomor WhatsApp Anda.',
            'masked_wa' => $maskedWa,
            'cooldown' => 60,
        ]);
    }

    /**
     * Menyimpan data rekening pencairan dana user setelah verifikasi OTP.
     */
    public function updateRekening(Request $request)
    {
        $validated = $request->validate([
            'withdraw_channel_id' => ['required', 'exists:withdraw_channels,id'],
            'account_number' => ['required', 'numeric', 'digits_between:5,30'],
            'account_name' => ['required', 'string', 'min:2', 'max:255'],
            'otp' => ['required', 'string', 'size:6'],
        ], [
            'withdraw_channel_id.required' => 'Silakan pilih bank atau e-wallet tujuan pencairan.',
            'withdraw_channel_id.exists' => 'Bank atau e-wallet yang dipilih tidak valid.',
            'account_number.required' => 'Nomor rekening wajib diisi.',
            'account_number.numeric' => 'Nomor rekening harus berupa angka.',
            'account_number.digits_between' => 'Nomor rekening harus antara :min dan :max digit.',
            'account_name.required' => 'Nama pemilik rekening wajib diisi.',
            'account_name.min' => 'Nama pemilik rekening minimal :min karakter.',
            'otp.required' => 'Kode OTP wajib diisi.',
            'otp.size' => 'Kode OTP harus berupa 6 digit angka.',
        ]);

        $user = $request->user();
        if (! $user) {
            return redirect()->route('login');
        }

        $cacheKey = "rekening_otp:{$user->id}";
        $cachedOtp = Cache::get($cacheKey);

        if (! $cachedOtp) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode OTP telah kedaluwarsa atau belum diminta. Silakan minta kode baru.',
                ], 422);
            }

            return redirect()->route('app.rekening')
                ->withErrors(['otp' => 'Kode OTP telah kedaluwarsa atau belum diminta. Silakan minta kode baru.'])
                ->withInput();
        }

        if ((string) $cachedOtp['otp'] !== trim($validated['otp'])) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode OTP yang Anda masukkan salah. Silakan periksa kembali pesan WhatsApp Anda.',
                ], 422);
            }

            return redirect()->route('app.rekening')
                ->withErrors(['otp' => 'Kode OTP yang Anda masukkan salah.'])
                ->withInput();
        }

        // Pastikan data rekening yang disimpan sama dengan data saat meminta OTP
        $pendingData = $cachedOtp['data'] ?? [];
        if (
            (string) ($pendingData['withdraw_channel_id'] ?? '') !== (string) $validated['withdraw_channel_id'] ||
            (string) ($pendingData['account_number'] ?? '') !== (string) $validated['account_number'] ||
            trim((string) ($pendingData['account_name'] ?? '')) !== trim($validated['account_name'])
        ) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data rekening tidak sesuai dengan sesi verifikasi OTP. Silakan minta OTP baru.',
                ], 422);
            }

            return redirect()->route('app.rekening')
                ->withErrors(['otp' => 'Data rekening tidak sesuai dengan sesi verifikasi OTP.'])
                ->withInput();
        }

        // Update data rekening user di database
        $user->update([
            'withdraw_channel_id' => $validated['withdraw_channel_id'],
            'account_number' => $validated['account_number'],
            'account_name' => $validated['account_name'],
        ]);

        // Hapus cache OTP setelah berhasil
        Cache::forget($cacheKey);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Rekening pencairan dana berhasil diperbarui.',
            ]);
        }

        return redirect()->route('app.rekening')
            ->with('status', 'rekening-updated')
            ->with('success', 'Rekening pencairan dana berhasil diperbarui.');
    }
}
