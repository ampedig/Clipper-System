<?php

namespace Tests\Feature\Admin;

use App\Models\ClipCampaign;
use App\Models\ClipSubmission;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Models\WithdrawChannel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClipperAdminTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $clipper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->clipper = User::factory()->create([
            'name' => 'Budi Clipper',
            'email' => 'budi@clipper.com',
            'role' => 'clipper',
            'is_active' => true,
            'balance' => 150000,
        ]);
    }

    public function test_admin_can_view_clippers_list(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.clippers.index'));

        $response->assertStatus(200);
        $response->assertSee('Data Clipper');
        $response->assertSee('Budi Clipper');
        $response->assertSee('budi@clipper.com');
        $response->assertSee('Rp 150.000');
    }

    public function test_non_admin_cannot_view_clippers_list(): void
    {
        $response = $this->actingAs($this->clipper)->get(route('admin.clippers.index'));

        $response->assertRedirect(route('app.home'));
    }

    public function test_admin_can_toggle_clipper_status(): void
    {
        $response = $this->actingAs($this->admin)->patchJson(route('admin.clippers.status', $this->clipper), [
            'is_active' => false,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertFalse($this->clipper->fresh()->is_active);
    }

    public function test_admin_can_search_clippers_by_name(): void
    {
        User::factory()->create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@example.com',
            'whatsapp' => '081233334444',
            'role' => 'clipper',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.clippers.index', ['search' => 'Siti']));

        $response->assertStatus(200);
        $response->assertSee('Siti Nurhaliza');
        $response->assertDontSee('Budi Clipper');
    }

    public function test_admin_can_search_clippers_by_email(): void
    {
        User::factory()->create([
            'name' => 'Dewi Lestari',
            'email' => 'dewi.lestari@creator.com',
            'whatsapp' => '081255556666',
            'role' => 'clipper',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.clippers.index', ['search' => 'creator.com']));

        $response->assertStatus(200);
        $response->assertSee('Dewi Lestari');
        $response->assertDontSee('Budi Clipper');
    }

    public function test_admin_can_search_clippers_by_whatsapp(): void
    {
        User::factory()->create([
            'name' => 'Eko Prasetyo',
            'email' => 'eko@example.com',
            'whatsapp' => '089876543210',
            'role' => 'clipper',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.clippers.index', ['search' => '089876543210']));

        $response->assertStatus(200);
        $response->assertSee('Eko Prasetyo');
        $response->assertDontSee('Budi Clipper');
    }

    public function test_clipper_search_and_per_page_parameters_are_preserved(): void
    {
        User::factory()->count(25)->create([
            'role' => 'clipper',
            'name' => 'Multi Page Clipper',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.clippers.index', [
            'search' => 'Multi Page',
            'per_page' => 10,
        ]));

        $response->assertStatus(200);
        $response->assertSee('Multi Page Clipper');
        $response->assertSee('Menampilkan hasil pencarian untuk:');
        $response->assertSee('per_page=10');
    }

    public function test_admin_can_delete_clipper(): void
    {
        $response = $this->actingAs($this->admin)->delete(route('admin.clippers.destroy', $this->clipper));

        $response->assertRedirect(route('admin.clippers.index'));
        $this->assertDatabaseMissing('users', [
            'id' => $this->clipper->id,
        ]);
    }

    public function test_admin_can_update_clipper_role_to_admin(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admin.clippers.update', $this->clipper), [
            'name' => 'Budi Diubah',
            'whatsapp' => '081234567890',
            'email' => 'budi_baru@clipper.com',
            'role' => 'admin',
        ]);

        $response->assertRedirect(route('admin.clippers.index'));
        $this->assertEquals('admin', $this->clipper->fresh()->role);
        $this->assertEquals('Budi Diubah', $this->clipper->fresh()->name);
    }

    public function test_admin_can_view_clipper_detail_page(): void
    {
        $channel = WithdrawChannel::factory()->create([
            'name' => 'BCA',
            'code' => 'BCA',
        ]);

        $this->clipper->update([
            'withdraw_channel_id' => $channel->id,
            'account_number' => '1234567890',
            'account_name' => 'Budi Clipper Santoso',
        ]);

        WalletTransaction::factory()->create([
            'user_id' => $this->clipper->id,
            'type' => 'credit',
            'amount' => 50000,
            'balance_after' => 50000,
            'notes' => 'Bonus Submission Klip',
        ]);

        $campaign = ClipCampaign::factory()->create([
            'title' => 'Kempen Ramadhan Seru',
        ]);

        ClipSubmission::create([
            'clip_campaign_id' => $campaign->id,
            'user_id' => $this->clipper->id,
            'submitted_url' => 'https://www.tiktok.com/@budi/video/99887766',
            'video_id' => '99887766',
            'status' => 'approved',
            'current_views' => 5000,
            'credited_views' => 5000,
            'total_earned' => 50000,
            'submitted_at' => now(),
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.clippers.show', $this->clipper));

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.clippers.show');
        $response->assertSee('Budi Clipper');
        $response->assertSee('budi@clipper.com');
        $response->assertSee('BCA');
        $response->assertSee('1234567890');
        $response->assertSee('Budi Clipper Santoso');
        $response->assertSee('Bonus Submission Klip');
        $response->assertSee('Kempen Ramadhan Seru');
        $response->assertSee('100%');
    }
}
