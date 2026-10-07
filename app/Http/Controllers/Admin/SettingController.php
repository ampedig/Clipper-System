<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key');

        return view('dashboard.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'cs_whatsapp' => 'nullable|string',
            'cs_telegram' => 'nullable|string',
            'link_grup' => 'nullable|string|max:500',
            'min_withdraw' => 'required|string',
            'max_tiktok_akun' => 'required|integer|min:1|max:100',
            'home_announcement' => 'nullable|string',
        ]);

        // Clean formatting from numbers
        $minWithdraw = (int) preg_replace('/\D/', '', $validated['min_withdraw']);

        $csWhatsapp = $validated['cs_whatsapp'] ? preg_replace('/\D/', '', $validated['cs_whatsapp']) : null;
        $csTelegram = $validated['cs_telegram'] ? str_replace('@', '', $validated['cs_telegram']) : null;
        $linkGrup = ! empty($validated['link_grup']) ? trim($validated['link_grup']) : null;

        $dataToSave = [
            'cs_whatsapp' => $csWhatsapp,
            'cs_telegram' => $csTelegram,
            'link_grup' => $linkGrup,
            'minimal_wd' => (string) $minWithdraw,
            'max_tiktok_akun' => (string) max(1, (int) $validated['max_tiktok_akun']),
            'home_announcement' => $validated['home_announcement'] ?? null,
        ];

        foreach ($dataToSave as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->route('admin.settings.index')->with('success', 'Pengaturan berhasil disimpan!');
    }
}
