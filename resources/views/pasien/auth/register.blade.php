@extends('layout.app')

@section('content')
<style>
    :root {
        --polkes-green: #059669;
        --polkes-dark: #064e3b;
        --polkes-light: #f0fdf4;
        --soft-gray: #f9fafb;
        --error-red: #dc2626;
    }

    body {
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        min-height: 100vh;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .reg-container {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 60px 20px;
    }

    .reg-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(20px);
        width: 100%;
        max-width: 650px;
        border-radius: 32px;
        box-shadow: 0 25px 50px -12px rgba(5, 150, 105, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.7);
        padding: 50px;
        position: relative;
    }

    /* Menangani tampilan di HP agar tetap di tengah dan suportif */
    @media (max-width: 640px) {
        .reg-card {
            padding: 30px 20px;
            border-radius: 24px;
        }
        .reg-header h2 { font-size: 24px; }
    }

    .reg-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .reg-header .badge {
        background: var(--polkes-light);
        color: var(--polkes-green);
        padding: 8px 16px;
        border-radius: 100px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 15px;
        display: inline-block;
    }

    .reg-header h2 {
        font-size: 32px;
        font-weight: 800;
        color: var(--polkes-dark);
        margin-top: 10px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }

    @media (max-width: 600px) {
        .form-grid { grid-template-columns: 1fr; }
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
        padding-left: 5px;
    }

    .form-control {
        width: 100%;
        padding: 14px 18px;
        background: #ffffff;
        border: 2px solid #e5e7eb;
        border-radius: 16px;
        font-size: 15px;
        transition: all 0.3s ease;
    }

    /* Style untuk input yang error */
    .form-control.is-invalid {
        border-color: var(--error-red);
        background-color: #fef2f2;
    }

    .error-feedback {
        color: var(--error-red);
        font-size: 12px;
        margin-top: 6px;
        display: block;
        font-weight: 600;
        padding-left: 5px;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--polkes-green);
        box-shadow: 0 0 0 5px rgba(16, 185, 129, 0.1);
        background: #fdfdfd;
    }

    .input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .password-input {
        padding-right: 50px !important;
    }

    .toggle-password {
        position: absolute;
        right: 18px;
        cursor: pointer;
        color: #9ca3af;
        font-size: 18px;
        transition: all 0.3s ease;
        user-select: none;
    }

    .btn-register {
        width: 100%;
        padding: 16px;
        background: linear-gradient(135deg, var(--polkes-green) 0%, var(--polkes-dark) 100%);
        color: white;
        border: none;
        border-radius: 16px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        margin-top: 10px;
        box-shadow: 0 10px 20px -5px rgba(5, 150, 105, 0.3);
    }

    .btn-register:hover {
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 20px 30px -10px rgba(5, 150, 105, 0.4);
    }

    .login-footer {
        text-align: center;
        margin-top: 30px;
        color: #6b7280;
        font-size: 14px;
    }

    .login-footer a {
        color: var(--polkes-green);
        font-weight: 700;
        text-decoration: none;
    }

    .bg-circle {
        position: absolute;
        width: 200px;
        height: 200px;
        background: rgba(16, 185, 129, 0.05);
        border-radius: 50%;
        z-index: -1;
    }
</style>

<div class="reg-container">
    <div class="reg-card">
        <div class="bg-circle" style="top: -50px; left: -50px;"></div>
        
        <div class="reg-header">
            <span class="badge">Sistem Informasi Rekam Medis</span>
            <h2>Daftar Akun Baru</h2>
            <p style="color: #6b7280;">Lengkapi data diri sesuai kartu identitas Anda</p>
        </div>

        <form method="POST" action="{{ route('register.process') }}">
            @csrf
            
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Masukkan nama sesuai KTP" required class="form-control @error('name') is-invalid @enderror">
                @error('name') <span class="error-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Nomor NIK / KTP</label>
                    <input type="text" 
                           name="no_identitas" 
                           value="{{ old('no_identitas') }}"
                           placeholder="16 digit NIK" 
                           required 
                           maxlength="16"
                           minlength="16"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                           class="form-control @error('no_identitas') is-invalid @enderror">
                    @error('no_identitas') <span class="error-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required class="form-control @error('tanggal_lahir') is-invalid @enderror">
                    @error('tanggal_lahir') <span class="error-feedback">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-group">
                <label>Email Aktif</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="contoh: pasien@email.com" required class="form-control @error('email') is-invalid @enderror">
                @error('email') <span class="error-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Kata Sandi</label>
                    <div class="input-wrapper">
                        <input type="password" name="password" id="password" placeholder="Minimal 8 karakter" required class="form-control password-input @error('password') is-invalid @enderror">
                        <span class="toggle-password" onclick="togglePassword('password', this)">
                            <i class="fa-regular fa-eye"></i>
                        </span>
                    </div>
                    @error('password') <span class="error-feedback">{{ $message }}</span> @enderror
                </div>
                
                <div class="form-group">
                    <label>Konfirmasi Sandi</label>
                    <div class="input-wrapper">
                        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Ulangi sandi" required class="form-control password-input">
                        <span class="toggle-password" onclick="togglePassword('password_confirmation', this)">
                            <i class="fa-regular fa-eye"></i>
                        </span>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-register">
                BUAT AKUN SEKARANG
            </button>
        </form>

        <div class="login-footer">
            Sudah memiliki akun? <a href="{{ route('login') }}">Masuk ke Portal</a>
        </div>
    </div>
</div>

<script>
    // Fungsi Toggle Password
    function togglePassword(inputId, el) {
        const input = document.getElementById(inputId);
        const icon = el.querySelector('i');
        
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = "password";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // Feedback visual sederhana saat tombol submit diklik
    document.querySelector('form').onsubmit = function() {
        const btn = document.querySelector('.btn-register');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> MEMPROSES...';
        btn.style.opacity = '0.8';
    };
</script>
@endsection