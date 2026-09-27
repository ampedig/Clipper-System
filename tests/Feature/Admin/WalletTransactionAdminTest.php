<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WalletTransactionAdminTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Memastikan guest tidak dapat mengakses halaman riwayat saldo admin.
     */
    public function test_guest_cannot_access_riwayat_saldo(): void
    {
        $response = $this->get(route('admin.riwayat-saldo.index'));

        $response->assertRedirect('/login');
    }

    /**
     * Memastikan clipper/non-admin dialihkan saat mengakses halaman riwayat saldo admin.
     */
    public function test_non_admin_cannot_access_riwayat_saldo(): void
    {
        $clipper = User::factory()->create(['role' => 'clipper']);

        $response = $this->actingAs($clipper)->get(route('admin.riwayat-saldo.index'));

        $response->assertRedirect('/');
    }

    /**
     * Memastikan admin dapat mengakses halaman riwayat saldo dan melihat mutasi transaksi.
     */
    public function test_admin_can_view_riwayat_saldo_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $clipper = User::factory()->create(['name' => 'Budi Santoso', 'role' => 'clipper']);

        $tx = WalletTransaction::create([
            'user_id' => $clipper->id,
            'type' => 'credit',
            'amount' => 75000,
            'balance_before' => 0,
            'balance_after' => 75000,
            'notes' => 'Komisi submission video TikTok',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.riwayat-saldo.index'));

        $response->assertOk();
        $response->assertViewIs('dashboard.wallet_transactions.index');
        $response->assertSee('Budi Santoso');
        $response->assertSee('75.000');
    }

    /**
     * Memastikan filter tipe mutasi (credit & debit) berfungsi dengan benar.
     */
    public function test_admin_can_filter_transactions_by_type(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $clipper = User::factory()->create(['role' => 'clipper']);

        $creditTx = WalletTransaction::create([
            'user_id' => $clipper->id,
            'type' => 'credit',
            'amount' => 100000,
            'balance_before' => 0,
            'balance_after' => 100000,
            'notes' => 'Bonus Komisi',
        ]);

        $debitTx = WalletTransaction::create([
            'user_id' => $clipper->id,
            'type' => 'debit',
            'amount' => 50000,
            'balance_before' => 100000,
            'balance_after' => 50000,
            'notes' => 'Pencairan Dana WD',
        ]);

        // Filter Tambah / Credit
        $responseCredit = $this->actingAs($admin)->get(route('admin.riwayat-saldo.index', ['type' => 'tambah']));
        $responseCredit->assertOk();
        $responseCredit->assertSee('Bonus Komisi');
        $responseCredit->assertDontSee('Pencairan Dana WD');

        // Filter Kurang / Debit
        $responseDebit = $this->actingAs($admin)->get(route('admin.riwayat-saldo.index', ['type' => 'kurang']));
        $responseDebit->assertOk();
        $responseDebit->assertSee('Pencairan Dana WD');
        $responseDebit->assertDontSee('Bonus Komisi');
    }

    /**
     * Memastikan pencarian riwayat saldo berfungsi pada nama clipper atau notes.
     */
    public function test_admin_can_search_transactions(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $clipper1 = User::factory()->create(['name' => 'Ahmad Dani', 'role' => 'clipper']);
        $clipper2 = User::factory()->create(['name' => 'Siti Nurhaliza', 'role' => 'clipper']);

        WalletTransaction::create([
            'user_id' => $clipper1->id,
            'type' => 'credit',
            'amount' => 80000,
            'balance_before' => 0,
            'balance_after' => 80000,
            'notes' => 'Reward views campaign A',
        ]);

        WalletTransaction::create([
            'user_id' => $clipper2->id,
            'type' => 'credit',
            'amount' => 90000,
            'balance_before' => 0,
            'balance_after' => 90000,
            'notes' => 'Reward views campaign B',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.riwayat-saldo.index', ['search' => 'Ahmad']));
        $response->assertOk();
        $response->assertSee('Ahmad Dani');
        $response->assertDontSee('Siti Nurhaliza');
        $response->assertSee('Menampilkan hasil pencarian untuk:');
    }

    /**
     * Memastikan pencarian riwayat saldo berfungsi pada email clipper.
     */
    public function test_admin_can_search_transactions_by_user_email(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $clipper1 = User::factory()->create(['name' => 'User One', 'email' => 'clipper.one@gmail.com', 'role' => 'clipper']);
        $clipper2 = User::factory()->create(['name' => 'User Two', 'email' => 'clipper.two@yahoo.com', 'role' => 'clipper']);

        WalletTransaction::create([
            'user_id' => $clipper1->id,
            'type' => 'credit',
            'amount' => 50000,
            'balance_before' => 0,
            'balance_after' => 50000,
            'notes' => 'Reward views',
        ]);

        WalletTransaction::create([
            'user_id' => $clipper2->id,
            'type' => 'credit',
            'amount' => 60000,
            'balance_before' => 0,
            'balance_after' => 60000,
            'notes' => 'Reward views',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.riwayat-saldo.index', ['search' => 'clipper.one@gmail.com']));
        $response->assertOk();
        $response->assertSee('clipper.one@gmail.com');
        $response->assertDontSee('clipper.two@yahoo.com');
    }

    /**
     * Memastikan jika hasil pencarian kosong, pesan yang sesuai ditampilkan.
     */
    public function test_empty_search_shows_appropriate_message(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.riwayat-saldo.index', ['search' => 'NonExistentUser']));
        $response->assertOk();
        $response->assertSee('Tidak ada riwayat transaksi saldo yang cocok dengan pencarian "NonExistentUser"', false);
    }

    /**
     * Memastikan parameter search, type, dan per_page dipertahankan pada pagination link.
     */
    public function test_search_type_and_per_page_parameters_are_preserved(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $clipper = User::factory()->create(['name' => 'Test Preservation User', 'role' => 'clipper']);

        for ($i = 0; $i < 25; $i++) {
            WalletTransaction::create([
                'user_id' => $clipper->id,
                'type' => 'credit',
                'amount' => 10000,
                'balance_before' => $i * 10000,
                'balance_after' => ($i + 1) * 10000,
                'notes' => 'Reward mutasi '.$i,
            ]);
        }

        $response = $this->actingAs($admin)->get(route('admin.riwayat-saldo.index', [
            'search' => 'Preservation',
            'type' => 'tambah',
            'per_page' => 10,
        ]));

        $response->assertOk();
        $response->assertSee('Test Preservation User');
        $response->assertSee('per_page=10');
        $response->assertSee('type=tambah');
        $this->assertTrue(
            str_contains($response->getContent(), 'search=Preservation')
        );
    }
}
