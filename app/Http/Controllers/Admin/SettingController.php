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
            'cs_whatsapp' => 'required|string',
            'cs_telegram' => 'required|string',
            'min_withdraw' => 'required|string',
        ]);

        // Clean formatting from numbers
        $minWithdraw = (int) preg_replace('/\D/', '', $validated['min_withdraw']);
        // Store whatsapp clean without leading +62, 62, 0 or anything, just store as is submitted (the view strips leading 62/0)
        // Actually, let's keep what the view sends, but ensure no spaces or strange chars
        $csWhatsapp = preg_replace('/\D/', '', $validated['cs_whatsapp']);
        // For telegram, just ensure it doesn't have @ (or add it if we want). We'll strip @ and let the view add it
        $csTelegram = str_replace('@', '', $validated['cs_telegram']);

        $dataToSave = [
            'cs_whatsapp' => $csWhatsapp,
            'cs_telegram' => $csTelegram,
            'minimal_wd' => (string) $minWithdraw,
        ];

        foreach ($dataToSave as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->route('admin.settings.index')->with('success', 'Pengaturan berhasil disimpan!');
    }
}
