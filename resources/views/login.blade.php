<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - SIPKOS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            min-height: 100vh;
            background: #edf2f7;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 32px;
            color: #13213c;
        }

        .auth-wrapper {
            width: 100%;
            max-width: 1220px;
            min-height: 640px;
            background: #fff;
            border-radius: 36px;
            overflow: hidden;
            display: grid;
            grid-template-columns: 1fr 1fr;
            box-shadow: 0 24px 60px rgba(15, 23, 42, .12);
        }

        .auth-left {
            background: linear-gradient(135deg, #2563eb, #22c8df);
            color: #fff;
            padding: 56px 48px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .brand h1 {
            font-size: 36px;
            font-weight: 800;
            letter-spacing: 1px;
            margin-bottom: 14px;
        }

        .brand p {
            font-size: 18px;
            line-height: 1.6;
            opacity: .95;
        }

        .hero-text h2 {
            font-size: 42px;
            line-height: 1.25;
            font-weight: 800;
            margin-bottom: 22px;
        }

        .hero-text p {
            font-size: 18px;
            line-height: 1.7;
            max-width: 520px;
            opacity: .95;
        }

        .auth-left .register-text {
            font-size: 15px;
        }

        .auth-left .register-text a {
            color: #fff;
            font-weight: 700;
        }

        .auth-right {
            padding: 62px 58px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-box {
            width: 100%;
            max-width: 500px;
        }

        .back-link {
            display: inline-block;
            text-decoration: none;
            color: #536b8d;
            font-weight: 500;
            margin-bottom: 26px;
        }

        .login-box h2 {
            font-size: 36px;
            font-weight: 800;
            margin-bottom: 8px;
            color: #13213c;
        }

        .subtitle {
            color: #5d6f91;
            font-size: 18px;
            line-height: 1.6;
            margin-bottom: 36px;
        }

        .alert {
            padding: 14px 16px;
            border-radius: 14px;
            margin-bottom: 18px;
            font-size: 14px;
            font-weight: 500;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            font-weight: 700;
            margin-bottom: 10px;
            color: #14233f;
        }

        .form-group input {
            width: 100%;
            height: 60px;
            border: 1px solid #d8e2f2;
            background: #eaf2ff;
            border-radius: 14px;
            padding: 0 18px;
            font-size: 15px;
            outline: none;
        }

        .form-group input:focus {
            border-color: #2563eb;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, .12);
        }

        .error-text {
            display: block;
            margin-top: 8px;
            font-size: 13px;
            color: #dc2626;
        }

        .btn-login {
            width: 100%;
            height: 62px;
            border: none;
            border-radius: 14px;
            background: #2563eb;
            color: #fff;
            font-size: 18px;
            font-weight: 800;
            cursor: pointer;
            margin-top: 6px;
            transition: .2s ease;
        }

        .btn-login:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
        }

        .bottom-text {
            text-align: center;
            margin-top: 28px;
            color: #5d6f91;
            font-size: 15px;
        }

        .bottom-text a {
            color: #2563eb;
            font-weight: 800;
            text-decoration: none;
        }

        @media (max-width: 900px) {
            body {
                padding: 20px;
            }

            .auth-wrapper {
                grid-template-columns: 1fr;
                max-width: 560px;
            }

            .auth-left {
                display: none;
            }

            .auth-right {
                padding: 42px 28px;
            }

            .login-box h2 {
                font-size: 30px;
            }
        }
    </style>
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-left">
        <div class="brand">
            <h1>SIPKOS</h1>
            <p>Masuk untuk mulai mencari kos impianmu.</p>
        </div>

        <div class="hero-text">
            <h2>Cari Kos dengan Mudah,<br>Cepat, dan Nyaman</h2>
            <p>
                Temukan kos berdasarkan lokasi, harga, fasilitas, maps terdekat,
                dan booking secara online.
            </p>
        </div>

        <div class="register-text">
            Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a>
        </div>
    </div>

    <div class="auth-right">
        <div class="login-box">
            <a href="{{ route('landing') }}" class="back-link">← Kembali</a>

            <h2>Login SIPKOS</h2>
            <p class="subtitle">
                Masuk menggunakan akun yang sudah terdaftar.
            </p>

            @if (session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Masukkan email"
                        required
                    >
                    @error('email')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan password"
                        required
                    >
                    @error('password')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn-login">
                    Masuk
                </button>
            </form>

            <div class="bottom-text">
                Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>