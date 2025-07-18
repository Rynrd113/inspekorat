<?php

namespace Tests\Browser\Admin;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ProfilTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected $superAdmin;
    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create super admin user
        $this->superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@inspektorat.id',
            'password' => bcrypt('superadmin123'),
            'role' => 'superadmin',
            'is_active' => true,
        ]);

        // Create admin user
        $this->adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@inspektorat.id',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Create test profile data
        $this->createTestProfileData();
    }

    private function createTestProfileData()
    {
        Profile::create([
            'title' => 'Profil Inspektorat Papua Tengah',
            'content' => 'Sejarah singkat tentang Inspektorat Papua Tengah yang bertugas melaksanakan pengawasan internal pemerintah daerah...',
            'slug' => 'profil-inspektorat-papua-tengah',
            'meta_title' => 'Profil Inspektorat Papua Tengah',
            'meta_description' => 'Profil lengkap Inspektorat Papua Tengah sebagai lembaga pengawasan internal pemerintah daerah',
            'image' => 'profiles/inspektorat-profile.jpg',
            'is_active' => true,
            'sort_order' => 1,
            'created_by' => $this->superAdmin->id,
            'updated_by' => $this->superAdmin->id,
        ]);

        Profile::create([
            'title' => 'Visi dan Misi',
            'content' => 'Visi: Mewujudkan pengawasan internal yang efektif dan efisien. Misi: Melaksanakan pengawasan internal yang berkualitas...',
            'slug' => 'visi-dan-misi',
            'meta_title' => 'Visi dan Misi Inspektorat Papua Tengah',
            'meta_description' => 'Visi dan Misi Inspektorat Papua Tengah dalam menjalankan tugas pengawasan internal',
            'image' => 'profiles/visi-misi.jpg',
            'is_active' => true,
            'sort_order' => 2,
            'created_by' => $this->superAdmin->id,
            'updated_by' => $this->superAdmin->id,
        ]);

        Profile::create([
            'title' => 'Struktur Organisasi',
            'content' => 'Struktur organisasi Inspektorat Papua Tengah terdiri dari Inspektur, Sekretaris, dan Inspektur Pembantu...',
            'slug' => 'struktur-organisasi',
            'meta_title' => 'Struktur Organisasi Inspektorat Papua Tengah',
            'meta_description' => 'Struktur organisasi lengkap Inspektorat Papua Tengah',
            'image' => 'profiles/struktur-organisasi.jpg',
            'is_active' => true,
            'sort_order' => 3,
            'created_by' => $this->superAdmin->id,
            'updated_by' => $this->superAdmin->id,
        ]);

        Profile::create([
            'title' => 'Tugas dan Fungsi',
            'content' => 'Tugas pokok Inspektorat Papua Tengah adalah melaksanakan pengawasan internal terhadap penyelenggaraan pemerintahan daerah...',
            'slug' => 'tugas-dan-fungsi',
            'meta_title' => 'Tugas dan Fungsi Inspektorat Papua Tengah',
            'meta_description' => 'Tugas dan fungsi Inspektorat Papua Tengah sebagai lembaga pengawasan internal',
            'image' => 'profiles/tugas-fungsi.jpg',
            'is_active' => true,
            'sort_order' => 4,
            'created_by' => $this->superAdmin->id,
            'updated_by' => $this->superAdmin->id,
        ]);

        Profile::create([
            'title' => 'Sejarah Inspektorat',
            'content' => 'Inspektorat Papua Tengah didirikan pada tahun 2022 bersamaan dengan pembentukan Provinsi Papua Tengah...',
            'slug' => 'sejarah-inspektorat',
            'meta_title' => 'Sejarah Inspektorat Papua Tengah',
            'meta_description' => 'Sejarah pembentukan dan perkembangan Inspektorat Papua Tengah',
            'image' => 'profiles/sejarah.jpg',
            'is_active' => true,
            'sort_order' => 5,
            'created_by' => $this->superAdmin->id,
            'updated_by' => $this->superAdmin->id,
        ]);

        Profile::create([
            'title' => 'Motto dan Nilai',
            'content' => 'Motto: "Integritas, Profesionalisme, dan Akuntabilitas". Nilai-nilai: Kejujuran, Transparansi, dan Kepercayaan...',
            'slug' => 'motto-dan-nilai',
            'meta_title' => 'Motto dan Nilai Inspektorat Papua Tengah',
            'meta_description' => 'Motto dan nilai-nilai yang dijunjung tinggi oleh Inspektorat Papua Tengah',
            'image' => 'profiles/motto-nilai.jpg',
            'is_active' => true,
            'sort_order' => 6,
            'created_by' => $this->superAdmin->id,
            'updated_by' => $this->superAdmin->id,
        ]);

        Profile::create([
            'title' => 'Kontak Informasi',
            'content' => 'Alamat: Jl. Trikora No. 1, Nabire, Papua Tengah. Telepon: (0967) 421234. Email: info@inspektorat.paputengah.go.id',
            'slug' => 'kontak-informasi',
            'meta_title' => 'Kontak Informasi Inspektorat Papua Tengah',
            'meta_description' => 'Informasi kontak dan alamat lengkap Inspektorat Papua Tengah',
            'image' => 'profiles/kontak.jpg',
            'is_active' => true,
            'sort_order' => 7,
            'created_by' => $this->superAdmin->id,
            'updated_by' => $this->superAdmin->id,
        ]);

        Profile::create([
            'title' => 'Prestasi dan Penghargaan',
            'content' => 'Berbagai prestasi dan penghargaan yang telah diraih oleh Inspektorat Papua Tengah dalam menjalankan tugasnya...',
            'slug' => 'prestasi-dan-penghargaan',
            'meta_title' => 'Prestasi dan Penghargaan Inspektorat Papua Tengah',
            'meta_description' => 'Prestasi dan penghargaan yang telah diraih oleh Inspektorat Papua Tengah',
            'image' => 'profiles/prestasi.jpg',
            'is_active' => true,
            'sort_order' => 8,
            'created_by' => $this->superAdmin->id,
            'updated_by' => $this->superAdmin->id,
        ]);

        Profile::create([
            'title' => 'Program Kerja',
            'content' => 'Program kerja Inspektorat Papua Tengah mencakup berbagai kegiatan pengawasan dan pembinaan...',
            'slug' => 'program-kerja',
            'meta_title' => 'Program Kerja Inspektorat Papua Tengah',
            'meta_description' => 'Program kerja dan rencana kegiatan Inspektorat Papua Tengah',
            'image' => 'profiles/program-kerja.jpg',
            'is_active' => true,
            'sort_order' => 9,
            'created_by' => $this->superAdmin->id,
            'updated_by' => $this->superAdmin->id,
        ]);

        Profile::create([
            'title' => 'Komitmen Layanan',
            'content' => 'Komitmen Inspektorat Papua Tengah dalam memberikan layanan terbaik kepada masyarakat dan pemerintah daerah...',
            'slug' => 'komitmen-layanan',
            'meta_title' => 'Komitmen Layanan Inspektorat Papua Tengah',
            'meta_description' => 'Komitmen layanan dan standar pelayanan Inspektorat Papua Tengah',
            'image' => 'profiles/komitmen-layanan.jpg',
            'is_active' => true,
            'sort_order' => 10,
            'created_by' => $this->superAdmin->id,
            'updated_by' => $this->superAdmin->id,
        ]);
    }

    /**
     * Test Profile index page
     */
    public function testProfileIndexPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/profil')
                ->assertSee('Profil')
                ->assertSee('Tambah Profil')
                ->assertSee('Profil Inspektorat Papua Tengah')
                ->assertSee('Visi dan Misi')
                ->assertSee('Struktur Organisasi')
                ->assertSee('Sort Order')
                ->assertSee('Status')
                ->assertSee('Actions');
        });
    }

    /**
     * Test Profile pagination
     */
    public function testProfilePagination()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/profil')
                ->assertSee('Profil')
                ->assertSee('Next')
                ->clickLink('Next')
                ->pause(1000)
                ->assertSee('Komitmen Layanan');
        });
    }

    /**
     * Test Profile search functionality
     */
    public function testProfileSearch()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/profil')
                ->type('search', 'Visi dan Misi')
                ->press('Search')
                ->pause(1000)
                ->assertSee('Visi dan Misi')
                ->assertDontSee('Profil Inspektorat Papua Tengah');
        });
    }

    /**
     * Test Profile create page
     */
    public function testProfileCreatePage()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/profil')
                ->clickLink('Tambah Profil')
                ->pause(1000)
                ->assertPathIs('/admin/profil/create')
                ->assertSee('Tambah Profil')
                ->assertPresent('input[name="title"]')
                ->assertPresent('textarea[name="content"]')
                ->assertPresent('input[name="slug"]')
                ->assertPresent('input[name="meta_title"]')
                ->assertPresent('textarea[name="meta_description"]')
                ->assertPresent('input[name="image"]')
                ->assertPresent('input[name="sort_order"]')
                ->assertPresent('input[name="is_active"]');
        });
    }

    /**
     * Test Profile store functionality
     */
    public function testProfileStore()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/profil/create')
                ->type('title', 'Profil Baru')
                ->type('content', 'Konten profil baru yang akan ditambahkan ke dalam website.')
                ->type('slug', 'profil-baru')
                ->type('meta_title', 'Profil Baru - Inspektorat Papua Tengah')
                ->type('meta_description', 'Profil baru yang berisi informasi terbaru.')
                ->attach('image', __DIR__ . '/../../fixtures/profile-image.jpg')
                ->type('sort_order', '11')
                ->check('is_active')
                ->press('Simpan')
                ->pause(2000)
                ->assertPathIs('/admin/profil')
                ->assertSee('Data berhasil disimpan')
                ->assertSee('Profil Baru');
        });
    }

    /**
     * Test Profile store validation
     */
    public function testProfileStoreValidation()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/profil/create')
                ->press('Simpan')
                ->pause(1000)
                ->assertSee('The title field is required')
                ->assertSee('The content field is required')
                ->assertSee('The slug field is required')
                ->assertSee('The sort order field is required');
        });
    }

    /**
     * Test Profile slug uniqueness validation
     */
    public function testProfileSlugUniquenessValidation()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/profil/create')
                ->type('title', 'Test Profil')
                ->type('content', 'Test konten profil')
                ->type('slug', 'profil-inspektorat-papua-tengah') // Already exists
                ->type('sort_order', '12')
                ->press('Simpan')
                ->pause(1000)
                ->assertSee('The slug has already been taken');
        });
    }

    /**
     * Test Profile show page
     */
    public function testProfileShowPage()
    {
        $profile = Profile::first();
        
        $this->browse(function (Browser $browser) use ($profile) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/profil')
                ->click('a[href="/admin/profil/' . $profile->id . '"]')
                ->pause(1000)
                ->assertPathIs('/admin/profil/' . $profile->id)
                ->assertSee($profile->title)
                ->assertSee($profile->content)
                ->assertSee($profile->slug)
                ->assertSee($profile->meta_title)
                ->assertSee($profile->meta_description)
                ->assertSee('SEO Preview')
                ->assertSee('Content Preview');
        });
    }

    /**
     * Test Profile edit page
     */
    public function testProfileEditPage()
    {
        $profile = Profile::first();
        
        $this->browse(function (Browser $browser) use ($profile) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/profil')
                ->click('a[href="/admin/profil/' . $profile->id . '/edit"]')
                ->pause(1000)
                ->assertPathIs('/admin/profil/' . $profile->id . '/edit')
                ->assertSee('Edit Profil')
                ->assertInputValue('title', $profile->title)
                ->assertInputValue('slug', $profile->slug)
                ->assertInputValue('meta_title', $profile->meta_title)
                ->assertInputValue('sort_order', $profile->sort_order);
        });
    }

    /**
     * Test Profile update functionality
     */
    public function testProfileUpdate()
    {
        $profile = Profile::first();
        
        $this->browse(function (Browser $browser) use ($profile) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/profil/' . $profile->id . '/edit')
                ->clear('title')
                ->type('title', 'Profil Terupdate')
                ->clear('content')
                ->type('content', 'Konten profil yang telah diperbarui.')
                ->clear('meta_title')
                ->type('meta_title', 'Profil Terupdate - Inspektorat Papua Tengah')
                ->clear('meta_description')
                ->type('meta_description', 'Profil yang telah diperbarui dengan informasi terbaru.')
                ->press('Update')
                ->pause(2000)
                ->assertPathIs('/admin/profil')
                ->assertSee('Data berhasil diperbarui')
                ->assertSee('Profil Terupdate');
        });
    }

    /**
     * Test Profile delete functionality
     */
    public function testProfileDelete()
    {
        $profile = Profile::orderBy('id', 'desc')->first();
        $profileTitle = $profile->title;
        
        $this->browse(function (Browser $browser) use ($profile, $profileTitle) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/profil')
                ->click('button[onclick="if(confirm(\'Yakin ingin menghapus profil ini?\')) { document.getElementById(\'delete-form-' . $profile->id . '\').submit(); }"]')
                ->pause(1000)
                ->acceptDialog()
                ->pause(2000)
                ->assertPathIs('/admin/profil')
                ->assertSee('Data berhasil dihapus')
                ->assertDontSee($profileTitle);
        });
    }

    /**
     * Test Profile status filter
     */
    public function testProfileStatusFilter()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/profil')
                ->select('status', 'active')
                ->press('Filter')
                ->pause(1000)
                ->assertSee('Active')
                ->select('status', 'inactive')
                ->press('Filter')
                ->pause(1000)
                ->assertSee('Inactive');
        });
    }

    /**
     * Test Profile sorting
     */
    public function testProfileSorting()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/profil')
                ->click('th[data-column="sort_order"]')
                ->pause(1000)
                ->assertSee('Profil Inspektorat Papua Tengah') // First by sort order
                ->click('th[data-column="title"]')
                ->pause(1000)
                ->assertSee('Kontak Informasi'); // First alphabetically
        });
    }

    /**
     * Test Profile reorder functionality
     */
    public function testProfileReorder()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/profil')
                ->clickLink('Reorder')
                ->pause(1000)
                ->assertSee('Drag and drop to reorder')
                ->drag('.profile-item[data-id="1"]', '.profile-item[data-id="2"]')
                ->pause(1000)
                ->press('Save Order')
                ->pause(1000)
                ->assertSee('Order updated successfully');
        });
    }

    /**
     * Test Profile bulk actions
     */
    public function testProfileBulkActions()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/profil')
                ->check('select-all')
                ->select('bulk-action', 'activate')
                ->press('Apply')
                ->pause(1000)
                ->assertSee('Bulk action berhasil dijalankan');
        });
    }

    /**
     * Test Profile responsive design
     */
    public function testProfileResponsiveDesign()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(375, 667) // iPhone 6/7/8 size
                ->loginAs($this->superAdmin)
                ->visit('/admin/profil')
                ->assertSee('Profil')
                ->assertSee('Tambah Profil')
                ->resize(768, 1024) // iPad size
                ->assertSee('Profil')
                ->assertSee('Tambah Profil')
                ->resize(1280, 720); // Desktop size
        });
    }

    /**
     * Test Profile image upload
     */
    public function testProfileImageUpload()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/profil/create')
                ->type('title', 'Profil dengan Gambar')
                ->type('content', 'Konten profil dengan gambar yang diupload.')
                ->type('slug', 'profil-dengan-gambar')
                ->type('sort_order', '11')
                ->attach('image', __DIR__ . '/../../fixtures/profile-image.jpg')
                ->press('Simpan')
                ->pause(2000)
                ->assertSee('Image uploaded successfully')
                ->assertSee('Data berhasil disimpan');
        });
    }

    /**
     * Test Profile slug auto-generation
     */
    public function testProfileSlugAutoGeneration()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/profil/create')
                ->type('title', 'Profil Test Auto Slug')
                ->pause(1000)
                ->assertInputValue('slug', 'profil-test-auto-slug');
        });
    }

    /**
     * Test Profile content preview
     */
    public function testProfileContentPreview()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/profil/create')
                ->type('title', 'Profil Preview')
                ->type('content', 'Konten profil untuk preview.')
                ->type('slug', 'profil-preview')
                ->type('sort_order', '11')
                ->press('Preview')
                ->pause(1000)
                ->assertSee('Preview Mode')
                ->assertSee('Profil Preview')
                ->assertSee('Konten profil untuk preview.');
        });
    }

    /**
     * Test Profile SEO optimization
     */
    public function testProfileSeoOptimization()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/profil/create')
                ->type('title', 'Profil SEO Test')
                ->type('content', 'Konten profil untuk test SEO optimization.')
                ->type('slug', 'profil-seo-test')
                ->type('meta_title', 'Profil SEO Test - Inspektorat Papua Tengah')
                ->type('meta_description', 'Profil SEO test description untuk optimization.')
                ->type('sort_order', '11')
                ->press('Simpan')
                ->pause(2000)
                ->assertSee('SEO optimized')
                ->assertSee('Data berhasil disimpan');
        });
    }

    /**
     * Test Profile duplicate functionality
     */
    public function testProfileDuplicate()
    {
        $profile = Profile::first();
        
        $this->browse(function (Browser $browser) use ($profile) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/profil')
                ->click('button[onclick="duplicateProfile(' . $profile->id . ')"]')
                ->pause(1000)
                ->acceptDialog()
                ->pause(2000)
                ->assertSee('Profil berhasil diduplikasi')
                ->assertSee($profile->title . ' (Copy)');
        });
    }

    /**
     * Test Profile export functionality
     */
    public function testProfileExport()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/profil')
                ->click('a[href="/admin/profil/export"]')
                ->pause(2000)
                ->assertSee('Export berhasil');
        });
    }

    /**
     * Test Profile import functionality
     */
    public function testProfileImport()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/profil')
                ->clickLink('Import')
                ->pause(1000)
                ->attach('file', __DIR__ . '/../../fixtures/profiles-import.xlsx')
                ->press('Import')
                ->pause(2000)
                ->assertSee('Import berhasil');
        });
    }

    /**
     * Test Profile advanced search
     */
    public function testProfileAdvancedSearch()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/profil')
                ->clickLink('Advanced Search')
                ->pause(1000)
                ->type('title', 'Profil')
                ->type('content', 'Inspektorat')
                ->select('status', 'active')
                ->type('sort_order_min', '1')
                ->type('sort_order_max', '5')
                ->press('Search')
                ->pause(1000)
                ->assertSee('Profil Inspektorat Papua Tengah');
        });
    }

    /**
     * Test Profile version control
     */
    public function testProfileVersionControl()
    {
        $profile = Profile::first();
        
        $this->browse(function (Browser $browser) use ($profile) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/profil/' . $profile->id . '/edit')
                ->clear('title')
                ->type('title', 'Profil Updated v2')
                ->press('Update')
                ->pause(2000)
                ->assertSee('Data berhasil diperbarui')
                ->visit('/admin/profil/' . $profile->id)
                ->click('a[href="#version-history"]')
                ->pause(1000)
                ->assertSee('Version History')
                ->assertSee('v1.0')
                ->assertSee('v2.0');
        });
    }

    /**
     * Test Profile role-based access
     */
    public function testProfileRoleBasedAccess()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->adminUser)
                ->visit('/admin/profil')
                ->assertSee('Profil')
                ->assertSee('Tambah Profil')
                ->assertSee('Edit')
                ->assertSee('Delete');
        });
    }

    /**
     * Test Profile analytics
     */
    public function testProfileAnalytics()
    {
        $profile = Profile::first();
        
        $this->browse(function (Browser $browser) use ($profile) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/profil/' . $profile->id)
                ->click('a[href="#analytics"]')
                ->pause(1000)
                ->assertSee('Analytics')
                ->assertSee('Page Views')
                ->assertSee('Unique Visitors')
                ->assertSee('Bounce Rate')
                ->assertSee('Average Time on Page');
        });
    }

    /**
     * Test Profile rich text editor
     */
    public function testProfileRichTextEditor()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/profil/create')
                ->type('title', 'Profil Rich Text')
                ->click('.editor-toolbar .bold')
                ->type('.editor-content', 'Konten dengan formatting bold.')
                ->click('.editor-toolbar .italic')
                ->type('.editor-content', ' Dan italic.')
                ->type('slug', 'profil-rich-text')
                ->type('sort_order', '11')
                ->press('Simpan')
                ->pause(2000)
                ->assertSee('Data berhasil disimpan')
                ->assertSee('Profil Rich Text');
        });
    }

    /**
     * Test Profile template selection
     */
    public function testProfileTemplateSelection()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/profil/create')
                ->type('title', 'Profil Template Test')
                ->select('template', 'default')
                ->pause(1000)
                ->assertSee('Default Template')
                ->select('template', 'custom')
                ->pause(1000)
                ->assertSee('Custom Template')
                ->type('content', 'Konten dengan template custom.')
                ->type('slug', 'profil-template-test')
                ->type('sort_order', '11')
                ->press('Simpan')
                ->pause(2000)
                ->assertSee('Data berhasil disimpan');
        });
    }

    /**
     * Test Profile scheduled publishing
     */
    public function testProfileScheduledPublishing()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/profil/create')
                ->type('title', 'Profil Scheduled')
                ->type('content', 'Konten profil yang akan dipublish nanti.')
                ->type('slug', 'profil-scheduled')
                ->type('sort_order', '11')
                ->type('published_at', '2024-12-31 10:00:00')
                ->press('Simpan')
                ->pause(2000)
                ->assertSee('Scheduled for publishing')
                ->assertSee('Data berhasil disimpan');
        });
    }

    /**
     * Test Profile content validation
     */
    public function testProfileContentValidation()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->superAdmin)
                ->visit('/admin/profil/create')
                ->type('title', 'A')
                ->type('content', 'Short')
                ->type('slug', 'a')
                ->type('sort_order', '11')
                ->press('Simpan')
                ->pause(1000)
                ->assertSee('Title must be at least 3 characters')
                ->assertSee('Content must be at least 10 characters')
                ->assertSee('Slug must be at least 3 characters');
        });
    }
}
