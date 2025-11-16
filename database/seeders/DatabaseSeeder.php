<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Access control
            PermissionSeeder::class,
            RoleSeeder::class,

            // Users
            UserSeeder::class,
        ]);
    }
}
