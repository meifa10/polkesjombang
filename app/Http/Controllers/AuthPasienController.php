<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthPasienController extends Controller
{
    /**
     * Tampilkan form login pasien
     */
    public function showLogin(Request $request)
    {
        return view('auth.login');
    }

    /**
     * Proses login pasien
     */
    public function login(Request $request)
    {
        // validasi input
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // proses login
        if (Auth::attempt($credentials)) {

            // regenerasi session (WAJIB Laravel 12)
            $request->session()->regenerate();

            // pastikan role adalah pasien
            if (auth()->user()->role !== 'pasien') {
                Auth::logout();

                return back()->withErrors([
                    'email' => 'Akun ini bukan akun pasien.',
                ]);
            }

            /**
             * ============================
             * LOGIKA REDIRECT CERDAS
             * ============================
             * - Jika login karena klik "Pendaftaran Poliklinik"
             *   → kembali ke form pendaftaran poliklinik
             * - Jika login biasa
             *   → dashboard pendaftaran online
             */
            $redirectTo = session()->pull('redirect_to');

            if ($redirectTo === 'pendaftaran-poliklinik') {
                return redirect()->route('pendaftaran.poliklinik');
            }

            return redirect()->route('pendaftaran.online');
        }

        // login gagal
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    /**
     * Logout pasien
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
