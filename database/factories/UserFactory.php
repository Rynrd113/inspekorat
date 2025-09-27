<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => 'content_admin',
            'status' => 'active',
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Create user dengan role super admin
     */
    public function superAdmin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'super_admin',
            'name' => 'Super Admin',
            'email' => 'superadmin@inspektorat.com',
        ]);
    }

    /**
     * Create user dengan role admin
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
            'name' => 'Admin',
            'email' => 'admin@inspektorat.com',
        ]);
    }

    /**
     * Create user dengan role content manager
     */
    public function contentManager(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'content_manager',
            'name' => 'Content Manager',
            'email' => 'content@inspektorat.com',
        ]);
    }

    /**
     * Create user dengan role service manager
     */
    public function serviceManager(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'service_manager',
            'name' => 'Service Manager',
            'email' => 'service@inspektorat.com',
        ]);
    }

    /**
     * Create user dengan role WBS manager
     */
    public function wbsManager(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'wbs_manager',
            'name' => 'WBS Manager',
            'email' => 'wbs@inspektorat.com',
        ]);
    }

    /**
     * Create user dengan role OPD manager
     */
    public function opdManager(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'opd_manager',
            'name' => 'OPD Manager',
            'email' => 'opd@inspektorat.com',
        ]);
    }

    /**
     * Create user dengan role admin berita
     */
    public function adminBerita(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin_berita',
            'name' => 'Admin Berita',
            'email' => 'admin.berita@inspektorat.com',
        ]);
    }

    /**
     * Create user dengan role admin pelayanan
     */
    public function adminPelayanan(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin_pelayanan',
            'name' => 'Admin Pelayanan',
            'email' => 'admin.pelayanan@inspektorat.com',
        ]);
    }

    /**
     * Create user dengan status inactive
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }

    /**
     * Create user dengan password khusus
     */
    public function withPassword(string $password): static
    {
        return $this->state(fn (array $attributes) => [
            'password' => Hash::make($password),
        ]);
    }
}
