<?php

namespace App\Actions\Dashboard;

use App\Models\Role;
use App\Models\User;

class GetDashboardMetricsAction
{
    /**
     * Get dashboard metrics.
     */
    public function execute(): array
    {
        try {
            return [
                'total_users' => User::count(),
                'active_users' => User::where('status', 'active')->count(),
                'total_roles' => Role::count(),
                'users_with_roles' => User::whereHas('roles')->count(),
            ];
        } catch (\Exception $e) {
            // Fallback data in case of errors
            return [
                'total_users' => 0,
                'active_users' => 0,
                'total_roles' => 0,
                'users_with_roles' => 0,
            ];
        }
    }
}

