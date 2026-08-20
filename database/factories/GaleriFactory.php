<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Galeri;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Galeri>
 */
class GaleriFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'judul' => fake()->sentence(4),
            'deskripsi' => fake()->paragraph(3),
            'kategori' => collect(['Kegiatan', 'Infrastruktur', 'Pelayanan', 'Pengawasan'])->random(),
            'file_path' => 'galeri/' . fake()->uuid() . '.jpg',
            'file_name' => fake()->uuid() . '.jpg',
            'file_type' => 'image/jpeg',
            'file_size' => fake()->numberBetween(50000, 5000000),
            'status' => true,
            'tanggal_publikasi' => fake()->dateTimeBetween('-6 months', 'now'),
        ];
    }

    /**
     * Indicate that the gallery should be in draft status.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => false,
        ]);
    }

    /**
     * Indicate that the gallery should be published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => true,
        ]);
    }

    /**
     * Create gallery with specific number of images.
     */
    public function withImages(int $count = 5): static
    {
        return $this->state(fn (array $attributes) => [
            'file_path' => 'galeri/' . fake()->uuid() . '.jpg',
            'file_name' => fake()->uuid() . '.jpg',
        ]);
    }

    /**
     * Create gallery with specific creator.
     */
    public function createdBy(int $userId): static
    {
        return $this->state(fn (array $attributes) => [
            'created_by' => $userId,
        ]);
    }

    /**
     * Create recent gallery.
     */
    public function recent(): static
    {
        return $this->state(fn (array $attributes) => [
            'tanggal_publikasi' => fake()->dateTimeBetween('-1 week', 'now'),
        ]);
    }
}