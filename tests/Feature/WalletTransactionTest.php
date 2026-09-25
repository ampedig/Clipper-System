<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WalletTransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_wallet_transaction_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $transaction = WalletTransaction::factory()->create([
            'user_id' => $user->id,
            'type' => 'credit',
            'amount' => 50000,
            'balance_before' => 0,
            'balance_after' => 50000,
            'notes' => 'Komisi submission klip #1',
        ]);

        $this->assertInstanceOf(User::class, $transaction->user);
        $this->assertEquals($user->id, $transaction->user->id);
    }

    public function test_user_has_many_wallet_transactions(): void
    {
        $user = User::factory()->create();
        WalletTransaction::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);

        $this->assertCount(3, $user->walletTransactions);
    }

    public function test_can_record_credit_and_debit_transactions(): void
    {
        $user = User::factory()->create(['balance' => 0]);

        $creditTx = $user->walletTransactions()->create([
            'type' => 'credit',
            'amount' => 150000,
            'balance_before' => 0,
            'balance_after' => 150000,
            'notes' => 'Reward views campaign',
        ]);

        $user->update(['balance' => $creditTx->balance_after]);

        $this->assertDatabaseHas('wallet_transactions', [
            'id' => $creditTx->id,
            'user_id' => $user->id,
            'type' => 'credit',
            'amount' => 150000,
            'balance_before' => 0,
            'balance_after' => 150000,
            'notes' => 'Reward views campaign',
        ]);

        $debitTx = $user->walletTransactions()->create([
            'type' => 'debit',
            'amount' => 50000,
            'balance_before' => 150000,
            'balance_after' => 100000,
            'notes' => 'Penarikan saldo',
        ]);

        $user->update(['balance' => $debitTx->balance_after]);

        $this->assertDatabaseHas('wallet_transactions', [
            'id' => $debitTx->id,
            'user_id' => $user->id,
            'type' => 'debit',
            'amount' => 50000,
            'balance_before' => 150000,
            'balance_after' => 100000,
        ]);

        $this->assertEquals(100000, $user->fresh()->balance);
    }

    public function test_deleting_user_cascades_wallet_transactions(): void
    {
        $user = User::factory()->create();
        $transaction = WalletTransaction::factory()->create([
            'user_id' => $user->id,
        ]);

        $user->delete();

        $this->assertDatabaseMissing('wallet_transactions', [
            'id' => $transaction->id,
        ]);
    }
}
