<?php

namespace Tests\Feature;

use App\Enums\CampaignStatus;
use App\Models\ClipCampaign;
use App\Models\ClipSubmission;
use App\Models\User;
use App\Models\UserTiktokAccount;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ClipSubmissionValidationTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private ClipCampaign $campaign;

    protected function setUp(): void
    {
        parent::setUp();

        Queue::fake();

        $this->user = User::factory()->create([
            'role' => 'clipper',
        ]);

        $this->campaign = ClipCampaign::factory()->create([
            'status' => CampaignStatus::Active,
            'clipper_limit' => 10,
        ]);
    }

    public function test_guest_cannot_submit_clip(): void
    {
        $response = $this->post(route('app.campaigns.submissions.store', $this->campaign->slug), [
            'submitted_url' => 'https://www.tiktok.com/@creator/video/7400000000000000001',
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_user_without_verified_tiktok_account_is_rejected(): void
    {
        // User has an unverified TikTok account
        UserTiktokAccount::create([
            'user_id' => $this->user->id,
            'username' => 'creator_pending',
            'is_verified' => false,
        ]);

        $response = $this->actingAs($this->user)
            ->from(route('app.campaigns.show', $this->campaign->slug))
            ->post(route('app.campaigns.submissions.store', $this->campaign->slug), [
                'submitted_url' => 'https://www.tiktok.com/@creator_pending/video/7400000000000000001',
            ]);

        $response->assertRedirect(route('app.campaigns.show', $this->campaign->slug));
        $response->assertSessionHas('error', 'Anda belum memiliki akun TikTok yang terverifikasi. Silakan daftarkan dan verifikasi akun TikTok Anda terlebih dahulu di menu Profil.');
        $this->assertDatabaseCount('clip_submissions', 0);
    }

    public function test_user_cannot_submit_clip_from_different_tiktok_account(): void
    {
        // User has verified account @creator_legit
        UserTiktokAccount::create([
            'user_id' => $this->user->id,
            'username' => 'creator_legit',
            'is_verified' => true,
            'verified_at' => now(),
        ]);

        // Submitting video from @random_person
        $response = $this->actingAs($this->user)
            ->from(route('app.campaigns.show', $this->campaign->slug))
            ->post(route('app.campaigns.submissions.store', $this->campaign->slug), [
                'submitted_url' => 'https://www.tiktok.com/@random_person/video/7400000000000000002',
            ]);

        $response->assertRedirect(route('app.campaigns.show', $this->campaign->slug));
        $response->assertSessionHas('error');
        $this->assertTrue(str_contains(session('error'), '@random_person'));
        $this->assertTrue(str_contains(session('error'), '@creator_legit'));
        $this->assertDatabaseCount('clip_submissions', 0);
    }

    public function test_user_can_submit_clip_from_their_verified_tiktok_account(): void
    {
        UserTiktokAccount::create([
            'user_id' => $this->user->id,
            'username' => 'creator_valid',
            'is_verified' => true,
            'verified_at' => now(),
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('app.campaigns.submissions.store', $this->campaign->slug), [
                'submitted_url' => 'https://www.tiktok.com/@creator_valid/video/7400000000000000003?is_from_webapp=1',
            ]);

        $response->assertRedirect(route('app.submissions.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('clip_submissions', [
            'clip_campaign_id' => $this->campaign->id,
            'user_id' => $this->user->id,
            'video_id' => '7400000000000000003',
            'status' => 'pending',
        ]);
    }

    public function test_user_can_submit_short_vt_tiktok_url(): void
    {
        UserTiktokAccount::create([
            'user_id' => $this->user->id,
            'username' => 'creator_short',
            'is_verified' => true,
            'verified_at' => now(),
        ]);

        Http::fake([
            'https://vt.tiktok.com/ZSxyz123/' => Http::response('', 301, [
                'Location' => 'https://www.tiktok.com/@creator_short/video/7400000000000000004?_r=1',
            ]),
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('app.campaigns.submissions.store', $this->campaign->slug), [
                'submitted_url' => 'https://vt.tiktok.com/ZSxyz123/',
            ]);

        $response->assertRedirect(route('app.submissions.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('clip_submissions', [
            'clip_campaign_id' => $this->campaign->id,
            'user_id' => $this->user->id,
            'video_id' => '7400000000000000004',
            'status' => 'pending',
        ]);
    }

    public function test_user_cannot_submit_duplicate_video_id(): void
    {
        UserTiktokAccount::create([
            'user_id' => $this->user->id,
            'username' => 'creator_dup',
            'is_verified' => true,
            'verified_at' => now(),
        ]);

        // Pre-existing submission with video_id
        ClipSubmission::create([
            'clip_campaign_id' => $this->campaign->id,
            'user_id' => $this->user->id,
            'submitted_url' => 'https://www.tiktok.com/@creator_dup/video/7400000000000000005',
            'video_id' => '7400000000000000005',
            'status' => 'pending',
            'current_views' => 0,
            'credited_views' => 0,
            'total_earned' => 0,
        ]);

        $response = $this->actingAs($this->user)
            ->from(route('app.campaigns.show', $this->campaign->slug))
            ->post(route('app.campaigns.submissions.store', $this->campaign->slug), [
                'submitted_url' => 'https://www.tiktok.com/@creator_dup/video/7400000000000000005',
            ]);

        $response->assertRedirect(route('app.campaigns.show', $this->campaign->slug));
        $response->assertSessionHas('error');
        $this->assertDatabaseCount('clip_submissions', 1);
    }

    public function test_user_can_submit_tiktok_photo_url_from_their_verified_account(): void
    {
        UserTiktokAccount::create([
            'user_id' => $this->user->id,
            'username' => 'creator_photo',
            'is_verified' => true,
            'verified_at' => now(),
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('app.campaigns.submissions.store', $this->campaign->slug), [
                'submitted_url' => 'https://www.tiktok.com/@creator_photo/photo/7400000000000000006?is_from_webapp=1',
            ]);

        $response->assertRedirect(route('app.submissions.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('clip_submissions', [
            'clip_campaign_id' => $this->campaign->id,
            'user_id' => $this->user->id,
            'video_id' => '7400000000000000006',
            'status' => 'pending',
        ]);
    }

    public function test_user_cannot_submit_tiktok_photo_from_different_account(): void
    {
        UserTiktokAccount::create([
            'user_id' => $this->user->id,
            'username' => 'creator_legit',
            'is_verified' => true,
            'verified_at' => now(),
        ]);

        $response = $this->actingAs($this->user)
            ->from(route('app.campaigns.show', $this->campaign->slug))
            ->post(route('app.campaigns.submissions.store', $this->campaign->slug), [
                'submitted_url' => 'https://www.tiktok.com/@other_person/photo/7400000000000000007',
            ]);

        $response->assertRedirect(route('app.campaigns.show', $this->campaign->slug));
        $response->assertSessionHas('error');
        $this->assertTrue(str_contains(session('error'), '@other_person'));
        $this->assertDatabaseCount('clip_submissions', 0);
    }

    public function test_short_url_resolving_to_photo_is_saved_successfully(): void
    {
        UserTiktokAccount::create([
            'user_id' => $this->user->id,
            'username' => 'creator_slide',
            'is_verified' => true,
            'verified_at' => now(),
        ]);

        Http::fake([
            'https://vt.tiktok.com/ZSphoto123/' => Http::response('', 301, [
                'Location' => 'https://www.tiktok.com/@creator_slide/photo/7400000000000000008?_r=1',
            ]),
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('app.campaigns.submissions.store', $this->campaign->slug), [
                'submitted_url' => 'https://vt.tiktok.com/ZSphoto123/',
            ]);

        $response->assertRedirect(route('app.submissions.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('clip_submissions', [
            'clip_campaign_id' => $this->campaign->id,
            'user_id' => $this->user->id,
            'video_id' => '7400000000000000008',
            'status' => 'pending',
        ]);
    }
}
