<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Buat Akun Baru');
        $response->assertSee('assets/images/logo.png');
        $response->assertSee(route('register'));
        $response->assertSee(route('login'));
        $response->assertSee('Nama Lengkap');
        $response->assertSee('Nomor WhatsApp');
        $response->assertSee('Daftar Sekarang');
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'whatsapp' => '081234567890',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'terms' => '1',
        ]);

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'whatsapp' => '081234567890',
            'role' => 'clipper',
        ]);
        $response->assertRedirect(route('app.home', absolute: false));
    }

    public function test_registration_fails_without_terms(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'whatsapp' => '081234567890',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors(['terms']);
    }

    public function test_registration_fails_with_invalid_whatsapp(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'whatsapp' => '123',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'terms' => '1',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors(['whatsapp']);
    }
}
