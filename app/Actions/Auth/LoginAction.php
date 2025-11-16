<?php

namespace App\Actions\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginAction
{
    /**
     * Attempt to login user.
     *
     * @throws \Exception
     */
    public function execute(Request $request): void
    {
        $userField = filter_var($request->input('user'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $userField => $request->input('user'),
            'password' => $request->input('password')
        ];

        $remember = $request->boolean('remember');

        if (!Auth::attempt($credentials, $remember)) {
            throw new \Exception('Username atau Password salah');
        }

        $user = Auth::user();

        // Check if user status is active
        if ($user->status === 'inactive') {
            Auth::logout();
            throw new \Exception('Akun Anda tidak aktif. Silakan hubungi administrator.');
        }

        if ($user->status === 'pending') {
            Auth::logout();
            throw new \Exception('Akun Anda masih menunggu verifikasi. Silakan hubungi administrator.');
        }

        $request->session()->regenerate();
    }
}

