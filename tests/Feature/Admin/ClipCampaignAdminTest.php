<?php

namespace Tests\Feature\Admin;

use App\Enums\CampaignStatus;
use App\Models\ClipCampaign;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClipCampaignAdminTest extends TestCase
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

    public function test_admin_can_view_clip_campaigns_index(): void
    {
        $creator = User::factory()->create(['name' => 'Creator One']);
        ClipCampaign::factory()->create([
            'title' => 'Campaign Brand Awesome',
            'created_by' => $creator->id,
            'status' => CampaignStatus::Active,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.clip-campaigns.index'));

        $response->assertStatus(200);
        $response->assertSee('Campaign Brand Awesome');
        $response->assertSee('Creator One');
    }

    public function test_admin_can_search_campaigns_by_title(): void
    {
        $creator = User::factory()->create();
        ClipCampaign::factory()->create([
            'title' => 'Unique Gaming Campaign',
            'created_by' => $creator->id,
            'status' => CampaignStatus::Active,
        ]);
        ClipCampaign::factory()->create([
            'title' => 'Cooking Daily Vlog',
            'created_by' => $creator->id,
            'status' => CampaignStatus::Active,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.clip-campaigns.index', ['search' => 'Gaming']));

        $response->assertStatus(200);
        $response->assertSee('Unique Gaming Campaign');
        $response->assertDontSee('Cooking Daily Vlog');
        $response->assertSee('Menampilkan hasil pencarian untuk:');
    }

    public function test_admin_can_search_campaigns_by_creator_name(): void
    {
        $creatorA = User::factory()->create(['name' => 'Aditya Pratama']);
        $creatorB = User::factory()->create(['name' => 'Bambang Sudirman']);

        ClipCampaign::factory()->create([
            'title' => 'Campaign Alpha',
            'created_by' => $creatorA->id,
            'status' => CampaignStatus::Active,
        ]);
        ClipCampaign::factory()->create([
            'title' => 'Campaign Beta',
            'created_by' => $creatorB->id,
            'status' => CampaignStatus::Active,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.clip-campaigns.index', ['search' => 'Aditya']));

        $response->assertStatus(200);
        $response->assertSee('Campaign Alpha');
        $response->assertSee('Aditya Pratama');
        $response->assertDontSee('Campaign Beta');
    }

    public function test_empty_search_shows_appropriate_message(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.clip-campaigns.index', ['search' => 'NonExistentTitle']));

        $response->assertStatus(200);
        $response->assertSee('Tidak ada data campaign clipper yang cocok dengan pencarian "NonExistentTitle"', false);
    }

    public function test_search_and_per_page_parameters_are_preserved(): void
    {
        $creator = User::factory()->create();
        ClipCampaign::factory()->count(25)->create([
            'title' => 'Preserved Title Test',
            'created_by' => $creator->id,
            'status' => CampaignStatus::Active,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.clip-campaigns.index', [
            'search' => 'Preserved Title',
            'per_page' => 10,
        ]));

        $response->assertStatus(200);
        $response->assertSee('Preserved Title Test');
        $response->assertSee('per_page=10');
        $this->assertTrue(
            str_contains($response->getContent(), 'search=Preserved+Title') || str_contains($response->getContent(), 'search=Preserved%20Title')
        );
    }
}
