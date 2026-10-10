<?php

namespace Tests\Feature;

use App\Enums\CampaignStatus;
use App\Models\ClipCampaign;
use App\Models\Setting;
use App\Models\User;
use App\Models\WithdrawChannel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_home_displays_join_group_button_when_link_grup_is_configured(): void
    {
        Setting::create(['key' => 'link_grup', 'value' => 'https://chat.whatsapp.com/testgroup123']);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Komunitas');
        $response->assertSee('https://chat.whatsapp.com/testgroup123');
    }

    public function test_home_does_not_display_join_group_button_when_link_grup_is_empty(): void
    {
        Setting::where('key', 'link_grup')->delete();

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('Komunitas');
    }

    public function test_the_akun_page_returns_a_successful_response(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/akun');

        $response->assertStatus(200);
    }

    public function test_the_akun_edit_page_redirects_guest(): void
    {
        $response = $this->get('/akun/edit');

        $response->assertRedirect('/login');
    }

    public function test_the_akun_edit_page_returns_successful_response_for_authenticated_user(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/akun/edit');

        $response->assertStatus(200);
    }

    public function test_user_can_update_profile(): void
    {
        $user = User::factory()->create([
            'whatsapp' => '081299998888',
        ]);

        $response = $this->actingAs($user)->put('/akun/edit', [
            'name' => 'Nama Baru',
            'whatsapp' => '081234567890',
        ]);

        $response->assertRedirect('/akun');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Nama Baru',
            'whatsapp' => '081299998888',
        ]);
    }

    public function test_the_password_page_redirects_guest(): void
    {
        $response = $this->get('/akun/kata-sandi');

        $response->assertRedirect('/login');
    }

    public function test_the_password_page_returns_successful_response_for_authenticated_user(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/akun/kata-sandi');

        $response->assertStatus(200);
    }

    public function test_user_can_update_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password_lama'),
        ]);

        $response = $this->actingAs($user)->put('/akun/kata-sandi', [
            'current_password' => 'password_lama',
            'password' => 'password_baru123',
            'password_confirmation' => 'password_baru123',
        ]);

        $response->assertRedirect('/akun');
        $this->assertTrue(Hash::check('password_baru123', $user->fresh()->password));
    }

    public function test_the_policy_page_returns_a_successful_response(): void
    {
        $response = $this->get('/kebijakan-layanan');

        $response->assertStatus(200);
        $response->assertSee('Kebijakan Layanan');
        $response->assertSee('Daftar Ketentuan');
    }

    public function test_the_help_page_returns_a_successful_response(): void
    {
        Setting::updateOrCreate(['key' => 'cs_whatsapp'], ['value' => '08123456789']);
        Setting::updateOrCreate(['key' => 'cs_telegram'], ['value' => '@cs_telegram']);

        $response = $this->get('/bantuan');

        $response->assertStatus(200);
        $response->assertSee('Pusat Bantuan');
        $response->assertSee('CS WhatsApp');
        $response->assertSee('CS Telegram');
    }

    public function test_the_rekening_page_redirects_guest(): void
    {
        $response = $this->get('/akun/rekening');

        $response->assertRedirect('/login');
    }

    public function test_the_rekening_page_returns_successful_response_for_authenticated_user(): void
    {
        $user = User::factory()->create();
        WithdrawChannel::factory()->create([
            'name' => 'Bank BCA',
            'fee' => 2500,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->get('/akun/rekening');

        $response->assertStatus(200);
        $response->assertSee('Atur Rekening');
        $response->assertSee('Bank BCA');
        $response->assertSee('Fee 2.500');
    }

    public function test_user_can_update_rekening(): void
    {
        $user = User::factory()->create();
        $channel = WithdrawChannel::factory()->create([
            'name' => 'Bank Mandiri',
            'fee' => 0,
            'is_active' => true,
        ]);

        Cache::put("rekening_otp:{$user->id}", [
            'otp' => '123456',
            'data' => [
                'withdraw_channel_id' => (string) $channel->id,
                'account_number' => '1234567890',
                'account_name' => 'Budi Santoso',
            ],
        ], now()->addMinutes(5));

        $response = $this->actingAs($user)->put('/akun/rekening', [
            'withdraw_channel_id' => $channel->id,
            'account_number' => '1234567890',
            'account_name' => 'Budi Santoso',
            'otp' => '123456',
        ]);

        $response->assertRedirect('/akun/rekening');
        $response->assertSessionHas('status', 'rekening-updated');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'withdraw_channel_id' => $channel->id,
            'account_number' => '1234567890',
            'account_name' => 'Budi Santoso',
        ]);
    }

    public function test_the_campaign_page_returns_successful_response_with_active_campaigns(): void
    {
        ClipCampaign::factory()->active()->create([
            'title' => 'Kampanye Video Edukasi',
            'commission_amount' => 15000,
            'view_threshold' => 1000,
        ]);

        $response = $this->get('/campaign');

        $response->assertStatus(200);
        $response->assertSee('Kampanye Video Edukasi');
        $response->assertSee('Rp15.000');
        $response->assertSee('1K views');
    }

    public function test_inactive_campaigns_are_not_displayed_on_campaign_page(): void
    {
        ClipCampaign::factory()->inactive()->create([
            'title' => 'Kampanye Tidak Aktif',
        ]);
        ClipCampaign::factory()->completed()->create([
            'title' => 'Kampanye Sudah Selesai',
        ]);

        $response = $this->get('/campaign');

        $response->assertStatus(200);
        $response->assertDontSee('Kampanye Tidak Aktif');
        $response->assertDontSee('Kampanye Sudah Selesai');
    }

    public function test_the_campaign_detail_page_returns_successful_response_using_slug(): void
    {
        $campaign = ClipCampaign::factory()->active()->create([
            'title' => 'Kampanye Produk Glowing',
            'slug' => 'kampanye-produk-glowing-xyz',
            'description' => 'Deskripsi kampanye produk glowing',
            'brief' => '<p>Wajib video vertikal</p>',
            'commission_amount' => 20000,
            'view_threshold' => 1000,
        ]);

        $response = $this->get('/campaign/'.$campaign->slug);

        $response->assertStatus(200);
        $response->assertSee('Kampanye Produk Glowing');
        $response->assertSee('Deskripsi kampanye produk glowing');
        $response->assertSee('Wajib video vertikal', false);
        $response->assertSee('Rp20.000');
    }

    public function test_inactive_campaign_detail_returns_404(): void
    {
        $inactiveCampaign = ClipCampaign::factory()->inactive()->create([
            'slug' => 'kampanye-nonaktif-slug',
        ]);

        $response = $this->get('/campaign/'.$inactiveCampaign->slug);

        $response->assertStatus(404);
    }

    public function test_non_existent_campaign_slug_returns_404(): void
    {
        $response = $this->get('/campaign/slug-yang-tidak-ada');

        $response->assertStatus(404);
    }

    public function test_user_can_view_own_clip_submission_detail(): void
    {
        $user = User::factory()->create();
        $campaign = ClipCampaign::factory()->create([
            'title' => 'Campaign Keren',
        ]);
        $submission = $campaign->clipSubmissions()->create([
            'user_id' => $user->id,
            'submitted_url' => 'https://www.tiktok.com/@user/video/1234567890',
            'video_id' => '1234567890',
            'status' => 'pending',
            'current_views' => 100,
            'credited_views' => 0,
            'total_earned' => 0,
        ]);

        $response = $this->actingAs($user)->get('/klip/'.$submission->id);

        $response->assertStatus(200);
        $response->assertSee('Campaign Keren');
    }

    public function test_user_cannot_view_others_clip_submission_detail(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $campaign = ClipCampaign::factory()->create();
        $submission = $campaign->clipSubmissions()->create([
            'user_id' => $owner->id,
            'submitted_url' => 'https://www.tiktok.com/@owner/video/9999999999',
            'video_id' => '9999999999',
            'status' => 'active',
            'current_views' => 500,
            'credited_views' => 0,
            'total_earned' => 0,
        ]);

        $response = $this->actingAs($otherUser)->get('/klip/'.$submission->id);

        $response->assertStatus(403);
    }

    public function test_user_can_view_paginated_submissions_and_infinite_scroll_ajax(): void
    {
        $user = User::factory()->create();
        $campaign = ClipCampaign::factory()->create();

        // Create 15 submissions
        for ($i = 1; $i <= 15; $i++) {
            $campaign->clipSubmissions()->create([
                'user_id' => $user->id,
                'submitted_url' => "https://www.tiktok.com/@owner/video/1000{$i}",
                'video_id' => "1000{$i}",
                'status' => 'pending',
                'current_views' => 0,
                'credited_views' => 0,
                'total_earned' => 0,
            ]);
        }

        // Test normal page 1 (non-AJAX)
        $response = $this->actingAs($user)->get('/klip');
        $response->assertStatus(200);

        // Test AJAX infinite scroll page 2
        $ajaxResponse = $this->actingAs($user)->getJson('/klip?page=2', [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);
        $ajaxResponse->assertStatus(200);
        $ajaxResponse->assertJsonStructure(['html', 'has_more', 'next_page', 'total']);
        $ajaxResponse->assertJson([
            'has_more' => false,
            'next_page' => null,
            'total' => 15,
        ]);
    }

    public function test_user_can_filter_submissions_by_status(): void
    {
        $user = User::factory()->create();
        $campaign = ClipCampaign::factory()->create();

        $campaign->clipSubmissions()->create([
            'user_id' => $user->id,
            'submitted_url' => 'https://www.tiktok.com/@owner/video/1111111111',
            'video_id' => '1111111111',
            'status' => 'rejected',
            'current_views' => 0,
            'credited_views' => 0,
            'total_earned' => 0,
        ]);

        $campaign->clipSubmissions()->create([
            'user_id' => $user->id,
            'submitted_url' => 'https://www.tiktok.com/@owner/video/2222222222',
            'video_id' => '2222222222',
            'status' => 'approved',
            'current_views' => 500,
            'credited_views' => 0,
            'total_earned' => 0,
        ]);

        $ajaxResponse = $this->actingAs($user)->getJson('/klip?status=ditolak', [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);

        $ajaxResponse->assertStatus(200);
        $ajaxResponse->assertJson([
            'total' => 1,
        ]);
        $ajaxResponse->assertSee('11111111');
        $ajaxResponse->assertDontSee('22222222');
    }

    public function test_user_can_view_paginated_campaigns_and_ajax_infinite_scroll(): void
    {
        $user = User::factory()->create();

        // Create 25 active campaigns
        for ($i = 1; $i <= 25; $i++) {
            ClipCampaign::factory()->create([
                'title' => "Campaign Auto {$i}",
                'status' => CampaignStatus::Active,
            ]);
        }

        // Test standard page 1 view
        $response = $this->actingAs($user)->get('/campaign');
        $response->assertStatus(200);
        $response->assertSee('Campaign Auto 25'); // latest first

        // Test AJAX page 2 infinite scroll
        $ajaxResponse = $this->actingAs($user)->getJson('/campaign?page=2', [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);
        $ajaxResponse->assertStatus(200);
        $ajaxResponse->assertJsonStructure(['html', 'has_more', 'next_page', 'total']);
        $ajaxResponse->assertJson([
            'has_more' => false,
            'next_page' => null,
            'total' => 25,
        ]);
    }

    public function test_user_can_search_campaigns_from_database(): void
    {
        $user = User::factory()->create();

        ClipCampaign::factory()->create([
            'title' => 'Sepatu Olahraga Pria Nike',
            'description' => 'Promosikan sepatu lari original',
            'status' => CampaignStatus::Active,
        ]);

        ClipCampaign::factory()->create([
            'title' => 'Kemeja Flannel Kasual Uniqlo',
            'description' => 'Outfit kasual pria dan wanita',
            'status' => CampaignStatus::Active,
        ]);

        $ajaxResponse = $this->actingAs($user)->getJson('/campaign?q=Nike', [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);

        $ajaxResponse->assertStatus(200);
        $ajaxResponse->assertJson([
            'total' => 1,
        ]);
        $ajaxResponse->assertSee('Sepatu Olahraga Pria Nike');
        $ajaxResponse->assertDontSee('Kemeja Flannel Kasual Uniqlo');
    }
}
