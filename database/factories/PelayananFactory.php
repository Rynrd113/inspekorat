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
            'prosedur' => json_encode([$this->faker->sentence(), $this->faker->sentence(), $this->faker->sentence()]),
            'persyaratan' => json_encode([$this->faker->sentence(), $this->faker->sentence()]),
            'waktu_penyelesaian' => $this->faker->randomElement(['1 hari', '2 hari', '3 hari', '1 minggu']),
            'biaya' => $this->faker->randomElement(['Gratis', 'Rp 50.000', 'Rp 100.000', 'Rp 200.000']),
            'kategori' => $this->faker->randomElement(['perizinan', 'administrasi', 'pengawasan', 'konsultasi', 'audit', 'lainnya']),
            'status' => $this->faker->boolean(),
            'urutan' => $this->faker->numberBetween(1, 100),
            'kontak_pic' => $this->faker->name(),
            'email_pic' => $this->faker->email(),
            'telepon_pic' => $this->faker->phoneNumber(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => true,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => false,
        ]);
    }
}
