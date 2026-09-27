<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WithdrawalAdminTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $clipper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->clipper = User::factory()->create(['role' => 'clipper', 'balance' => 100000]);
    }

    public function test_admin_can_view_withdrawals_list()
    {
        Withdrawal::factory()->create([
            'user_id' => $this->clipper->id,
            'amount' => 50000,
            'net_amount' => 50000,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.withdrawals.index'));

        $response->assertStatus(200);
        $response->assertSee('Penarikan Dana (Withdraw)');
    }

    public function test_non_admin_cannot_view_withdrawals_list()
    {
        $response = $this->actingAs($this->clipper)->get(route('admin.withdrawals.index'));
        $response->assertRedirect(route('app.home')); // Redirected because not admin
    }

    public function test_admin_can_update_withdrawal_to_completed()
    {
        $withdrawal = Withdrawal::factory()->create([
            'user_id' => $this->clipper->id,
            'amount' => 50000,
            'net_amount' => 50000,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->patch(route('admin.withdrawals.update-status', $withdrawal), [
            'status' => 'completed',
            'notes' => 'Transfer berhasil.',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('withdrawals', [
            'id' => $withdrawal->id,
            'status' => 'completed',
            'notes' => 'Transfer berhasil.',
        ]);
    }

    public function test_admin_can_reject_withdrawal_and_refund_balance()
    {
        $initialBalance = $this->clipper->balance;
        $amount = 50000;

        $withdrawal = Withdrawal::factory()->create([
            'user_id' => $this->clipper->id,
            'amount' => $amount,
            'net_amount' => $amount,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->patch(route('admin.withdrawals.update-status', $withdrawal), [
            'status' => 'rejected',
            'notes' => 'Rekening tidak valid.',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('withdrawals', [
            'id' => $withdrawal->id,
            'status' => 'rejected',
            'notes' => 'Rekening tidak valid.',
        ]);

        // Pastikan saldo kembali
        $this->assertEquals($initialBalance + $amount, $this->clipper->fresh()->balance);

        // Pastikan mutasi refund tercatat
        $this->assertDatabaseHas('wallet_transactions', [
            'user_id' => $this->clipper->id,
            'type' => 'credit',
            'amount' => $amount,
        ]);
    }

    public function test_cannot_update_already_completed_withdrawal()
    {
        $withdrawal = Withdrawal::factory()->create([
            'user_id' => $this->clipper->id,
            'amount' => 50000,
            'net_amount' => 50000,
            'status' => 'completed',
        ]);

        $response = $this->actingAs($this->admin)->patch(route('admin.withdrawals.update-status', $withdrawal), [
            'status' => 'rejected',
        ]);

        $response->assertSessionHas('error');

        // Tetap completed
        $this->assertDatabaseHas('withdrawals', [
            'id' => $withdrawal->id,
            'status' => 'completed',
        ]);
    }
}
