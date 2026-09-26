<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WalletTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_is_redirected_from_wallet_page(): void
    {
        $response = $this->get('/saldo');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_wallet_page_with_empty_state(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/saldo');

        $response->assertStatus(200);
        $response->assertSee('Riwayat Saldo');
        $response->assertSee('Belum Ada Riwayat Saldo');
        $response->assertSee('Mulai Ikut Campaign');
    }

    public function test_user_can_see_credit_and_debit_transactions_grouped_by_date(): void
    {
        $user = User::factory()->create();

        // 1. Credit transaction today
        WalletTransaction::create([
            'user_id' => $user->id,
            'type' => 'credit',
            'amount' => 25000,
            'balance_before' => 0,
            'balance_after' => 25000,
            'notes' => 'Komisi +25.000 views - Promo Spesial',
            'created_at' => now(),
        ]);

        // 2. Debit transaction yesterday
        WalletTransaction::create([
            'user_id' => $user->id,
            'type' => 'debit',
            'amount' => 50000,
            'balance_before' => 100000,
            'balance_after' => 50000,
            'notes' => 'Penarikan saldo ke Bank BCA (123456789)',
            'created_at' => now()->subDay(),
        ]);

        $response = $this->actingAs($user)->get('/saldo');

        $response->assertStatus(200);
        $response->assertSee('Hari Ini');
        $response->assertSee('Kemarin');
        $response->assertSee('+Rp 25.000');
        $response->assertSee('-Rp 50.000');
        $response->assertSee('Reward Komisi Klip');
        $response->assertSee('Penarikan Dana');
        $response->assertSee('Promo Spesial');
    }

    public function test_user_cannot_see_other_users_wallet_transactions(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        WalletTransaction::create([
            'user_id' => $userB->id,
            'type' => 'credit',
            'amount' => 99000,
            'balance_before' => 0,
            'balance_after' => 99000,
            'notes' => 'Private Transaction Note For User B',
            'created_at' => now(),
        ]);

        $response = $this->actingAs($userA)->get('/saldo');

        $response->assertStatus(200);
        $response->assertDontSee('Private Transaction Note For User B');
        $response->assertDontSee('99.000');
    }

    public function test_wallet_transactions_are_paginated_by_25_items(): void
    {
        $user = User::factory()->create();

        for ($i = 1; $i <= 30; $i++) {
            WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'credit',
                'amount' => 1000 * $i,
                'balance_before' => 0,
                'balance_after' => 1000 * $i,
                'notes' => "Transaksi #{$i}",
                'created_at' => now()->subMinutes(30 - $i),
            ]);
        }

        $response = $this->actingAs($user)->get('/saldo');

        $response->assertStatus(200);
        $response->assertSee('Transaksi #30');
        $response->assertDontSee('Transaksi #5');
    }

    public function test_ajax_infinite_scroll_returns_next_page_json(): void
    {
        $user = User::factory()->create();

        for ($i = 1; $i <= 30; $i++) {
            WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'credit',
                'amount' => 1000 * $i,
                'balance_before' => 0,
                'balance_after' => 1000 * $i,
                'notes' => "Transaksi #{$i}",
                'created_at' => now()->subMinutes(30 - $i),
            ]);
        }

        $response = $this->actingAs($user)->getJson('/saldo?page=2');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'html',
            'has_more',
            'next_page',
            'total',
        ]);
        $response->assertJson([
            'has_more' => false,
            'next_page' => null,
            'total' => 30,
        ]);
        $this->assertStringContainsString('Transaksi #5', $response->json('html'));
    }

    public function test_filter_by_income_and_outcome_works(): void
    {
        $user = User::factory()->create();

        WalletTransaction::create([
            'user_id' => $user->id,
            'type' => 'credit',
            'amount' => 15000,
            'balance_before' => 0,
            'balance_after' => 15000,
            'notes' => 'Unique Credit Income Note',
            'created_at' => now(),
        ]);

        WalletTransaction::create([
            'user_id' => $user->id,
            'type' => 'debit',
            'amount' => 20000,
            'balance_before' => 50000,
            'balance_after' => 30000,
            'notes' => 'Unique Debit Outcome Note',
            'created_at' => now(),
        ]);

        // Filter income
        $incomeResponse = $this->actingAs($user)->getJson('/saldo?type=income');
        $incomeResponse->assertStatus(200);
        $this->assertStringContainsString('Unique Credit Income Note', $incomeResponse->json('html'));
        $this->assertStringNotContainsString('Unique Debit Outcome Note', $incomeResponse->json('html'));

        // Filter outcome
        $outcomeResponse = $this->actingAs($user)->getJson('/saldo?type=outcome');
        $outcomeResponse->assertStatus(200);
        $this->assertStringContainsString('Unique Debit Outcome Note', $outcomeResponse->json('html'));
        $this->assertStringNotContainsString('Unique Credit Income Note', $outcomeResponse->json('html'));
    }
}
