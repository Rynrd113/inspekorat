<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\Galeri;
use App\Models\Album;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class GaleriControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $admin;
    protected $album;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin user
        $this->admin = User::factory()->create([
            'email' => 'admin@test.com',
            'role' => 'admin',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        // Create album
        $this->album = Album::factory()->create([
            'nama_album' => 'Test Album',
            'status' => 1,
        ]);

        // Fake storage for file uploads
        Storage::fake('public');
    }

    /** @test */
    public function it_can_display_galeri_index()
    {
        // Arrange
        Galeri::factory()->count(5)->create();

        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('admin.galeri.index'));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('admin.galeri.index');
        $response->assertViewHas('galeris');
    }

    /** @test */
    public function it_can_show_create_form()
    {
        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('admin.galeri.create'));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('admin.galeri.create');
    }

    /** @test */
    public function it_can_store_galeri_with_image_file()
    {
        // Arrange
        $file = UploadedFile::fake()->image('photo.jpg', 640, 480);

        $data = [
            'judul' => 'Test Gallery Image',
            'deskripsi' => 'Test description',
            'kategori' => 'Kegiatan',
            'album_id' => $this->album->id,
            'tanggal_publikasi' => now()->format('Y-m-d'),
            'file_galeri' => $file,
            'status' => 1,
        ];

        // Act
        $response = $this->actingAs($this->admin)
            ->post(route('admin.galeri.store'), $data);

        // Assert
        $response->assertRedirect(route('admin.galeri.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('galeris', [
            'judul' => 'Test Gallery Image',
            'kategori' => 'Kegiatan',
        ]);

        // Check file and thumbnail were stored
        $galeri = Galeri::where('judul', 'Test Gallery Image')->first();
        $this->assertNotNull($galeri->file_path);
        $this->assertNotNull($galeri->thumbnail);
        Storage::disk('public')->assertExists($galeri->file_path);
        Storage::disk('public')->assertExists($galeri->thumbnail);
    }

    /** @test */
    public function it_auto_generates_thumbnail_for_images()
    {
        // Arrange
        $file = UploadedFile::fake()->image('photo.jpg', 640, 480);

        $data = [
            'judul' => 'Image With Auto Thumbnail',
            'deskripsi' => 'Test',
            'kategori' => 'Test',
            'tanggal_publikasi' => now()->format('Y-m-d'),
            'file_galeri' => $file,
            'status' => 1,
        ];

        // Act
        $response = $this->actingAs($this->admin)
            ->post(route('admin.galeri.store'), $data);

        // Assert
        $galeri = Galeri::where('judul', 'Image With Auto Thumbnail')->first();
        $this->assertNotNull($galeri->thumbnail);
        // Auto thumbnail for images should be the same as file_path
        $this->assertEquals($galeri->file_path, $galeri->thumbnail);
    }

    /** @test */
    public function it_can_store_galeri_with_custom_thumbnail()
    {
        // Arrange
        $file = UploadedFile::fake()->image('photo.jpg', 640, 480);
        $thumbnail = UploadedFile::fake()->image('thumbnail.jpg', 300, 300);

        $data = [
            'judul' => 'Image With Custom Thumbnail',
            'deskripsi' => 'Test',
            'kategori' => 'Test',
            'tanggal_publikasi' => now()->format('Y-m-d'),
            'file_galeri' => $file,
            'thumbnail' => $thumbnail,
            'status' => 1,
        ];

        // Act
        $response = $this->actingAs($this->admin)
            ->post(route('admin.galeri.store'), $data);

        // Assert
        $galeri = Galeri::orderBy('id', 'desc')->first();
        $this->assertNotNull($galeri->thumbnail);
        // Custom thumbnail should be different from file_path
        $this->assertNotEquals($galeri->file_path, $galeri->thumbnail);
        Storage::disk('public')->assertExists($galeri->thumbnail);
    }

    /** @test */
    public function it_validates_required_fields_on_store()
    {
        // Act
        $response = $this->actingAs($this->admin)
            ->post(route('admin.galeri.store'), []);

        // Assert
        $response->assertSessionHasErrors(['judul', 'kategori', 'tanggal_publikasi', 'file_galeri']);
    }

    /** @test */
    public function it_can_show_galeri_detail()
    {
        // Arrange
        $galeri = Galeri::factory()->create();

        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('admin.galeri.show', $galeri->id));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('admin.galeri.show');
        $response->assertViewHas('galeri', $galeri);
    }

    /** @test */
    public function it_can_show_edit_form()
    {
        // Arrange
        $galeri = Galeri::factory()->create();

        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('admin.galeri.edit', $galeri->id));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('admin.galeri.edit');
        $response->assertViewHas('galeri', $galeri);
    }

    /** @test */
    public function it_can_update_galeri_with_new_file()
    {
        // Arrange
        $galeri = Galeri::factory()->create([
            'file_path' => 'galeri/old-file.jpg',
            'thumbnail' => 'galeri/old-file.jpg'
        ]);

        // Store old file
        Storage::disk('public')->put('galeri/old-file.jpg', 'fake content');

        $newFile = UploadedFile::fake()->image('new-file.jpg', 640, 480);

        $data = [
            'judul' => 'Updated Gallery',
            'deskripsi' => 'Updated description',
            'kategori' => 'Updated Category',
            'tanggal_publikasi' => now()->format('Y-m-d'),
            'file_galeri' => $newFile,
            'status' => 1,
        ];

        // Act
        $response = $this->actingAs($this->admin)
            ->put(route('admin.galeri.update', $galeri->id), $data);

        // Assert
        $response->assertRedirect(route('admin.galeri.index'));

        $updatedGaleri = $galeri->fresh();
        $this->assertNotEquals('galeri/old-file.jpg', $updatedGaleri->file_path);
        Storage::disk('public')->assertExists($updatedGaleri->file_path);
        Storage::disk('public')->assertMissing('galeri/old-file.jpg');
    }

    /** @test */
    public function it_can_delete_galeri_file()
    {
        // Arrange
        $galeri = Galeri::factory()->create([
            'file_path' => 'galeri/to-delete.jpg',
            'thumbnail' => 'galeri/to-delete.jpg'
        ]);

        // Store file
        Storage::disk('public')->put('galeri/to-delete.jpg', 'fake content');

        $data = [
            'judul' => $galeri->judul,
            'deskripsi' => $galeri->deskripsi,
            'kategori' => $galeri->kategori,
            'tanggal_publikasi' => $galeri->tanggal_publikasi->format('Y-m-d'),
            'delete_file' => 1,
            'status' => 1,
        ];

        // Act
        $response = $this->actingAs($this->admin)
            ->put(route('admin.galeri.update', $galeri->id), $data);

        // Assert
        $response->assertRedirect(route('admin.galeri.index'));

        $updatedGaleri = $galeri->fresh();
        $this->assertNull($updatedGaleri->file_path);
        $this->assertNull($updatedGaleri->file_name);
        $this->assertNull($updatedGaleri->file_type);
        $this->assertNull($updatedGaleri->file_size);
        Storage::disk('public')->assertMissing('galeri/to-delete.jpg');
    }

    /** @test */
    public function it_can_delete_galeri()
    {
        // Arrange
        $galeri = Galeri::factory()->create([
            'file_path' => 'galeri/delete-file.jpg',
            'thumbnail' => 'galeri/delete-thumbnail.jpg'
        ]);

        // Store files
        Storage::disk('public')->put('galeri/delete-file.jpg', 'fake content');
        Storage::disk('public')->put('galeri/delete-thumbnail.jpg', 'fake content');

        // Act
        $response = $this->actingAs($this->admin)
            ->delete(route('admin.galeri.destroy', $galeri->id));

        // Assert
        $response->assertRedirect(route('admin.galeri.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('galeris', ['id' => $galeri->id]);
        Storage::disk('public')->assertMissing('galeri/delete-file.jpg');
        Storage::disk('public')->assertMissing('galeri/delete-thumbnail.jpg');
    }

    /** @test */
    public function it_can_delete_galeri_without_files()
    {
        // Arrange
        $galeri = Galeri::factory()->create([
            'file_path' => null,
            'thumbnail' => null
        ]);

        // Act
        $response = $this->actingAs($this->admin)
            ->delete(route('admin.galeri.destroy', $galeri->id));

        // Assert
        $response->assertRedirect(route('admin.galeri.index'));
        $this->assertDatabaseMissing('galeris', ['id' => $galeri->id]);
    }

    /** @test */
    public function it_can_search_galeri()
    {
        // Arrange
        Galeri::factory()->create(['judul' => 'Gallery Testing']);
        Galeri::factory()->create(['judul' => 'Other Gallery']);

        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('admin.galeri.index', ['search' => 'Testing']));

        // Assert
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_filter_galeri_by_kategori()
    {
        // Arrange
        Galeri::factory()->create(['kategori' => 'Kegiatan']);
        Galeri::factory()->create(['kategori' => 'Dokumentasi']);

        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('admin.galeri.index', ['kategori' => 'Kegiatan']));

        // Assert
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_filter_galeri_by_tipe()
    {
        // Arrange
        Galeri::factory()->create(['file_type' => 'jpg']);
        Galeri::factory()->create(['file_type' => 'mp4']);

        // Act - Filter by foto (image types)
        $response = $this->actingAs($this->admin)
            ->get(route('admin.galeri.index', ['tipe' => 'foto']));

        // Assert
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_filter_galeri_by_status()
    {
        // Arrange
        Galeri::factory()->create(['status' => 1]);
        Galeri::factory()->create(['status' => 0]);

        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('admin.galeri.index', ['status' => '1']));

        // Assert
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_filter_galeri_by_album()
    {
        // Arrange
        $album1 = Album::factory()->create(['nama_album' => 'Album 1']);
        $album2 = Album::factory()->create(['nama_album' => 'Album 2']);

        Galeri::factory()->create(['album_id' => $album1->id]);
        Galeri::factory()->create(['album_id' => $album2->id]);

        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('admin.galeri.index', ['album_id' => $album1->id]));

        // Assert
        $response->assertStatus(200);
    }

    /** @test */
    public function it_requires_authentication()
    {
        // Act
        $response = $this->get(route('admin.galeri.index'));

        // Assert
        $this->assertTrue($response->status() === 302 || $response->status() === 401 || $response->status() === 403);
    }

    /** @test */
    public function it_validates_file_type()
    {
        // Arrange
        $invalidFile = UploadedFile::fake()->create('file.txt', 100);

        $data = [
            'judul' => 'Test',
            'deskripsi' => 'Test',
            'kategori' => 'Test',
            'tanggal_publikasi' => now()->format('Y-m-d'),
            'file_galeri' => $invalidFile,
            'status' => 1,
        ];

        // Act
        $response = $this->actingAs($this->admin)
            ->post(route('admin.galeri.store'), $data);

        // Assert
        $response->assertSessionHasErrors('file_galeri');
    }

    /** @test */
    public function it_validates_file_size()
    {
        // Arrange
        $largeFile = UploadedFile::fake()->image('large.jpg')->size(25000);

        $data = [
            'judul' => 'Test',
            'deskripsi' => 'Test',
            'kategori' => 'Test',
            'tanggal_publikasi' => now()->format('Y-m-d'),
            'file_galeri' => $largeFile,
            'status' => 1,
        ];

        // Act
        $response = $this->actingAs($this->admin)
            ->post(route('admin.galeri.store'), $data);

        // Assert
        $response->assertSessionHasErrors('file_galeri');
    }

    /** @test */
    public function it_can_update_galeri_without_changing_file()
    {
        // Arrange
        $galeri = Galeri::factory()->create([
            'file_path' => 'galeri/original-file.jpg',
            'thumbnail' => 'galeri/original-file.jpg'
        ]);

        Storage::disk('public')->put('galeri/original-file.jpg', 'fake content');

        $data = [
            'judul' => 'Updated Title Only',
            'deskripsi' => 'Updated description',
            'kategori' => $galeri->kategori,
            'tanggal_publikasi' => $galeri->tanggal_publikasi->format('Y-m-d'),
            'status' => 1,
        ];

        // Act
        $response = $this->actingAs($this->admin)
            ->put(route('admin.galeri.update', $galeri->id), $data);

        // Assert
        $response->assertRedirect(route('admin.galeri.index'));

        $updatedGaleri = $galeri->fresh();
        $this->assertEquals('galeri/original-file.jpg', $updatedGaleri->file_path);
    }

    /** @test */
    public function it_stores_file_metadata()
    {
        // Arrange
        $file = UploadedFile::fake()->image('photo.jpg', 640, 480);

        $data = [
            'judul' => 'Test Metadata',
            'deskripsi' => 'Test',
            'kategori' => 'Test',
            'tanggal_publikasi' => now()->format('Y-m-d'),
            'file_galeri' => $file,
            'status' => 1,
        ];

        // Act
        $this->actingAs($this->admin)
            ->post(route('admin.galeri.store'), $data);

        // Assert
        $galeri = Galeri::where('judul', 'Test Metadata')->first();
        $this->assertNotNull($galeri->file_name);
        $this->assertNotNull($galeri->file_type);
        $this->assertNotNull($galeri->file_size);
        $this->assertGreaterThan(0, $galeri->file_size);
    }

    /** @test */
    public function bulk_move_moves_photos_to_album()
    {
        // Arrange
        $album1 = Album::factory()->create();
        $album2 = Album::factory()->create();

        $photo1 = Galeri::factory()->create(['album_id' => $album1->id]);
        $photo2 = Galeri::factory()->create(['album_id' => $album1->id]);

        $data = [
            'photo_ids' => [$photo1->id, $photo2->id],
            'album_id' => $album2->id,
        ];

        // Act
        $response = $this->actingAs($this->admin)
            ->post(route('admin.galeri.bulkMove'), $data);

        // Assert
        $response->assertRedirect();
        $this->assertEquals($album2->id, $photo1->fresh()->album_id);
        $this->assertEquals($album2->id, $photo2->fresh()->album_id);
    }
}

