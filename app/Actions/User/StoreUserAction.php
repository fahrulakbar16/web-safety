<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StoreUserAction
{
    /**
     * Store a new user.
     */
    public function execute(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'status' => $data['status'],
            'password' => Hash::make('password'), // Default password
        ]);

        // Assign role
        $user->assignRole($data['role']);

        return $user;
    }
}

