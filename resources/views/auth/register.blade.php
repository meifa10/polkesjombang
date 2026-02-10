@extends('layout.app')

@section('content')
<style>
    .register-wrapper {
        min-height: 70vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background: #f4f7fb;
    }

    .register-card {
        background: #fff;
        padding: 32px;
        width: 100%;
        max-width: 420px;
        border-radius: 14px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    }

    .register-card h2 {
        text-align: center;
        margin-bottom: 6px;
        font-weight: 600;
    }

    .register-card p {
        text-align: center;
        font-size: 14px;
        color: #777;
        margin-bottom: 25px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        font-size: 14px;
        font-weight: 500;
        display: block;
        margin-bottom: 6px;
    }

    .form-group input {
        width: 100%;
        padding: 11px 12px;
        border-radius: 8px;
        border: 1px solid #ddd;
        outline: none;
        transition: 0.2s;
    }

    .form-group input:focus {
        border-color: #4a90e2;
        box-shadow: 0 0 0 2px rgba(74,144,226,0.15);
    }

    .btn-register {
        width: 100%;
        padding: 12px;
        border: none;
        border-radius: 10px;
        background: linear-gradient(135deg, #4a90e2, #357ae8);
        color: #fff;
        font-weight: 600;
        cursor: pointer;
        margin-top: 10px;
    }

    .btn-register:hover {
        opacity: 0.95;
    }

    .register-footer {
        text-align: center;
        margin-top: 18px;
        font-size: 14px;
    }

    .register-footer a {
        color: #4a90e2;
        text-decoration: none;
        font-weight: 500;
    }

    .error-message {
        background: #ffe5e5;
        color: #c0392b;
        padding: 10px;
        border-radius: 8px;
        font-size: 14px;
        margin-bottom: 15px;
        text-align: center;
    }

        .password-wrapper {
        position: relative;
        width: 100%;
    }

    .password-wrapper input {
        width: 100%;
        padding: 11px 40px 11px 12px; /* ruang untuk ikon */
        border-radius: 8px;
        border: 1px solid #ddd;
        outline: none;
    }

    .toggle-password {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        font-size: 18px;
        color: #777;
        user-select: none;
    }

    .toggle-password:hover {
        color: #4a90e2;
    }

</style>

<div class="register-wrapper">
    <div class="register-card">

        <h2>Registrasi Pasien</h2>
        <p>Silakan daftar untuk menggunakan pendaftaran online</p>

        @if ($errors->any())
            <div class="error-message">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register.pasien') }}">
             @csrf
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="name" placeholder="Nama sesuai KTP" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="contoh@email.com" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <div class="password-wrapper">
                    <input 
                        type="password" 
                        name="password" 
                        id="register-password" 
                        placeholder="Minimal 8 karakter"
                        required
                    >
                    <span 
                        class="toggle-password" 
                        onclick="togglePassword('register-password', this)">
                        👁️
                    </span>
                </div>
            </div>

            <button type="submit" class="btn-register">
                Daftar
            </button>
        </form>

        <div class="register-footer">
            Sudah punya akun?
            <a href="{{ route('login') }}">Login</a>
        </div>

    </div>
</div>
@endsection
