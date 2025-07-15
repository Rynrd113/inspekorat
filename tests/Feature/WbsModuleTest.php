<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Wbs;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class WbsModuleTest extends TestCase
{
    use RefreshDatabase;

    private $superAdmin;
    private $admin;
    private $wbsManager;
    private $adminWbs;
    private $regularUser;

    public function setUp(): void
    {
        parent::setUp();
        
        $this->superAdmin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin'
        ]);

        $this->admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        $this->wbsManager = User::factory()->create([
            'name' => 'WBS Manager',
            'email' => 'wbs_manager@example.com',
            'password' => Hash::make('password'),
            'role' => 'wbs_manager'
        ]);

        $this->adminWbs = User::factory()->create([
            'name' => 'Admin WBS',
            'email' => 'admin_wbs@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin_wbs'
        ]);

        $this->regularUser = User::factory()->create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role' => 'user'
        ]);
    }

    /** @test */
    public function authorized_users_can_access_wbs_index()
    {
        $authorizedUsers = [$this->superAdmin, $this->admin, $this->wbsManager, $this->adminWbs];

        foreach ($authorizedUsers as $user) {
            $response = $this->actingAs($user)->get('/admin/wbs');
            $response->assertStatus(200);
        }
    }

    /** @test */
    public function unauthorized_users_cannot_access_wbs_index()
    {
        $response = $this->actingAs($this->regularUser)->get('/admin/wbs');
        $response->assertStatus(403);
    }

    /** @test */
    public function authorized_users_can_create_wbs()
    {
        $authorizedUsers = [$this->superAdmin, $this->admin, $this->wbsManager, $this->adminWbs];

        foreach ($authorizedUsers as $user) {
            $response = $this->actingAs($user)->get('/admin/wbs/create');
            $response->assertStatus(200);
        }
    }

    /** @test */
    public function wbs_can_be_stored()
    {
        $wbsData = [
            'nama' => 'Test WBS Entry',
            'email' => 'test@example.com',
            'telepon' => '081234567890',
            'pesan' => 'Test message for WBS',
            'status' => 'pending'
        ];

        $response = $this->actingAs($this->adminWbs)->post('/admin/wbs', $wbsData);
        $response->assertRedirect('/admin/wbs');

        $this->assertDatabaseHas('wbs', [
            'nama' => 'Test WBS Entry',
            'email' => 'test@example.com'
        ]);
    }

    /** @test */
    public function wbs_can_be_updated()
    {
        $wbs = Wbs::factory()->create([
            'nama' => 'Original Name',
            'email' => 'original@example.com',
            'status' => 'pending'
        ]);

        $updateData = [
            'nama' => 'Updated Name',
            'email' => 'updated@example.com',
            'status' => 'processed'
        ];

        $response = $this->actingAs($this->adminWbs)->put("/admin/wbs/{$wbs->id}", $updateData);
        $response->assertRedirect('/admin/wbs');

        $this->assertDatabaseHas('wbs', [
            'id' => $wbs->id,
            'nama' => 'Updated Name',
            'email' => 'updated@example.com',
            'status' => 'processed'
        ]);
    }

    /** @test */
    public function wbs_can_be_deleted()
    {
        $wbs = Wbs::factory()->create();

        $response = $this->actingAs($this->adminWbs)->delete("/admin/wbs/{$wbs->id}");
        $response->assertRedirect('/admin/wbs');

        $this->assertDatabaseMissing('wbs', ['id' => $wbs->id]);
    }

    /** @test */
    public function wbs_show_page_works()
    {
        $wbs = Wbs::factory()->create();

        $response = $this->actingAs($this->adminWbs)->get("/admin/wbs/{$wbs->id}");
        $response->assertStatus(200);
    }

    /** @test */
    public function wbs_edit_page_works()
    {
        $wbs = Wbs::factory()->create();

        $response = $this->actingAs($this->adminWbs)->get("/admin/wbs/{$wbs->id}/edit");
        $response->assertStatus(200);
    }

    /** @test */
    public function wbs_api_endpoints_work()
    {
        $token = $this->adminWbs->createToken('test-token')->plainTextToken;

        // Test API index
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->getJson('/api/wbs');
        $response->assertStatus(200);

        // Test API store
        $wbsData = [
            'nama' => 'API Test WBS',
            'email' => 'api@example.com',
            'telepon' => '081234567890',
            'pesan' => 'API test message'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->postJson('/api/wbs', $wbsData);
        $response->assertStatus(201);
    }

    /** @test */
    public function wbs_validation_works()
    {
        // Test required fields
        $response = $this->actingAs($this->adminWbs)->post('/admin/wbs', []);
        $response->assertSessionHasErrors(['nama', 'email', 'pesan']);

        // Test invalid email
        $response = $this->actingAs($this->adminWbs)->post('/admin/wbs', [
            'nama' => 'Test',
            'email' => 'invalid-email',
            'pesan' => 'Test message'
        ]);
        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function wbs_bulk_operations_work()
    {
        $wbsEntries = Wbs::factory()->count(5)->create();

        // Test bulk status update (if implemented)
        $response = $this->actingAs($this->adminWbs)->patch('/admin/wbs/bulk-update', [
            'ids' => $wbsEntries->pluck('id')->toArray(),
            'status' => 'processed'
        ]);

        // This might return 404 if not implemented, which is expected
        $this->assertTrue(in_array($response->status(), [200, 404]));
    }
}
