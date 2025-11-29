<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create basic roles
        $driver = Role::firstOrCreate(['name' => 'Driver', 'guard_name' => 'web']);

        // Driver gets dashboard and assessment permissions
        $driverPermissions = Permission::whereIn('name', [
            'dashboard.view',
            'driver.assessment.view',
        ])->get();
        $driver->syncPermissions($driverPermissions);

        // Superadmin gets all permissions
        $superadmin = Role::firstOrCreate(['name' => 'Superadmin', 'guard_name' => 'web']);
        $superadmin->syncPermissions(Permission::all());

        // Admin gets users and roles permissions
        $admin = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $adminPermissions = Permission::whereIn('name', [
            'dashboard.view',
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',
        ])->get();
        $admin->syncPermissions($adminPermissions);
    }
}
