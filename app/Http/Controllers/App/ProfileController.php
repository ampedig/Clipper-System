<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
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

        return view('app.profile.index', [
            'user' => $user,
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
}
