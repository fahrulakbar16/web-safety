<?php

namespace App\Actions\Driver;

use App\Models\Driver;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class StoreDriverAction
{
    /**
     * Store a new driver.
     */
    public function execute(Request $request, array $data): Driver
    {
        $fotoKtpPath = null;
        $fotoSimPath = null;
        $fotoDiriPath = null;

        // Handle file uploads
        if ($request->hasFile('foto_ktp')) {
            $file = $request->file('foto_ktp');
            $fotoKtpPath = $file->store('drivers/ktp', 'public');
        }

        if ($request->hasFile('foto_sim')) {
            $file = $request->file('foto_sim');
            $fotoSimPath = $file->store('drivers/sim', 'public');
        }

        if ($request->hasFile('foto_diri')) {
            $file = $request->file('foto_diri');
            $fotoDiriPath = $file->store('drivers/diri', 'public');
        }

        // Create user account
        $user = User::create([
            'name'     => $data['name'],
            'username' => $data['username'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'status'   => $data['status'] ?? 'pending',
        ]);

        // Assign driver role if exists, otherwise assign default role
        try {
            $user->assignRole('Driver');
        } catch (\Exception $e) {
            // If Driver role doesn't exist, assign first available role or skip
            // You might want to create Driver role in RoleSeeder
        }

        // Create driver
        $driver = Driver::create([
            'user_id'    => $user->id,
            'company_id' => $data['company_id'],
            'name'       => $data['name'],
            'alamat'     => $data['alamat'],
            'no_hp'      => $data['no_hp'],
            'no_ktp'     => $data['no_ktp'],
            'no_sim'     => $data['no_sim'] ?? null,
            'foto_ktp'   => $fotoKtpPath,
            'foto_sim'   => $fotoSimPath,
            'foto_diri'  => $fotoDiriPath,
            'status'     => $data['status'] ?? 'pending',
        ]);

        return $driver;
    }
}

