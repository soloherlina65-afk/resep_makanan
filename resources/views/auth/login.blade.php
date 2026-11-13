<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - Racikara</title>
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
            justify-content: center;
            overflow: hidden;
            position: relative;
            background: #fffaf0;
        }
        .login-form-wrap {
            width: 100%;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 44px 24px;
            position: relative;
            z-index: 1;
            background: #f4f8ef;
        }
        .login-form-wrap form,
        .login-form-wrap .intro {
            width: min(100%, 460px);
            margin-right: auto;
            margin-left: auto;
        }
        .auth-logo {
            display: block;
            width: 78px;
            height: 78px;
            margin: 0 auto 18px;
            border-radius: 50%;
            object-fit: cover;
            object-position: center;
        }
        .login-form-wrap h1 {
            margin: 0 0 8px;
            text-align: center;
            font-size: 27px;
            font-weight: 700;
            color: #30402a;
        }
        .back-link {
            position: absolute;
            top: 24px;
            left: 28px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            color: #7a8f5c;
            font-size: 11px;
            font-weight: 700;
            text-decoration: none;
        }
        .back-link:hover { color: var(--sage); }
        .login-form-wrap .intro {
            margin: 0 0 22px;
            text-align: center;
            color: #6c5748;
            font-size: 10px;
            margin-right: auto;
            margin-left: auto;
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
            border-radius: 9px;
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
            border-radius: 9px;
            font-size: 10px;
            font-weight: 700;
        }
        .login-button {
            border: 0;
            color: #fff;
            background: #7a8f5c;
            box-shadow: 0 5px 12px rgba(95, 116, 70, 0.2);
            cursor: pointer;
        }
        .login-button:hover { background: #7a8f5c; }
        .google-button {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 8px;
            border: 1px solid #eee4d4;
            background: #fffdf8;
            color: #30251f;
            text-decoration: none;
        }
        .google-button:hover { border-color: #c9b9aa; background: #fff; }
        .google-icon { width: 18px; height: 18px; margin-right: 7px; }
        .auth-error {
            margin: -7px 0 8px;
            color: #dc2626;
            font-size: 10px;
        }
        @media (max-width: 900px) {
            .login-form-wrap { padding-right: 20px; padding-left: 20px; }
        }
    </style>
</head>
<body>
<main class="login-page">
    <section class="login-form-wrap">
        <img class="auth-logo" src="{{ asset('images/racikara-logo.png') }}" alt="Racikara">
        <a class="back-link" href="{{ url('/') }}">← Kembali</a>
        <h1>Masuk</h1>
        <p class="intro">Belum punya akun? <a href="{{ route('register') }}">Daftar</a></p>

        <x-auth-session-status class="mb-4" :status="session('status')" />
        @error('google')
            <p class="auth-error">{{ $message }}</p>
        @enderror

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
            <a class="google-button" href="{{ route('google.redirect') }}">
                <svg class="google-icon" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill="#4285F4" d="M21.35 12.27c0-.72-.06-1.42-.18-2.09H12v3.96h5.24a4.48 4.48 0 0 1-1.94 2.94v2.45h3.14c1.84-1.69 2.91-4.18 2.91-7.26Z"/>
                    <path fill="#34A853" d="M12 21.75c2.63 0 4.84-.87 6.45-2.36l-3.14-2.45c-.87.58-1.98.92-3.31.92-2.55 0-4.71-1.72-5.49-4.03H3.27v2.53A9.74 9.74 0 0 0 12 21.75Z"/>
                    <path fill="#FBBC05" d="M6.51 13.83a5.85 5.85 0 0 1 0-3.66V7.64H3.27a9.75 9.75 0 0 0 0 8.72l3.24-2.53Z"/>
                    <path fill="#EA4335" d="M12 6.14c1.43 0 2.72.49 3.74 1.46l2.8-2.8C16.84 3.23 14.63 2.25 12 2.25a9.74 9.74 0 0 0-8.73 5.39l3.24 2.53C7.29 7.86 9.45 6.14 12 6.14Z"/>
                </svg>
                Masuk dengan Google
            </a>
        </form>
    </section>

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
