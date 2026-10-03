<?php

namespace Tests\Unit;

use App\Services\TiktokRapidApiService;
use App\Services\TiktokRapidApiServices;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TiktokRapidApiServiceTest extends TestCase
{
    protected TiktokRapidApiService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new TiktokRapidApiService(
            apiKey: 'test-api-key'
        );
    }

    public function test_clean_username_removes_at_symbol_and_whitespace(): void
    {
        $this->assertSame('mrizky_fr', $this->service->cleanUsername(' @mrizky_fr '));
        $this->assertSame('taylorswift', $this->service->cleanUsername('@taylorswift'));
        $this->assertSame('user.name', $this->service->cleanUsername('user.name'));
    }

    public function test_get_user_info_returns_data_when_api_is_successful(): void
    {
        $mockPayload = [
            'statusCode' => 0,
            'userInfo' => [
                'user' => [
                    'uniqueId' => 'taylorswift',
                    'nickname' => 'Taylor Swift',
                    'signature' => 'My verification code is 582910',
                    'avatarLarger' => 'https://example.com/avatar.jpg',
                ],
                'stats' => [
                    'followerCount' => 33000000,
                ],
            ],
        ];

        Http::fake([
            'https://tiktok-api23.p.rapidapi.com/api/user/info*' => Http::response($mockPayload, 200),
        ]);

        $result = $this->service->getUserInfo('taylorswift');

        $this->assertNotNull($result);
        $this->assertSame('Taylor Swift', $result['userInfo']['user']['nickname']);
        $this->assertSame('taylorswift', $result['userInfo']['user']['uniqueId']);
    }

    public function test_verify_bio_code_returns_success_when_code_matches(): void
    {
        $mockPayload = [
            'statusCode' => 0,
            'userInfo' => [
                'user' => [
                    'uniqueId' => 'taylorswift',
                    'nickname' => 'Taylor Swift',
                    'signature' => 'Check out my music! Bio code: 582910',
                    'avatarLarger' => 'https://example.com/avatar.jpg',
                ],
            ],
        ];

        Http::fake([
            'https://tiktok-api23.p.rapidapi.com/api/user/info*' => Http::response($mockPayload, 200),
        ]);

        $verification = $this->service->verifyBioCode('taylorswift', '582910');

        $this->assertTrue($verification['verified']);
        $this->assertSame('success', $verification['status']);
        $this->assertSame('Taylor Swift', $verification['user_data']['nickname']);
        $this->assertSame('https://example.com/avatar.jpg', $verification['user_data']['avatar_url']);
    }

    public function test_verify_bio_code_returns_code_not_found_when_code_is_missing(): void
    {
        $mockPayload = [
            'statusCode' => 0,
            'userInfo' => [
                'user' => [
                    'uniqueId' => 'taylorswift',
                    'nickname' => 'Taylor Swift',
                    'signature' => 'Just another regular bio without any code',
                ],
            ],
        ];

        Http::fake([
            'https://tiktok-api23.p.rapidapi.com/api/user/info*' => Http::response($mockPayload, 200),
        ]);

        $verification = $this->service->verifyBioCode('taylorswift', '999999');

        $this->assertFalse($verification['verified']);
        $this->assertSame('code_not_found', $verification['status']);
        $this->assertNull($verification['user_data']);
    }

    public function test_verify_bio_code_handles_api_failure_gracefully(): void
    {
        Http::fake([
            'https://tiktok-api23.p.rapidapi.com/api/user/info*' => Http::response('Server error', 500),
        ]);

        $verification = $this->service->verifyBioCode('taylorswift', '123456');

        $this->assertFalse($verification['verified']);
        $this->assertSame('api_error', $verification['status']);
        $this->assertNull($verification['user_data']);
    }

    public function test_alias_class_tiktok_rapid_api_services_works(): void
    {
        $aliasService = new TiktokRapidApiServices;
        $this->assertInstanceOf(TiktokRapidApiService::class, $aliasService);
    }
}
