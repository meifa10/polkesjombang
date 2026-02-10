@extends('layout.app')

@section('content')
<style>
    .login-wrapper {
        min-height: 70vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background: #f4f7fb;
    }

    .login-card {
        background: #fff;
        padding: 30px;
        width: 100%;
        max-width: 380px;
        border-radius: 14px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    }

    .login-card h2 {
        text-align: center;
        margin-bottom: 5px;
        font-weight: 600;
    }

    .login-card p {
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
        margin-bottom: 5px;
    }

    .form-group input {
        width: 100%;
        padding: 10px 12px;
        border-radius: 8px;
        border: 1px solid #ddd;
        outline: none;
        transition: 0.2s;
    }

    .form-group input:focus {
        border-color: #4a90e2;
        box-shadow: 0 0 0 2px rgba(74,144,226,0.15);
    }

    .btn-login {
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

    .btn-login:hover {
        opacity: 0.95;
    }

    .login-footer {
        text-align: center;
        margin-top: 18px;
        font-size: 14px;
    }

    .login-footer a {
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
        padding: 11px 40px 11px 12px; /* kanan dikasih ruang */
        border-radius: 8px;
        border: 1px solid #ddd;
        outline: none;
    }

    .toggle-password {
        position: absolute;
        top: 50%;
        right: 12px;
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

<div class="login-wrapper">
    <div class="login-card">

        <h2>Login Pasien</h2>
        <p>Silakan login untuk pendaftaran online</p>

        @if ($errors->any())
            <div class="error-message">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

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
                        id="login-password" 
                        placeholder="••••••••" 
                        required
                    >
                    <span class="toggle-password" onclick="togglePassword('login-password', this)">
                        👁️
                    </span>
                </div>
            </div>

            <button type="submit" class="btn-login">
                Login
            </button>
        </form>

        <div class="login-footer">
            Belum punya akun?
            <a href="{{ route('register.pasien') }}">Daftar</a>
        </div>

    </div>
</div>
@endsection
