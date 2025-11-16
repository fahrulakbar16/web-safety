<?php

namespace App\Actions\Company;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoreCompanyAction
{
    /**
     * Store a new company.
     */
    public function execute(Request $request, array $data): Company
    {
        $logoPath = null;

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $logoPath = $file->store('companies', 'public');
        }

        $company = Company::create([
            'name' => $data['name'],
            'alamat' => $data['alamat'] ?? null,
            'logo' => $logoPath,
            'status' => $data['status'] ?? 'active',
        ]);

        return $company;
    }
}

