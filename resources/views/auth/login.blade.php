<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - Resep Kita</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; }
        :root { --sage: #7a8f5c; --sage-light: #e4ebd8; }
        body {
            margin: 0;
            min-height: 100vh;
            color: #242424;
            background: #fffaf0;
            font-family: Arial, Helvetica, sans-serif;
        }
        .login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            overflow: hidden;
            position: relative;
            background: #fffaf0;
        }
        .login-form-wrap {
            width: min(45%, 520px);
            margin-left: max(5vw, 30px);
            padding: 48px clamp(28px, 5vw, 70px);
            position: relative;
            z-index: 1;
            border-radius: 18px;
            background: #fffdf8;
            box-shadow: 0 12px 30px rgba(90, 55, 20, 0.1);
        }
        .brand-dot {
            position: absolute;
            top: 18px;
            left: 18px;
            width: 13px;
            height: 13px;
            border-radius: 50%;
            background: #7a8f5c;
            box-shadow: 0 0 0 3px #e4ebd8;
        }
        .login-form-wrap h1 {
            margin: 0 0 8px;
            font-size: 24px;
            font-weight: 700;
            color: #65231d;
        }
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-bottom: 24px;
            color: #7a8f5c;
            font-size: 11px;
            font-weight: 700;
            text-decoration: none;
        }
        .back-link:hover { color: var(--sage); }
        .login-form-wrap .intro {
            margin: 0 0 24px;
            color: #6c5748;
            font-size: 10px;
        }
        .intro a, .login-options a {
            color: #7a8f5c;
            font-weight: 700;
            text-decoration: none;
        }
        .login-field {
            width: 100%;
            height: 40px;
            margin-bottom: 12px;
            padding: 0 16px;
            border: 0;
            border-radius: 2px;
            outline: 0;
            background: #fff;
            color: #30251f;
            font-size: 11px;
            border: 1px solid #eee4d4;
        }
        .password-wrap { position: relative; }
        .password-wrap .login-field { padding-right: 42px; }
        .password-toggle {
            position: absolute;
            top: 10px;
            right: 12px;
            border: 0;
            padding: 0;
            background: transparent;
            color: #7a8f5c;
            cursor: pointer;
            font-size: 15px;
        }
        .login-field:focus { box-shadow: 0 0 0 2px var(--sage-light); border-color: var(--sage); }
        .login-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 2px 0 15px;
            color: #6c5748;
            font-size: 9px;
        }
        .remember {
            display: flex;
            gap: 5px;
            align-items: center;
        }
        .remember input { accent-        color: var(--sage); }
        .login-button, .google-button {
            width: 100%;
            height: 40px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 700;
        }
        .login-button {
            border: 0;
            color: #fff;
            background: #7a8f5c;
            box-shadow: 0 5px 12px rgba(163, 50, 41, 0.2);
            cursor: pointer;
        }
        .login-button:hover { background: #7a8f5c; }
        .google-button {
            margin-top: 8px;
            border: 1px solid #eee4d4;
            background: #fffdf8;
            color: #30251f;
        }
        .google-button:disabled { cursor: not-allowed; }
        .google-icon { color: #4285f4; font-size: 15px; margin-right: 5px; }
        .auth-error {
            margin: -7px 0 8px;
            color: #dc2626;
            font-size: 10px;
        }
        .login-art {
            position: absolute;
            top: 50%;
            right: -170px;
            width: 700px;
            height: 700px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #f4c95d;
            transform: translateY(-50%);
        }
        .login-plate {
            width: 540px;
            height: 540px;
            padding: 25px;
            border-radius: 50%;
            background: #fffdf8;
        }
        .login-plate img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }
        @media (max-width: 900px) {
            .login-form-wrap { width: min(92%, 500px); margin: auto; padding: 42px 28px; }
            .login-art { opacity: .16; right: -360px; z-index: 0; }
        }
    </style>
</head>
<body>
<main class="login-page">
    <span class="brand-dot" aria-hidden="true"></span>
    <section class="login-form-wrap">
        <a class="back-link" href="{{ url('/') }}">← Kembali</a>
        <h1>Masuk</h1>
        <p class="intro">Belum punya akun? <a href="{{ route('register') }}">Daftar</a></p>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <input class="login-field" type="email" name="email" value="{{ old('email') }}"
                required autofocus autocomplete="username" placeholder="Alamat email">
            @error('email')
                <p class="auth-error">{{ $message }}</p>
            @enderror

            <div class="password-wrap">
                <input class="login-field" id="login-password" type="password" name="password"
                    required autocomplete="current-password" placeholder="Kata sandi">
                <button class="password-toggle" type="button" data-password-target="login-password"
                    aria-label="Tampilkan password" aria-pressed="false">◉</button>
            </div>
            @error('password')
                <p class="auth-error">{{ $message }}</p>
            @enderror

            <div class="login-options">
                <label class="remember">
                    <input type="checkbox" name="remember">
                    <span>Ingat saya</span>
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">Lupa kata sandi?</a>
                @endif
            </div>

            <button class="login-button" type="submit">Masuk</button>
            <button class="google-button" type="button" disabled>
                <span class="google-icon">G</span> Masuk dengan Google
            </button>
        </form>
    </section>

    <aside class="login-art" aria-label="Hidangan pasta">
        <div class="login-plate">
            <img src="images/ayam.png" alt="Hidangan ayam">
        </div>
    </aside>
</main>
<script>
    document.querySelectorAll('[data-password-target]').forEach(function (button) {
        button.addEventListener('click', function () {
            const input = document.getElementById(button.dataset.passwordTarget);
            const visible = input.type === 'password';
            input.type = visible ? 'text' : 'password';
            button.textContent = visible ? '◉' : '◌';
            button.setAttribute('aria-label', visible ? 'Sembunyikan password' : 'Tampilkan password');
            button.setAttribute('aria-pressed', String(visible));
        });
    });
</script>
</body>
</html>
