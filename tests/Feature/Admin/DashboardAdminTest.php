<?php

namespace Tests\Feature\Admin;

use App\Enums\CampaignStatus;
use App\Models\ClipCampaign;
use App\Models\ClipSubmission;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Models\Withdrawal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardAdminTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $clipper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->clipper = User::factory()->create([
            'role' => 'clipper',
            'is_active' => true,
        ]);
    }

    public function test_guest_cannot_view_admin_dashboard(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_clipper_cannot_view_admin_dashboard(): void
    {
        $response = $this->actingAs($this->clipper)->get(route('admin.dashboard'));

        $response->assertRedirect(route('app.home'));
    }

    public function test_admin_can_view_dashboard_with_dynamic_metrics(): void
    {
        // 1. Campaign Setup: 2 active, 1 inactive
        $campaignActive1 = ClipCampaign::factory()->create(['status' => CampaignStatus::Active]);
        ClipCampaign::factory()->create(['status' => CampaignStatus::Active]);
        ClipCampaign::factory()->create(['status' => CampaignStatus::Inactive]);

        // 2. Submissions: 1 bulan ini, 1 bulan lalu
        ClipSubmission::create([
            'clip_campaign_id' => $campaignActive1->id,
            'user_id' => $this->clipper->id,
            'submitted_url' => 'https://www.tiktok.com/@test/video/1234567890',
            'video_id' => '1234567890',
            'status' => 'pending',
            'current_views' => 100,
            'credited_views' => 0,
            'total_earned' => 0,
            'created_at' => now(),
        ]);
        ClipSubmission::create([
            'clip_campaign_id' => $campaignActive1->id,
            'user_id' => $this->clipper->id,
            'submitted_url' => 'https://www.tiktok.com/@test/video/9876543210',
            'video_id' => '9876543210',
            'status' => 'pending',
            'current_views' => 100,
            'credited_views' => 0,
            'total_earned' => 0,
            'created_at' => now()->subMonth(),
        ]);

        // 3. Wallet Transactions: 1 komisi kredit bulan ini (Rp 75.000), 1 refund bulan ini (Rp 50.000 - diabaikan), 1 komisi bulan lalu (Rp 30.000)
        WalletTransaction::factory()->create([
            'user_id' => $this->clipper->id,
            'type' => 'credit',
            'amount' => 75000,
            'notes' => 'Komisi +500 views - Test Campaign',
            'created_at' => now(),
        ]);
        WalletTransaction::factory()->create([
            'user_id' => $this->clipper->id,
            'type' => 'credit',
            'amount' => 50000,
            'notes' => 'Pengembalian saldo: Penarikan dana ditolak (Refund)',
            'created_at' => now(),
        ]);
        WalletTransaction::factory()->create([
            'user_id' => $this->clipper->id,
            'type' => 'credit',
            'amount' => 30000,
            'notes' => 'Komisi +200 views - Old Campaign',
            'created_at' => now()->subMonth(),
        ]);

        // 4. Withdrawal: 1 completed (Rp 120.000), 1 pending (Rp 80.000)
        Withdrawal::factory()->create([
            'user_id' => $this->clipper->id,
            'status' => 'completed',
            'amount' => 120000,
        ]);
        Withdrawal::factory()->create([
            'user_id' => $this->clipper->id,
            'status' => 'pending',
            'amount' => 80000,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);

        // Assert 4 cards exist with expected calculated values
        $response->assertSee('Campaign Aktif');
        $response->assertSee('Total Komisi');
        $response->assertSee('Klip Di Submit');
        $response->assertSee('Jumlah Withdraw');

        $response->assertSee('Bulan Ini');
        $response->assertSee('2'); // 2 active campaigns
        $response->assertSee('Rp 75.000'); // only valid commission of this month
        $response->assertSee('1'); // 1 submission this month
        $response->assertSee('Rp 120.000'); // completed withdrawal lifetime

        // Assert recent submissions table is rendered
        $response->assertSee('Pengajuan Klip Terbaru');
        $response->assertSee(route('admin.clip-submissions.index'));
        $response->assertSee('Tonton');
        $response->assertSee('Salin Link');

        // Assert dynamic charts are rendered
        $response->assertSee('Tren Pengajuan Klip');
        $response->assertSee('Status Pengajuan Klip');
        $response->assertSee('submissionTrendChart');
        $response->assertSee('submissionStatusChart');
        $response->assertSee('Klip Diajukan');
        $response->assertSee('Klip Disetujui');
        $response->assertSee('Menunggu Review');
    }
}
