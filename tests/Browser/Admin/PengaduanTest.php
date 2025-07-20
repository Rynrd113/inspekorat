<?php

namespace Tests\Browser\Admin;

use App\Models\User;
use App\Models\Pengaduan;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PengaduanTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create([
            'role' => 'super_admin',
            'email' => 'admin@inspektorat.id'
        ]);
        
        $this->createTestPengaduanData();
    }

    private function createTestPengaduanData()
    {
        $categories = ['pelayanan', 'fasilitas', 'pegawai', 'sistem', 'lainnya'];
        $statuses = ['pending', 'in_progress', 'resolved', 'closed'];
        
        for ($i = 1; $i <= 15; $i++) {
            Pengaduan::create([
                'nama_pengadu' => 'Pengadu Test ' . $i,
                'email' => 'pengadu' . $i . '@test.com',
                'telepon' => '08123456789' . $i,
                'subjek' => 'Pengaduan Test ' . $i,
                'isi_pengaduan' => 'Isi pengaduan test ' . $i . '. Pengaduan mengenai pelayanan inspektorat Papua Tengah.',
                'kategori' => $categories[($i - 1) % 5],
                'status' => $statuses[($i - 1) % 4],
                'tanggal_pengaduan' => now()->subDays(rand(1, 30)),
                'is_anonymous' => ($i % 4 === 0), // 25% anonymous
                'bukti_files' => ($i % 3 === 0) ? ['pengaduan/bukti-' . $i . '.jpg'] : null,
                'created_at' => now()->subDays(rand(1, 30))
            ]);
        }
    }

    /**
     * Test Pengaduan index page
     */
    public function testPengaduanIndexPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/pengaduan')
                ->assertSee('Pengaduan')
                ->assertSee('Nama Pengadu')
                ->assertSee('Subjek')
                ->assertSee('Status')
                ->screenshot('admin_pengaduan_index');
        });
    }

    /**
     * Test Pengaduan pagination
     */
    public function testPengaduanPagination()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/pengaduan')
                ->assertSee('Pengaduan Test 1')
                ->assertSee('Pengaduan Test 10')
                ->screenshot('admin_pengaduan_pagination');
        });
    }

    /**
     * Test Pengaduan search functionality
     */
    public function testPengaduanSearch()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/pengaduan')
                ->type('search', 'Pengaduan Test 1')
                ->press('Cari')
                ->pause(1000)
                ->assertSee('Pengaduan Test 1')
                ->assertDontSee('Pengaduan Test 2')
                ->screenshot('admin_pengaduan_search');
        });
    }

    /**
     * Test Pengaduan show page
     */
    public function testPengaduanShowPage()
    {
        $pengaduan = Pengaduan::first();
        
        $this->browse(function (Browser $browser) use ($pengaduan) {
            $browser->loginAs($this->admin)
                ->visit("/admin/pengaduan/{$pengaduan->id}")
                ->assertSee('Detail Pengaduan')
                ->assertSee($pengaduan->nama_pengadu)
                ->assertSee($pengaduan->subjek)
                ->assertSee($pengaduan->isi_pengaduan)
                ->screenshot('admin_pengaduan_show');
        });
    }

    /**
     * Test Pengaduan status update
     */
    public function testPengaduanStatusUpdate()
    {
        $pengaduan = Pengaduan::where('status', 'pending')->first();
        
        $this->browse(function (Browser $browser) use ($pengaduan) {
            $browser->loginAs($this->admin)
                ->visit("/admin/pengaduan/{$pengaduan->id}")
                ->select('status', 'in_progress')
                ->type('tanggapan', 'Pengaduan sedang diproses oleh tim terkait')
                ->press('Update Status')
                ->pause(2000)
                ->assertSee('Status pengaduan berhasil diperbarui')
                ->assertSee('in_progress')
                ->screenshot('admin_pengaduan_status_update');
        });
    }

    /**
     * Test Pengaduan category filter
     */
    public function testPengaduanCategoryFilter()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/pengaduan')
                ->select('kategori', 'pelayanan')
                ->press('Filter')
                ->pause(1000)
                ->assertSee('pelayanan')
                ->screenshot('admin_pengaduan_category_filter');
        });
    }

    /**
     * Test Pengaduan status filter
     */
    public function testPengaduanStatusFilter()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/pengaduan')
                ->select('status', 'pending')
                ->press('Filter')
                ->pause(1000)
                ->assertSee('pending')
                ->screenshot('admin_pengaduan_status_filter');
        });
    }

    /**
     * Test Pengaduan anonymous display
     */
    public function testPengaduanAnonymousDisplay()
    {
        $pengaduanAnonymous = Pengaduan::where('is_anonymous', true)->first();
        
        $this->browse(function (Browser $browser) use ($pengaduanAnonymous) {
            $browser->loginAs($this->admin)
                ->visit("/admin/pengaduan/{$pengaduanAnonymous->id}")
                ->assertSee('Anonim')
                ->assertSee('Pengaduan Anonim')
                ->screenshot('admin_pengaduan_anonymous');
        });
    }

    /**
     * Test Pengaduan evidence file download
     */
    public function testPengaduanEvidenceDownload()
    {
        $pengaduanWithEvidence = Pengaduan::whereNotNull('bukti_files')->first();
        
        $this->browse(function (Browser $browser) use ($pengaduanWithEvidence) {
            $browser->loginAs($this->admin)
                ->visit("/admin/pengaduan/{$pengaduanWithEvidence->id}")
                ->assertSee('File Bukti')
                ->assertSee('Download')
                ->screenshot('admin_pengaduan_evidence');
        });
    }

    /**
     * Test Pengaduan bulk actions
     */
    public function testPengaduanBulkActions()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/pengaduan')
                ->check('pengaduan_1')
                ->check('pengaduan_2')
                ->select('bulk_action', 'mark_resolved')
                ->press('Terapkan')
                ->pause(2000)
                ->assertSee('Aksi berhasil diterapkan')
                ->screenshot('admin_pengaduan_bulk_actions');
        });
    }

    /**
     * Test Pengaduan export functionality
     */
    public function testPengaduanExport()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/pengaduan')
                ->press('Export Pengaduan')
                ->pause(2000)
                ->screenshot('admin_pengaduan_export');
        });
    }

    /**
     * Test Pengaduan response functionality
     */
    public function testPengaduanResponse()
    {
        $pengaduan = Pengaduan::first();
        
        $this->browse(function (Browser $browser) use ($pengaduan) {
            $browser->loginAs($this->admin)
                ->visit("/admin/pengaduan/{$pengaduan->id}")
                ->type('tanggapan', 'Terima kasih atas pengaduan Anda. Kami akan segera menindaklanjuti.')
                ->press('Kirim Tanggapan')
                ->pause(2000)
                ->assertSee('Tanggapan berhasil dikirim')
                ->screenshot('admin_pengaduan_response');
        });
    }

    /**
     * Test Pengaduan statistics dashboard
     */
    public function testPengaduanStatistics()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/pengaduan/stats')
                ->assertSee('Statistik Pengaduan')
                ->assertSee('Total Pengaduan')
                ->assertSee('Pending')
                ->assertSee('Resolved')
                ->assertSee('Grafik Pengaduan')
                ->screenshot('admin_pengaduan_statistics');
        });
    }

    /**
     * Test Pengaduan date range filter
     */
    public function testPengaduanDateFilter()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/pengaduan')
                ->type('date_from', now()->subDays(7)->format('Y-m-d'))
                ->type('date_to', now()->format('Y-m-d'))
                ->press('Filter')
                ->pause(1000)
                ->screenshot('admin_pengaduan_date_filter');
        });
    }

    /**
     * Test Pengaduan priority assignment
     */
    public function testPengaduanPriorityAssignment()
    {
        $pengaduan = Pengaduan::first();
        
        $this->browse(function (Browser $browser) use ($pengaduan) {
            $browser->loginAs($this->admin)
                ->visit("/admin/pengaduan/{$pengaduan->id}")
                ->select('prioritas', 'tinggi')
                ->press('Update Prioritas')
                ->pause(2000)
                ->assertSee('Prioritas berhasil diperbarui')
                ->assertSee('tinggi')
                ->screenshot('admin_pengaduan_priority');
        });
    }
}
