<?php

namespace Tests\Feature\Admin;

use App\Models\Setting;
use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WhatsAppServiceTest extends TestCase
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

    public function test_whatsapp_service_returns_unconfigured_when_no_api_key(): void
    {
        Setting::where('key', 'apikey_whatsapp')->delete();

        $result = WhatsAppService::checkDeviceStatus();

        $this->assertFalse($result['success']);
        $this->assertFalse($result['connected']);
        $this->assertSame('unconfigured', $result['status']);
        $this->assertNull($result['data']);
    }

    public function test_whatsapp_service_checks_status_successfully_when_connected(): void
    {
        Setting::updateOrCreate(['key' => 'apikey_whatsapp'], ['value' => 'test-api-key-123']);

        Http::fake([
            '*/api/device/status' => Http::response([
                'success' => true,
                'message' => 'Status device berhasil diambil.',
                'data' => [
                    'id' => 1,
                    'name' => 'CS Utama',
                    'phone_number' => '6281234567890',
                    'status' => 'connected',
                    'message_quota' => 5000,
                    'expired_at' => '2026-12-31T23:59:59.000Z',
                    'is_expired' => false,
                    'can_connect' => true,
                ],
            ], 200),
        ]);

        $result = WhatsAppService::checkDeviceStatus();

        $this->assertTrue($result['success']);
        $this->assertTrue($result['connected']);
        $this->assertSame('connected', $result['status']);
        $this->assertSame('CS Utama', $result['data']['name']);
        $this->assertSame('6281234567890', $result['data']['phone_number']);
        $this->assertSame(5000, $result['data']['message_quota']);
    }

    public function test_whatsapp_service_handles_disconnected_device(): void
    {
        Setting::updateOrCreate(['key' => 'apikey_whatsapp'], ['value' => 'test-api-key-123']);

        Http::fake([
            '*/api/device/status' => Http::response([
                'success' => true,
                'message' => 'Status device berhasil diambil.',
                'data' => [
                    'id' => 2,
                    'name' => 'Device Cadangan',
                    'phone_number' => null,
                    'status' => 'disconnected',
                    'message_quota' => 1000,
                    'expired_at' => '2026-12-31T23:59:59.000Z',
                    'is_expired' => false,
                    'can_connect' => true,
                ],
            ], 200),
        ]);

        $result = WhatsAppService::checkDeviceStatus();

        $this->assertTrue($result['success']);
        $this->assertFalse($result['connected']);
        $this->assertSame('disconnected', $result['status']);
    }

    public function test_whatsapp_service_handles_api_error_response(): void
    {
        Setting::updateOrCreate(['key' => 'apikey_whatsapp'], ['value' => 'invalid-key']);

        Http::fake([
            '*/api/device/status' => Http::response([
                'success' => false,
                'message' => 'API Key tidak valid atau tidak ditemukan.',
            ], 400),
        ]);

        $result = WhatsAppService::checkDeviceStatus();

        $this->assertFalse($result['success']);
        $this->assertFalse($result['connected']);
        $this->assertSame('error', $result['status']);
        $this->assertSame('API Key tidak valid atau tidak ditemukan.', $result['message']);
    }

    public function test_whatsapp_service_handles_network_timeout(): void
    {
        Setting::updateOrCreate(['key' => 'apikey_whatsapp'], ['value' => 'any-key']);

        Http::fake([
            '*/api/device/status' => Http::failedConnection(),
        ]);

        $result = WhatsAppService::checkDeviceStatus();

        $this->assertFalse($result['success']);
        $this->assertFalse($result['connected']);
        $this->assertSame('error', $result['status']);
        $this->assertStringContainsString('Gagal terhubung ke server WhatsApp Gateway', $result['message']);
    }

    public function test_admin_can_access_whatsapp_status_endpoint(): void
    {
        Setting::updateOrCreate(['key' => 'apikey_whatsapp'], ['value' => 'test-api-key-123']);

        Http::fake([
            '*/api/device/status' => Http::response([
                'success' => true,
                'message' => 'Status device berhasil diambil.',
                'data' => [
                    'id' => 1,
                    'name' => 'CS Utama',
                    'phone_number' => '6281234567890',
                    'status' => 'connected',
                    'message_quota' => 5000,
                    'expired_at' => '2026-12-31T23:59:59.000Z',
                    'is_expired' => false,
                    'can_connect' => true,
                ],
            ], 200),
        ]);

        $response = $this->actingAs($this->admin)->getJson(route('admin.settings.whatsapp.status'));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'connected' => true,
            'status' => 'connected',
            'data' => [
                'name' => 'CS Utama',
                'phone_number' => '6281234567890',
            ],
        ]);
    }

    public function test_guest_cannot_access_whatsapp_status_endpoint(): void
    {
        $response = $this->get(route('admin.settings.whatsapp.status'));

        $response->assertRedirect(route('login'));
    }

    public function test_whatsapp_service_constructor_initializes_api_key(): void
    {
        Setting::updateOrCreate(['key' => 'apikey_whatsapp'], ['value' => 'db-token-abc']);

        $serviceFromDb = new WhatsAppService;
        $this->assertSame('db-token-abc', $serviceFromDb->getApiKey());

        $serviceWithCustomKey = new WhatsAppService('custom-token-xyz');
        $this->assertSame('custom-token-xyz', $serviceWithCustomKey->getApiKey());
    }
}
