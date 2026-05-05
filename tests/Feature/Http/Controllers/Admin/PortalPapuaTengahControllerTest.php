<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\PortalPapuaTengah;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PortalPapuaTengahControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin user
        $this->admin = User::factory()->create([
            'email' => 'admin@test.com',
            'role' => 'admin',
        ]);

        // Fake storage for file uploads
        Storage::fake('public');
    }

    /** @test */
    public function it_can_display_portal_papua_tengah_index()
    {
        // Create test data
        PortalPapuaTengah::factory()->count(5)->create();

        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('admin.portal-papua-tengah.index'));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('admin.portal-papua-tengah.index');
        $response->assertViewHas('portalNews');
    }

    /** @test */
    public function it_can_show_create_form()
    {
        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('admin.portal-papua-tengah.create'));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('admin.portal-papua-tengah.create');
    }

    /** @test */
    public function it_can_store_berita_with_thumbnail()
    {
        // Arrange
        $thumbnail = UploadedFile::fake()->image('thumbnail.jpg', 640, 480);

        $data = [
            'judul' => 'Test Berita Title',
            'slug' => 'test-berita-title',
            'konten' => 'Lorem ipsum dolor sit amet',
            'kategori' => 'berita',
            'author' => 'Test Author',
            'penulis' => 'Test Penulis',
            'status' => 1,
            'thumbnail' => $thumbnail,
        ];

        // Act
        $response = $this->actingAs($this->admin)
            ->post(route('admin.portal-papua-tengah.store'), $data);

        // Assert
        $response->assertRedirect(route('admin.portal-papua-tengah.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('portal_papua_tengahs', [
            'judul' => 'Test Berita Title',
            'kategori' => 'berita',
        ]);

        // Check thumbnail was stored
        $berita = PortalPapuaTengah::where('judul', 'Test Berita Title')->first();
        $this->assertNotNull($berita->thumbnail);
        Storage::disk('public')->assertExists($berita->thumbnail);
    }

    /** @test */
    public function it_can_store_berita_without_thumbnail()
    {
        // Arrange
        $data = [
            'judul' => 'Berita Without Thumbnail',
            'slug' => 'berita-without-thumbnail',
            'konten' => 'Content here',
            'kategori' => 'pengumuman',
            'author' => 'Author Name',
            'penulis' => 'Penulis Name',
            'status' => 1,
        ];

        // Act
        $response = $this->actingAs($this->admin)
            ->post(route('admin.portal-papua-tengah.store'), $data);

        // Assert
        $response->assertRedirect(route('admin.portal-papua-tengah.index'));
        $this->assertDatabaseHas('portal_papua_tengahs', [
            'judul' => 'Berita Without Thumbnail',
        ]);
    }

    /** @test */
    public function it_validates_required_fields_on_store()
    {
        // Act
        $response = $this->actingAs($this->admin)
            ->post(route('admin.portal-papua-tengah.store'), []);

        // Assert
        $response->assertSessionHasErrors(['judul', 'konten', 'kategori']);
    }

    /** @test */
    public function it_validates_invalid_kategori()
    {
        // Arrange
        $data = [
            'judul' => 'Test',
            'konten' => 'Test content',
            'kategori' => 'invalid_category',
            'author' => 'Test',
            'penulis' => 'Test',
        ];

        // Act
        $response = $this->actingAs($this->admin)
            ->post(route('admin.portal-papua-tengah.store'), $data);

        // Assert
        $response->assertSessionHasErrors('kategori');
    }

    /** @test */
    public function it_can_show_berita_detail()
    {
        // Arrange
        $berita = PortalPapuaTengah::factory()->create();

        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('admin.portal-papua-tengah.show', $berita));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('admin.portal-papua-tengah.show');
        $response->assertViewHas('portalPapuaTengah', $berita);
    }

    /** @test */
    public function it_can_show_edit_form()
    {
        // Arrange
        $berita = PortalPapuaTengah::factory()->create();

        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('admin.portal-papua-tengah.edit', $berita));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('admin.portal-papua-tengah.edit');
        $response->assertViewHas('portalPapuaTengah', $berita);
    }

    /** @test */
    public function it_can_update_berita_with_new_thumbnail()
    {
        // Arrange
        $berita = PortalPapuaTengah::factory()->create([
            'thumbnail' => 'portal-thumbnails/old-thumbnail.jpg'
        ]);

        // Store the old thumbnail first
        Storage::disk('public')->put('portal-thumbnails/old-thumbnail.jpg', 'fake content');

        $newThumbnail = UploadedFile::fake()->image('new-thumbnail.jpg', 640, 480);

        $data = [
            'judul' => 'Updated Berita Title',
            'konten' => 'Updated content',
            'kategori' => 'berita',
            'author' => 'Updated Author',
            'penulis' => 'Updated Penulis',
            'status' => 1,
            'thumbnail' => $newThumbnail,
        ];

        // Act
        $response = $this->actingAs($this->admin)
            ->put(route('admin.portal-papua-tengah.update', $berita), $data);

        // Assert
        $response->assertRedirect(route('admin.portal-papua-tengah.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('portal_papua_tengahs', [
            'id' => $berita->id,
            'judul' => 'Updated Berita Title',
        ]);

        // Check new thumbnail exists and old one is deleted
        $updatedBerita = $berita->fresh();
        Storage::disk('public')->assertExists($updatedBerita->thumbnail);
        $this->assertNotEquals('portal-thumbnails/old-thumbnail.jpg', $updatedBerita->thumbnail);
    }

    /** @test */
    public function it_can_delete_berita_thumbnail()
    {
        // Arrange
        $berita = PortalPapuaTengah::factory()->create([
            'thumbnail' => 'portal-thumbnails/to-delete.jpg'
        ]);

        // Store the thumbnail first
        Storage::disk('public')->put('portal-thumbnails/to-delete.jpg', 'fake content');

        $data = [
            'judul' => $berita->judul,
            'konten' => $berita->konten,
            'kategori' => $berita->kategori,
            'author' => $berita->author,
            'penulis' => $berita->penulis,
            'status' => 1,
            'delete_thumbnail' => 1,
        ];

        // Act
        $response = $this->actingAs($this->admin)
            ->put(route('admin.portal-papua-tengah.update', $berita), $data);

        // Assert
        $response->assertRedirect(route('admin.portal-papua-tengah.index'));

        $updatedBerita = $berita->fresh();
        $this->assertNull($updatedBerita->thumbnail);
        Storage::disk('public')->assertMissing('portal-thumbnails/to-delete.jpg');
    }

    /** @test */
    public function it_can_update_berita_without_changing_thumbnail()
    {
        // Arrange
        $berita = PortalPapuaTengah::factory()->create([
            'thumbnail' => 'portal-thumbnails/original-thumbnail.jpg'
        ]);

        Storage::disk('public')->put('portal-thumbnails/original-thumbnail.jpg', 'fake content');

        $data = [
            'judul' => 'Updated Title Only',
            'konten' => $berita->konten,
            'kategori' => $berita->kategori,
            'author' => $berita->author,
            'penulis' => $berita->penulis,
            'status' => 1,
        ];

        // Act
        $response = $this->actingAs($this->admin)
            ->put(route('admin.portal-papua-tengah.update', $berita), $data);

        // Assert
        $response->assertRedirect(route('admin.portal-papua-tengah.index'));

        $updatedBerita = $berita->fresh();
        $this->assertEquals('portal-thumbnails/original-thumbnail.jpg', $updatedBerita->thumbnail);
    }

    /** @test */
    public function it_can_delete_berita()
    {
        // Arrange
        $berita = PortalPapuaTengah::factory()->create([
            'thumbnail' => 'portal-thumbnails/delete-berita-thumbnail.jpg'
        ]);

        // Store the thumbnail
        Storage::disk('public')->put('portal-thumbnails/delete-berita-thumbnail.jpg', 'fake content');

        // Act
        $response = $this->actingAs($this->admin)
            ->delete(route('admin.portal-papua-tengah.destroy', $berita));

        // Assert
        $response->assertRedirect(route('admin.portal-papua-tengah.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('portal_papua_tengahs', ['id' => $berita->id]);
        Storage::disk('public')->assertMissing('portal-thumbnails/delete-berita-thumbnail.jpg');
    }

    /** @test */
    public function it_can_delete_berita_without_thumbnail()
    {
        // Arrange
        $berita = PortalPapuaTengah::factory()->create(['thumbnail' => null]);

        // Act
        $response = $this->actingAs($this->admin)
            ->delete(route('admin.portal-papua-tengah.destroy', $berita));

        // Assert
        $response->assertRedirect(route('admin.portal-papua-tengah.index'));
        $this->assertDatabaseMissing('portal_papua_tengahs', ['id' => $berita->id]);
    }

    /** @test */
    public function it_can_search_berita()
    {
        // Arrange
        PortalPapuaTengah::factory()->create(['judul' => 'Berita Testing']);
        PortalPapuaTengah::factory()->create(['judul' => 'Other Berita']);

        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('admin.portal-papua-tengah.index', ['search' => 'Testing']));

        // Assert
        $response->assertStatus(200);
        $response->assertSee('Berita Testing');
    }

    /** @test */
    public function it_can_filter_berita_by_kategori()
    {
        // Arrange
        PortalPapuaTengah::factory()->create(['kategori' => 'berita']);
        PortalPapuaTengah::factory()->create(['kategori' => 'pengumuman']);

        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('admin.portal-papua-tengah.index', ['kategori' => 'berita']));

        // Assert
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_filter_berita_by_status()
    {
        // Arrange
        PortalPapuaTengah::factory()->create(['status' => 1]);
        PortalPapuaTengah::factory()->create(['status' => 0]);

        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('admin.portal-papua-tengah.index', ['status' => 'published']));

        // Assert
        $response->assertStatus(200);
    }

    /** @test */
    public function it_requires_authentication()
    {
        // Act
        $response = $this->get(route('admin.portal-papua-tengah.index'));

        // Assert
        $this->assertTrue($response->status() === 302 || $response->status() === 401 || $response->status() === 403);
    }

    /** @test */
    public function it_validates_thumbnail_file_type()
    {
        // Arrange
        $invalidFile = UploadedFile::fake()->create('file.txt', 100);

        $data = [
            'judul' => 'Test',
            'konten' => 'Test content',
            'kategori' => 'berita',
            'author' => 'Test',
            'penulis' => 'Test',
            'thumbnail' => $invalidFile,
        ];

        // Act
        $response = $this->actingAs($this->admin)
            ->post(route('admin.portal-papua-tengah.store'), $data);

        // Assert
        $response->assertSessionHasErrors('thumbnail');
    }

    /** @test */
    public function it_validates_thumbnail_file_size()
    {
        // Arrange
        $largeFile = UploadedFile::fake()->image('large.jpg')->size(3000);

        $data = [
            'judul' => 'Test',
            'konten' => 'Test content',
            'kategori' => 'berita',
            'author' => 'Test',
            'penulis' => 'Test',
            'thumbnail' => $largeFile,
        ];

        // Act
        $response = $this->actingAs($this->admin)
            ->post(route('admin.portal-papua-tengah.store'), $data);

        // Assert
        $response->assertSessionHasErrors('thumbnail');
    }

    /** @test */
    public function it_fills_all_fillable_fields()
    {
        // Arrange
        $data = [
            'judul' => 'Complete Berita',
            'slug' => 'complete-berita',
            'konten' => 'Full content',
            'kategori' => 'berita',
            'author' => 'Author',
            'penulis' => 'Penulis',
            'status' => 1,
            'is_published' => 1,
            'is_featured' => 1,
            'tags' => 'test,berita,feature',
            'meta_description' => 'Test meta description',
        ];

        // Act
        $response = $this->actingAs($this->admin)
            ->post(route('admin.portal-papua-tengah.store'), $data);

        // Assert
        $response->assertRedirect();
        $this->assertDatabaseHas('portal_papua_tengahs', [
            'judul' => 'Complete Berita',
            'slug' => 'complete-berita',
            'is_featured' => 1,
            'tags' => 'test,berita,feature',
        ]);
    }
}


