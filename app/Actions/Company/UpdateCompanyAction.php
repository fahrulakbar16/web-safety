<?php

namespace App\Actions\Company;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UpdateCompanyAction
{
    /**
     * Update a company.
     */
    public function execute(Company $company, Request $request, array $data): Company
    {
        $logoPath = $company->logo;

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($company->logo && Storage::disk('public')->exists($company->logo)) {
                Storage::disk('public')->delete($company->logo);
            }

            $file = $request->file('logo');
            $logoPath = $file->store('companies', 'public');
        }
        // If no file uploaded, keep existing logo (don't update logo field)

        $company->update([
            'name' => $data['name'],
            'alamat' => $data['alamat'] ?? null,
            'logo' => $logoPath,
            'status' => $data['status'] ?? 'active',
        ]);

        return $company->fresh();
    }
}

