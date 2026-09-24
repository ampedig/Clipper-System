<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class PasswordController extends Controller
{
    /**
     * Menampilkan form ubah kata sandi untuk end-user.
     */
    public function edit(): View
    {
        return view('app.profile.password');
    }

    /**
     * Memvalidasi dan menyimpan kata sandi baru user.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'current_password.current_password' => 'Kata sandi saat ini tidak sesuai.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi baru minimal :min karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $user = $request->user();
        if (! $user) {
            return redirect()->route('login');
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('app.profile')->with('status', 'password-updated');
    }
}
