<?php

namespace App\Actions\Company;

use App\Models\Company;
use Illuminate\Http\Request;

class GetCompaniesAction
{
    /**
     * Get paginated companies with filters.
     */
    public function execute(Request $request): array
    {
        $sortBy = $request->get('sortBy', 'created_at');
        $sortDirection = $request->get('sortDirection', 'desc');
        $search = $request->input('search');
        $status = $request->input('status');
        $perPage = $request->input('per_page', 10);

        if ($status == "all") {
            $status = null;
        }

        $companies = Company::when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('alamat', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->orderBy($sortBy, $sortDirection)
            ->paginate($perPage)
            ->withQueryString();

        // Get counts for status tabs
        $statusCounts = [
            'all' => Company::when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('alamat', 'like', "%{$search}%");
                });
            })
            ->count(),
            'active' => Company::when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('alamat', 'like', "%{$search}%");
                });
            })
            ->where('status', 'active')
            ->count(),
            'inactive' => Company::when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('alamat', 'like', "%{$search}%");
                });
            })
            ->where('status', 'inactive')
            ->count(),
        ];

        return [
            'companies' => $companies,
            'statusCounts' => $statusCounts,
        ];
    }
}

