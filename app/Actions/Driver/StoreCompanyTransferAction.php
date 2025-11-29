<?php

namespace App\Actions\Driver;

use App\Models\DriverCompanyTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StoreCompanyTransferAction
{
    /**
     * Store a new company transfer request.
     */
    public function execute(Request $request): DriverCompanyTransfer
    {
        $user = Auth::user();
        $driver = $user->drivers()->first();

        if (!$driver) {
            throw new \Exception('Data driver tidak ditemukan.');
        }

        $oldCompanyId = $driver->company_id;
        $newCompanyId = $request->input('new_company_id');

        if ($oldCompanyId == $newCompanyId) {
            throw new \Exception('Perusahaan baru harus berbeda dengan perusahaan lama.');
        }

        // Handle file upload
        $suratPengunduranDiriPath = null;

        if ($request->hasFile('surat_pengunduran_diri')) {
            $file = $request->file('surat_pengunduran_diri');
            $suratPengunduranDiriPath = $file->store('company-transfers/surat-pengunduran-diri', 'public');
        }

        // Create transfer request with status 'waiting_quiz' - driver needs to take quiz first
        $transfer = DriverCompanyTransfer::create([
            'driver_id' => $driver->id,
            'old_company_id' => $oldCompanyId,
            'new_company_id' => $newCompanyId,
            'surat_pengunduran_diri' => $suratPengunduranDiriPath,
            'screenshot_quiz' => null, // Will be uploaded after quiz
            'notes' => $request->input('notes'),
            'status' => 'waiting_quiz', // Status: waiting_quiz -> pending (after screenshot uploaded)
        ]);

        return $transfer;
    }
}

