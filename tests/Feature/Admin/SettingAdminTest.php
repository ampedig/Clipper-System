<?php

namespace Tests\Feature\Admin;

use App\Models\Setting;
use App\Models\User;
use Database\Seeders\SettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingAdminTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'name' => 'Admin User',
            'email' => 'admin@azclip.com',
        ]);
    }

    public function test_setting_seeder_creates_link_grup(): void
    {
        $this->seed(SettingSeeder::class);

        $this->assertDatabaseHas('settings', [
            'key' => 'link_grup',
        ]);
    }

    public function test_admin_can_view_settings_page(): void
    {
        Setting::create(['key' => 'link_grup', 'value' => 'https://chat.whatsapp.com/testgroup']);

        $response = $this->actingAs($this->admin)->get(route('admin.settings.index'));

        $response->assertStatus(200);
        $response->assertSee('Customer Service & Komunitas', false);
        $response->assertSee('https://chat.whatsapp.com/testgroup');
    }

    public function test_admin_can_update_settings_with_link_grup(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admin.settings.update'), [
            'cs_whatsapp' => '08123456789',
            'cs_telegram' => '@support_tg',
            'link_grup' => 'https://chat.whatsapp.com/newlink123',
            'min_withdraw' => '50.000',
            'max_tiktok_akun' => 10,
            'home_announcement' => 'Pengumuman baru',
        ]);

        $response->assertRedirect(route('admin.settings.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('settings', [
            'key' => 'link_grup',
            'value' => 'https://chat.whatsapp.com/newlink123',
        ]);
    }

    public function test_setting_seeder_creates_apikey_whatsapp(): void
    {
        $this->seed(SettingSeeder::class);

        $this->assertDatabaseHas('settings', [
            'key' => 'apikey_whatsapp',
        ]);
    }

    public function test_admin_can_view_whatsapp_setting_page(): void
    {
        Setting::create(['key' => 'apikey_whatsapp', 'value' => 'dummy-api-key-test']);

        $response = $this->actingAs($this->admin)->get(route('admin.settings.whatsapp'));

        $response->assertStatus(200);
        $response->assertSee('WhatsApp Gateway', false);
        $response->assertSee('dummy-api-key-test', false);
    }

    public function test_admin_can_update_whatsapp_api_key(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admin.settings.whatsapp.update'), [
            'apikey_whatsapp' => 'secret-wa-token-456',
        ]);

        $response->assertRedirect(route('admin.settings.whatsapp'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('settings', [
            'key' => 'apikey_whatsapp',
            'value' => 'secret-wa-token-456',
        ]);
    }

    public function test_admin_can_clear_whatsapp_api_key(): void
    {
        Setting::create(['key' => 'apikey_whatsapp', 'value' => 'existing-token']);

        $response = $this->actingAs($this->admin)->put(route('admin.settings.whatsapp.update'), [
            'apikey_whatsapp' => '',
        ]);

        $response->assertRedirect(route('admin.settings.whatsapp'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('settings', [
            'key' => 'apikey_whatsapp',
            'value' => null,
        ]);
    }
}
