<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $modules = [
            // Menu Utama
            'Dashboard' => [
                'key' => 'dashboard',
                'actions' => ['view'],
            ],

            // Konfigurasi
            'Daftar Pengguna' => [
                'key' => 'users',
                'actions' => ['view', 'create', 'edit', 'delete'],
            ],
            'Jabatan & Akses' => [
                'key' => 'roles',
                'actions' => ['view', 'create', 'edit', 'delete'],
            ],
            'Perusahaan' => [
                'key' => 'companies',
                'actions' => ['view', 'create', 'edit', 'delete'],
            ],
            'Driver' => [
                'key' => 'drivers',
                'actions' => ['view', 'create', 'edit', 'delete'],
            ],
            'Event' => [
                'key' => 'events',
                'actions' => ['view', 'create', 'edit', 'delete'],
            ],
            'Assessment Driver' => [
                'key' => 'driver.assessment',
                'actions' => ['view'],
            ],
            'Pengaturan Website' => [
                'key' => 'settings',
                'actions' => ['view', 'edit'],
            ],
        ];

        $labels = [
            'view'    => 'Lihat Data',
            'create'  => 'Tambah Data',
            'edit'    => 'Edit Data',
            'delete'  => 'Hapus Data',
        ];

        foreach ($modules as $groupName => $module) {
            foreach ($module['actions'] as $action) {
                Permission::firstOrCreate(
                    [
                        'name' => "{$module['key']}.{$action}",
                        'guard_name' => 'web',
                    ],
                    [
                        'group_name'   => $groupName,
                        'display_name' => "{$labels[$action]}",
                    ]
                );
            }
        }
    }
}
