@extends('layout.app')

@section('content')
<style>
    :root {
        --polkes-green: #059669;
        --polkes-dark: #064e3b;
        --polkes-light: #ecfdf5;
        --danger-red: #dc2626;
        --danger-bg: #fef2f2;
    }

    body {
        background: radial-gradient(circle at top left, #f0fdf4 0%, #dcfce7 100%);
        min-height: 100vh;
        font-family: 'Inter', sans-serif;
    }

    .login-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 90vh;
        padding: 20px;
    }

    .login-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(20px);
        width: 100%;
        max-width: 480px;
        padding: 50px;
        border-radius: 30px;
        box-shadow: 0 25px 50px -12px rgba(5, 150, 105, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.6);
    }

    /* Tampilan Alert Error seperti Admin */
    .alert-custom {
        display: flex;
        align-items: center;
        background-color: var(--danger-bg);
        border: 1px solid #fecaca;
        color: var(--danger-red);
        padding: 15px;
        border-radius: 12px;
        margin-bottom: 25px;
        gap: 15px;
    }

    .alert-icon {
        background: #fff;
        width: 35px;
        height: 35px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 4px rgba(220, 38, 38, 0.1);
    }

    .alert-content b {
        display: block;
        font-size: 14px;
        margin-bottom: 2px;
    }

    .alert-content span {
        font-size: 13px;
        opacity: 0.9;
    }

    .brand-logo {
        font-size: 24px;
        font-weight: 800;
        color: var(--polkes-dark);
        text-align: center;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .brand-logo span { color: var(--polkes-green); }

    .form-group { margin-bottom: 20px; }

    .form-group label {
        display: block;
        font-size: 12px;
        font-weight: 700;
        color: var(--polkes-dark);
        margin-bottom: 8px;
        text-transform: uppercase;
    }

    .input-wrapper { position: relative; }

    .form-control {
        width: 100%;
        padding: 14px 20px;
        border: 1.5px solid #e5e7eb;
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--polkes-green);
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
    }

    .toggle-password {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #9ca3af;
    }

    .btn-login {
        width: 100%;
        padding: 16px;
        background: linear-gradient(135deg, var(--polkes-green), var(--polkes-dark));
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        cursor: pointer;
        margin-top: 10px;
    }
</style>

<div class="login-container">
    <div class="login-card">
        <div class="brand-logo">
            <i class="fa-solid fa-shield-halved" style="color: var(--polkes-green)"></i>
            POLKES<span>JOMBANG</span>
        </div>
        <p style="text-align: center; color: #6b7280; margin-bottom: 30px;">Portal Pelayanan Pasien Online</p>

        @if(session('error'))
            <div class="alert-custom">
                <div class="alert-icon">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div class="alert-content">
                    <b>Login Gagal</b>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('login.process') }}">
            @csrf <div class="form-group">
                <label>Email Resmi</label>
                <input type="email" name="email" class="form-control" 
                       placeholder="pasien@email.com" 
                       value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label>Kata Sandi</label>
                <div class="input-wrapper">
                    <input type="password" name="password" id="password" 
                           class="form-control" placeholder="••••••••" required>
                    <span class="toggle-password" id="toggleEye">
                        <i class="fa-regular fa-eye"></i>
                    </span>
                </div>
            </div>

            <button type="submit" class="btn-login">Masuk Sekarang</button>
        </form>

        <div style="text-align: center; margin-top: 25px; font-size: 14px; color: #6b7280;">
            Belum memiliki akun? <a href="{{ route('register') }}" style="color: var(--polkes-green); font-weight: 700; text-decoration: none;">Buat Akun Baru</a>
        </div>
    </div>
</div>

<script>
    document.getElementById('toggleEye').addEventListener('click', function () {
        const passwordInput = document.getElementById('password');
        const icon = this.querySelector('i');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    });
</script>
@endsection