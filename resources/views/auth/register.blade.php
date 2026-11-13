<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat akun - Racikara</title>
    <style>
        * { box-sizing: border-box; }
        :root { --sage: #7a8f5c; --sage-light: #e4ebd8; }
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f4f8ef;
            color: #542313;
            font-family: Figtree, Arial, Helvetica, sans-serif;
        }
        .signup-card {
            width: 100%;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            background: #f4f8ef;
        }
        .signup-form-side {
            position: relative;
            width: min(100% - 48px, 460px);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 52px 0;
        }
        .signup-form-side form { width: 100%; }
        .auth-logo {
            display: block;
            width: 78px;
            height: 78px;
            margin: 0 auto 18px;
            border-radius: 50%;
            object-fit: cover;
            object-position: center;
        }
        .signup-title {
            margin: 0;
            text-align: center;
            color: #4d1f0f;
            font-size: 31px;
            font-weight: 850;
        }
        .back-link {
            position: fixed;
            top: 24px;
            left: 28px;
            color: #5f7446;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
        }
        .back-link:hover { color: var(--sage); }
        .signup-subtitle {
            margin: 9px 0 28px;
            color: #806b61;
            text-align: center;
            font-size: 12px;
        }
        .form-group { margin-bottom: 15px; }
        .form-group label {
            display: block;
            margin-bottom: 7px;
            color: #684b3e;
            font-size: 11px;
            font-weight: 700;
        }
        .form-group label span { color: #5f7446; }
        .signup-input, .signup-select {
            width: 100%;
            height: 43px;
            padding: 0 16px;
            border: 1px solid #f0dfd2;
            border-radius: 9px;
            outline: 0;
            background: #fff;
            color: #542313;
            font-size: 12px;
        }
        .signup-input::placeholder { color: #b19d92; }
        .signup-input:focus, .signup-select:focus {
            border-color: var(--sage);
            box-shadow: 0 0 0 3px rgba(122, 143, 92, .16);
        }
        .password-wrap { position: relative; }
        .password-wrap .signup-input { padding-right: 42px; }
        .password-toggle {
            position: absolute;
            top: 10px;
            right: 13px;
            border: 0;
            padding: 0;
            background: transparent;
            color: #5f7446;
            cursor: pointer;
            font-size: 15px;
        }
        .signup-btn {
            width: 100%;
            height: 44px;
            margin-top: 8px;
            border: 0;
            border-radius: 9px;
            color: #fff;
            background: #7a8f5c;
            box-shadow: 0 7px 15px rgba(233, 104, 19, .2);
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
        }
        .signup-btn:hover { background: #5f7446; }
        .auth-error { margin-top: 5px; color: #c83d2d; font-size: 11px; }
        .login-redirect {
            margin-top: 25px;
            color: #806b61;
            text-align: center;
            font-size: 11px;
        }
        .login-redirect a { color: var(--sage); font-weight: 800; text-decoration: none; }
        .login-redirect a:hover { color: #5f7446; }
        @media (max-width: 700px) {
            .signup-form-side { width: min(100% - 40px, 460px); padding: 42px 0; }
            .back-link { top: 20px; left: 20px; }
        }
    </style>
</head>
<body>
<main class="signup-card">
    <section class="signup-form-side">
        <img class="auth-logo" src="{{ asset('images/racikara-logo.png') }}" alt="Racikara">
        <a class="back-link" href="{{ url('/') }}">← Kembali</a>
        <h1 class="signup-title">Buat akun</h1>
        <p class="signup-subtitle">Daftar dengan email dan kata sandi yang valid.</p>

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <label>Nama <span>*</span></label>
                <input class="signup-input" type="text" name="name" value="{{ old('name') }}"
                    required autofocus placeholder="Nama pengguna">
                @error('name') <div class="auth-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Email <span>*</span></label>
                <input class="signup-input" type="email" name="email" value="{{ old('email') }}"
                    required placeholder="Email">
                @error('email') <div class="auth-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Kata sandi <span>*</span></label>
                <div class="password-wrap">
                    <input class="signup-input" id="register-password" type="password" name="password" required placeholder="Kata sandi">
                    <button class="password-toggle" type="button" data-password-target="register-password"
                        aria-label="Tampilkan password" aria-pressed="false">◉</button>
                </div>
                @error('password') <div class="auth-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Konfirmasi kata sandi <span>*</span></label>
                <div class="password-wrap">
                    <input class="signup-input" id="register-password-confirmation" type="password" name="password_confirmation"
                        required placeholder="Ulangi kata sandi">
                    <button class="password-toggle" type="button" data-password-target="register-password-confirmation"
                        aria-label="Tampilkan password" aria-pressed="false">◉</button>
                </div>
            </div>
            <div class="form-group">
                <label>Kota</label>
                <input class="signup-input" type="text" name="city" value="{{ old('city') }}" placeholder="Kota">
            </div>
            <div class="form-group">
                <label>Jenis kelamin</label>
                <select class="signup-select" name="gender">
                    <option value="">Pilih jenis kelamin</option>
                    <option value="male">Laki-laki</option>
                    <option value="female">Perempuan</option>
                </select>
            </div>
            <button class="signup-btn" type="submit">Daftar</button>
        </form>

        <p class="login-redirect">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>
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
