<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // 1. Tampilkan form login admin
    public function showLogin()
    {
        return view('admin.login');
    }

    // 2. Proses login admin
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            // pastikan role admin
            if (auth()->user()->role !== 'admin') {
                Auth::logout();

                return back()->withErrors([
                    'email' => 'Akun ini bukan admin.',
                ]);
            }

            return redirect('/admin/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    // 3. Logout admin
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }
}
