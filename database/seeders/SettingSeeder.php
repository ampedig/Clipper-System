<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            ['key' => 'minimal_wd', 'value' => '50000'],
            ['key' => 'cs_whatsapp', 'value' => '082212345678'],
            ['key' => 'cs_telegram', 'value' => '@cstele'],
            ['key' => 'home_announcement', 'value' => 'Jumlah view di refresh setiap jam 12 malem, dan proses pencairan komisi'],
            ['key' => 'max_tiktok_akun', 'value' => '10'],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value']]
            );
        }
    }
}
