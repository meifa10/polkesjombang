@extends('layout.app')

@section('content')
<style>
    .login-page {
        min-height: 80vh;
        background: linear-gradient(135deg, #4ae27dff, #5cc1ff);
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Segoe UI', sans-serif;
    }

    .login-card {
        background: #fff;
        width: 100%;
        max-width: 380px;
        border-radius: 18px;
        padding: 35px 30px;
        box-shadow: 0 15px 40px rgba(0,0,0,.15);
        text-align: center;
    }

    .login-icon {
        width: 70px;
        height: 70px;
        background: #e8f1ff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: auto;
        margin-bottom: 15px;
    }

    .login-icon i {
        font-size: 30px;
        color: #0d6e39ff;
    }

    .form-group {
        text-align: left;
        margin-bottom: 15px;
    }

    .form-control {
        width: 100%;
        padding: 10px 12px;
        border-radius: 8px;
        border: 1px solid #ccc;
    }

    .password-wrapper {
        position: relative;
    }

    .password-wrapper i {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #888;
    }

    .btn-login {
        width: 100%;
        padding: 11px;
        border-radius: 10px;
        background: #086628ff;
        color: #fff;
        border: none;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-login:hover {
        background: #1fc175ff;
    }

    .error {
        background: #ffecec;
        color: #d63031;
        padding: 10px;
        border-radius: 8px;
        font-size: 13px;
        margin-bottom: 15px;
    }
</style>

<div class="login-page">
    <div class="login-card">

        <div class="login-icon">
            <i class="fas fa-user-shield"></i>
        </div>

        <h3>Login Admin</h3>
        <p>Masuk ke sistem administrasi Polkes</p>

        @if ($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('admin.login') }}">
            @csrf

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <div class="password-wrapper">
                    <input type="password" name="password" id="password" class="form-control" required>
                    <i class="fas fa-eye" onclick="togglePassword(this)"></i>
                </div>
            </div>

            <button class="btn-login">Login</button>
        </form>

    </div>
</div>

<script>
function togglePassword(icon) {
    const input = document.getElementById('password');
    if (input.type === "password") {
        input.type = "text";
        icon.classList.replace("fa-eye", "fa-eye-slash");
    } else {
        input.type = "password";
        icon.classList.replace("fa-eye-slash", "fa-eye");
    }
}
</script>
@endsection
