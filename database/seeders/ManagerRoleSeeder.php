<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ManagerRoleSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create manager users
        $managers = [
            [
                'name' => 'Content Manager',
                'email' => 'content.manager@inspektorat.id',
                'password' => Hash::make('contentmanager123'),
                'role' => 'content_manager',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Service Manager',
                'email' => 'service.manager@inspektorat.id',
                'password' => Hash::make('servicemanager123'),
                'role' => 'service_manager',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'OPD Manager',
                'email' => 'opd.manager@inspektorat.id',
                'password' => Hash::make('opdmanager123'),
                'role' => 'opd_manager',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'WBS Manager',
                'email' => 'wbs.manager@inspektorat.id',
                'password' => Hash::make('wbsmanager123'),
                'role' => 'wbs_manager',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($managers as $manager) {
            User::updateOrCreate(
                ['email' => $manager['email']],
                $manager
            );
        }

        $this->command->info('Manager roles seeded successfully!');
        $this->command->info('Credentials:');
        $this->command->info('Content Manager: content.manager@inspektorat.id / contentmanager123');
        $this->command->info('Service Manager: service.manager@inspektorat.id / servicemanager123');
        $this->command->info('OPD Manager: opd.manager@inspektorat.id / opdmanager123');
        $this->command->info('WBS Manager: wbs.manager@inspektorat.id / wbsmanager123');
    }
}
