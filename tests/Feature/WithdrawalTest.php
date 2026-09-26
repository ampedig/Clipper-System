<?php

namespace Tests\Feature;

use App\Models\ClipCampaign;
use App\Models\Setting;
use App\Models\User;
use App\Models\Withdrawal;
use App\Models\WithdrawChannel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WithdrawalTest extends TestCase
{
    use RefreshDatabase;

    public function test_withdrawal_page_requires_authentication(): void
    {
        $response = $this->get('/tarik-saldo');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_withdrawal_page(): void
    {
        $channel = WithdrawChannel::factory()->create([
            'name' => 'BCA',
            'fee' => 2500,
            'is_active' => true,
        ]);

        $user = User::factory()->create([
            'balance' => 200000,
            'withdraw_channel_id' => $channel->id,
            'account_number' => '1234567890',
            'account_name' => 'John Doe',
        ]);

        Setting::updateOrCreate(['key' => 'minimal_wd'], ['value' => '50000']);

        $response = $this->actingAs($user)->get('/tarik-saldo');

        $response->assertStatus(200);
        $response->assertSee('Tarik Saldo');
        $response->assertSee('200.000');
        $response->assertSee('BCA');
        $response->assertSee('1234567890');
    }

    public function test_withdrawal_redirects_if_rekening_not_set(): void
    {
        $user = User::factory()->create([
            'balance' => 200000,
            'withdraw_channel_id' => null,
            'account_number' => null,
            'account_name' => null,
        ]);

        $response = $this->actingAs($user)->post('/tarik-saldo', [
            'amount' => 50000,
        ]);

        $response->assertRedirect('/akun/rekening');
        $this->assertEquals(200000, $user->fresh()->balance);
        $this->assertDatabaseCount('withdrawals', 0);
    }

    public function test_withdrawal_fails_if_amount_below_minimal_wd(): void
    {
        $channel = WithdrawChannel::factory()->create(['is_active' => true]);
        $user = User::factory()->create([
            'balance' => 200000,
            'withdraw_channel_id' => $channel->id,
            'account_number' => '1234567890',
            'account_name' => 'John Doe',
        ]);

        Setting::updateOrCreate(['key' => 'minimal_wd'], ['value' => '50000']);

        $response = $this->actingAs($user)->post('/tarik-saldo', [
            'amount' => 40000,
        ]);

        $response->assertSessionHasErrors('amount');
        $this->assertEquals(200000, $user->fresh()->balance);
        $this->assertDatabaseCount('withdrawals', 0);
    }

    public function test_withdrawal_fails_if_amount_exceeds_balance(): void
    {
        $channel = WithdrawChannel::factory()->create(['is_active' => true]);
        $user = User::factory()->create([
            'balance' => 100000,
            'withdraw_channel_id' => $channel->id,
            'account_number' => '1234567890',
            'account_name' => 'John Doe',
        ]);

        Setting::updateOrCreate(['key' => 'minimal_wd'], ['value' => '50000']);

        $response = $this->actingAs($user)->post('/tarik-saldo', [
            'amount' => 150000,
        ]);

        $response->assertSessionHasErrors('amount');
        $this->assertEquals(100000, $user->fresh()->balance);
        $this->assertDatabaseCount('withdrawals', 0);
    }

    public function test_successful_withdrawal_deducts_balance_and_creates_records(): void
    {
        $channel = WithdrawChannel::factory()->create([
            'name' => 'BCA',
            'fee' => 2500,
            'is_active' => true,
        ]);

        $user = User::factory()->create([
            'balance' => 200000,
            'withdraw_channel_id' => $channel->id,
            'account_number' => '1234567890',
            'account_name' => 'John Doe',
        ]);

        Setting::updateOrCreate(['key' => 'minimal_wd'], ['value' => '50000']);

        $response = $this->actingAs($user)->post('/tarik-saldo', [
            'amount' => 100000,
        ]);

        $response->assertRedirect('/tarik-saldo');
        $response->assertSessionHas('success');

        // 1. Balance berkurang
        $this->assertEquals(100000, $user->fresh()->balance);

        // 2. Withdrawal record tersimpan
        $this->assertDatabaseHas('withdrawals', [
            'user_id' => $user->id,
            'amount' => 100000,
            'fee' => 2500,
            'net_amount' => 97500,
            'bank_name' => 'BCA',
            'account_number' => '1234567890',
            'account_name' => 'John Doe',
            'status' => 'pending',
        ]);

        // 3. Mutasi dompet debit tercatat
        $this->assertDatabaseHas('wallet_transactions', [
            'user_id' => $user->id,
            'type' => 'debit',
            'amount' => 100000,
            'balance_before' => 200000,
            'balance_after' => 100000,
        ]);
    }

    public function test_home_page_displays_real_user_balance_and_aggregated_stats(): void
    {
        $user = User::factory()->create([
            'name' => 'Budi Santoso',
            'balance' => 350000,
        ]);

        $campaign = ClipCampaign::factory()->create();

        // 2 approved submissions with 5000 views total
        $campaign->clipSubmissions()->create([
            'user_id' => $user->id,
            'submitted_url' => 'https://www.tiktok.com/@budi/video/1',
            'video_id' => 'video_1',
            'status' => 'approved',
            'current_views' => 3000,
            'credited_views' => 0,
            'total_earned' => 0,
        ]);

        $campaign->clipSubmissions()->create([
            'user_id' => $user->id,
            'submitted_url' => 'https://www.tiktok.com/@budi/video/2',
            'video_id' => 'video_2',
            'status' => 'completed',
            'current_views' => 2000,
            'credited_views' => 0,
            'total_earned' => 0,
        ]);

        // 1 pending submission with 1000 views
        $campaign->clipSubmissions()->create([
            'user_id' => $user->id,
            'submitted_url' => 'https://www.tiktok.com/@budi/video/3',
            'video_id' => 'video_3',
            'status' => 'pending',
            'current_views' => 1000,
            'credited_views' => 0,
            'total_earned' => 0,
        ]);

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertSee('Halo, Budi Santoso 👋');
        $response->assertSee('Rp350.000');
        $response->assertSee('6 K'); // total views: 3000 + 2000 + 1000 = 6000 -> 6 K
        $response->assertSee('2 Video'); // approved count: 2
        $response->assertSee('/tarik-saldo');
    }
}
