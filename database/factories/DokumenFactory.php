<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Dokumen;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dokumen>
 */
class DokumenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['peraturan', 'laporan', 'panduan', 'formulir', 'lainnya'];
        
        return [
            'judul' => fake()->sentence(6),
            'deskripsi' => fake()->paragraph(2),
            'kategori' => fake()->randomElement($categories),
            'file_path' => 'documents/' . fake()->uuid() . '.pdf',
            'file_name' => fake()->word() . '.pdf',
            'file_size' => fake()->numberBetween(100000, 5000000), // 100KB - 5MB
            'mime_type' => 'application/pdf',
            'status' => 'published',
            'download_count' => fake()->numberBetween(0, 100),
            'created_by' => 1,
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'updated_at' => now(),
        ];
    }

    /**
     * Indicate that the document should be in draft status.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
        ]);
    }

    /**
     * Indicate that the document should be published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
        ]);
    }

    /**
     * Create document with specific category.
     */
    public function category(string $category): static
    {
        return $this->state(fn (array $attributes) => [
            'kategori' => $category,
        ]);
    }

    /**
     * Create document with specific file type.
     */
    public function fileType(string $extension, string $mimeType): static
    {
        return $this->state(fn (array $attributes) => [
            'file_name' => fake()->word() . '.' . $extension,
            'mime_type' => $mimeType,
        ]);
    }

    /**
     * Create document with high download count.
     */
    public function popular(): static
    {
        return $this->state(fn (array $attributes) => [
            'download_count' => fake()->numberBetween(500, 2000),
        ]);
    }

    /**
     * Create document with specific creator.
     */
    public function createdBy(int $userId): static
    {
        return $this->state(fn (array $attributes) => [
            'created_by' => $userId,
        ]);
    }

    /**
     * Create large file document.
     */
    public function largeFile(): static
    {
        return $this->state(fn (array $attributes) => [
            'file_size' => fake()->numberBetween(10000000, 50000000), // 10MB - 50MB
        ]);
    }

    /**
     * Create recent document.
     */
    public function recent(): static
    {
        return $this->state(fn (array $attributes) => [
            'created_at' => fake()->dateTimeBetween('-1 month', 'now'),
            'updated_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ]);
    }
}