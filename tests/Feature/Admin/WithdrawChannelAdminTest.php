<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\WithdrawChannel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WithdrawChannelAdminTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);
    }

    public function test_admin_can_view_withdraw_channels_list(): void
    {
        $channel = WithdrawChannel::factory()->create([
            'name' => 'Bank Central Asia',
            'code' => 'BCA',
            'fee' => 2500,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.withdraw-channels.index'));

        $response->assertStatus(200);
        $response->assertSee('Bank Central Asia');
        $response->assertSee('BCA');
        $response->assertSee('Rp 2.500');
    }

    public function test_admin_can_search_withdraw_channel_by_name(): void
    {
        WithdrawChannel::factory()->create([
            'name' => 'Bank Mandiri',
            'code' => 'MANDIRI',
        ]);
        WithdrawChannel::factory()->create([
            'name' => 'Bank Rakyat Indonesia',
            'code' => 'BRI',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.withdraw-channels.index', [
            'search' => 'Mandiri',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Bank Mandiri');
        $response->assertSee('MANDIRI');
        $response->assertDontSee('Bank Rakyat Indonesia');
        $response->assertSee('Menampilkan hasil pencarian untuk:');
    }

    public function test_admin_can_search_withdraw_channel_by_code(): void
    {
        WithdrawChannel::factory()->create([
            'name' => 'Bank Central Asia',
            'code' => 'BCA',
        ]);
        WithdrawChannel::factory()->create([
            'name' => 'Bank Negara Indonesia',
            'code' => 'BNI',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.withdraw-channels.index', [
            'search' => 'BNI',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Bank Negara Indonesia');
        $response->assertSee('BNI');
        $response->assertDontSee('Bank Central Asia');
    }

    public function test_empty_search_shows_appropriate_message(): void
    {
        WithdrawChannel::factory()->create([
            'name' => 'Bank Central Asia',
            'code' => 'BCA',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.withdraw-channels.index', [
            'search' => 'NonExistentBank',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Tidak ada data metode penarikan yang cocok dengan pencarian "NonExistentBank"', false);
    }

    public function test_search_and_per_page_parameters_are_preserved(): void
    {
        WithdrawChannel::factory()->count(25)->create([
            'name' => 'Bank Test Channel',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.withdraw-channels.index', [
            'search' => 'Test Channel',
            'per_page' => 10,
        ]));

        $response->assertStatus(200);
        $response->assertSee('Bank Test Channel');
        $response->assertSee('per_page=10');
        $this->assertTrue(
            str_contains($response->getContent(), 'search=Test+Channel') || str_contains($response->getContent(), 'search=Test%20Channel')
        );
    }
}
