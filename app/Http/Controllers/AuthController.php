<?php

namespace App\Http\Controllers;

use App\Actions\Auth\LoginAction;
use App\Actions\Auth\LogoutAction;
use App\Http\Requests\Auth\LoginRequest;
use Inertia\Inertia;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login()
    {
        return Inertia::render('Admin/Auth/Login');
    }

    public function auth(LoginRequest $request)
    {
        try {
            app(LoginAction::class)->execute($request);

            return redirect()->intended('/dashboard')->with('success', 'Login berhasil');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        app(LogoutAction::class)->execute($request);

        return redirect()->route('login');
    }
}
