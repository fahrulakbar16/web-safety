<?php

namespace App\Actions\Driver;

use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UpdateDriverAction
{
    /**
     * Update a driver.
     */
    public function execute(Driver $driver, Request $request, array $data): Driver
    {
        $fotoKtpPath = $driver->foto_ktp;
        $fotoSimPath = $driver->foto_sim;
        $fotoDiriPath = $driver->foto_diri;

        // Handle foto_ktp upload
        if ($request->hasFile('foto_ktp')) {
            // Delete old file if exists
            if ($driver->foto_ktp && Storage::disk('public')->exists($driver->foto_ktp)) {
                Storage::disk('public')->delete($driver->foto_ktp);
            }
            $file = $request->file('foto_ktp');
            $fotoKtpPath = $file->store('drivers/ktp', 'public');
        }

        // Handle foto_sim upload
        if ($request->hasFile('foto_sim')) {
            // Delete old file if exists
            if ($driver->foto_sim && Storage::disk('public')->exists($driver->foto_sim)) {
                Storage::disk('public')->delete($driver->foto_sim);
            }
            $file = $request->file('foto_sim');
            $fotoSimPath = $file->store('drivers/sim', 'public');
        }

        // Handle foto_diri upload
        if ($request->hasFile('foto_diri')) {
            // Delete old file if exists
            if ($driver->foto_diri && Storage::disk('public')->exists($driver->foto_diri)) {
                Storage::disk('public')->delete($driver->foto_diri);
            }
            $file = $request->file('foto_diri');
            $fotoDiriPath = $file->store('drivers/diri', 'public');
        }

        // Update user account only if email, username, or password is provided
        // This allows updating biodata without updating user account
        // For profile page, we don't update user account at all
        $user = $driver->user;
        if ($user && (isset($data['email']) || isset($data['username']) || !empty($data['password']))) {
            $userData = [];

            // Only update username if provided
            if (isset($data['username'])) {
                $userData['username'] = $data['username'];
            }

            // Only update email if provided
            if (isset($data['email'])) {
                $userData['email'] = $data['email'];
            }

            // Update password only if provided
            if (!empty($data['password'])) {
                $userData['password'] = Hash::make($data['password']);
            }

            // Only update status if provided
            if (isset($data['status'])) {
                $userData['status'] = $data['status'];
            }

            // Only update if there's data to update
            if (!empty($userData)) {
                $user->update($userData);
            }
        }

        // Update driver
        $driver->update([
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

        return $driver->fresh();
    }
}

