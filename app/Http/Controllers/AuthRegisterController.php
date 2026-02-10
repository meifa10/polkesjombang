<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthRegisterController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        // simpan ke users
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'pasien',
        ]);

        // simpan ke patients
        Patient::create([
            'user_id'        => $user->id,
            'nik'            => '-',
            'tanggal_lahir'  => now(),
            'jenis_kelamin'  => 'L',
            'alamat'         => '-',
            'no_hp'          => '-',
        ]);

        Auth::login($user);
        // pastikan session benar-benar aktif
        $request->session()->regenerate();
        return redirect()->route('pendaftaran.online');

    }
}
