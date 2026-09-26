<?php

namespace Tests\Feature;

use App\Jobs\SendTelegramMessageJob;
use App\Models\ClipCampaign;
use App\Models\Setting;
use App\Models\User;
use App\Models\Withdrawal;
use App\Models\WithdrawChannel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
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
        Queue::fake([SendTelegramMessageJob::class]);

        $channel = WithdrawChannel::factory()->create([
            'name' => 'BCA',
            'fee' => 2500,
            'is_active' => true,
        ]);

        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
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

        // 4. Notifikasi telegram ter-dispatch dengan topic withdraw dan sisa saldo
        Queue::assertPushed(SendTelegramMessageJob::class, function (SendTelegramMessageJob $job) {
            return $job->topicId === 3
                && str_contains($job->text, 'PERMOHONAN TARIK SALDO')
                && str_contains($job->text, 'John Doe')
                && str_contains($job->text, 'john@example.com')
                && str_contains($job->text, 'Rp100.000')
                && str_contains($job->text, 'Rp2.500')
                && str_contains($job->text, 'Rp97.500')
                && str_contains($job->text, 'Sisa Saldo:</b> Rp100.000');
        });
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

    public function test_withdrawal_history_requires_authentication(): void
    {
        $response = $this->get('/tarik-saldo/riwayat');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_withdrawal_history_with_statuses(): void
    {
        $user = User::factory()->create();

        // 1. Pending / processing withdrawal
        Withdrawal::create([
            'user_id' => $user->id,
            'amount' => 100000,
            'fee' => 2500,
            'net_amount' => 97500,
            'bank_name' => 'BCA',
            'account_number' => '1234567890',
            'account_name' => 'John Doe',
            'status' => 'pending',
            'created_at' => now(),
        ]);

        // 2. Completed withdrawal
        Withdrawal::create([
            'user_id' => $user->id,
            'amount' => 250000,
            'fee' => 2500,
            'net_amount' => 247500,
            'bank_name' => 'Mandiri',
            'account_number' => '0987654321',
            'account_name' => 'John Doe',
            'status' => 'completed',
            'created_at' => now()->subDay(),
        ]);

        // 3. Rejected withdrawal
        Withdrawal::create([
            'user_id' => $user->id,
            'amount' => 50000,
            'fee' => 0,
            'net_amount' => 50000,
            'bank_name' => 'BRI',
            'account_number' => '1122334455',
            'account_name' => 'John Doe',
            'status' => 'rejected',
            'notes' => 'Nomor rekening tidak terdaftar.',
            'created_at' => now()->subDays(2),
        ]);

        $response = $this->actingAs($user)->get('/tarik-saldo/riwayat');

        $response->assertStatus(200);
        $response->assertSee('Riwayat Penarikan');
        $response->assertSee('Diproses');
        $response->assertSee('Berhasil');
        $response->assertSee('Gagal');
        $response->assertSee('BCA • John Doe');
        $response->assertSee('Mandiri • John Doe');
        $response->assertSee('BRI • John Doe');
        $response->assertSee('-Rp100.000');
        $response->assertSee('-Rp250.000');
        $response->assertSee('-Rp50.000');
    }

    public function test_user_cannot_see_other_users_withdrawals(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        Withdrawal::create([
            'user_id' => $userB->id,
            'amount' => 999999,
            'fee' => 0,
            'net_amount' => 999999,
            'bank_name' => 'Secret Bank B',
            'account_number' => '88888888',
            'account_name' => 'Secret User B',
            'status' => 'completed',
        ]);

        $response = $this->actingAs($userA)->get('/tarik-saldo/riwayat');

        $response->assertStatus(200);
        $response->assertDontSee('Secret Bank B');
        $response->assertDontSee('Secret User B');
    }
}
