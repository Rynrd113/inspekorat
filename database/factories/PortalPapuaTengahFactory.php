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
            'status' => true,
            'gambar' => $this->faker->imageUrl(640, 480, 'news'),
            'tanggal_publikasi' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'author' => $this->faker->name,
            'views' => 0,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => true,
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => false,
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
