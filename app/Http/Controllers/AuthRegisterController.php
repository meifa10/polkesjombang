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
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'no_identitas' => 'required|numeric|digits:16|unique:users,no_identitas',
            'tanggal_lahir' => 'required|date',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed', 
        ], [
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

        User::create([
            'name' => $data['name'],
            'no_identitas' => $data['no_identitas'],
            'tanggal_lahir' => $data['tanggal_lahir'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'pasien', 
        ]);

        return redirect()->route('login')
            ->with('success', 'Registrasi berhasil! Silakan masuk ke akun Anda.');
    }
}