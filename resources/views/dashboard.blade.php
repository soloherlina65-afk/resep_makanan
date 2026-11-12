@php
    $user = auth()->user();
    $imageMap = [
        'Ayam Kecap' => 'images/ayamkecap.png',
        'Udang Saus Padang' => 'images/udangsauspadang.png',
        'Nasi Goreng Spesial' => 'images/nasigorengspesial.png',
        'Pancake Klasik Madu' => 'images/pancakeklasikmadu.png',
        'Mocalatte Spesial' => 'images/mocalatte.png',
        'Waterlemon' => 'images/waterlemon.png',
    ];
    $recipeImage = fn ($recipe) => $recipe->foto ?: ($imageMap[$recipe->nama_resep] ?? null);
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Resep Kita') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --dash-orange: #7a8f5c;
            --dash-orange-dark: #5f7446;
            --dash-sage: #7a8f5c;
            --dash-sage-light: #e4ebd8;
            --dash-cream: #f4f8ef;
            --dash-brown: #30402a;
            --dash-muted: #5f7446;
            --dash-line: #d5e0c5;
        }
        * { box-sizing: border-box; }
        body { margin: 0; background: var(--dash-cream); color: var(--dash-brown); font-family: Figtree, Arial, sans-serif; }
        .dashboard-shell { display: flex; min-height: 100vh; }
        .dashboard-sidebar { width: 218px; flex: 0 0 218px; padding: 27px 16px; background: #fff; border-right: 1px solid var(--dash-line); }
        .dashboard-brand { display: flex; align-items: center; gap: 9px; padding: 0 10px 31px; color: var(--dash-brown); font-size: 21px; font-weight: 900; line-height: .9; }
        .dashboard-brand span { color: var(--dash-orange-dark); }
        .brand-chef { display: grid; place-items: center; width: 39px; height: 39px; color: var(--dash-orange-dark); font-size: 31px; }
        .sidebar-label { margin: 0 10px 12px; color: #6f8060; font-size: 10px; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; }
        .dashboard-nav { display: grid; gap: 5px; }
        .dashboard-nav a, .dashboard-logout { display: flex; align-items: center; gap: 13px; padding: 11px 12px; border-radius: 11px; color: #3f5635; font-size: 14px; font-weight: 600; transition: .2s; }
        .dashboard-nav a:hover, .dashboard-nav a.active { background: var(--dash-orange); color: #fff; }
        .dashboard-nav .nav-icon { width: 20px; text-align: center; font-size: 19px; }
        .sidebar-divider { margin: 20px 10px; border: 0; border-top: 1px solid #d5e0c5; }
        .dashboard-logout { width: 100%; border: 0; background: #e4ebd8; color: #5f7446; cursor: pointer; text-align: left; }
        .dashboard-logout:hover { background: #d5e0c5; }
        .sidebar-decoration { margin: 48px auto 0; color: #b7c8a0; font-size: 65px; text-align: center; opacity: .8; }
        .dashboard-main { width: min(100%, 1200px); margin: 0 auto; padding: 28px 28px 45px; }
        .dashboard-topbar { display: flex; align-items: center; justify-content: space-between; gap: 20px; margin-bottom: 20px; }
        .dashboard-kicker { margin: 0 0 2px; color: #7a8f5c; font-size: 11px; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; }
        .dashboard-title { margin: 0; color: #30402a; font-size: clamp(29px, 4vw, 37px); font-weight: 850; }
        .dashboard-subtitle { margin: 3px 0 0; color: var(--dash-muted); font-size: 15px; }
        .topbar-user { display: flex; align-items: center; gap: 10px; }
        .notification { position: relative; color: var(--dash-brown); font-size: 24px; }
        .notification-badge { position: absolute; top: -7px; right: -8px; display: grid; place-items: center; width: 17px; height: 17px; border-radius: 50%; background: #7a8f5c; color: #fff; font-size: 10px; font-weight: 800; }
        .user-avatar, .profile-avatar { display: grid; place-items: center; border-radius: 50%; background: #e4ebd8; color: #5f7446; font-weight: 800; }
        .user-avatar { width: 38px; height: 38px; }
        .welcome-card { position: relative; display: flex; align-items: center; min-height: 164px; overflow: hidden; padding: 25px; border-radius: 16px; background: #5f7446; color: #fff; }
        .welcome-card::before { content: ''; position: absolute; inset: 0; background: linear-gradient(90deg, rgba(48, 64, 42, .82) 0%, rgba(48, 64, 42, .52) 46%, rgba(48, 64, 42, .16) 100%), url('{{ asset('images/dashboard-food.png') }}') center/cover no-repeat; }
        .welcome-card > div:first-child { position: relative; z-index: 1; max-width: 58%; }
        .welcome-card h2 { margin: 8px 0 5px; font-size: 28px; }
        .welcome-card p { max-width: 430px; margin: 0; color: #f4f8ef; font-size: 14px; line-height: 1.5; }
        .share-recipe-link { display: inline-block; margin-top: 12px; padding: 8px 14px; border-radius: 18px; background: #e4ebd8; color: var(--dash-orange-dark); font-size: 11px; font-weight: 800; }
        .welcome-pill { display: inline-block; padding: 6px 11px; border-radius: 20px; background: #e4ebd8; color: #30402a; font-size: 11px; font-weight: 800; }
        .welcome-food { display: none; }
        .profile-card { display: flex; align-items: center; gap: 13px; margin-top: 16px; padding: 19px; border: 1px solid var(--dash-line); border-radius: 16px; background: #fff; }
        .profile-avatar { width: 72px; height: 72px; flex: 0 0 72px; font-size: 25px; }
        .profile-card small { color: #6f8060; }
        .profile-card h3 { margin: 3px 0; font-size: 17px; }
        .profile-card p { margin: 0; color: var(--dash-muted); font-size: 12px; }
        .edit-profile { display: block; margin-top: 12px; padding: 8px 22px; border: 1px solid var(--dash-orange); border-radius: 18px; color: var(--dash-orange-dark); font-size: 12px; font-weight: 800; text-align: center; }
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-top: 16px; }
        .stat-card { display: flex; align-items: center; gap: 13px; padding: 14px; border: 1px solid var(--dash-line); border-radius: 14px; background: #fff; }
        .stat-icon { display: grid; place-items: center; width: 48px; height: 48px; flex: 0 0 48px; border-radius: 50%; background: #e4ebd8; color: var(--dash-orange-dark); font-size: 26px; }
        .stat-card:nth-child(2) .stat-icon { background: #d5e0c5; color: #5f7446; }
        .stat-card:nth-child(3) .stat-icon { background: #e4ebd8; color: #7a8f5c; }
        .stat-card strong { display: block; margin-top: 2px; font-size: 23px; }
        .stat-card span { color: var(--dash-muted); font-size: 11px; }
        .dashboard-columns { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 16px; }
        .dashboard-section { min-width: 0; padding: 16px; border: 1px solid var(--dash-line); border-radius: 16px; background: #fff; }
        .section-heading { display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-bottom: 13px; }
        .section-heading h2 { margin: 0; font-size: 17px; }
        .section-heading h2 span { margin-right: 5px; color: var(--dash-sage); }
        .section-heading a { color: var(--dash-sage); font-size: 11px; font-weight: 700; }
        .recipe-list { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
        .mini-recipe { min-width: 0; overflow: hidden; border: 1px solid #d5e0c5; border-radius: 10px; background: #f4f8ef; }
        .mini-recipe-image { position: relative; height: 80px; background: #e4ebd8; }
        .mini-recipe-image img { width: 100%; height: 100%; object-fit: cover; }
        .mini-heart { position: absolute; top: 5px; right: 5px; display: grid; place-items: center; width: 22px; height: 22px; border-radius: 50%; background: #fff; color: #7a8f5c; font-size: 14px; }
        .mini-recipe-body { padding: 7px; }
        .mini-recipe h3 { overflow: hidden; margin: 0; color: #30402a; font-size: 11px; text-overflow: ellipsis; white-space: nowrap; }
        .mini-recipe p { overflow: hidden; margin: 4px 0 0; color: var(--dash-sage); font-size: 10px; text-overflow: ellipsis; white-space: nowrap; }
        .empty-card { padding: 20px 8px; border: 1px dashed #b7c8a0; border-radius: 10px; color: var(--dash-muted); font-size: 12px; text-align: center; }
        .created-section { margin-top: 16px; }
        .created-section .mini-recipe { display: flex; align-items: center; }
        .created-section .mini-recipe-image { width: 105px; height: 69px; flex: 0 0 105px; }
        .created-section .mini-recipe-body { flex: 1; }
        .recipe-link { display: inline-block; margin-top: 5px; color: var(--dash-orange-dark); font-size: 10px; font-weight: 700; }
        @media (min-width: 980px) {
            .dashboard-main { display: grid; grid-template-columns: minmax(0, 1fr) 288px; align-content: start; column-gap: 16px; }
            .dashboard-topbar, .welcome-card, .stats-grid, .dashboard-columns, .created-section { grid-column: 1 / -1; }
            .welcome-card { grid-column: 1; }
            .profile-card { grid-column: 2; grid-row: 2; }
            .stats-grid { grid-row: 3; }
            .dashboard-columns { grid-row: 4; }
            .created-section { grid-row: 5; }
        }
        @media (max-width: 979px) {
            .dashboard-sidebar { width: 190px; flex-basis: 190px; }
        }
        @media (max-width: 700px) {
            .dashboard-shell { display: block; }
            .dashboard-sidebar { width: 100%; padding: 14px; border-right: 0; border-bottom: 1px solid var(--dash-line); }
            .dashboard-brand { padding-bottom: 14px; }
            .dashboard-nav { grid-template-columns: repeat(2, 1fr); }
            .sidebar-label, .sidebar-divider, .sidebar-decoration { display: none; }
            .dashboard-main { padding: 20px 14px 35px; }
            .dashboard-topbar { align-items: flex-start; }
            .welcome-card { min-height: 180px; }
            .welcome-card > div:first-child { max-width: 100%; }
            .stats-grid, .dashboard-columns { grid-template-columns: 1fr; }
        }
        @media (max-width: 430px) {
            .recipe-list { grid-template-columns: repeat(2, 1fr); }
            .topbar-user { display: none; }
        }
    </style>
</head>
<body>
<div class="dashboard-shell">
    <aside class="dashboard-sidebar">
        <a class="dashboard-brand" href="{{ route('home') }}">
            <span class="brand-chef">♨</span>
            <span>RESEP<br><span>KITA</span></span>
        </a>
        <p class="sidebar-label">Menu utama</p>
        <nav class="dashboard-nav" aria-label="Navigasi dashboard">
            <a href="{{ route('home') }}"><span class="nav-icon">⌂</span>Beranda</a>
            <a class="active" href="{{ route('dashboard') }}"><span class="nav-icon">▦</span>Dashboard</a>
            <a href="{{ route('recipes.favorites') }}"><span class="nav-icon">♡</span>Resep Favorit</a>
            <a href="{{ route('recipes.liked') }}"><span class="nav-icon">♥</span>Resep Disukai</a>
            <a href="{{ route('recipes.mine') }}"><span class="nav-icon">♨</span>Resep Saya</a>
        </nav>
        <hr class="sidebar-divider">
        <a href="{{ route('profile.edit') }}" class="dashboard-nav"><span class="nav-icon">⚙</span>Pengaturan Profil</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="dashboard-logout"><span class="nav-icon">↪</span>Logout</button>
        </form>
        <div class="sidebar-decoration">♨</div>
    </aside>

    <main class="dashboard-main">
        <header class="dashboard-topbar">
            <div>
                <p class="dashboard-kicker">Area pengguna</p>
                <h1 class="dashboard-title">Dashboard Saya</h1>
                <p class="dashboard-subtitle">Kelola profil dan kumpulan resep favoritmu.</p>
            </div>
            <div class="topbar-user">
                <span class="notification" aria-label="Notifikasi">♧<span class="notification-badge">2</span></span>
                <span class="user-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                <span>⌄</span>
            </div>
        </header>

        <section class="welcome-card">
            <div>
                <span class="welcome-pill">SELAMAT DATANG 👋</span>
                <h2>Halo, {{ $user->name }}!</h2>
                <p>Senang melihatmu kembali. Yuk, temukan inspirasi masakan dan kelola resep favoritmu.</p>
                <a class="share-recipe-link" href="{{ route('recipes.create') }}">+ Posting Resep Baru</a>
            </div>
            <div class="welcome-food">
                <img src="{{ asset('images/dashboard-food.png') }}" alt="Salad segar">
            </div>
        </section>

        <section class="profile-card">
            <div class="profile-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
            <div>
                <small>Profil Pengguna</small>
                <h3>{{ $user->name }}</h3>
                <p>{{ $user->email }}</p>
                <a class="edit-profile" href="{{ route('profile.edit') }}">✎ &nbsp;Edit Profil</a>
            </div>
        </section>

        <section class="stats-grid" aria-label="Ringkasan aktivitas">
            <div class="stat-card"><div class="stat-icon">♡</div><div><span>Total Favorit</span><strong>{{ $favorites->count() }}</strong><span>Resep tersimpan</span></div></div>
            <div class="stat-card"><div class="stat-icon">♥</div><div><span>Total Like</span><strong>{{ $likedRecipes->count() }}</strong><span>Resep disukai</span></div></div>
            <div class="stat-card"><div class="stat-icon">♨</div><div><span>Resep Saya</span><strong>{{ $userRecipes->count() }}</strong><span>Resep yang dibuat</span></div></div>
        </section>

        <div class="dashboard-columns">
            <section id="favorites" class="dashboard-section">
                <div class="section-heading"><h2><span>☆</span>Resep Favorit</h2><a href="{{ route('recipes.favorites') }}">Lihat Semua →</a></div>
                @if ($favorites->isNotEmpty())
                    <div class="recipe-list">
                        @foreach ($favorites as $favorite)
                            @php $recipe = $favorite->recipe; $image = $recipeImage($recipe); @endphp
                            <article class="mini-recipe">
                                <a href="{{ route('recipes.show', $recipe) }}">
                                    <div class="mini-recipe-image">
                                        @if ($image)<img src="{{ str_starts_with($image, 'images/') ? asset($image) : asset('storage/' . $image) }}" alt="{{ $recipe->nama_resep }}">@else<span>🍲</span>@endif
                                        <span class="mini-heart">♥</span>
                                    </div>
                                    <div class="mini-recipe-body"><h3>{{ $recipe->nama_resep }}</h3><p>★ Resep tersimpan</p></div>
                                </a>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="empty-card">♡<br>Belum ada resep favorit.</div>
                @endif
            </section>

            <section id="liked" class="dashboard-section">
                <div class="section-heading"><h2><span>♡</span>Resep yang Disukai</h2><a href="{{ route('recipes.liked') }}">Lihat Semua →</a></div>
                @if ($likedRecipes->isNotEmpty())
                    <div class="recipe-list">
                        @foreach ($likedRecipes as $liked)
                            @php $recipe = $liked->recipe; $image = $recipeImage($recipe); @endphp
                            <article class="mini-recipe">
                                <a href="{{ route('recipes.show', $recipe) }}">
                                    <div class="mini-recipe-image">
                                        @if ($image)<img src="{{ str_starts_with($image, 'images/') ? asset($image) : asset('storage/' . $image) }}" alt="{{ $recipe->nama_resep }}">@else<span>🍲</span>@endif
                                        <span class="mini-heart">♡</span>
                                    </div>
                                    <div class="mini-recipe-body"><h3>{{ $recipe->nama_resep }}</h3><p>♥ Resep disukai</p></div>
                                </a>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="empty-card">♡<br>Belum ada resep yang disukai.</div>
                @endif
            </section>
        </div>

        <section id="created" class="dashboard-section created-section">
            <div class="section-heading"><h2><span>♨</span>Resep Buatan Saya</h2><a href="{{ route('recipes.mine') }}">Lihat Semua →</a></div>
            @if ($userRecipes->isNotEmpty())
                <div class="recipe-list">
                    @foreach ($userRecipes as $recipe)
                        @php $image = $recipeImage($recipe); @endphp
                        <article class="mini-recipe">
                            <div class="mini-recipe-image">@if ($image)<img src="{{ str_starts_with($image, 'images/') ? asset($image) : asset('storage/' . $image) }}" alt="{{ $recipe->nama_resep }}">@else<span>🍲</span>@endif</div>
                            <div class="mini-recipe-body"><h3>{{ $recipe->nama_resep }}</h3><p>{{ $recipe->category->nama_kategori ?? 'Umum' }}</p><a class="recipe-link" href="{{ route('recipes.show', $recipe) }}">Lihat resep →</a></div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="empty-card">🍳<br>Belum ada resep buatanmu.</div>
            @endif
        </section>
    </main>
</div>
</body>
</html>
