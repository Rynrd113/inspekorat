<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\Pelayanan;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PelayananControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create([
            'email' => 'admin@test.com',
            'role' => 'admin',
        ]);
    }

    /** @test */
    public function it_can_display_pelayanan_index()
    {
        // Arrange
        Pelayanan::factory()->count(5)->create();

        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('admin.pelayanan.index'));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('admin.pelayanan.index');
        $response->assertViewHas('pelayanan');
    }

    /** @test */
    public function it_can_show_create_form()
    {
        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('admin.pelayanan.create'));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('admin.pelayanan.create');
    }

    /** @test */
    public function it_can_store_pelayanan()
    {
        // Arrange
        Storage::fake('public');
        
        $data = [
            'nama' => 'Test Pelayanan',
            'deskripsi' => 'Test deskripsi',
            'persyaratan' => 'Test persyaratan',
            'waktu_pelayanan' => '2 hari',
            'biaya' => 'Gratis',
            'status' => 'aktif',
            'dokumen' => UploadedFile::fake()->create('test.pdf', 100),
        ];

        // Act
        $response = $this->actingAs($this->admin)
            ->post(route('admin.pelayanan.store'), $data);

        // Assert
        $response->assertRedirect(route('admin.pelayanan.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('pelayanan', [
            'nama' => 'Test Pelayanan',
            'deskripsi' => 'Test deskripsi',
        ]);
    }

    /** @test */
    public function it_validates_required_fields_on_store()
    {
        // Act
        $response = $this->actingAs($this->admin)
            ->post(route('admin.pelayanan.store'), []);

        // Assert
        $response->assertSessionHasErrors([
            'nama',
            'deskripsi',
            'persyaratan',
            'waktu_pelayanan',
            'biaya',
            'status',
        ]);
    }

    /** @test */
    public function it_can_show_pelayanan_detail()
    {
        // Arrange
        $pelayanan = Pelayanan::factory()->create();

        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('admin.pelayanan.show', $pelayanan));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('admin.pelayanan.show');
        $response->assertViewHas('pelayanan', $pelayanan);
    }

    /** @test */
    public function it_can_show_edit_form()
    {
        // Arrange
        $pelayanan = Pelayanan::factory()->create();

        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('admin.pelayanan.edit', $pelayanan));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('admin.pelayanan.edit');
        $response->assertViewHas('pelayanan', $pelayanan);
    }

    /** @test */
    public function it_can_update_pelayanan()
    {
        // Arrange
        $pelayanan = Pelayanan::factory()->create([
            'nama' => 'Original Name',
        ]);

        $data = [
            'nama' => 'Updated Name',
            'deskripsi' => 'Updated deskripsi',
            'persyaratan' => 'Updated persyaratan',
            'waktu_pelayanan' => '3 hari',
            'biaya' => 'Rp 50.000',
            'status' => 'aktif',
        ];

        // Act
        $response = $this->actingAs($this->admin)
            ->put(route('admin.pelayanan.update', $pelayanan), $data);

        // Assert
        $response->assertRedirect(route('admin.pelayanan.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('pelayanan', [
            'id' => $pelayanan->id,
            'nama' => 'Updated Name',
        ]);
    }

    /** @test */
    public function it_can_delete_pelayanan()
    {
        // Arrange
        $pelayanan = Pelayanan::factory()->create();

        // Act
        $response = $this->actingAs($this->admin)
            ->delete(route('admin.pelayanan.destroy', $pelayanan));

        // Assert
        $response->assertRedirect(route('admin.pelayanan.index'));
        $response->assertSessionHas('success');
        $this->assertSoftDeleted('pelayanan', ['id' => $pelayanan->id]);
    }

    /** @test */
    public function it_requires_authentication()
    {
        // Act
        $response = $this->get(route('admin.pelayanan.index'));

        // Assert
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function it_requires_admin_role()
    {
        // Arrange
        $user = User::factory()->create(['role' => 'user']);

        // Act
        $response = $this->actingAs($user)
            ->get(route('admin.pelayanan.index'));

        // Assert
        $response->assertStatus(403);
    }

    /** @test */
    public function it_can_search_pelayanan()
    {
        // Arrange
        Pelayanan::factory()->create(['nama' => 'Pelayanan Testing']);
        Pelayanan::factory()->create(['nama' => 'Other Service']);

        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('admin.pelayanan.index', ['search' => 'Testing']));

        // Assert
        $response->assertStatus(200);
        $response->assertSee('Pelayanan Testing');
        $response->assertDontSee('Other Service');
    }

    /** @test */
    public function it_handles_file_upload_on_create()
    {
        // Arrange
        Storage::fake('public');
        
        $file = UploadedFile::fake()->create('document.pdf', 100);
        $data = [
            'nama' => 'Test Pelayanan',
            'deskripsi' => 'Test deskripsi',
            'persyaratan' => 'Test persyaratan',
            'waktu_pelayanan' => '2 hari',
            'biaya' => 'Gratis',
            'status' => 'aktif',
            'dokumen' => $file,
        ];

        // Act
        $response = $this->actingAs($this->admin)
            ->post(route('admin.pelayanan.store'), $data);

        // Assert
        $response->assertRedirect(route('admin.pelayanan.index'));
        Storage::disk('public')->assertExists('pelayanan/' . $file->hashName());
    }
}
