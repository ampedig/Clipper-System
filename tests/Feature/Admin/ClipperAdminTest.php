<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClipperAdminTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $clipper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->clipper = User::factory()->create([
            'name' => 'Budi Clipper',
            'email' => 'budi@clipper.com',
            'role' => 'clipper',
            'is_active' => true,
            'balance' => 150000,
        ]);
    }

    public function test_admin_can_view_clippers_list(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.clippers.index'));

        $response->assertStatus(200);
        $response->assertSee('Data Clipper');
        $response->assertSee('Budi Clipper');
        $response->assertSee('budi@clipper.com');
        $response->assertSee('Rp 150.000');
    }

    public function test_non_admin_cannot_view_clippers_list(): void
    {
        $response = $this->actingAs($this->clipper)->get(route('admin.clippers.index'));

        $response->assertRedirect(route('app.home'));
    }

    public function test_admin_can_toggle_clipper_status(): void
    {
        $response = $this->actingAs($this->admin)->patchJson(route('admin.clippers.status', $this->clipper), [
            'is_active' => false,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertFalse($this->clipper->fresh()->is_active);
    }

    public function test_admin_can_search_clippers(): void
    {
        User::factory()->create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@example.com',
            'role' => 'clipper',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.clippers.index', ['search' => 'Siti']));

        $response->assertStatus(200);
        $response->assertSee('Siti Nurhaliza');
        $response->assertDontSee('Budi Clipper');
    }

    public function test_admin_can_delete_clipper(): void
    {
        $response = $this->actingAs($this->admin)->delete(route('admin.clippers.destroy', $this->clipper));

        $response->assertRedirect(route('admin.clippers.index'));
        $this->assertDatabaseMissing('users', [
            'id' => $this->clipper->id,
        ]);
    }

    public function test_admin_can_update_clipper_role_to_admin(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admin.clippers.update', $this->clipper), [
            'name' => 'Budi Diubah',
            'whatsapp' => '081234567890',
            'email' => 'budi_baru@clipper.com',
            'role' => 'admin',
        ]);

        $response->assertRedirect(route('admin.clippers.index'));
        $this->assertEquals('admin', $this->clipper->fresh()->role);
        $this->assertEquals('Budi Diubah', $this->clipper->fresh()->name);
    }
}
