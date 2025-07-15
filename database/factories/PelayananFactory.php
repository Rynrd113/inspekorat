<?php

namespace Database\Factories;

use App\Models\Pelayanan;
use Illuminate\Database\Eloquent\Factories\Factory;

class PelayananFactory extends Factory
{
    protected $model = Pelayanan::class;

    public function definition(): array
    {
        return [
            'nama' => $this->faker->sentence(3),
            'deskripsi' => $this->faker->paragraph(3),
            'persyaratan' => $this->faker->paragraph(2),
            'waktu_pelayanan' => $this->faker->randomElement(['1 hari', '2 hari', '3 hari', '1 minggu']),
            'biaya' => $this->faker->randomElement(['Gratis', 'Rp 50.000', 'Rp 100.000', 'Rp 200.000']),
            'status' => $this->faker->randomElement(['aktif', 'nonaktif']),
            'dokumen' => $this->faker->optional()->filePath(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'aktif',
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'nonaktif',
        ]);
    }
}
