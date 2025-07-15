<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\PortalPapuaTengah;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PortalPapuaTengahTest extends TestCase
{
    use RefreshDatabase;

    private $superAdmin;
    private $admin;
    private $contentManager;
    private $adminBerita;
    private $regularUser;

    public function setUp(): void
    {
        parent::setUp();
        
        Storage::fake('public');

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

        $this->contentManager = User::factory()->create([
            'name' => 'Content Manager',
            'email' => 'content_manager@example.com',
            'password' => Hash::make('password'),
            'role' => 'content_manager'
        ]);

        $this->adminBerita = User::factory()->create([
            'name' => 'Admin Berita',
            'email' => 'admin_berita@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin_berita'
        ]);

        $this->regularUser = User::factory()->create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role' => 'user'
        ]);
    }

    /** @test */
    public function authorized_users_can_access_portal_papua_tengah_index()
    {
        $authorizedUsers = [$this->superAdmin, $this->admin, $this->contentManager, $this->adminBerita];

        foreach ($authorizedUsers as $user) {
            $response = $this->actingAs($user)->get('/admin/portal-papua-tengah');
            $response->assertStatus(200);
        }
    }

    /** @test */
    public function unauthorized_users_cannot_access_portal_papua_tengah_index()
    {
        $response = $this->actingAs($this->regularUser)->get('/admin/portal-papua-tengah');
        $response->assertStatus(403);
    }

    /** @test */
    public function authorized_users_can_create_portal_papua_tengah()
    {
        $authorizedUsers = [$this->superAdmin, $this->admin, $this->contentManager, $this->adminBerita];

        foreach ($authorizedUsers as $user) {
            $response = $this->actingAs($user)->get('/admin/portal-papua-tengah/create');
            $response->assertStatus(200);
        }
    }

    /** @test */
    public function portal_papua_tengah_can_be_stored()
    {
        $file = UploadedFile::fake()->image('test-image.jpg');

        $newsData = [
            'judul' => 'Test News Title',
            'konten' => 'Test news content',
            'kategori' => 'berita',
            'status' => 'published',
            'gambar' => $file
        ];

        $response = $this->actingAs($this->adminBerita)->post('/admin/portal-papua-tengah', $newsData);
        $response->assertRedirect('/admin/portal-papua-tengah');

        $this->assertDatabaseHas('portal_papua_tengah', [
            'judul' => 'Test News Title',
            'konten' => 'Test news content',
            'kategori' => 'berita',
            'status' => 'published'
        ]);
    }

    /** @test */
    public function portal_papua_tengah_can_be_updated()
    {
        $news = PortalPapuaTengah::factory()->create([
            'judul' => 'Original Title',
            'konten' => 'Original content',
            'kategori' => 'berita',
            'status' => 'draft'
        ]);

        $updateData = [
            'judul' => 'Updated Title',
            'konten' => 'Updated content',
            'kategori' => 'pengumuman',
            'status' => 'published'
        ];

        $response = $this->actingAs($this->adminBerita)->put("/admin/portal-papua-tengah/{$news->id}", $updateData);
        $response->assertRedirect('/admin/portal-papua-tengah');

        $this->assertDatabaseHas('portal_papua_tengah', [
            'id' => $news->id,
            'judul' => 'Updated Title',
            'konten' => 'Updated content',
            'kategori' => 'pengumuman',
            'status' => 'published'
        ]);
    }

    /** @test */
    public function portal_papua_tengah_can_be_deleted()
    {
        $news = PortalPapuaTengah::factory()->create();

        $response = $this->actingAs($this->adminBerita)->delete("/admin/portal-papua-tengah/{$news->id}");
        $response->assertRedirect('/admin/portal-papua-tengah');

        $this->assertDatabaseMissing('portal_papua_tengah', ['id' => $news->id]);
    }

    /** @test */
    public function portal_papua_tengah_show_page_works()
    {
        $news = PortalPapuaTengah::factory()->create();

        $response = $this->actingAs($this->adminBerita)->get("/admin/portal-papua-tengah/{$news->id}");
        $response->assertStatus(200);
    }

    /** @test */
    public function portal_papua_tengah_edit_page_works()
    {
        $news = PortalPapuaTengah::factory()->create();

        $response = $this->actingAs($this->adminBerita)->get("/admin/portal-papua-tengah/{$news->id}/edit");
        $response->assertStatus(200);
    }

    /** @test */
    public function portal_papua_tengah_api_endpoints_work()
    {
        $token = $this->adminBerita->createToken('test-token')->plainTextToken;

        // Test API index
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->getJson('/api/portal-papua-tengah');
        $response->assertStatus(200);

        // Test API store
        $newsData = [
            'judul' => 'API Test News',
            'konten' => 'API test content',
            'kategori' => 'berita',
            'status' => 'published'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->postJson('/api/portal-papua-tengah', $newsData);
        $response->assertStatus(201);
    }

    /** @test */
    public function portal_papua_tengah_validation_works()
    {
        // Test required fields
        $response = $this->actingAs($this->adminBerita)->post('/admin/portal-papua-tengah', []);
        $response->assertSessionHasErrors(['judul', 'konten']);

        // Test invalid kategori
        $response = $this->actingAs($this->adminBerita)->post('/admin/portal-papua-tengah', [
            'judul' => 'Test',
            'konten' => 'Test content',
            'kategori' => 'invalid-kategori'
        ]);
        $response->assertSessionHasErrors(['kategori']);
    }

    /** @test */
    public function public_can_view_published_news()
    {
        $publishedNews = PortalPapuaTengah::factory()->create([
            'status' => 'published'
        ]);

        $response = $this->get("/berita/{$publishedNews->id}");
        $response->assertStatus(200);
    }

    /** @test */
    public function public_cannot_view_draft_news()
    {
        $draftNews = PortalPapuaTengah::factory()->create([
            'status' => 'draft'
        ]);

        $response = $this->get("/berita/{$draftNews->id}");
        $response->assertStatus(404);
    }

    /** @test */
    public function public_api_returns_only_published_news()
    {
        PortalPapuaTengah::factory()->count(3)->create(['status' => 'published']);
        PortalPapuaTengah::factory()->count(2)->create(['status' => 'draft']);

        $response = $this->getJson('/api/portal-papua-tengah/public');
        $response->assertStatus(200);
        
        $data = $response->json();
        $this->assertCount(3, $data['data']);
        
        foreach ($data['data'] as $news) {
            $this->assertEquals('published', $news['status']);
        }
    }

    /** @test */
    public function news_search_functionality_works()
    {
        PortalPapuaTengah::factory()->create([
            'judul' => 'Laravel Testing Guide',
            'konten' => 'Complete guide to Laravel testing',
            'status' => 'published'
        ]);

        PortalPapuaTengah::factory()->create([
            'judul' => 'PHP Best Practices',
            'konten' => 'Best practices for PHP development',
            'status' => 'published'
        ]);

        $response = $this->get('/berita?search=Laravel');
        $response->assertStatus(200);
        $response->assertSee('Laravel Testing Guide');
        $response->assertDontSee('PHP Best Practices');
    }

    /** @test */
    public function news_category_filter_works()
    {
        PortalPapuaTengah::factory()->create([
            'judul' => 'News Item',
            'kategori' => 'berita',
            'status' => 'published'
        ]);

        PortalPapuaTengah::factory()->create([
            'judul' => 'Announcement Item',
            'kategori' => 'pengumuman',
            'status' => 'published'
        ]);

        $response = $this->get('/berita?kategori=berita');
        $response->assertStatus(200);
        $response->assertSee('News Item');
        $response->assertDontSee('Announcement Item');
    }
}
