<?php

namespace App\Actions\Driver;

use App\Models\Driver;
use Illuminate\Http\Request;

class GetDriversAction
{
    /**
     * Get paginated drivers with filters.
     */
    public function execute(Request $request): array
    {
        $sortBy = $request->get('sortBy', 'created_at');
        $sortDirection = $request->get('sortDirection', 'desc');
        $search = $request->input('search');
        $status = $request->input('status');
        $companyId = $request->input('company_id');
        $perPage = $request->input('per_page', 10);
        
        if ($status == "all") {
            $status = null;
        }

        $drivers = Driver::with(['user', 'company'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('no_hp', 'like', "%{$search}%")
                      ->orWhere('no_ktp', 'like', "%{$search}%")
                      ->orWhere('no_sim', 'like', "%{$search}%")
                      ->orWhere('alamat', 'like', "%{$search}%")
                      ->orWhereHas('user', function ($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                      })
                      ->orWhereHas('company', function ($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      });
                });
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($companyId, function ($query, $companyId) {
                $query->where('company_id', $companyId);
            })
            ->orderBy($sortBy, $sortDirection)
            ->paginate($perPage)
            ->withQueryString();

        // Get counts for status tabs
        $statusCounts = [
            'all' => Driver::when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('no_hp', 'like', "%{$search}%")
                      ->orWhere('no_ktp', 'like', "%{$search}%")
                      ->orWhere('no_sim', 'like', "%{$search}%")
                      ->orWhere('alamat', 'like', "%{$search}%")
                      ->orWhereHas('user', function ($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                      })
                      ->orWhereHas('company', function ($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      });
                });
            })
            ->when($companyId, function ($query, $companyId) {
                $query->where('company_id', $companyId);
            })
            ->count(),
            'pending' => Driver::when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('no_hp', 'like', "%{$search}%")
                      ->orWhere('no_ktp', 'like', "%{$search}%")
                      ->orWhere('no_sim', 'like', "%{$search}%")
                      ->orWhere('alamat', 'like', "%{$search}%")
                      ->orWhereHas('user', function ($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                      })
                      ->orWhereHas('company', function ($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      });
                });
            })
            ->when($companyId, function ($query, $companyId) {
                $query->where('company_id', $companyId);
            })
            ->where('status', 'pending')
            ->count(),
            'active' => Driver::when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('no_hp', 'like', "%{$search}%")
                      ->orWhere('no_ktp', 'like', "%{$search}%")
                      ->orWhere('no_sim', 'like', "%{$search}%")
                      ->orWhere('alamat', 'like', "%{$search}%")
                      ->orWhereHas('user', function ($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                      })
                      ->orWhereHas('company', function ($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      });
                });
            })
            ->when($companyId, function ($query, $companyId) {
                $query->where('company_id', $companyId);
            })
            ->where('status', 'active')
            ->count(),
            'inactive' => Driver::when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('no_hp', 'like', "%{$search}%")
                      ->orWhere('no_ktp', 'like', "%{$search}%")
                      ->orWhere('no_sim', 'like', "%{$search}%")
                      ->orWhere('alamat', 'like', "%{$search}%")
                      ->orWhereHas('user', function ($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                      })
                      ->orWhereHas('company', function ($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      });
                });
            })
            ->when($companyId, function ($query, $companyId) {
                $query->where('company_id', $companyId);
            })
            ->where('status', 'inactive')
            ->count(),
        ];

        return [
            'drivers' => $drivers,
            'statusCounts' => $statusCounts,
        ];
    }
}

