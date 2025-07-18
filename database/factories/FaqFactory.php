<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Faq;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Faq>
 */
class FaqFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['umum', 'pelayanan', 'wbs', 'dokumen', 'kontak'];
        
        $questions = [
            'Bagaimana cara mengajukan pengaduan melalui WBS?',
            'Apa saja dokumen yang diperlukan untuk layanan ini?',
            'Berapa lama waktu pemrosesan layanan?',
            'Bagaimana cara mengetahui status pengaduan saya?',
            'Apakah layanan ini berbayar?',
            'Dimana lokasi kantor Inspektorat Papua Tengah?',
            'Jam operasional pelayanan adalah?',
            'Bagaimana cara mendapatkan informasi publik?',
            'Siapa yang bisa menggunakan layanan WBS?',
            'Bagaimana cara mengunduh dokumen dari portal?'
        ];

        $answers = [
            'Anda dapat mengajukan pengaduan melalui menu WBS di halaman utama website. Isi formulir dengan lengkap dan sertakan bukti pendukung jika ada.',
            'Dokumen yang diperlukan meliputi KTP, surat permohonan, dan dokumen pendukung lainnya sesuai jenis layanan.',
            'Waktu pemrosesan bervariasi tergantung jenis layanan, umumnya 3-7 hari kerja.',
            'Anda dapat mengecek status pengaduan menggunakan nomor tiket yang diberikan saat pengajuan.',
            'Sebagian besar layanan kami gratis, kecuali untuk layanan tertentu yang memerlukan biaya administrasi.',
            'Kantor Inspektorat Papua Tengah berlokasi di Jl. Raya Nabire, Papua Tengah.',
            'Jam operasional pelayanan adalah Senin-Jumat pukul 08.00-16.00 WIT.',
            'Informasi publik dapat diperoleh melalui portal website atau datang langsung ke kantor.',
            'Layanan WBS dapat digunakan oleh siapa saja, baik dengan identitas maupun anonim.',
            'Klik pada dokumen yang diinginkan, kemudian pilih tombol download untuk mengunduh.'
        ];

        return [
            'pertanyaan' => fake()->randomElement($questions),
            'jawaban' => fake()->randomElement($answers),
            'kategori' => fake()->randomElement($categories),
            'status' => 'published',
            'urutan' => fake()->numberBetween(1, 100),
            'view_count' => fake()->numberBetween(0, 500),
            'is_featured' => fake()->boolean(20), // 20% chance to be featured
            'created_by' => 1,
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'updated_at' => now(),
        ];
    }

    /**
     * Indicate that the FAQ should be published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
        ]);
    }

    /**
     * Indicate that the FAQ should be in draft status.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
        ]);
    }

    /**
     * Create FAQ with specific category.
     */
    public function category(string $category): static
    {
        return $this->state(fn (array $attributes) => [
            'kategori' => $category,
        ]);
    }

    /**
     * Create featured FAQ.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
            'urutan' => fake()->numberBetween(1, 10),
        ]);
    }

    /**
     * Create popular FAQ.
     */
    public function popular(): static
    {
        return $this->state(fn (array $attributes) => [
            'view_count' => fake()->numberBetween(1000, 5000),
            'is_featured' => true,
        ]);
    }

    /**
     * Create FAQ with specific order.
     */
    public function withOrder(int $order): static
    {
        return $this->state(fn (array $attributes) => [
            'urutan' => $order,
        ]);
    }

    /**
     * Create FAQ with specific creator.
     */
    public function createdBy(int $userId): static
    {
        return $this->state(fn (array $attributes) => [
            'created_by' => $userId,
        ]);
    }

    /**
     * Create WBS related FAQ.
     */
    public function wbsRelated(): static
    {
        $wbsQuestions = [
            'Bagaimana cara melaporkan dugaan korupsi?',
            'Apakah identitas pelapor akan dirahasiakan?',
            'Dokumen apa saja yang bisa dilampirkan dalam laporan?',
            'Berapa lama proses penanganan laporan WBS?',
            'Bagaimana cara melacak status laporan saya?'
        ];

        $wbsAnswers = [
            'Anda dapat melaporkan melalui sistem WBS online atau datang langsung ke kantor.',
            'Ya, identitas pelapor akan dijaga kerahasiaannya sesuai peraturan yang berlaku.',
            'Dapat melampirkan foto, dokumen, video, atau bukti pendukung lainnya.',
            'Proses penanganan laporan akan dilakukan maksimal 30 hari kerja.',
            'Gunakan nomor tiket yang diberikan untuk melacak status laporan Anda.'
        ];

        return $this->state(fn (array $attributes) => [
            'kategori' => 'wbs',
            'pertanyaan' => fake()->randomElement($wbsQuestions),
            'jawaban' => fake()->randomElement($wbsAnswers),
        ]);
    }
}