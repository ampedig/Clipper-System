<?php

namespace Tests\Feature\Admin;

use App\Enums\CampaignStatus;
use App\Models\ClipCampaign;
use App\Models\ClipSubmission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClipSubmissionAdminTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);
    }

    public function test_admin_can_view_clip_submissions_index(): void
    {
        $campaign = ClipCampaign::factory()->create([
            'title' => 'Viral Dance Challenge',
            'status' => CampaignStatus::Active,
        ]);
        $clipper = User::factory()->create([
            'name' => 'John Clipper',
            'role' => 'clipper',
        ]);

        ClipSubmission::create([
            'clip_campaign_id' => $campaign->id,
            'user_id' => $clipper->id,
            'submitted_url' => 'https://www.tiktok.com/@john/video/111111',
            'video_id' => '111111',
            'status' => 'pending',
            'current_views' => 1000,
            'credited_views' => 0,
            'total_earned' => 0,
            'submitted_at' => now(),
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.clip-submissions.index'));

        $response->assertStatus(200);
        $response->assertSee('John Clipper');
        $response->assertSee('Viral Dance Challenge');
    }

    public function test_admin_can_search_submissions_by_user_name(): void
    {
        $campaign = ClipCampaign::factory()->create(['status' => CampaignStatus::Active]);
        $clipper1 = User::factory()->create(['name' => 'Budi Santoso', 'role' => 'clipper']);
        $clipper2 = User::factory()->create(['name' => 'Jessica Wongso', 'role' => 'clipper']);

        ClipSubmission::create([
            'clip_campaign_id' => $campaign->id,
            'user_id' => $clipper1->id,
            'submitted_url' => 'https://www.tiktok.com/@budi/video/111',
            'video_id' => '111',
            'status' => 'pending',
            'current_views' => 0,
            'credited_views' => 0,
            'total_earned' => 0,
        ]);

        ClipSubmission::create([
            'clip_campaign_id' => $campaign->id,
            'user_id' => $clipper2->id,
            'submitted_url' => 'https://www.tiktok.com/@jessica/video/222',
            'video_id' => '222',
            'status' => 'pending',
            'current_views' => 0,
            'credited_views' => 0,
            'total_earned' => 0,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.clip-submissions.index', ['search' => 'Budi']));

        $response->assertStatus(200);
        $response->assertSee('Budi Santoso');
        $response->assertDontSee('Jessica Wongso');
        $response->assertSee('Menampilkan hasil pencarian untuk:');
    }

    public function test_admin_can_search_submissions_by_user_email(): void
    {
        $campaign = ClipCampaign::factory()->create(['status' => CampaignStatus::Active]);
        $clipper1 = User::factory()->create(['email' => 'special_clipper@mail.com', 'role' => 'clipper']);
        $clipper2 = User::factory()->create(['email' => 'other_clipper@mail.com', 'role' => 'clipper']);

        ClipSubmission::create([
            'clip_campaign_id' => $campaign->id,
            'user_id' => $clipper1->id,
            'submitted_url' => 'https://www.tiktok.com/@c1/video/111',
            'video_id' => '111',
            'status' => 'pending',
            'current_views' => 0,
            'credited_views' => 0,
            'total_earned' => 0,
        ]);

        ClipSubmission::create([
            'clip_campaign_id' => $campaign->id,
            'user_id' => $clipper2->id,
            'submitted_url' => 'https://www.tiktok.com/@c2/video/222',
            'video_id' => '222',
            'status' => 'pending',
            'current_views' => 0,
            'credited_views' => 0,
            'total_earned' => 0,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.clip-submissions.index', ['search' => 'special_clipper@mail.com']));

        $response->assertStatus(200);
        $response->assertSee('special_clipper@mail.com');
        $response->assertDontSee('other_clipper@mail.com');
    }

    public function test_admin_can_search_submissions_by_clip_campaign_title(): void
    {
        $campaignA = ClipCampaign::factory()->create([
            'title' => 'Super Epic Product Review',
            'status' => CampaignStatus::Active,
        ]);
        $campaignB = ClipCampaign::factory()->create([
            'title' => 'Cooking Vlog Series',
            'status' => CampaignStatus::Active,
        ]);

        $clipper = User::factory()->create(['role' => 'clipper']);

        ClipSubmission::create([
            'clip_campaign_id' => $campaignA->id,
            'user_id' => $clipper->id,
            'submitted_url' => 'https://www.tiktok.com/@clipper/video/111',
            'video_id' => '111',
            'status' => 'pending',
            'current_views' => 0,
            'credited_views' => 0,
            'total_earned' => 0,
        ]);

        ClipSubmission::create([
            'clip_campaign_id' => $campaignB->id,
            'user_id' => $clipper->id,
            'submitted_url' => 'https://www.tiktok.com/@clipper/video/222',
            'video_id' => '222',
            'status' => 'pending',
            'current_views' => 0,
            'credited_views' => 0,
            'total_earned' => 0,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.clip-submissions.index', ['search' => 'Epic Product']));

        $response->assertStatus(200);
        $response->assertSee('Super Epic Product Review');
        $response->assertDontSee('Cooking Vlog Series');
    }

    public function test_empty_search_shows_appropriate_message(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.clip-submissions.index', ['search' => 'KeywordNotFound']));

        $response->assertStatus(200);
        $response->assertSee('Tidak ada pengajuan video yang cocok dengan pencarian "KeywordNotFound"', false);
    }

    public function test_search_status_and_per_page_parameters_are_preserved(): void
    {
        $campaign = ClipCampaign::factory()->create([
            'title' => 'Preserved Search Campaign',
            'status' => CampaignStatus::Active,
        ]);
        $clipper = User::factory()->create(['role' => 'clipper']);

        for ($i = 0; $i < 25; $i++) {
            ClipSubmission::create([
                'clip_campaign_id' => $campaign->id,
                'user_id' => $clipper->id,
                'submitted_url' => 'https://www.tiktok.com/@clipper/video/'.$i,
                'video_id' => (string) $i,
                'status' => 'pending',
                'current_views' => 0,
                'credited_views' => 0,
                'total_earned' => 0,
            ]);
        }

        $response = $this->actingAs($this->admin)->get(route('admin.clip-submissions.index', [
            'search' => 'Preserved Search',
            'status' => 'pending',
            'per_page' => 10,
        ]));

        $response->assertStatus(200);
        $response->assertSee('Preserved Search Campaign');
        $response->assertSee('per_page=10');
        $response->assertSee('status=pending');
        $this->assertTrue(
            str_contains($response->getContent(), 'search=Preserved+Search') || str_contains($response->getContent(), 'search=Preserved%20Search')
        );
    }
}
