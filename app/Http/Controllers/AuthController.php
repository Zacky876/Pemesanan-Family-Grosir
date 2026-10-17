<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller {
   
    //halaman login
    public function showLogin() {
        return view('auth.login');
    }

    // proses login
    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()
                    ->withErrors([
                        'email' => 'Akun Anda sudah dinonaktifkan.',
                    ])
                    ->onlyInput('email');
            }

            return match ($user->role) {
                'admin' => redirect()->route('admin.dashboard'),
                'kasir' => redirect()->route('kasir.dashboard'),
                'user' => redirect()->route('user.dashboard'),
                default => abort(403, 'Role tidak valid.')
            };
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // Logout
    public function logout(Request $request) {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
