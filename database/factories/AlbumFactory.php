<?php

namespace Database\Factories;

use App\Models\Album;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Album>
 */
class AlbumFactory extends Factory
{
    protected $model = Album::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $namaAlbum = fake()->sentence(3);
        
        return [
            'parent_id' => null,
            'nama_album' => $namaAlbum,
            'slug' => Str::slug($namaAlbum) . '-' . Str::random(5),
            'deskripsi' => fake()->paragraph(2),
            'cover_image' => null,
            'tanggal_kegiatan' => fake()->dateTimeBetween('-1 year', 'now'),
            'status' => true,
            'urutan' => fake()->numberBetween(0, 100),
        ];
    }

    /**
     * Indicate that the album is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => false,
        ]);
    }

    /**
     * Indicate that the album is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => true,
        ]);
    }

    /**
     * Indicate that the album has a parent (is a sub-album).
     */
    public function withParent(?Album $parent = null): static
    {
        return $this->state(fn (array $attributes) => [
            'parent_id' => $parent?->id ?? Album::factory(),
        ]);
    }

    /**
     * Indicate that the album has a cover image.
     */
    public function withCover(): static
    {
        return $this->state(fn (array $attributes) => [
            'cover_image' => 'albums/covers/' . fake()->uuid() . '.jpg',
        ]);
    }
}
