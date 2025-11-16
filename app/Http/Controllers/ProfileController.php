<?php

namespace App\Http\Controllers;

use App\Actions\Profile\GetUserProfileAction;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function showDetail()
    {
        $user = app(GetUserProfileAction::class)->execute();

        return Inertia::render('Profile/Show', [
            // Props expected by Profile/Show.vue
            'confirmsTwoFactorAuthentication' => false,
            'sessions' => [],
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
}
