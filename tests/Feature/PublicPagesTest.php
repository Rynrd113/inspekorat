<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function homepage_loads()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /** @test */
    public function berita_index_page_loads()
    {
        $response = $this->get('/berita');
        $response->assertStatus(200);
    }

    /** @test */
    public function wbs_page_loads()
    {
        $response = $this->get('/wbs');
        $response->assertStatus(200);
    }

    /** @test */
    public function wbs_form_can_be_submitted()
    {
        $response = $this->post('/wbs', [
            'nama' => 'Test User',
            'email' => 'test@example.com',
            'telepon' => '081234567890',
            'pesan' => 'Test message untuk WBS'
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    /** @test */
    public function profil_page_loads()
    {
        $response = $this->get('/profil');
        $response->assertStatus(200);
    }

    /** @test */
    public function pelayanan_index_page_loads()
    {
        $response = $this->get('/pelayanan');
        $response->assertStatus(200);
    }

    /** @test */
    public function dokumen_index_page_loads()
    {
        $response = $this->get('/dokumen');
        $response->assertStatus(200);
    }

    /** @test */
    public function galeri_index_page_loads()
    {
        $response = $this->get('/galeri');
        $response->assertStatus(200);
    }

    /** @test */
    public function faq_page_loads()
    {
        $response = $this->get('/faq');
        $response->assertStatus(200);
    }

    /** @test */
    public function kontak_page_loads()
    {
        $response = $this->get('/kontak');
        $response->assertStatus(200);
    }

    /** @test */
    public function kontak_form_can_be_submitted()
    {
        $response = $this->post('/kontak', [
            'nama' => 'Test User',
            'email' => 'test@example.com',
            'subjek' => 'Test Subject',
            'pesan' => 'Test message untuk kontak'
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    /** @test */
    public function pengaduan_page_loads()
    {
        $response = $this->get('/pengaduan');
        $response->assertStatus(200);
    }

    /** @test */
    public function portal_opd_index_page_loads()
    {
        $response = $this->get('/portal-opd');
        $response->assertStatus(200);
    }

    /** @test */
    public function all_public_pages_are_accessible()
    {
        $publicRoutes = [
            '/',
            '/berita',
            '/wbs',
            '/profil',
            '/pelayanan',
            '/dokumen',
            '/galeri',
            '/faq',
            '/kontak',
            '/pengaduan',
            '/portal-opd'
        ];

        foreach ($publicRoutes as $route) {
            $response = $this->get($route);
            $this->assertTrue(
                $response->isSuccessful(),
                "Route {$route} returned status {$response->status()}"
            );
        }
    }

    /** @test */
    public function all_public_api_endpoints_are_accessible()
    {
        $publicApiRoutes = [
            '/api/portal-papua-tengah/public',
            '/api/info-kantor/public',
            '/api/v1/portal-papua-tengah',
            '/api/v1/info-kantor'
        ];

        foreach ($publicApiRoutes as $route) {
            $response = $this->getJson($route);
            $this->assertTrue(
                $response->isSuccessful(),
                "API Route {$route} returned status {$response->status()}"
            );
        }
    }

    /** @test */
    public function public_wbs_api_accepts_submissions()
    {
        $response = $this->postJson('/api/wbs/public', [
            'nama' => 'Test User',
            'email' => 'test@example.com',
            'telepon' => '081234567890',
            'pesan' => 'Test message untuk WBS API'
        ]);

        $response->assertStatus(201);
    }

    /** @test */
    public function public_wbs_v1_api_accepts_submissions()
    {
        $response = $this->postJson('/api/v1/wbs', [
            'nama' => 'Test User',
            'email' => 'test@example.com',
            'telepon' => '081234567890',
            'pesan' => 'Test message untuk WBS API V1'
        ]);

        $response->assertStatus(201);
    }
}
