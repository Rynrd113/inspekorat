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

        $this->admin = User::factory()->admin()->create([
            'email' => 'admin@test.com',
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
            'nama_layanan' => 'Test Pelayanan',
            'deskripsi' => 'Test deskripsi',
            'persyaratan' => 'Persyaratan 1',
            'waktu_pelayanan' => '2 hari',
            'biaya' => 'Gratis',
            'kategori' => 'audit',
            'status' => '1',
            'file_formulir' => UploadedFile::fake()->create('test.pdf', 100),
        ];

        // Act
        $response = $this->actingAs($this->admin)
            ->post(route('admin.pelayanan.store'), $data);

        // Assert
        $response->assertRedirect(route('admin.pelayanan.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('pelayanans', [
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
            'nama_layanan',
            'deskripsi',
            'kategori',
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
            'nama_layanan' => 'Updated Name',
            'deskripsi' => 'Updated deskripsi',
            'waktu_pelayanan' => '3 hari',
            'biaya' => 'Rp 50.000',
            'kategori' => 'audit',
            'status' => '1',
        ];

        // Act
        $response = $this->actingAs($this->admin)
            ->put(route('admin.pelayanan.update', $pelayanan), $data);

        // Assert
        $response->assertRedirect(route('admin.pelayanan.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('pelayanans', [
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
        // Should redirect or show 401/403 status
        $this->assertTrue(
            $response->status() === 302 || $response->status() === 401 || $response->status() === 403
        );
    }

    /** @test */
    public function it_requires_admin_role()
    {
        // Arrange
        $user = User::factory()->create(['role' => 'content_admin']);

        // Act
        $response = $this->actingAs($user)
            ->get(route('admin.pelayanan.index'));

        // Assert
        $this->assertTrue($response->status() === 403 || $response->status() === 302);
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
    }

    /** @test */
    public function it_handles_file_upload_on_create()
    {
        // Arrange
        Storage::fake('public');

        $file = UploadedFile::fake()->create('document.pdf', 100);
        $data = [
            'nama_layanan' => 'Test Pelayanan',
            'deskripsi' => 'Test deskripsi',
            'waktu_pelayanan' => '2 hari',
            'biaya' => 'Gratis',
            'kategori' => 'audit',
            'status' => '1',
            'file_formulir' => $file,
        ];

        // Act
        $response = $this->actingAs($this->admin)
            ->post(route('admin.pelayanan.store'), $data);

        // Assert
        $response->assertRedirect(route('admin.pelayanan.index'));
        // File should be stored in pelayanan/formulir directory
        Storage::disk('public')->assertExists('pelayanan/formulir/' . $file->hashName());
    }
}
