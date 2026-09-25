<?php

namespace Tests\Feature\Admin;

use App\Enums\CampaignStatus;
use App\Models\ClipCampaign;
use App\Models\ClipSubmission;
use App\Models\User;
use App\Services\TikTokScraperService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class ClipSubmissionCheckViewsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Memastikan guest tidak dapat mengakses endpoint check-views.
     */
    public function test_guest_cannot_check_views(): void
    {
        $campaign = ClipCampaign::factory()->create(['status' => CampaignStatus::Active]);
        $submission = ClipSubmission::create([
            'clip_campaign_id' => $campaign->id,
            'user_id' => User::factory()->create()->id,
            'submitted_url' => 'https://www.tiktok.com/@user/video/1234567890',
            'video_id' => '1234567890',
            'status' => 'active',
            'current_views' => 0,
            'credited_views' => 0,
            'total_earned' => 0,
        ]);

        $response = $this->postJson(route('admin.clip-submissions.check-views', $submission));

        $response->assertStatus(401);
    }

    /**
     * Memastikan user non-admin dialihkan saat mencoba mengakses endpoint check-views.
     */
    public function test_non_admin_cannot_check_views(): void
    {
        $user = User::factory()->create(['role' => 'clipper']);
        $campaign = ClipCampaign::factory()->create(['status' => CampaignStatus::Active]);
        $submission = ClipSubmission::create([
            'clip_campaign_id' => $campaign->id,
            'user_id' => $user->id,
            'submitted_url' => 'https://www.tiktok.com/@user/video/1234567890',
            'video_id' => '1234567890',
            'status' => 'active',
            'current_views' => 0,
            'credited_views' => 0,
            'total_earned' => 0,
        ]);

        $response = $this->actingAs($user)->post(route('admin.clip-submissions.check-views', $submission));

        $response->assertRedirect('/');
    }

    /**
     * Memastikan submission dengan status selain active (misal: pending) ditolak dengan 422.
     */
    public function test_cannot_check_views_for_non_active_submission(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $campaign = ClipCampaign::factory()->create(['status' => CampaignStatus::Active]);
        $submission = ClipSubmission::create([
            'clip_campaign_id' => $campaign->id,
            'user_id' => User::factory()->create()->id,
            'submitted_url' => 'https://www.tiktok.com/@user/video/1234567890',
            'video_id' => '1234567890',
            'status' => 'pending',
            'current_views' => 0,
            'credited_views' => 0,
            'total_earned' => 0,
        ]);

        $response = $this->actingAs($admin)->postJson(route('admin.clip-submissions.check-views', $submission));

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
            ]);
    }

    /**
     * Memastikan admin dapat cek views dan komisi berhasil dicairkan saat views mencapai kelipatan threshold.
     */
    public function test_admin_can_check_views_and_disburse_commission(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $clipper = User::factory()->create(['role' => 'clipper', 'balance' => 0]);

        // Campaign dengan threshold 1.000 views = Rp 25.000
        $campaign = ClipCampaign::factory()->create([
            'status' => CampaignStatus::Active,
            'view_threshold' => 1000,
            'commission_amount' => 25000,
            'view_max' => null,
            'end_at' => null,
        ]);

        $submission = ClipSubmission::create([
            'clip_campaign_id' => $campaign->id,
            'user_id' => $clipper->id,
            'submitted_url' => 'https://www.tiktok.com/@user/video/1234567890',
            'video_id' => '1234567890',
            'status' => 'active',
            'current_views' => 0,
            'credited_views' => 0,
            'total_earned' => 0,
        ]);

        // Mock ScraperService agar mengembalikan 2.500 views (berhak 2x kelipatan = 2.000 views = Rp 50.000)
        $scraperMock = Mockery::mock(TikTokScraperService::class);
        $scraperMock->shouldReceive('getVideoStats')
            ->once()
            ->with($submission->submitted_url)
            ->andReturn([
                'views' => 2500,
                'likes' => 100,
                'comments' => 20,
            ]);
        $this->app->instance(TikTokScraperService::class, $scraperMock);

        $response = $this->actingAs($admin)->postJson(route('admin.clip-submissions.check-views', $submission));

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'data' => [
                    'current_views' => 2500,
                    'credited_views' => 2000,
                    'delta_views' => 2000,
                    'earned_now' => 50000,
                    'total_earned' => 50000,
                ],
            ]);

        // Verifikasi saldo clipper bertambah
        $this->assertEquals(50000, $clipper->fresh()->balance);

        // Verifikasi catatan mutasi dompet dibuat
        $this->assertDatabaseHas('wallet_transactions', [
            'user_id' => $clipper->id,
            'type' => 'credit',
            'amount' => 50000,
        ]);

        // Verifikasi submission diperbarui
        $this->assertDatabaseHas('clip_submissions', [
            'id' => $submission->id,
            'current_views' => 2500,
            'credited_views' => 2000,
            'total_earned' => 50000,
        ]);
    }

    /**
     * Memastikan ketika views belum mencapai threshold baru, data views diperbarui tanpa pencairan komisi.
     */
    public function test_admin_can_check_views_without_commission_when_threshold_not_reached(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $clipper = User::factory()->create(['role' => 'clipper', 'balance' => 0]);

        $campaign = ClipCampaign::factory()->create([
            'status' => CampaignStatus::Active,
            'view_threshold' => 1000,
            'commission_amount' => 25000,
        ]);

        $submission = ClipSubmission::create([
            'clip_campaign_id' => $campaign->id,
            'user_id' => $clipper->id,
            'submitted_url' => 'https://www.tiktok.com/@user/video/1234567890',
            'video_id' => '1234567890',
            'status' => 'active',
            'current_views' => 0,
            'credited_views' => 0,
            'total_earned' => 0,
        ]);

        $scraperMock = Mockery::mock(TikTokScraperService::class);
        $scraperMock->shouldReceive('getVideoStats')
            ->once()
            ->with($submission->submitted_url)
            ->andReturn([
                'views' => 750, // Kurang dari threshold 1000
                'likes' => 30,
                'comments' => 5,
            ]);
        $this->app->instance(TikTokScraperService::class, $scraperMock);

        $response = $this->actingAs($admin)->postJson(route('admin.clip-submissions.check-views', $submission));

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'data' => [
                    'current_views' => 750,
                    'credited_views' => 0,
                    'delta_views' => 0,
                    'earned_now' => 0,
                    'total_earned' => 0,
                ],
            ]);

        // Saldo tetap 0 dan tidak ada transaksi dompet baru
        $this->assertEquals(0, $clipper->fresh()->balance);
        $this->assertDatabaseMissing('wallet_transactions', [
            'user_id' => $clipper->id,
        ]);

        // Current views terupdate
        $this->assertDatabaseHas('clip_submissions', [
            'id' => $submission->id,
            'current_views' => 750,
            'credited_views' => 0,
            'total_earned' => 0,
        ]);
    }
}
