<?php

namespace Database\Factories;

use App\Models\PortalPapuaTengah;
use Illuminate\Database\Eloquent\Factories\Factory;

class PortalPapuaTengahFactory extends Factory
{
    protected $model = PortalPapuaTengah::class;

    public function definition(): array
    {
        return [
            'judul' => $this->faker->sentence,
            'konten' => $this->faker->paragraphs(3, true),
            'kategori' => $this->faker->randomElement(['berita', 'pengumuman', 'kegiatan']),
            'status' => $this->faker->randomElement(['draft', 'published']),
            'gambar' => $this->faker->imageUrl(640, 480, 'news'),
            'created_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'updated_at' => now(),
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
        ]);
    }

    public function berita(): static
    {
        return $this->state(fn (array $attributes) => [
            'kategori' => 'berita',
        ]);
    }

    public function pengumuman(): static
    {
        return $this->state(fn (array $attributes) => [
            'kategori' => 'pengumuman',
        ]);
    }

    public function kegiatan(): static
    {
        return $this->state(fn (array $attributes) => [
            'kategori' => 'kegiatan',
        ]);
    }
}
