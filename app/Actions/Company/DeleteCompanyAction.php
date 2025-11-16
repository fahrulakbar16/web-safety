<?php

namespace App\Actions\Company;

use App\Models\Company;

class DeleteCompanyAction
{
    /**
     * Delete a company.
     *
     * @throws \Exception
     */
    public function execute(Company $company): void
    {
        // Check if company has related drivers
        if ($company->drivers()->count() > 0) {
            throw new \Exception('Tidak dapat menghapus perusahaan yang masih memiliki driver');
        }

        $company->delete();
    }
}

