<?php

namespace Database\Factories;

use App\Models\Wbs;
use Illuminate\Database\Eloquent\Factories\Factory;

class WbsFactory extends Factory
{
    protected $model = Wbs::class;

    public function definition(): array
    {
        return [
            'nama' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'telepon' => $this->faker->phoneNumber,
            'pesan' => $this->faker->paragraph,
            'status' => $this->faker->randomElement(['pending', 'processed', 'completed']),
            'created_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'updated_at' => now(),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    public function processed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'processed',
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
        ]);
    }
}
