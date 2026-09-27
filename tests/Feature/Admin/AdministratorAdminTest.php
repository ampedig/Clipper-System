<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdministratorAdminTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'balance' => 250000,
        ]);
    }

    public function test_admin_can_view_administrators_list_with_saldo(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.administrators.index'));

        $response->assertStatus(200);
        $response->assertSee('Data Administrator');
        $response->assertSee('Saldo');
        $response->assertSee('Super Admin');
        $response->assertSee('admin@example.com');
        $response->assertSee('Rp 250.000');
    }

    public function test_admin_can_search_administrators_by_name(): void
    {
        User::factory()->create([
            'role' => 'admin',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'whatsapp' => '0811111111',
        ]);
        User::factory()->create([
            'role' => 'admin',
            'name' => 'Zack Peterson',
            'email' => 'zack@example.com',
            'whatsapp' => '0833333333',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.administrators.index', ['search' => 'John']));

        $response->assertStatus(200);
        $response->assertSee('John Doe');
        $response->assertDontSee('Zack Peterson');
    }

    public function test_admin_can_search_administrators_by_email(): void
    {
        User::factory()->create([
            'role' => 'admin',
            'name' => 'Jane Smith',
            'email' => 'jane.smith@special.com',
            'whatsapp' => '0822222222',
        ]);
        User::factory()->create([
            'role' => 'admin',
            'name' => 'Zack Peterson',
            'email' => 'zack@example.com',
            'whatsapp' => '0833333333',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.administrators.index', ['search' => 'special.com']));

        $response->assertStatus(200);
        $response->assertSee('Jane Smith');
        $response->assertDontSee('Zack Peterson');
    }

    public function test_admin_can_search_administrators_by_whatsapp(): void
    {
        User::factory()->create([
            'role' => 'admin',
            'name' => 'Mark Spencer',
            'email' => 'mark@example.com',
            'whatsapp' => '08999888777',
        ]);
        User::factory()->create([
            'role' => 'admin',
            'name' => 'Zack Peterson',
            'email' => 'zack@example.com',
            'whatsapp' => '0833333333',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.administrators.index', ['search' => '08999888777']));

        $response->assertStatus(200);
        $response->assertSee('Mark Spencer');
        $response->assertDontSee('Zack Peterson');
    }

    public function test_search_and_per_page_parameters_are_preserved(): void
    {
        User::factory()->count(30)->create([
            'role' => 'admin',
            'name' => 'Preserved Admin',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.administrators.index', [
            'search' => 'Preserved',
            'per_page' => 10,
        ]));

        $response->assertStatus(200);
        $response->assertSee('Preserved Admin');
        // Pagination link should include both search and per_page
        $response->assertSee('search=Preserved');
        $response->assertSee('per_page=10');
    }
}
