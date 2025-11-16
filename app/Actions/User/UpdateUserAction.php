<?php

namespace App\Actions\User;

use App\Models\User;

class UpdateUserAction
{
    /**
     * Update a user.
     */
    public function execute(User $user, array $data): User
    {
        $user->update([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'status' => $data['status'],
        ]);

        // Sync role
        $user->syncRoles([$data['role']]);

        return $user->fresh();
    }
}

