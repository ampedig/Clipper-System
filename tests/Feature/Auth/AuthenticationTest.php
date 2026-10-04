<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Masuk ke Akun');
        $response->assertSee('assets/images/logo.png');
        $response->assertSee(route('login'));
        $response->assertSee(route('register'));
        $response->assertSee('Ingat saya');
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create(['role' => 'clipper']);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/');
    }

    public function test_admin_user_redirected_to_admin_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->post('/login', [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($admin);
        $response->assertRedirect(route('admin.dashboard', absolute: false));
    }

    public function test_users_can_authenticate_with_remember_me(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
            'remember' => '1',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/');
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }

    public function test_inactive_user_cannot_authenticate_and_sees_suspended_message(): void
    {
        $user = User::factory()->create([
            'is_active' => false,
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors(['email']);
        $this->assertTrue(str_contains(session('errors')->first('email'), 'ditangguhkan'));
    }

    public function test_active_user_suspended_mid_session_is_forced_to_logout(): void
    {
        $user = User::factory()->create([
            'is_active' => true,
            'role' => 'clipper',
        ]);

        // User is currently authenticated
        $this->actingAs($user);

        // Administrator suspends user
        $user->update(['is_active' => false]);

        // User makes next request to an authenticated page
        $response = $this->get('/akun');

        $response->assertRedirect(route('login'));
        $this->assertGuest();
        $response->assertSessionHas('error');
        $this->assertTrue(str_contains(session('error'), 'ditangguhkan'));
    }
}
