<?php

namespace Tests\Browser\Public;

use App\Models\Faq;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * FAQ Public Test With Results
 * Test FAQ functionality with database result verification
 */
class FaqPublicTestWithResults extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createTestFaqs();
    }

    private function createTestFaqs()
    {
        // Create test FAQs
        Faq::create([
            'pertanyaan' => 'Bagaimana cara mengajukan pengaduan?',
            'jawaban' => 'Anda dapat mengajukan pengaduan melalui sistem WBS yang tersedia di website ini atau datang langsung ke kantor inspektorat.',
            'kategori' => 'pelayanan',
            'urutan' => 1,
            'status' => 'active',
            'view_count' => 0,
            'helpful_count' => 0
        ]);

        Faq::create([
            'pertanyaan' => 'Apa saja dokumen yang diperlukan untuk pengaduan?',
            'jawaban' => 'Dokumen yang diperlukan antara lain identitas diri, bukti-bukti yang mendukung pengaduan, dan surat keterangan jika diperlukan.',
            'kategori' => 'pelayanan',
            'urutan' => 2,
            'status' => 'active',
            'view_count' => 5,
            'helpful_count' => 3
        ]);

        Faq::create([
            'pertanyaan' => 'Berapa lama proses penanganan pengaduan?',
            'jawaban' => 'Proses penanganan pengaduan akan diselesaikan dalam waktu maksimal 30 hari kerja sejak pengaduan diterima.',
            'kategori' => 'pelayanan',
            'urutan' => 3,
            'status' => 'active',
            'view_count' => 10,
            'helpful_count' => 8
        ]);

        Faq::create([
            'pertanyaan' => 'Bagaimana cara mengecek status pengaduan?',
            'jawaban' => 'Anda dapat mengecek status pengaduan melalui nomor tiket yang diberikan saat pengaduan pertama kali diajukan.',
            'kategori' => 'pelayanan',
            'urutan' => 4,
            'status' => 'active',
            'view_count' => 15,
            'helpful_count' => 12
        ]);

        Faq::create([
            'pertanyaan' => 'Apakah pengaduan dapat diajukan secara anonim?',
            'jawaban' => 'Ya, sistem WBS memungkinkan pengaduan diajukan secara anonim untuk melindungi identitas pelapor.',
            'kategori' => 'wbs',
            'urutan' => 5,
            'status' => 'active',
            'view_count' => 20,
            'helpful_count' => 18
        ]);

        Faq::create([
            'pertanyaan' => 'Bagaimana cara mengakses dokumen publik?',
            'jawaban' => 'Dokumen publik dapat diakses melalui menu Dokumen di website ini. Beberapa dokumen mungkin memerlukan registrasi terlebih dahulu.',
            'kategori' => 'informasi',
            'urutan' => 6,
            'status' => 'active',
            'view_count' => 8,
            'helpful_count' => 5
        ]);

        Faq::create([
            'pertanyaan' => 'Apa itu Portal OPD?',
            'jawaban' => 'Portal OPD adalah direktori resmi yang berisi informasi lengkap tentang Organisasi Perangkat Daerah di Papua Tengah.',
            'kategori' => 'informasi',
            'urutan' => 7,
            'status' => 'active',
            'view_count' => 12,
            'helpful_count' => 9
        ]);
    }

    /**
     * Test FAQ view count increment
     */
    public function testFaqViewCountIncrement()
    {
        $this->browse(function (Browser $browser) {
            $faq = Faq::first();
            $initialViewCount = $faq->view_count;

            $browser->visit('/faq')
                ->waitFor('.faq-list', 10)
                ->click('.faq-item:first-child .faq-question')
                ->waitFor('.faq-answer', 10)
                ->assertSee($faq->jawaban)
                ->screenshot('faq-view-count-increment');

            // Verify view count increased
            $faq->refresh();
            $this->assertEquals($initialViewCount + 1, $faq->view_count);
        });
    }

    /**
     * Test FAQ helpful vote functionality
     */
    public function testFaqHelpfulVoteFunctionality()
    {
        $this->browse(function (Browser $browser) {
            $faq = Faq::first();
            $initialHelpfulCount = $faq->helpful_count;

            $browser->visit('/faq')
                ->waitFor('.faq-list', 10)
                ->click('.faq-item:first-child .faq-question')
                ->waitFor('.faq-answer', 10)
                ->click('.helpful-btn')
                ->waitForText('Terima kasih atas feedback Anda', 10)
                ->screenshot('faq-helpful-vote');

            // Verify helpful count increased
            $faq->refresh();
            $this->assertEquals($initialHelpfulCount + 1, $faq->helpful_count);
        });
    }

    /**
     * Test FAQ search functionality with results
     */
    public function testFaqSearchWithResults()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/faq')
                ->waitFor('.search-form', 10)
                ->type('search', 'pengaduan')
                ->press('Search')
                ->waitFor('.search-results', 10)
                ->assertSee('Bagaimana cara mengajukan pengaduan?')
                ->assertSee('Apa saja dokumen yang diperlukan untuk pengaduan?')
                ->assertSee('Berapa lama proses penanganan pengaduan?')
                ->assertDontSee('Apa itu Portal OPD?')
                ->screenshot('faq-search-results');

            // Verify search results count
            $browser->assertSee('4 pertanyaan ditemukan');
        });
    }

    /**
     * Test FAQ category filter with results
     */
    public function testFaqCategoryFilterWithResults()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/faq')
                ->waitFor('.category-filter', 10)
                ->select('kategori', 'pelayanan')
                ->press('Filter')
                ->waitFor('.filtered-results', 10)
                ->assertSee('Bagaimana cara mengajukan pengaduan?')
                ->assertSee('Apa saja dokumen yang diperlukan untuk pengaduan?')
                ->assertDontSee('Apa itu Portal OPD?')
                ->screenshot('faq-category-filter');

            // Verify category filter results count
            $browser->assertSee('4 pertanyaan kategori pelayanan');
        });
    }

    /**
     * Test FAQ popular questions display
     */
    public function testFaqPopularQuestionsDisplay()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/faq')
                ->waitFor('.popular-faqs', 10)
                ->assertSee('Pertanyaan Populer')
                ->assertSee('Apakah pengaduan dapat diajukan secara anonim?') // Highest view count
                ->assertSee('Bagaimana cara mengecek status pengaduan?')
                ->screenshot('faq-popular-questions');

            // Verify popular questions are ordered by view count
            $browser->assertSeeInOrder([
                'Apakah pengaduan dapat diajukan secara anonim?',
                'Bagaimana cara mengecek status pengaduan?',
                'Apa itu Portal OPD?'
            ]);
        });
    }

    /**
     * Test FAQ most helpful questions display
     */
    public function testFaqMostHelpfulQuestionsDisplay()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/faq')
                ->waitFor('.helpful-faqs', 10)
                ->assertSee('Paling Membantu')
                ->assertSee('Apakah pengaduan dapat diajukan secara anonim?') // Highest helpful count
                ->assertSee('Bagaimana cara mengecek status pengaduan?')
                ->screenshot('faq-most-helpful-questions');

            // Verify helpful questions are ordered by helpful count
            $browser->assertSeeInOrder([
                'Apakah pengaduan dapat diajukan secara anonim?',
                'Bagaimana cara mengecek status pengaduan?',
                'Berapa lama proses penanganan pengaduan?'
            ]);
        });
    }

    /**
     * Test FAQ accordion functionality with database tracking
     */
    public function testFaqAccordionFunctionalityWithTracking()
    {
        $this->browse(function (Browser $browser) {
            $faq = Faq::where('pertanyaan', 'Bagaimana cara mengajukan pengaduan?')->first();
            $initialViewCount = $faq->view_count;

            $browser->visit('/faq')
                ->waitFor('.faq-accordion', 10)
                ->click('.faq-item:first-child .faq-question')
                ->waitFor('.faq-answer:first-child', 10)
                ->assertSee($faq->jawaban)
                ->screenshot('faq-accordion-open');

            // Verify accordion opens
            $browser->assertPresent('.faq-item:first-child .faq-answer.show');

            // Close accordion
            $browser->click('.faq-item:first-child .faq-question')
                ->waitUntilMissing('.faq-answer.show', 10)
                ->screenshot('faq-accordion-close');

            // Verify view count increased
            $faq->refresh();
            $this->assertEquals($initialViewCount + 1, $faq->view_count);
        });
    }

    /**
     * Test FAQ statistics tracking
     */
    public function testFaqStatisticsTracking()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/faq')
                ->waitFor('.faq-statistics', 10)
                ->assertSee('Total Pertanyaan: 7')
                ->assertSee('Total Dilihat: 70')
                ->assertSee('Total Helpful: 55')
                ->screenshot('faq-statistics');

            // Test statistics after interaction
            $browser->click('.faq-item:first-child .faq-question')
                ->waitFor('.faq-answer', 10)
                ->click('.helpful-btn')
                ->waitForText('Terima kasih', 10)
                ->refresh()
                ->waitFor('.faq-statistics', 10)
                ->assertSee('Total Dilihat: 71')
                ->assertSee('Total Helpful: 56')
                ->screenshot('faq-statistics-after-interaction');
        });
    }

    /**
     * Test FAQ category statistics
     */
    public function testFaqCategoryStatistics()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/faq')
                ->waitFor('.category-statistics', 10)
                ->assertSee('Pelayanan (4)')
                ->assertSee('Informasi (2)')
                ->assertSee('WBS (1)')
                ->screenshot('faq-category-statistics');

            // Test category click
            $browser->click('.category-pelayanan')
                ->waitFor('.filtered-results', 10)
                ->assertSee('4 pertanyaan kategori pelayanan')
                ->screenshot('faq-category-statistics-filter');
        });
    }

    /**
     * Test FAQ unhelpful vote functionality
     */
    public function testFaqUnhelpfulVoteFunctionality()
    {
        $this->browse(function (Browser $browser) {
            $faq = Faq::first();
            $initialUnhelpfulCount = $faq->unhelpful_count ?? 0;

            $browser->visit('/faq')
                ->waitFor('.faq-list', 10)
                ->click('.faq-item:first-child .faq-question')
                ->waitFor('.faq-answer', 10)
                ->click('.unhelpful-btn')
                ->waitForText('Terima kasih atas feedback Anda', 10)
                ->screenshot('faq-unhelpful-vote');

            // Verify unhelpful count increased
            $faq->refresh();
            $this->assertEquals($initialUnhelpfulCount + 1, $faq->unhelpful_count);
        });
    }

    /**
     * Test FAQ feedback form submission
     */
    public function testFaqFeedbackFormSubmission()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/faq')
                ->waitFor('.feedback-form', 10)
                ->type('feedback_question', 'Bagaimana cara menghubungi inspektorat?')
                ->type('feedback_email', 'user@example.com')
                ->press('Submit Feedback')
                ->waitForText('Feedback berhasil dikirim', 10)
                ->screenshot('faq-feedback-submission');

            // Verify feedback was saved
            $this->assertDatabaseHas('faq_feedback', [
                'question' => 'Bagaimana cara menghubungi inspektorat?',
                'email' => 'user@example.com'
            ]);
        });
    }

    /**
     * Test FAQ recent questions display
     */
    public function testFaqRecentQuestionsDisplay()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/faq')
                ->waitFor('.recent-faqs', 10)
                ->assertSee('Pertanyaan Terbaru')
                ->assertSee('Apa itu Portal OPD?') // Most recent
                ->assertSee('Bagaimana cara mengakses dokumen publik?')
                ->screenshot('faq-recent-questions');

            // Verify recent questions are ordered by creation date
            $browser->assertSeeInOrder([
                'Apa itu Portal OPD?',
                'Bagaimana cara mengakses dokumen publik?',
                'Apakah pengaduan dapat diajukan secara anonim?'
            ]);
        });
    }

    /**
     * Test FAQ search with no results
     */
    public function testFaqSearchWithNoResults()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/faq')
                ->waitFor('.search-form', 10)
                ->type('search', 'NonExistentQuestion')
                ->press('Search')
                ->waitFor('.no-results', 10)
                ->assertSee('Tidak ada pertanyaan yang ditemukan')
                ->assertSee('Ajukan pertanyaan baru')
                ->screenshot('faq-search-no-results');
        });
    }

    /**
     * Test FAQ vote prevention for same IP
     */
    public function testFaqVotePreventionSameIp()
    {
        $this->browse(function (Browser $browser) {
            $faq = Faq::first();
            $initialHelpfulCount = $faq->helpful_count;

            // First vote
            $browser->visit('/faq')
                ->waitFor('.faq-list', 10)
                ->click('.faq-item:first-child .faq-question')
                ->waitFor('.faq-answer', 10)
                ->click('.helpful-btn')
                ->waitForText('Terima kasih atas feedback Anda', 10);

            // Second vote should be prevented
            $browser->click('.helpful-btn')
                ->waitForText('Anda sudah memberikan vote', 10)
                ->screenshot('faq-vote-prevention');

            // Verify count only increased once
            $faq->refresh();
            $this->assertEquals($initialHelpfulCount + 1, $faq->helpful_count);
        });
    }

    /**
     * Test FAQ related questions display
     */
    public function testFaqRelatedQuestionsDisplay()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/faq')
                ->waitFor('.faq-list', 10)
                ->click('.faq-item:first-child .faq-question')
                ->waitFor('.related-questions', 10)
                ->assertSee('Pertanyaan Terkait')
                ->assertSee('Apa saja dokumen yang diperlukan untuk pengaduan?')
                ->assertSee('Berapa lama proses penanganan pengaduan?')
                ->screenshot('faq-related-questions');

            // Test related question click
            $browser->click('.related-question:first-child')
                ->waitFor('.faq-answer', 10)
                ->screenshot('faq-related-question-click');
        });
    }
}