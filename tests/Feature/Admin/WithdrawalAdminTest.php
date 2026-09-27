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

    public function test_admin_can_search_withdrawals_by_user_name(): void
    {
        $userA = User::factory()->create(['name' => 'Alice Wonderland', 'role' => 'clipper']);
        $userB = User::factory()->create(['name' => 'Bob Marley', 'role' => 'clipper']);

        Withdrawal::factory()->create([
            'user_id' => $userA->id,
            'amount' => 100000,
        ]);
        Withdrawal::factory()->create([
            'user_id' => $userB->id,
            'amount' => 200000,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.withdrawals.index', ['search' => 'Alice']));

        $response->assertStatus(200);
        $response->assertSee('Alice Wonderland');
        $response->assertDontSee('Bob Marley');
        $response->assertSee('Menampilkan hasil pencarian untuk:');
    }

    public function test_admin_can_search_withdrawals_by_user_email(): void
    {
        $userA = User::factory()->create(['email' => 'unique_clipper@domain.com', 'role' => 'clipper']);
        $userB = User::factory()->create(['email' => 'other_clipper@domain.com', 'role' => 'clipper']);

        Withdrawal::factory()->create(['user_id' => $userA->id]);
        Withdrawal::factory()->create(['user_id' => $userB->id]);

        $response = $this->actingAs($this->admin)->get(route('admin.withdrawals.index', ['search' => 'unique_clipper']));

        $response->assertStatus(200);
        $response->assertSee('unique_clipper@domain.com');
        $response->assertDontSee('other_clipper@domain.com');
    }

    public function test_admin_can_search_withdrawals_by_bank_name(): void
    {
        Withdrawal::factory()->create([
            'user_id' => $this->clipper->id,
            'bank_name' => 'Bank Mandiri',
        ]);
        Withdrawal::factory()->create([
            'user_id' => $this->clipper->id,
            'bank_name' => 'Bank Danamon',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.withdrawals.index', ['search' => 'Mandiri']));

        $response->assertStatus(200);
        $response->assertSee('Bank Mandiri');
        $response->assertDontSee('Bank Danamon');
    }

    public function test_admin_can_search_withdrawals_by_account_number(): void
    {
        Withdrawal::factory()->create([
            'user_id' => $this->clipper->id,
            'account_number' => '998877665544',
        ]);
        Withdrawal::factory()->create([
            'user_id' => $this->clipper->id,
            'account_number' => '112233445566',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.withdrawals.index', ['search' => '998877665544']));

        $response->assertStatus(200);
        $response->assertSee('998877665544');
        $response->assertDontSee('112233445566');
    }

    public function test_admin_can_search_withdrawals_by_nominal(): void
    {
        Withdrawal::factory()->create([
            'user_id' => $this->clipper->id,
            'amount' => 750000,
            'net_amount' => 750000,
        ]);
        Withdrawal::factory()->create([
            'user_id' => $this->clipper->id,
            'amount' => 125000,
            'net_amount' => 125000,
        ]);

        // Pencarian dengan format 750.000
        $response = $this->actingAs($this->admin)->get(route('admin.withdrawals.index', ['search' => '750.000']));

        $response->assertStatus(200);
        $response->assertSee('Rp 750.000');
        $response->assertDontSee('Rp 125.000');
    }

    public function test_admin_can_search_withdrawals_by_date(): void
    {
        $w1 = Withdrawal::factory()->create([
            'user_id' => $this->clipper->id,
            'bank_name' => 'SearchDateBankOne',
            'created_at' => now()->subDays(10),
        ]);
        $w2 = Withdrawal::factory()->create([
            'user_id' => $this->clipper->id,
            'bank_name' => 'SearchDateBankTwo',
            'created_at' => now(),
        ]);

        $dateString = $w1->created_at->format('Y-m-d');
        $response = $this->actingAs($this->admin)->get(route('admin.withdrawals.index', ['search' => $dateString]));

        $response->assertStatus(200);
        $response->assertSee('SearchDateBankOne');
        $response->assertDontSee('SearchDateBankTwo');
    }

    public function test_empty_search_shows_appropriate_message(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.withdrawals.index', ['search' => 'NoMatchingKeyword']));

        $response->assertStatus(200);
        $response->assertSee('Tidak ada riwayat penarikan dana yang cocok dengan pencarian "NoMatchingKeyword"', false);
    }

    public function test_search_status_and_per_page_parameters_are_preserved(): void
    {
        Withdrawal::factory()->count(25)->create([
            'user_id' => $this->clipper->id,
            'bank_name' => 'Bank Preserved Test',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.withdrawals.index', [
            'search' => 'Preserved Test',
            'status' => 'pending',
            'per_page' => 10,
        ]));

        $response->assertStatus(200);
        $response->assertSee('Bank Preserved Test');
        $response->assertSee('per_page=10');
        $response->assertSee('status=pending');
        $this->assertTrue(
            str_contains($response->getContent(), 'search=Preserved+Test') || str_contains($response->getContent(), 'search=Preserved%20Test')
        );
    }
}
