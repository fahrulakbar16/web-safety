<?php

namespace App\Actions\Driver;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CheckDriverBiodataCompleteAction
{
    /**
     * Check if driver biodata is complete.
     *
     * Required fields:
     * - company_id
     * - name
     * - alamat
     * - no_hp
     * - no_ktp
     * - email (from user)
     * - username (from user)
     *
     * Optional fields (also checked):
     * - no_sim
     * - foto_ktp
     * - foto_sim
     * - foto_diri
     *
     * @return array{
     *     is_complete: bool,
     *     missing_required_fields: array,
     *     missing_optional_fields: array,
     *     missing_fields: array
     * }
     */
    public function execute(): array
    {
        /** @var User $user */
        $user = Auth::user();

        // Check if user has driver role
        if (!$user->roles()->where('name', 'driver')->exists()) {
            return [
                'is_complete' => true,
                'missing_required_fields' => [],
                'missing_optional_fields' => [],
                'missing_fields' => [],
            ];
        }

        $driver = $user->drivers()->first();

        if (!$driver) {
            return [
                'is_complete' => false,
                'missing_required_fields' => ['driver_data'],
                'missing_optional_fields' => [],
                'missing_fields' => ['driver_data'],
            ];
        }

        $missingRequiredFields = [];

        // Semua field dianggap required
        if (empty($driver->company_id)) {
            $missingRequiredFields[] = 'company_id';
        }
        if (empty($driver->name) || trim($driver->name) === '') {
            $missingRequiredFields[] = 'name';
        }
        if (empty($driver->alamat) || trim($driver->alamat) === '') {
            $missingRequiredFields[] = 'alamat';
        }
        if (empty($driver->no_hp) || trim($driver->no_hp) === '') {
            $missingRequiredFields[] = 'no_hp';
        }
        if (empty($driver->no_ktp) || trim($driver->no_ktp) === '') {
            $missingRequiredFields[] = 'no_ktp';
        }

        // User fields (required)
        if (empty($user->email) || trim($user->email) === '') {
            $missingRequiredFields[] = 'email';
        }
        if (empty($user->username) || trim($user->username) === '') {
            $missingRequiredFields[] = 'username';
        }

        // Dulunya optional, sekarang required
        if (empty($driver->no_sim) || trim($driver->no_sim) === '') {
            $missingRequiredFields[] = 'no_sim';
        }
        if (empty($driver->foto_ktp)) {
            $missingRequiredFields[] = 'foto_ktp';
        }
        if (empty($driver->foto_sim)) {
            $missingRequiredFields[] = 'foto_sim';
        }
        if (empty($driver->foto_diri)) {
            $missingRequiredFields[] = 'foto_diri';
        }

        // All fields missing now required
        $missingFields = $missingRequiredFields;

        // Biodata is complete only if all required fields are filled
        $isComplete = empty($missingRequiredFields);

        return [
            'is_complete' => $isComplete,
            'missing_required_fields' => $missingRequiredFields,
            'missing_optional_fields' => [],
            'missing_fields' => $missingFields,
        ];
    }
}

