@extends('layout.app')

@section('content')
<style>
    :root {
        --polkes-green: #059669;
        --polkes-dark: #064e3b;
        --polkes-light: #ecfdf5;
        --accent-lime: #10b981;
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
        position: relative;
        z-index: 1;
    }

    /* Dekorasi Lingkaran Aesthetic */
    .login-card::before {
        content: "";
        position: absolute;
        top: -40px;
        right: -40px;
        width: 120px;
        height: 120px;
        background: var(--polkes-light);
        border-radius: 50%;
        z-index: -1;
    }

    .login-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .brand-logo {
        font-size: 24px;
        font-weight: 800;
        color: var(--polkes-dark);
        letter-spacing: -1px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .brand-logo span {
        color: var(--polkes-green);
    }

    .login-header p {
        color: #6b7280;
        font-size: 14px;
        font-weight: 500;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-group label {
        display: block;
        font-size: 12px;
        font-weight: 700;
        color: var(--polkes-dark);
        text-transform: uppercase;
        margin-bottom: 8px;
        letter-spacing: 0.8px;
    }

    /* Input Wrapper untuk icon mata */
    .input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .form-control {
        width: 100%;
        padding: 14px 50px 14px 20px; /* Ruang ekstra di kanan untuk mata */
        background: white;
        border: 1.5px solid #e5e7eb;
        border-radius: 15px;
        font-size: 15px;
        color: #1f2937;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .form-control:focus {
        outline: none;
        border-color: var(--polkes-green);
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        transform: translateY(-2px);
    }

    /* Style Icon Mata */
    .toggle-password {
        position: absolute;
        right: 18px;
        cursor: pointer;
        color: #9ca3af;
        font-size: 18px;
        transition: all 0.3s ease;
        padding: 5px;
        user-select: none;
    }

    .toggle-password:hover {
        color: var(--polkes-green);
    }

    .btn-login {
        width: 100%;
        padding: 16px;
        background: linear-gradient(135deg, var(--polkes-green) 0%, var(--polkes-dark) 100%);
        color: white;
        border: none;
        border-radius: 15px;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 10px 15px -3px rgba(5, 150, 105, 0.3);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 25px -5px rgba(5, 150, 105, 0.4);
        filter: brightness(1.1);
    }

    .register-link {
        text-align: center;
        margin-top: 30px;
        font-size: 14px;
        color: #6b7280;
    }

    .register-link a {
        color: var(--polkes-green);
        font-weight: 700;
        text-decoration: none;
        padding-bottom: 2px;
        border-bottom: 2px solid transparent;
        transition: all 0.3s ease;
    }

    .register-link a:hover {
        border-bottom-color: var(--polkes-green);
    }
</style>

<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <div class="brand-logo">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L3 7V17L12 22L21 17V7L12 2Z" stroke="#059669" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 8V16" stroke="#059669" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M8 12H16" stroke="#059669" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                POLKES<span>JOMBANG</span>
            </div>
            <p>Portal Pelayanan Pasien Online</p>
        </div>

        <form method="POST" action="{{ route('login.process') }}">
            @csrf
            <div class="form-group">
                <label>Email Resmi</label>
                <input type="email" name="email" class="form-control" placeholder="pasien@email.com" required>
            </div>

            <div class="form-group">
                <label>Kata Sandi</label>
                <div class="input-wrapper">
                    <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
                    <span class="toggle-password" id="toggleEye">
                        <i class="fa-regular fa-eye"></i>
                    </span>
                </div>
            </div>

            <button type="submit" class="btn-login">Masuk Sekarang</button>
        </form>

        <div class="register-link">
            Belum memiliki akun? <a href="{{ route('register') }}">Buat Akun Baru</a>
        </div>
    </div>
</div>

<script>
    document.getElementById('toggleEye').addEventListener('click', function () {
        const passwordInput = document.getElementById('password');
        const icon = this.querySelector('i');
        
        // Toggle tipe input
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
        
        // Animasi efek feedback saat diklik
        this.style.transform = 'scale(0.9)';
        setTimeout(() => {
            this.style.transform = 'scale(1)';
        }, 100);
    });
</script>
@endsection