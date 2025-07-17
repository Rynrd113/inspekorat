<?php

namespace Tests\Browser\API;

use App\Models\User;
use App\Models\Berita;
use App\Models\Wbs;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\Traits\InteractsWithAuthentication;

class APITestingComprehensiveTest extends DuskTestCase
{
    use DatabaseMigrations, InteractsWithAuthentication;

    /**
     * Test API Authentication - Token-based authentication
     */
    public function test_api_authentication_token_based()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/dashboard')
                ->script('
                    fetch("/api/berita", {
                        headers: {
                            "Authorization": "Bearer " + document.querySelector("meta[name=\"api-token\"]").content,
                            "Content-Type": "application/json"
                        }
                    })
                    .then(response => {
                        if (response.ok) {
                            document.body.innerHTML += "<div id=\"api-auth-ok\">API Auth OK</div>";
                        }
                    });
                ')
                ->waitFor('#api-auth-ok')
                ->assertSee('API Auth OK');
        });
    }

    /**
     * Test API Authentication - Unauthorized access
     */
    public function test_api_authentication_unauthorized_access()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->script('
                    fetch("/api/berita", {
                        headers: {
                            "Content-Type": "application/json"
                        }
                    })
                    .then(response => {
                        if (response.status === 401) {
                            document.body.innerHTML += "<div id=\"api-unauthorized\">API Unauthorized</div>";
                        }
                    });
                ')
                ->waitFor('#api-unauthorized')
                ->assertSee('API Unauthorized');
        });
    }

    /**
     * Test API Endpoints - GET /api/berita
     */
    public function test_api_get_berita_endpoint()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $berita = Berita::factory()->create();
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/dashboard')
                ->script('
                    fetch("/api/berita", {
                        headers: {
                            "Authorization": "Bearer " + document.querySelector("meta[name=\"api-token\"]").content,
                            "Content-Type": "application/json"
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.data && Array.isArray(data.data)) {
                            document.body.innerHTML += "<div id=\"api-berita-get\">API Berita GET OK</div>";
                        }
                    });
                ')
                ->waitFor('#api-berita-get')
                ->assertSee('API Berita GET OK');
        });
    }

    /**
     * Test API Endpoints - POST /api/berita
     */
    public function test_api_post_berita_endpoint()
    {
        $user = User::factory()->create(['role' => 'content_manager']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/dashboard')
                ->script('
                    fetch("/api/berita", {
                        method: "POST",
                        headers: {
                            "Authorization": "Bearer " + document.querySelector("meta[name=\"api-token\"]").content,
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector("meta[name=\"csrf-token\"]").content
                        },
                        body: JSON.stringify({
                            judul: "Test API Berita",
                            konten: "Konten test API",
                            status: "published"
                        })
                    })
                    .then(response => {
                        if (response.status === 201) {
                            document.body.innerHTML += "<div id=\"api-berita-post\">API Berita POST OK</div>";
                        }
                    });
                ')
                ->waitFor('#api-berita-post')
                ->assertSee('API Berita POST OK');
        });
    }

    /**
     * Test API Endpoints - PUT /api/berita/{id}
     */
    public function test_api_put_berita_endpoint()
    {
        $user = User::factory()->create(['role' => 'content_manager']);
        $berita = Berita::factory()->create();
        
        $this->browse(function (Browser $browser) use ($user, $berita) {
            $browser->loginAs($user)
                ->visit('/admin/dashboard')
                ->script('
                    fetch("/api/berita/' . $berita->id . '", {
                        method: "PUT",
                        headers: {
                            "Authorization": "Bearer " + document.querySelector("meta[name=\"api-token\"]").content,
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector("meta[name=\"csrf-token\"]").content
                        },
                        body: JSON.stringify({
                            judul: "Updated API Berita",
                            konten: "Updated konten API",
                            status: "published"
                        })
                    })
                    .then(response => {
                        if (response.ok) {
                            document.body.innerHTML += "<div id=\"api-berita-put\">API Berita PUT OK</div>";
                        }
                    });
                ')
                ->waitFor('#api-berita-put')
                ->assertSee('API Berita PUT OK');
        });
    }

    /**
     * Test API Endpoints - DELETE /api/berita/{id}
     */
    public function test_api_delete_berita_endpoint()
    {
        $user = User::factory()->create(['role' => 'content_manager']);
        $berita = Berita::factory()->create();
        
        $this->browse(function (Browser $browser) use ($user, $berita) {
            $browser->loginAs($user)
                ->visit('/admin/dashboard')
                ->script('
                    fetch("/api/berita/' . $berita->id . '", {
                        method: "DELETE",
                        headers: {
                            "Authorization": "Bearer " + document.querySelector("meta[name=\"api-token\"]").content,
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector("meta[name=\"csrf-token\"]").content
                        }
                    })
                    .then(response => {
                        if (response.ok) {
                            document.body.innerHTML += "<div id=\"api-berita-delete\">API Berita DELETE OK</div>";
                        }
                    });
                ')
                ->waitFor('#api-berita-delete')
                ->assertSee('API Berita DELETE OK');
        });
    }

    /**
     * Test API Validation - Required fields
     */
    public function test_api_validation_required_fields()
    {
        $user = User::factory()->create(['role' => 'content_manager']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/dashboard')
                ->script('
                    fetch("/api/berita", {
                        method: "POST",
                        headers: {
                            "Authorization": "Bearer " + document.querySelector("meta[name=\"api-token\"]").content,
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector("meta[name=\"csrf-token\"]").content
                        },
                        body: JSON.stringify({
                            // Missing required fields
                        })
                    })
                    .then(response => {
                        if (response.status === 422) {
                            document.body.innerHTML += "<div id=\"api-validation-error\">API Validation Error</div>";
                        }
                    });
                ')
                ->waitFor('#api-validation-error')
                ->assertSee('API Validation Error');
        });
    }

    /**
     * Test API Pagination - GET /api/berita with pagination
     */
    public function test_api_pagination_berita()
    {
        $user = User::factory()->create(['role' => 'admin']);
        Berita::factory()->count(25)->create();
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/dashboard')
                ->script('
                    fetch("/api/berita?page=1&per_page=10", {
                        headers: {
                            "Authorization": "Bearer " + document.querySelector("meta[name=\"api-token\"]").content,
                            "Content-Type": "application/json"
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.data && data.meta && data.meta.total > 10) {
                            document.body.innerHTML += "<div id=\"api-pagination-ok\">API Pagination OK</div>";
                        }
                    });
                ')
                ->waitFor('#api-pagination-ok')
                ->assertSee('API Pagination OK');
        });
    }

    /**
     * Test API Filtering - GET /api/berita with filters
     */
    public function test_api_filtering_berita()
    {
        $user = User::factory()->create(['role' => 'admin']);
        Berita::factory()->create(['status' => 'published']);
        Berita::factory()->create(['status' => 'draft']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/dashboard')
                ->script('
                    fetch("/api/berita?status=published", {
                        headers: {
                            "Authorization": "Bearer " + document.querySelector("meta[name=\"api-token\"]").content,
                            "Content-Type": "application/json"
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.data && data.data.length > 0) {
                            let allPublished = data.data.every(item => item.status === "published");
                            if (allPublished) {
                                document.body.innerHTML += "<div id=\"api-filtering-ok\">API Filtering OK</div>";
                            }
                        }
                    });
                ')
                ->waitFor('#api-filtering-ok')
                ->assertSee('API Filtering OK');
        });
    }

    /**
     * Test API Sorting - GET /api/berita with sorting
     */
    public function test_api_sorting_berita()
    {
        $user = User::factory()->create(['role' => 'admin']);
        Berita::factory()->count(5)->create();
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/dashboard')
                ->script('
                    fetch("/api/berita?sort=created_at&order=desc", {
                        headers: {
                            "Authorization": "Bearer " + document.querySelector("meta[name=\"api-token\"]").content,
                            "Content-Type": "application/json"
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.data && data.data.length > 1) {
                            let isSorted = true;
                            for (let i = 1; i < data.data.length; i++) {
                                if (new Date(data.data[i-1].created_at) < new Date(data.data[i].created_at)) {
                                    isSorted = false;
                                    break;
                                }
                            }
                            if (isSorted) {
                                document.body.innerHTML += "<div id=\"api-sorting-ok\">API Sorting OK</div>";
                            }
                        }
                    });
                ')
                ->waitFor('#api-sorting-ok')
                ->assertSee('API Sorting OK');
        });
    }

    /**
     * Test API Search - GET /api/berita with search
     */
    public function test_api_search_berita()
    {
        $user = User::factory()->create(['role' => 'admin']);
        Berita::factory()->create(['judul' => 'Unique Search Term']);
        Berita::factory()->create(['judul' => 'Another Title']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/dashboard')
                ->script('
                    fetch("/api/berita?search=Unique", {
                        headers: {
                            "Authorization": "Bearer " + document.querySelector("meta[name=\"api-token\"]").content,
                            "Content-Type": "application/json"
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.data && data.data.length === 1 && 
                            data.data[0].judul.includes("Unique")) {
                            document.body.innerHTML += "<div id=\"api-search-ok\">API Search OK</div>";
                        }
                    });
                ')
                ->waitFor('#api-search-ok')
                ->assertSee('API Search OK');
        });
    }

    /**
     * Test API Rate Limiting
     */
    public function test_api_rate_limiting()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/dashboard')
                ->script('
                    let promises = [];
                    let token = document.querySelector("meta[name=\"api-token\"]").content;
                    
                    // Make 100 rapid requests
                    for (let i = 0; i < 100; i++) {
                        promises.push(
                            fetch("/api/berita", {
                                headers: {
                                    "Authorization": "Bearer " + token,
                                    "Content-Type": "application/json"
                                }
                            })
                        );
                    }
                    
                    Promise.all(promises).then(responses => {
                        let rateLimited = responses.some(response => response.status === 429);
                        if (rateLimited) {
                            document.body.innerHTML += "<div id=\"api-rate-limited\">API Rate Limited</div>";
                        }
                    });
                ')
                ->waitFor('#api-rate-limited', 15)
                ->assertSee('API Rate Limited');
        });
    }

    /**
     * Test API Error Handling - 404 Not Found
     */
    public function test_api_error_handling_404()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/dashboard')
                ->script('
                    fetch("/api/berita/999999", {
                        headers: {
                            "Authorization": "Bearer " + document.querySelector("meta[name=\"api-token\"]").content,
                            "Content-Type": "application/json"
                        }
                    })
                    .then(response => {
                        if (response.status === 404) {
                            document.body.innerHTML += "<div id=\"api-404-error\">API 404 Error</div>";
                        }
                    });
                ')
                ->waitFor('#api-404-error')
                ->assertSee('API 404 Error');
        });
    }

    /**
     * Test API Error Handling - 500 Internal Server Error
     */
    public function test_api_error_handling_500()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/dashboard')
                ->script('
                    fetch("/api/berita/invalid-id", {
                        headers: {
                            "Authorization": "Bearer " + document.querySelector("meta[name=\"api-token\"]").content,
                            "Content-Type": "application/json"
                        }
                    })
                    .then(response => {
                        if (response.status >= 500) {
                            document.body.innerHTML += "<div id=\"api-500-error\">API 500 Error</div>";
                        }
                    });
                ')
                ->waitFor('#api-500-error')
                ->assertSee('API 500 Error');
        });
    }

    /**
     * Test API Response Format - JSON structure
     */
    public function test_api_response_format_json()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/dashboard')
                ->script('
                    fetch("/api/berita", {
                        headers: {
                            "Authorization": "Bearer " + document.querySelector("meta[name=\"api-token\"]").content,
                            "Content-Type": "application/json"
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.data && data.meta && data.links) {
                            document.body.innerHTML += "<div id=\"api-json-format\">API JSON Format OK</div>";
                        }
                    });
                ')
                ->waitFor('#api-json-format')
                ->assertSee('API JSON Format OK');
        });
    }

    /**
     * Test API File Upload - POST /api/berita with file
     */
    public function test_api_file_upload()
    {
        $user = User::factory()->create(['role' => 'content_manager']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/dashboard')
                ->script('
                    let formData = new FormData();
                    formData.append("judul", "Test API Upload");
                    formData.append("konten", "Konten test API");
                    formData.append("status", "published");
                    
                    // Create a dummy file
                    let file = new Blob(["test content"], { type: "text/plain" });
                    formData.append("gambar", file, "test.txt");
                    
                    fetch("/api/berita", {
                        method: "POST",
                        headers: {
                            "Authorization": "Bearer " + document.querySelector("meta[name=\"api-token\"]").content,
                            "X-CSRF-TOKEN": document.querySelector("meta[name=\"csrf-token\"]").content
                        },
                        body: formData
                    })
                    .then(response => {
                        if (response.status === 201) {
                            document.body.innerHTML += "<div id=\"api-file-upload\">API File Upload OK</div>";
                        }
                    });
                ')
                ->waitFor('#api-file-upload')
                ->assertSee('API File Upload OK');
        });
    }

    /**
     * Test API CORS Headers
     */
    public function test_api_cors_headers()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/dashboard')
                ->script('
                    fetch("/api/berita", {
                        method: "OPTIONS",
                        headers: {
                            "Origin": "https://example.com",
                            "Access-Control-Request-Method": "GET",
                            "Access-Control-Request-Headers": "Authorization"
                        }
                    })
                    .then(response => {
                        let corsHeader = response.headers.get("Access-Control-Allow-Origin");
                        if (corsHeader) {
                            document.body.innerHTML += "<div id=\"api-cors-ok\">API CORS OK</div>";
                        }
                    });
                ')
                ->waitFor('#api-cors-ok')
                ->assertSee('API CORS OK');
        });
    }

    /**
     * Test API Caching Headers
     */
    public function test_api_caching_headers()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/dashboard')
                ->script('
                    fetch("/api/berita", {
                        headers: {
                            "Authorization": "Bearer " + document.querySelector("meta[name=\"api-token\"]").content,
                            "Content-Type": "application/json"
                        }
                    })
                    .then(response => {
                        let cacheControl = response.headers.get("Cache-Control");
                        let etag = response.headers.get("ETag");
                        
                        if (cacheControl || etag) {
                            document.body.innerHTML += "<div id=\"api-caching-ok\">API Caching OK</div>";
                        }
                    });
                ')
                ->waitFor('#api-caching-ok')
                ->assertSee('API Caching OK');
        });
    }

    /**
     * Test API Versioning
     */
    public function test_api_versioning()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/dashboard')
                ->script('
                    fetch("/api/v1/berita", {
                        headers: {
                            "Authorization": "Bearer " + document.querySelector("meta[name=\"api-token\"]").content,
                            "Content-Type": "application/json"
                        }
                    })
                    .then(response => {
                        if (response.ok) {
                            document.body.innerHTML += "<div id=\"api-versioning-ok\">API Versioning OK</div>";
                        }
                    });
                ')
                ->waitFor('#api-versioning-ok')
                ->assertSee('API Versioning OK');
        });
    }

    /**
     * Test API WBS Endpoints
     */
    public function test_api_wbs_endpoints()
    {
        $user = User::factory()->create(['role' => 'wbs_manager']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/dashboard')
                ->script('
                    fetch("/api/wbs", {
                        headers: {
                            "Authorization": "Bearer " + document.querySelector("meta[name=\"api-token\"]").content,
                            "Content-Type": "application/json"
                        }
                    })
                    .then(response => {
                        if (response.ok) {
                            document.body.innerHTML += "<div id=\"api-wbs-ok\">API WBS OK</div>";
                        }
                    });
                ')
                ->waitFor('#api-wbs-ok')
                ->assertSee('API WBS OK');
        });
    }

    /**
     * Test API Role-based Access Control
     */
    public function test_api_role_based_access_control()
    {
        $user = User::factory()->create(['role' => 'user']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/dashboard')
                ->script('
                    fetch("/api/users", {
                        headers: {
                            "Authorization": "Bearer " + document.querySelector("meta[name=\"api-token\"]").content,
                            "Content-Type": "application/json"
                        }
                    })
                    .then(response => {
                        if (response.status === 403) {
                            document.body.innerHTML += "<div id=\"api-rbac-ok\">API RBAC OK</div>";
                        }
                    });
                ')
                ->waitFor('#api-rbac-ok')
                ->assertSee('API RBAC OK');
        });
    }

    /**
     * Test API Performance - Response time
     */
    public function test_api_performance_response_time()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/dashboard')
                ->script('
                    let startTime = performance.now();
                    
                    fetch("/api/berita", {
                        headers: {
                            "Authorization": "Bearer " + document.querySelector("meta[name=\"api-token\"]").content,
                            "Content-Type": "application/json"
                        }
                    })
                    .then(response => {
                        let endTime = performance.now();
                        let responseTime = endTime - startTime;
                        
                        if (responseTime < 1000) { // Less than 1 second
                            document.body.innerHTML += "<div id=\"api-performance-ok\">API Performance OK</div>";
                        }
                    });
                ')
                ->waitFor('#api-performance-ok')
                ->assertSee('API Performance OK');
        });
    }
}