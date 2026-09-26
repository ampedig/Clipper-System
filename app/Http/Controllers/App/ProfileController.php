<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\WithdrawChannel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
            'whatsapp' => ['required', 'numeric', 'digits_between:8,15'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.min' => 'Nama lengkap minimal :min karakter.',
            'whatsapp.required' => 'Nomor WhatsApp wajib diisi.',
            'whatsapp.numeric' => 'Nomor WhatsApp harus berupa angka.',
            'whatsapp.digits_between' => 'Nomor WhatsApp harus antara :min dan :max digit.',
        ]);

        $user = $request->user();
        if (! $user) {
            return redirect()->route('login');
        }

        // Simpan nomor whatsapp dengan standar format awalan 0
        $cleanWa = preg_replace('/^(?:\+62|62|0)/', '', (string) $validated['whatsapp']);
        $normalizedWa = '0'.$cleanWa;

        $user->update([
            'name' => $validated['name'],
            'whatsapp' => $normalizedWa,
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
     * Menyimpan data rekening pencairan dana user.
     */
    public function updateRekening(Request $request): RedirectResponse
    {
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

        $user = $request->user();
        if (! $user) {
            return redirect()->route('login');
        }

        $user->update([
            'withdraw_channel_id' => $validated['withdraw_channel_id'],
            'account_number' => $validated['account_number'],
            'account_name' => $validated['account_name'],
        ]);

        return redirect()->route('app.rekening')->with('status', 'rekening-updated');
    }
}
