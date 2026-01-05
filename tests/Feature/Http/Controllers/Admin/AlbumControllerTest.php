<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\Album;
use App\Models\User;
use App\Models\Galeri;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AlbumControllerTest extends TestCase
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
    public function it_can_display_albums_index()
    {
        // Arrange
        Album::factory()->count(5)->create();

        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('admin.albums.index'));

        // Assert
        $response->assertOk();
        $this->assertTrue(
            $response->original instanceof \Illuminate\View\View ||
            str_contains($response->getContent(), 'albums') ||
            str_contains($response->getContent(), 'Album')
        );
    }

    /** @test */
    public function it_can_search_albums()
    {
        // Arrange
        Album::factory()->create(['nama_album' => 'Kegiatan Inspeksi']);
        Album::factory()->create(['nama_album' => 'Rapat Koordinasi']);

        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('admin.albums.index', ['search' => 'Inspeksi']));

        // Assert
        $response->assertOk();
        $response->assertSee('Kegiatan Inspeksi');
    }

    /** @test */
    public function it_can_filter_albums_by_status()
    {
        // Arrange
        Album::factory()->create(['nama_album' => 'Album Aktif', 'status' => true]);
        Album::factory()->create(['nama_album' => 'Album Nonaktif', 'status' => false]);

        // Act - filter active only
        $response = $this->actingAs($this->admin)
            ->get(route('admin.albums.index', ['status' => 'active']));

        // Assert
        $response->assertOk();
        $response->assertSee('Album Aktif');
    }

    /** @test */
    public function it_can_show_create_form()
    {
        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('admin.albums.create'));

        // Assert
        $response->assertOk();
        $this->assertTrue(
            $response->original instanceof \Illuminate\View\View ||
            str_contains($response->getContent(), 'album') ||
            str_contains($response->getContent(), 'create') ||
            str_contains($response->getContent(), 'form')
        );
    }

    /** @test */
    public function it_can_store_album()
    {
        // Arrange
        Storage::fake('public');
        
        $data = [
            'nama_album' => 'Album Test Baru',
            'deskripsi' => 'Deskripsi album test',
            'tanggal_kegiatan' => '2025-12-30',
            'status' => true,
            'urutan' => 1,
        ];

        // Act
        $response = $this->actingAs($this->admin)
            ->post(route('admin.albums.store'), $data);

        // Assert
        $response->assertRedirect(route('admin.albums.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('albums', [
            'nama_album' => 'Album Test Baru',
            'deskripsi' => 'Deskripsi album test',
        ]);
    }

    /** @test */
    public function it_can_store_album_with_cover_image()
    {
        // Arrange
        Storage::fake('public');
        
        $data = [
            'nama_album' => 'Album Dengan Cover',
            'deskripsi' => 'Deskripsi album dengan cover',
            'cover_image' => UploadedFile::fake()->image('cover.jpg', 800, 600),
            'status' => true,
        ];

        // Act
        $response = $this->actingAs($this->admin)
            ->post(route('admin.albums.store'), $data);

        // Assert
        $response->assertRedirect(route('admin.albums.index'));
        $this->assertDatabaseHas('albums', [
            'nama_album' => 'Album Dengan Cover',
        ]);
        
        $album = Album::where('nama_album', 'Album Dengan Cover')->first();
        $this->assertNotNull($album->cover_image);
        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');
        $disk->assertExists($album->cover_image);
    }

    /** @test */
    public function it_can_store_album_with_parent()
    {
        // Arrange
        $parentAlbum = Album::factory()->create(['nama_album' => 'Parent Album']);
        
        $data = [
            'nama_album' => 'Sub Album',
            'deskripsi' => 'Deskripsi sub album',
            'parent_id' => $parentAlbum->id,
            'status' => true,
        ];

        // Act
        $response = $this->actingAs($this->admin)
            ->post(route('admin.albums.store'), $data);

        // Assert
        $response->assertRedirect(route('admin.albums.index'));
        $this->assertDatabaseHas('albums', [
            'nama_album' => 'Sub Album',
            'parent_id' => $parentAlbum->id,
        ]);
    }

    /** @test */
    public function it_validates_required_fields_on_store()
    {
        // Act
        $response = $this->actingAs($this->admin)
            ->post(route('admin.albums.store'), []);

        // Assert
        $response->assertSessionHasErrors(['nama_album']);
    }

    /** @test */
    public function it_can_show_album_detail()
    {
        // Arrange
        $album = Album::factory()->create();

        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('admin.albums.show', $album));

        // Assert
        $response->assertOk();
        $this->assertTrue(
            $response->original instanceof \Illuminate\View\View ||
            str_contains($response->getContent(), $album->nama_album) ||
            str_contains($response->getContent(), 'album')
        );
    }

    /** @test */
    public function it_can_show_edit_form()
    {
        // Arrange
        $album = Album::factory()->create();

        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('admin.albums.edit', $album));

        // Assert
        $response->assertOk();
        $this->assertTrue(
            $response->original instanceof \Illuminate\View\View ||
            str_contains($response->getContent(), 'edit') ||
            str_contains($response->getContent(), $album->nama_album)
        );
    }

    /** @test */
    public function it_can_update_album()
    {
        // Arrange
        $album = Album::factory()->create([
            'nama_album' => 'Album Lama',
        ]);
        
        $data = [
            'nama_album' => 'Album Diupdate',
            'deskripsi' => 'Deskripsi baru',
            'status' => true,
        ];

        // Act
        $response = $this->actingAs($this->admin)
            ->put(route('admin.albums.update', $album), $data);

        // Assert
        $response->assertRedirect(route('admin.albums.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('albums', [
            'id' => $album->id,
            'nama_album' => 'Album Diupdate',
            'deskripsi' => 'Deskripsi baru',
        ]);
    }

    /** @test */
    public function it_can_update_album_cover_image()
    {
        // Arrange
        Storage::fake('public');
        $album = Album::factory()->create();
        
        $data = [
            'nama_album' => $album->nama_album,
            'cover_image' => UploadedFile::fake()->image('new-cover.jpg', 800, 600),
            'status' => true,
        ];

        // Act
        $response = $this->actingAs($this->admin)
            ->put(route('admin.albums.update', $album), $data);

        // Assert
        $response->assertRedirect(route('admin.albums.index'));
        
        $album->refresh();
        $this->assertNotNull($album->cover_image);
        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');
        $disk->assertExists($album->cover_image);
    }

    /** @test */
    public function it_can_delete_album()
    {
        // Arrange
        $album = Album::factory()->create();

        // Act
        $response = $this->actingAs($this->admin)
            ->delete(route('admin.albums.destroy', $album));

        // Assert
        $response->assertRedirect(route('admin.albums.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('albums', ['id' => $album->id]);
    }

    /** @test */
    public function it_can_upload_photos_to_album()
    {
        // Arrange
        Storage::fake('public');
        $album = Album::factory()->create();
        
        $data = [
            'photos' => [
                UploadedFile::fake()->image('photo1.jpg', 800, 600),
                UploadedFile::fake()->image('photo2.jpg', 800, 600),
            ],
        ];

        // Act
        $response = $this->actingAs($this->admin)
            ->post(route('admin.albums.upload-photos', $album), $data);

        // Assert
        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        // Check photos were created
        $this->assertEquals(2, Galeri::where('album_id', $album->id)->count());
    }

    /** @test */
    public function guest_cannot_access_album_management()
    {
        // Arrange
        $album = Album::factory()->create();

        // Act & Assert - Index (should redirect to admin login)
        $response = $this->get(route('admin.albums.index'));
        $response->assertRedirect();
        $this->assertStringContainsString('login', $response->headers->get('Location'));

        // Act & Assert - Create
        $response = $this->get(route('admin.albums.create'));
        $response->assertRedirect();

        // Act & Assert - Store
        $response = $this->post(route('admin.albums.store'), []);
        $response->assertRedirect();

        // Act & Assert - Show
        $response = $this->get(route('admin.albums.show', $album));
        $response->assertRedirect();

        // Act & Assert - Edit
        $response = $this->get(route('admin.albums.edit', $album));
        $response->assertRedirect();

        // Act & Assert - Update
        $response = $this->put(route('admin.albums.update', $album), []);
        $response->assertRedirect();

        // Act & Assert - Delete
        $response = $this->delete(route('admin.albums.destroy', $album));
        $response->assertRedirect();
    }

    /** @test */
    public function it_generates_unique_slug_automatically()
    {
        // Arrange
        Album::factory()->create(['nama_album' => 'Same Name', 'slug' => 'same-name']);
        
        $data = [
            'nama_album' => 'Same Name',
            'status' => true,
        ];

        // Act
        $response = $this->actingAs($this->admin)
            ->post(route('admin.albums.store'), $data);

        // Assert
        $response->assertRedirect(route('admin.albums.index'));
        
        $newAlbum = Album::where('nama_album', 'Same Name')
            ->where('slug', '!=', 'same-name')
            ->first();
        $this->assertNotNull($newAlbum);
        $this->assertNotEquals('same-name', $newAlbum->slug);
    }
}
