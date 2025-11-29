<?php

namespace App\Http\Controllers;

use App\Actions\Assessment\CheckDriverPassedAction;
use App\Actions\Profile\GetUserProfileAction;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function showDetail()
    {
        /** @var User $user */
        $user = app(GetUserProfileAction::class)->execute();

        // Load driver data if user is a driver
        $driver = null;
        $companies = [];
        $isAssessmentPassed = false;

        // Check if user has driver role using Spatie Permission
        if ($user->roles()->where('name', 'driver')->exists()) {
            $driver = $user->drivers()->with('company')->first();
            if ($driver) {
                $companies = \App\Models\Company::where('status', 'active')->get();

                // Check if driver has passed assessment
                $passedCheck = app(CheckDriverPassedAction::class)->execute();
                $isAssessmentPassed = $passedCheck['is_passed'];
            }
        }

        return Inertia::render('Profile/Show', [
            // Props expected by Profile/Show.vue
            'confirmsTwoFactorAuthentication' => false,
            'sessions' => [],
            'driver' => $driver ? (new \App\Http\Resources\DriverResource($driver))->resolve() : null,
            'companies' => $companies,
            'isAssessmentPassed' => $isAssessmentPassed,
        ]);
    }

    public function edit(Request $request)
    {
        $user = app(GetUserProfileAction::class)->execute();

        return Inertia::render('Profile/Edit', [
            'user' => (new UserResource($user))->resolve(),
        ]);
    }

    public function apiShow(Request $request)
    {
        $user = app(GetUserProfileAction::class)->execute();

        return response()->json([
            'success' => true,
            'data' => (new UserResource($user))->resolve(),
        ]);
    }

    /**
     * Update driver biodata from profile page.
     */
    public function updateDriverBiodata(\App\Http\Requests\Driver\UpdateDriverBiodataRequest $request)
    {
        /** @var User $user */
        $user = app(GetUserProfileAction::class)->execute();

        // Check if user has driver role using Spatie Permission
        if (!$user->roles()->where('name', 'driver')->exists()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengubah biodata driver.');
        }

        $driver = $user->drivers()->first();

        if (!$driver) {
            return redirect()->back()->with('error', 'Data driver tidak ditemukan.');
        }

        // Check if driver has passed assessment - if yes, prevent editing
        $passedCheck = app(CheckDriverPassedAction::class)->execute();
        if ($passedCheck['is_passed']) {
            return redirect()->back()->with('error', 'Biodata tidak dapat diubah karena Anda telah lulus assessment. Silakan hubungi administrator jika ada perubahan data.');
        }

        try {
            $data = $request->validated();
            // Don't allow status change from profile page
            $data['status'] = $driver->status;

            app(\App\Actions\Driver\UpdateDriverAction::class)->execute($driver, $request, $data);

            return redirect()->back()->with('success', 'Biodata driver berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }
}
