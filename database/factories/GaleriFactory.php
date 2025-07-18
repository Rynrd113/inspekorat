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
            'gambar' => json_encode([
                'image1.jpg',
                'image2.jpg',
                'image3.jpg'
            ]),
            'status' => 'published',
            'tanggal_upload' => fake()->dateTimeBetween('-6 months', 'now'),
            'created_by' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Indicate that the gallery should be in draft status.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
        ]);
    }

    /**
     * Indicate that the gallery should be published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
        ]);
    }

    /**
     * Create gallery with specific number of images.
     */
    public function withImages(int $count = 5): static
    {
        $images = [];
        for ($i = 1; $i <= $count; $i++) {
            $images[] = "gallery_image_{$i}.jpg";
        }

        return $this->state(fn (array $attributes) => [
            'gambar' => json_encode($images),
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
            'tanggal_upload' => fake()->dateTimeBetween('-1 week', 'now'),
            'created_at' => fake()->dateTimeBetween('-1 week', 'now'),
            'updated_at' => now(),
        ]);
    }
}