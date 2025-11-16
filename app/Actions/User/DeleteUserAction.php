<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DeleteUserAction
{
    /**
     * Delete a user.
     *
     * @throws \Exception
     */
    public function execute(User $user): void
    {
        // Check if user that will be deleted is the currently logged in user
        if ($user->id === Auth::id()) {
            throw new \Exception('Tidak dapat menghapus akun sendiri');
        }

        $user->delete();
    }
}

