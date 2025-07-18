<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\PortalOpd;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PortalOpd>
 */
class PortalOpdFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $opdNames = [
            'Dinas Pendidikan Papua Tengah',
            'Dinas Kesehatan Papua Tengah',
            'Dinas Pekerjaan Umum Papua Tengah',
            'Dinas Perhubungan Papua Tengah',
            'Dinas Sosial Papua Tengah',
            'Dinas Tenaga Kerja Papua Tengah',
            'Bappeda Papua Tengah',
            'RSUD Papua Tengah',
            'Dinas Lingkungan Hidup Papua Tengah',
            'Dinas Pariwisata Papua Tengah'
        ];

        return [
            'nama_opd' => fake()->randomElement($opdNames),
            'deskripsi' => fake()->paragraph(4),
            'alamat' => fake()->address(),
            'telepon' => fake()->phoneNumber(),
            'email' => fake()->safeEmail(),
            'website' => fake()->url(),
            'logo' => 'logos/' . fake()->uuid() . '.png',
            'kepala_opd' => fake()->name(),
            'nip_kepala' => fake()->numerify('19#############'),
            'visi' => fake()->sentence(10),
            'misi' => fake()->paragraph(3),
            'tugas_fungsi' => fake()->paragraph(4),
            'struktur_organisasi' => 'struktur/' . fake()->uuid() . '.jpg',
            'status' => 'active',
            'urutan' => fake()->numberBetween(1, 100),
            'created_by' => 1,
            'created_at' => fake()->dateTimeBetween('-2 years', 'now'),
            'updated_at' => now(),
        ];
    }

    /**
     * Indicate that the OPD should be active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the OPD should be inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }

    /**
     * Create OPD with specific name.
     */
    public function withName(string $name): static
    {
        return $this->state(fn (array $attributes) => [
            'nama_opd' => $name,
        ]);
    }

    /**
     * Create OPD with complete information.
     */
    public function complete(): static
    {
        return $this->state(fn (array $attributes) => [
            'website' => 'https://' . fake()->domainName(),
            'logo' => 'logos/complete_opd_logo.png',
            'struktur_organisasi' => 'struktur/complete_struktur.jpg',
            'visi' => 'Menjadi OPD terdepan dalam pelayanan publik yang profesional dan berintegritas',
            'misi' => 'Memberikan pelayanan prima kepada masyarakat dengan transparansi dan akuntabilitas',
        ]);
    }

    /**
     * Create OPD with specific order.
     */
    public function withOrder(int $order): static
    {
        return $this->state(fn (array $attributes) => [
            'urutan' => $order,
        ]);
    }

    /**
     * Create OPD with specific creator.
     */
    public function createdBy(int $userId): static
    {
        return $this->state(fn (array $attributes) => [
            'created_by' => $userId,
        ]);
    }

    /**
     * Create priority OPD (high order).
     */
    public function priority(): static
    {
        return $this->state(fn (array $attributes) => [
            'urutan' => fake()->numberBetween(1, 10),
            'status' => 'active',
        ]);
    }
}