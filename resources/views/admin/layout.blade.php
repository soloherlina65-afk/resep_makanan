<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - Admin Racikara</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --sage: #7a8f5c; --sage-dark: #4f6638; --ink: #263323; --muted: #71806a; --line: #dce5d2; }
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; background: #f3f6ef; color: var(--ink); font-family: Figtree, Arial, sans-serif; }
        main { width: min(1180px, 100%); margin: 0 auto; padding: 28px 20px 48px; }
        header { display: flex; align-items: flex-start; justify-content: space-between; gap: 20px; margin-bottom: 20px; }
        h1 { margin: 0; font-size: clamp(26px, 4vw, 36px); }
        header p { margin: 4px 0 0; color: var(--muted); }
        a { color: var(--sage-dark); }
        .site-back { display: inline-flex; min-height: 38px; align-items: center; gap: 8px; margin-bottom: 18px; padding: 0 14px; border: 1px solid var(--line); border-radius: 10px; background: #fff; font-size: 12px; font-weight: 700; text-decoration: none; transition: background .2s, border-color .2s, transform .2s; }
        .site-back:hover { transform: translateX(-2px); border-color: #b7c8a0; background: #f8faf5; }
        .site-back:focus-visible { outline: 3px solid rgba(122, 143, 92, .3); outline-offset: 2px; }
        nav { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 18px; }
        nav a { padding: 8px 12px; border-radius: 18px; background: #e4ebd8; font-size: 12px; font-weight: 700; text-decoration: none; }
        .panel { margin-bottom: 16px; padding: 18px; border: 1px solid var(--line); border-radius: 15px; background: #fff; }
        .panel h2 { margin: 0 0 13px; font-size: 19px; }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th, td { padding: 10px 8px; border-bottom: 1px solid #eef2e8; font-size: 13px; vertical-align: middle; }
        th { color: var(--muted); font-size: 11px; text-transform: uppercase; }
        form.inline { display: inline-flex; align-items: center; gap: 6px; }
        input, select { min-height: 36px; padding: 7px 10px; border: 1px solid var(--line); border-radius: 8px; background: #fff; font: inherit; }
        button, .button { display: inline-block; padding: 8px 12px; border: 0; border-radius: 8px; background: var(--sage); color: #fff; font: inherit; font-size: 12px; font-weight: 700; cursor: pointer; text-decoration: none; }
        button.danger { background: #a53d32; }
        .notice { margin-bottom: 14px; padding: 12px 14px; border-radius: 10px; background: #eff9ee; color: #287835; font-size: 13px; }
        .notice.error { background: #fff1ef; color: #a33a2d; }
        .errors { margin-bottom: 14px; padding: 12px 14px; border-radius: 10px; background: #fff1ef; color: #a33a2d; font-size: 13px; }
        .empty { color: var(--muted); font-size: 13px; }
        .pagination { margin-top: 16px; }
        @media (max-width: 700px) { main { padding: 20px 12px 34px; } header { flex-direction: column; } .panel { padding: 14px; } }
    </style>
</head>
<body>
<main>
    <a class="site-back" href="{{ route('home') }}" onclick="if (window.history.length > 1) { window.history.back(); return false; }"><span aria-hidden="true">←</span>Kembali</a>
    <header>
        <div><p>Racikara · Area Admin</p><h1>@yield('heading')</h1></div>
        <div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </div>
    </header>
    <nav aria-label="Navigasi admin">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <a href="{{ route('admin.recipes.index') }}">Resep</a>
        <a href="{{ route('admin.categories.index') }}">Kategori</a>
        <a href="{{ route('admin.users.index') }}">Pengguna</a>
        <a href="{{ route('admin.reviews.index') }}">Moderasi Komentar</a>
        <a href="{{ route('about') }}">Anggota Tim</a>
        <a href="{{ route('admin.notifications.index') }}">Notifikasi</a>
        <a href="{{ route('admin.activities.index') }}">Aktivitas</a>
        <a href="{{ route('admin.settings.edit') }}">Pengaturan</a>
    </nav>
    @if (session('success'))<div class="notice" role="status">{{ session('success') }}</div>@endif
    @if (session('error'))<div class="notice error" role="alert">{{ session('error') }}</div>@endif
    @if ($errors->any())
        <div class="errors" role="alert"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif
    @yield('content')
</main>
</body>
</html>
