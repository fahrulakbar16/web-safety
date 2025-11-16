<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Superadmin user
        $superadmin = User::firstOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'name' => 'Superadmin',
                'username' => 'superadmin',
                'password' => Hash::make('123123'),
                'role' => 'Superadmin',
                'status' => 'active',
            ]
        );
        $superadmin->syncRoles('Superadmin');

        // Create Admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'username' => 'admin',
                'password' => Hash::make('123123'),
                'role' => 'Admin',
                'status' => 'active',
            ]
        );
        $admin->syncRoles('Admin');
    }
}
