<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Akses Dashboard Admin - Racikara</title>
    <style>
        * { box-sizing: border-box; }
        body {
            display: grid;
            min-height: 100vh;
            margin: 0;
            padding: 24px;
            place-items: center;
            background: #f4f8ef;
            color: #30402a;
            font-family: Arial, Helvetica, sans-serif;
        }
        .access-card {
            width: min(100%, 460px);
            padding: 36px;
            border: 1px solid #d5e0c5;
            border-radius: 18px;
            background: #fff;
            box-shadow: 0 16px 40px rgba(48, 64, 42, .1);
            text-align: center;
        }
        .access-icon {
            display: grid;
            width: 58px;
            height: 58px;
            margin: 0 auto 18px;
            place-items: center;
            border-radius: 50%;
            background: #e4ebd8;
            color: #5f7446;
            font-size: 28px;
        }
        h1 { margin: 0 0 12px; font-size: 24px; }
        p { margin: 0 0 24px; color: #5f6d57; line-height: 1.6; }
        .actions { display: grid; gap: 10px; }
        .button {
            display: block;
            padding: 12px 18px;
            border: 1px solid #5f7446;
            border-radius: 10px;
            background: #5f7446;
            color: #fff;
            font-weight: 700;
            text-decoration: none;
        }
        .button-secondary { background: #fff; color: #5f7446; }
        .home-link {
            display: inline-block;
            margin-top: 20px;
            color: #5f7446;
            font-size: 14px;
        }
        @media (max-width: 480px) {
            .access-card { padding: 28px 22px; }
            h1 { font-size: 21px; }
        }
    </style>
</head>
<body>
    <main class="access-card">
        <div class="access-icon" aria-hidden="true">!</div>
        <h1>Masuk untuk membuka dashboard</h1>
        <p>Silakan daftar atau login terlebih dahulu. Dashboard dan pengaturan admin hanya dapat dibuka oleh akun yang memiliki hak admin.</p>
        <div class="actions">
            <a class="button" href="{{ route('login') }}">Login</a>
            <a class="button button-secondary" href="{{ route('register') }}">Daftar akun</a>
        </div>
        <a class="home-link" href="{{ route('home') }}">Kembali ke beranda</a>
    </main>
</body>
</html>
