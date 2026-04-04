<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthRegisterController extends Controller
{
    public function index()
    {
        return view('pasien.auth.register');
    }

    public function register(Request $request)
    {
        // 1. Validasi Data dengan sangat ketat
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'no_identitas' => 'required|numeric|digits:16|unique:users,no_identitas',
            'tanggal_lahir' => 'required|date',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed', // 'confirmed' mencari input bernama password_confirmation
        ], [
            // Pesan Error Kustom (Bahasa Indonesia)
            'name.required' => 'Nama lengkap wajib diisi.',
            'no_identitas.required' => 'Nomor NIK wajib diisi.',
            'no_identitas.numeric' => 'NIK hanya boleh berisi angka.',
            'no_identitas.digits' => 'Nomor NIK harus tepat 16 digit.',
            'no_identitas.unique' => 'Nomor NIK ini sudah terdaftar.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah digunakan.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        // 2. Simpan ke Database
        User::create([
            'name' => $data['name'],
            'no_identitas' => $data['no_identitas'],
            'tanggal_lahir' => $data['tanggal_lahir'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            // Pastikan role diset otomatis jika Anda menggunakan sistem role
            'role' => 'pasien', 
        ]);

        // 3. Redirect dengan pesan sukses
        return redirect()->route('login')
            ->with('success', 'Registrasi berhasil! Silakan masuk ke akun Anda.');
    }
}