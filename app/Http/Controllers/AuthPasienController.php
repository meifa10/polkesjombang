<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthPasienController extends Controller
{

    /**
     * ======================================================
     * HALAMAN LOGIN
     * ======================================================
     * Ketika halaman login dibuka, user akan dipaksa logout
     * agar setiap klik "UMUM & NON JKN" selalu login ulang.
     */
    public function index(Request $request)
    {
        // Jika ada session login sebelumnya, logout terlebih dahulu
        if (Auth::check()) {

            Auth::logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();
        }

        return view('pasien.auth.login');
    }


    /**
     * ======================================================
     * PROSES LOGIN PASIEN
     * ======================================================
     */
    public function login(Request $request)
    {

        // Validasi input login
        $credentials = $request->validate([
            'email' => ['required','email'],
            'password' => ['required']
        ]);


        /**
         * Attempt login
         */
        if (Auth::attempt($credentials)) {

            // Regenerate session untuk keamanan
            $request->session()->regenerate();

            // Redirect ke dashboard pasien
            return redirect()
                ->route('dashboard')
                ->with('success','Login berhasil. Selamat datang!');
        }


        /**
         * Jika login gagal
         */
        return back()
            ->withInput($request->only('email'))
            ->with('error','Email atau password salah.');
    }


    /**
     * ======================================================
     * LOGOUT PASIEN
     * ======================================================
     */
    public function logout(Request $request)
    {

        // Logout user
        Auth::logout();

        // Hapus session
        $request->session()->invalidate();

        // Regenerate token CSRF
        $request->session()->regenerateToken();

        // Redirect ke halaman utama
        return redirect('/')
            ->with('success','Anda berhasil logout.');
    }
}