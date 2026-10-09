<?php

namespace Tests\Feature\App;

use App\Jobs\SendWhatsAppMessageJob;
use App\Models\Setting;
use App\Models\User;
use App\Models\WithdrawChannel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class RekeningOtpTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private WithdrawChannel $channel;

    protected function setUp(): void
    {
        parent::setUp();

        $this->channel = WithdrawChannel::create([
            'name' => 'BCA',
            'code' => 'BCA',
            'fee' => 2500,
            'is_active' => true,
        ]);

        $this->user = User::factory()->create([
            'role' => 'clipper',
            'name' => 'John Doe',
            'whatsapp' => '081234567890',
            'email' => 'clipper@azclip.com',
        ]);

        Setting::updateOrCreate(['key' => 'apikey_whatsapp'], ['value' => 'test-api-key-123']);
    }

    public function test_guest_cannot_send_rekening_otp(): void
    {
        $response = $this->postJson(route('app.rekening.send-otp'), [
            'withdraw_channel_id' => $this->channel->id,
            'account_number' => '1234567890',
            'account_name' => 'John Doe',
        ]);

        $response->assertStatus(401);
    }

    public function test_user_cannot_send_otp_without_whatsapp_number(): void
    {
        $userWithoutWa = User::factory()->create([
            'role' => 'clipper',
            'whatsapp' => null,
        ]);

        $response = $this->actingAs($userWithoutWa)->postJson(route('app.rekening.send-otp'), [
            'withdraw_channel_id' => $this->channel->id,
            'account_number' => '1234567890',
            'account_name' => 'John Doe',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'Nomor WhatsApp belum terdaftar di profil Anda. Silakan lengkapi nomor WhatsApp terlebih dahulu.',
        ]);
    }

    public function test_user_can_request_rekening_otp_successfully(): void
    {
        Queue::fake([SendWhatsAppMessageJob::class]);

        $response = $this->actingAs($this->user)->postJson(route('app.rekening.send-otp'), [
            'withdraw_channel_id' => $this->channel->id,
            'account_number' => '1234567890',
            'account_name' => 'John Doe',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Kode OTP berhasil dikirim ke nomor WhatsApp Anda.',
            'cooldown' => 60,
        ]);

        // Verifikasi cache tersimpan
        $cache = Cache::get("rekening_otp:{$this->user->id}");
        $this->assertNotNull($cache);
        $this->assertNotEmpty($cache['otp']);
        $this->assertSame((string) $this->channel->id, (string) $cache['data']['withdraw_channel_id']);
        $this->assertSame('1234567890', $cache['data']['account_number']);

        // Verifikasi cooldown
        $this->assertTrue(Cache::has("rekening_otp_cooldown:{$this->user->id}"));

        // Verifikasi job ter-push ke antrean dengan nomor WA yang benar
        Queue::assertPushed(SendWhatsAppMessageJob::class, function ($job) use ($cache) {
            return $job->to === '081234567890' && str_contains($job->message, $cache['otp']);
        });
    }

    public function test_send_whatsapp_message_job_executes_service_successfully(): void
    {
        Http::fake([
            '*/api/send-message' => Http::response([
                'success' => true,
                'message' => 'Pesan terkirim.',
            ], 200),
        ]);

        $job = new SendWhatsAppMessageJob('081234567890', 'Tes pesan antrean');
        $job->handle();

        Http::assertSent(function ($request) {
            return $request->url() === 'https://amblast.ampedig.id/api/send-message'
                && $request['to'] === '6281234567890'
                && $request['message'] === 'Tes pesan antrean';
        });
    }

    public function test_send_whatsapp_message_job_throws_exception_on_failure(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Gagal mengirim pesan WhatsApp via queue');

        Http::fake([
            '*/api/send-message' => Http::response([
                'success' => false,
                'message' => 'Nomor tujuan tidak aktif.',
            ], 400),
        ]);

        $job = new SendWhatsAppMessageJob('081234567890', 'Tes pesan gagal');
        $job->handle();
    }

    public function test_user_cannot_request_otp_while_cooldown_is_active(): void
    {
        Cache::put("rekening_otp_cooldown:{$this->user->id}", true, now()->addSeconds(60));

        $response = $this->actingAs($this->user)->postJson(route('app.rekening.send-otp'), [
            'withdraw_channel_id' => $this->channel->id,
            'account_number' => '1234567890',
            'account_name' => 'John Doe',
        ]);

        $response->assertStatus(429);
        $response->assertJson([
            'success' => false,
            'message' => 'Mohon tunggu 60 detik sebelum meminta kode OTP kembali.',
        ]);
    }

    public function test_user_can_update_rekening_with_valid_otp(): void
    {
        Cache::put("rekening_otp:{$this->user->id}", [
            'otp' => '654321',
            'data' => [
                'withdraw_channel_id' => (string) $this->channel->id,
                'account_number' => '9876543210',
                'account_name' => 'John Doe Verified',
            ],
        ], now()->addMinutes(5));

        $response = $this->actingAs($this->user)->putJson(route('app.rekening.update'), [
            'withdraw_channel_id' => $this->channel->id,
            'account_number' => '9876543210',
            'account_name' => 'John Doe Verified',
            'otp' => '654321',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Rekening pencairan dana berhasil diperbarui.',
        ]);

        // Pastikan rekening user terupdate di database
        $this->user->refresh();
        $this->assertSame($this->channel->id, $this->user->withdraw_channel_id);
        $this->assertSame('9876543210', $this->user->account_number);
        $this->assertSame('John Doe Verified', $this->user->account_name);

        // Pastikan cache OTP langsung dihapus
        $this->assertNull(Cache::get("rekening_otp:{$this->user->id}"));
    }

    public function test_user_cannot_update_rekening_with_invalid_otp(): void
    {
        Cache::put("rekening_otp:{$this->user->id}", [
            'otp' => '654321',
            'data' => [
                'withdraw_channel_id' => (string) $this->channel->id,
                'account_number' => '9876543210',
                'account_name' => 'John Doe Verified',
            ],
        ], now()->addMinutes(5));

        $response = $this->actingAs($this->user)->putJson(route('app.rekening.update'), [
            'withdraw_channel_id' => $this->channel->id,
            'account_number' => '9876543210',
            'account_name' => 'John Doe Verified',
            'otp' => '111111',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'Kode OTP yang Anda masukkan salah. Silakan periksa kembali pesan WhatsApp Anda.',
        ]);

        // Pastikan data user tidak berubah
        $this->user->refresh();
        $this->assertNotSame('9876543210', $this->user->account_number);
    }

    public function test_user_cannot_update_rekening_when_otp_expired_or_not_requested(): void
    {
        Cache::forget("rekening_otp:{$this->user->id}");

        $response = $this->actingAs($this->user)->putJson(route('app.rekening.update'), [
            'withdraw_channel_id' => $this->channel->id,
            'account_number' => '9876543210',
            'account_name' => 'John Doe Verified',
            'otp' => '654321',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'Kode OTP telah kedaluwarsa atau belum diminta. Silakan minta kode baru.',
        ]);
    }

    public function test_user_cannot_update_rekening_with_tampered_bank_data(): void
    {
        Cache::put("rekening_otp:{$this->user->id}", [
            'otp' => '654321',
            'data' => [
                'withdraw_channel_id' => (string) $this->channel->id,
                'account_number' => '9876543210',
                'account_name' => 'John Doe Verified',
            ],
        ], now()->addMinutes(5));

        // Mencoba ubah nomor rekening berbeda saat submit dengan OTP yang sama
        $response = $this->actingAs($this->user)->putJson(route('app.rekening.update'), [
            'withdraw_channel_id' => $this->channel->id,
            'account_number' => '9999999999', // Rekening diubah diam-diam
            'account_name' => 'John Doe Verified',
            'otp' => '654321',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'Data rekening tidak sesuai dengan sesi verifikasi OTP. Silakan minta OTP baru.',
        ]);
    }
}
