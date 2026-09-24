<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WithdrawChannel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_the_akun_page_returns_a_successful_response(): void
    {
        $response = $this->get('/akun');

        $response->assertStatus(200);
    }

    public function test_the_akun_edit_page_redirects_guest(): void
    {
        $response = $this->get('/akun/edit');

        $response->assertRedirect('/login');
    }

    public function test_the_akun_edit_page_returns_successful_response_for_authenticated_user(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/akun/edit');

        $response->assertStatus(200);
    }

    public function test_user_can_update_profile(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put('/akun/edit', [
            'name' => 'Nama Baru',
            'whatsapp' => '81234567890',
        ]);

        $response->assertRedirect('/akun');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Nama Baru',
            'whatsapp' => '081234567890',
        ]);
    }

    public function test_the_password_page_redirects_guest(): void
    {
        $response = $this->get('/akun/kata-sandi');

        $response->assertRedirect('/login');
    }

    public function test_the_password_page_returns_successful_response_for_authenticated_user(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/akun/kata-sandi');

        $response->assertStatus(200);
    }

    public function test_user_can_update_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password_lama'),
        ]);

        $response = $this->actingAs($user)->put('/akun/kata-sandi', [
            'current_password' => 'password_lama',
            'password' => 'password_baru123',
            'password_confirmation' => 'password_baru123',
        ]);

        $response->assertRedirect('/akun');
        $this->assertTrue(Hash::check('password_baru123', $user->fresh()->password));
    }

    public function test_the_policy_page_returns_a_successful_response(): void
    {
        $response = $this->get('/kebijakan-layanan');

        $response->assertStatus(200);
        $response->assertSee('Kebijakan Layanan');
        $response->assertSee('Daftar Ketentuan');
    }

    public function test_the_help_page_returns_a_successful_response(): void
    {
        $response = $this->get('/bantuan');

        $response->assertStatus(200);
        $response->assertSee('Pusat Bantuan');
        $response->assertSee('CS WhatsApp');
        $response->assertSee('CS Telegram');
    }

    public function test_the_rekening_page_redirects_guest(): void
    {
        $response = $this->get('/akun/rekening');

        $response->assertRedirect('/login');
    }

    public function test_the_rekening_page_returns_successful_response_for_authenticated_user(): void
    {
        $user = User::factory()->create();
        WithdrawChannel::factory()->create([
            'name' => 'Bank BCA',
            'fee' => 2500,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->get('/akun/rekening');

        $response->assertStatus(200);
        $response->assertSee('Atur Rekening');
        $response->assertSee('Bank BCA');
        $response->assertSee('Fee 2.500');
    }

    public function test_user_can_update_rekening(): void
    {
        $user = User::factory()->create();
        $channel = WithdrawChannel::factory()->create([
            'name' => 'Bank Mandiri',
            'fee' => 0,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->put('/akun/rekening', [
            'withdraw_channel_id' => $channel->id,
            'account_number' => '1234567890',
            'account_name' => 'Budi Santoso',
        ]);

        $response->assertRedirect('/akun/rekening');
        $response->assertSessionHas('status', 'rekening-updated');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'withdraw_channel_id' => $channel->id,
            'account_number' => '1234567890',
            'account_name' => 'Budi Santoso',
        ]);
    }
}
