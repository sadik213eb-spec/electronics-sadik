@extends('layouts.app')

@section('content')

    <style>
        .auth-wrapper {
            max-width: 480px;
            margin: 60px auto;
            padding: 0 20px;
            font-family: 'Poppins', sans-serif;
        }

        .auth-card {
            background: #fff;
            border-radius: 16px;
            padding: 40px;
            border: 1px solid #dddddd;
        }

        .auth-title {
            font-size: 1.6rem;
            font-weight: 700;
            color: #2a2a2a;
            font-family: 'Montserrat', sans-serif;
            margin-bottom: 6px;
            text-align: center;
        }

        .auth-subtitle {
            font-size: 0.9rem;
            color: #6b7280;
            text-align: center;
            margin-bottom: 28px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            font-size: 0.9rem;
            font-weight: 500;
            color: #2a2a2a;
            display: block;
            margin-bottom: 6px;
        }

        .form-input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #dddddd;
            border-radius: 8px;
            font-size: 0.95rem;
            color: #2a2a2a;
            font-family: 'Poppins', sans-serif;
            outline: none;
            transition: border-color 0.2s;
            background: #fff;
        }

        .form-input:focus {
            border-color: #d97706;
        }

        .error-msg {
            font-size: 0.8rem;
            color: #dc2626;
            margin-top: 4px;
            display: block;
        }

        .remember-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .remember-label {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
            color: #6b7280;
            cursor: pointer;
        }

        .forgot-link {
            font-size: 0.85rem;
            color: #d97706;
            text-decoration: none;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .submit-btn {
            width: 100%;
            padding: 14px;
            background: #d97706;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            font-family: 'Montserrat', sans-serif;
            transition: background 0.2s;
            margin-bottom: 16px;
        }

        .submit-btn:hover {
            background: #b45309;
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .divider-line {
            flex: 1;
            border-top: 1px solid #dddddd;
        }

        .divider-text {
            font-size: 0.82rem;
            color: #9ca3af;
        }

        .auth-link {
            text-align: center;
            font-size: 0.9rem;
            color: #6b7280;
        }

        .auth-link a {
            color: #d97706;
            font-weight: 600;
            text-decoration: none;
        }

        .auth-link a:hover {
            text-decoration: underline;
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 20px;
            color: #dc2626;
            font-size: 0.88rem;
        }
    </style>

    <div class="auth-wrapper">
        <div class="auth-card">
            <h1 class="auth-title">Welcome Back!</h1>
            <p class="auth-subtitle">Login to your account to continue</p>

            {{-- @if (str_contains(request()->session()->get('url.intended', ''), 'checkout'))
                <div
                    style="background:#fffbf0; border:1px solid #d97706; border-radius:8px; padding:12px 16px; margin-bottom:20px; color:#d97706; font-size:0.88rem; text-align:center; font-weight:500;">
                    🛒 Please login to continue to checkout
                </div>s
            @endif --}}

            @if ($errors->any())
                <div class="alert-error">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="/login" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-input" value="{{ old('email') }}"
                        placeholder="Enter your email" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-input" placeholder="Enter your password" required>
                </div>

                <div class="remember-row">
                    <label class="remember-label">
                        <input type="checkbox" name="remember">
                        Remember me
                    </label>
                    <a href="#" class="forgot-link">Forgot password?</a>
                </div>

                <button type="submit" class="submit-btn">Login</button>

                <div class="divider">
                    <div class="divider-line"></div>
                    <span class="divider-text">Don't have an account?</span>
                    <div class="divider-line"></div>
                </div>

                <p class="auth-link">
                    <a href="/register">Create New Account</a>
                </p>
            </form>
        </div>
    </div>

@endsection
