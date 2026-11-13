@php
    $imageMap = [
        'Ayam Kecap' => 'images/ayamkecap.png',
        'Udang Saus Padang' => 'images/udangsauspadang.png',
        'Nasi Goreng Spesial' => 'images/nasigorengspesial.png',
        'Pancake Klasik Madu' => 'images/pancakeklasikmadu.png',
        'Mocalatte Spesial' => 'images/mocalatte.png',
        'Waterlemon' => 'images/waterlemon.png',
    ];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Resep yang Disukai - Resep Kita</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --orange:#7a8f5c; --orange-dark:#5f7446; --sage:#7a8f5c; --sage-light:#e4ebd8; --brown:#542313; --muted:#806b61; --line:#ead9cc; }
        * { box-sizing:border-box; }
        body { margin:0; background:#fffaf5; color:var(--brown); font-family:Figtree,Arial,sans-serif; }
        .liked-shell { display:flex; min-height:100vh; }
        .liked-sidebar { width:218px; flex:0 0 218px; padding:27px 16px; background:#fff; border-right:1px solid var(--line); }
        .brand { display:flex; align-items:center; gap:9px; padding:0 10px 31px; color:var(--brown); font-size:21px; font-weight:900; line-height:.9; text-decoration:none; }
        .brand span { color:var(--orange-dark); }
        .brand-icon { display:grid; place-items:center; width:39px; height:39px; font-size:31px; }
        .menu-label { margin:0 10px 12px; color:#947a6c; font-size:10px; font-weight:800; letter-spacing:.08em; text-transform:uppercase; }
        .side-nav { display:grid; gap:5px; }
        .side-nav a,.logout { display:flex; align-items:center; gap:13px; padding:11px 12px; border-radius:11px; color:#613f32; font-size:14px; font-weight:600; text-decoration:none; }
        .side-nav a:hover,.side-nav a.active { background:var(--orange); color:#fff; }
        .nav-icon { width:20px; text-align:center; font-size:19px; }
        .side-divider { margin:20px 10px; border:0; border-top:1px solid #f1e4dc; }
        .logout { width:100%; border:0; background:#fff0e3; color:#b9571d; cursor:pointer; text-align:left; }
        .liked-main { width:min(100%,1200px); margin:0 auto; padding:28px 30px 45px; }
        .topbar { display:flex; align-items:center; justify-content:space-between; margin-bottom:15px; }
        .topbar h1 { margin:0; color:var(--brown); font-size:34px; }
        .topbar p { margin:4px 0 0; color:var(--muted); font-size:14px; }
        .top-user { display:flex; align-items:center; gap:10px; }
        .avatar { display:grid; place-items:center; width:38px; height:38px; border-radius:50%; background:#ffe0c5; color:#ad4b1b; font-weight:800; }
        .category-bar { display:flex; gap:9px; overflow-x:auto; padding:3px 0 14px; }
        .category-chip { flex:0 0 auto; padding:9px 15px; border:1px solid var(--line); border-radius:20px; background:#fffdf9; color:#5f4438; font-size:12px; text-decoration:none; }
        .category-chip:hover,.category-chip.active { border-color:var(--sage); background:var(--sage-light); color:#5f7446; }
        .liked-toolbar { display:flex; align-items:center; justify-content:space-between; padding:14px 18px; border:1px solid var(--line); border-radius:15px; background:#fffdf9; }
        .liked-toolbar h2 { margin:0; font-size:18px; }
        .liked-toolbar h2 span { color:var(--orange); font-size:24px; }
        .count { color:var(--muted); font-size:12px; }
        .recipe-grid { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:15px; margin-top:12px; }
        .recipe-card { overflow:hidden; border:1px solid var(--line); border-radius:15px; background:#fff5df; box-shadow:0 5px 16px rgba(104,51,15,.04); }
        .recipe-image { position:relative; height:162px; background:#ffe2c4; }
        .recipe-image img { width:100%; height:100%; object-fit:cover; }
        .placeholder { display:grid; place-items:center; width:100%; height:100%; font-size:58px; }
        .heart { position:absolute; top:10px; right:10px; display:grid; place-items:center; width:34px; height:34px; border-radius:50%; background:#fff; color:#ec543c; font-size:20px; box-shadow:0 2px 6px #8f4e3040; }
        .recipe-content { padding:11px 13px 14px; }
        .category { display:inline-block; padding:5px 10px; border-radius:16px; background:var(--sage-light); color:#5f7446; font-size:10px; }
        .recipe-content h3 { overflow:hidden; margin:9px 0 5px; color:#432017; font-size:17px; text-overflow:ellipsis; white-space:nowrap; }
        .recipe-meta { margin:0; color:#674d40; font-size:12px; }
        .card-actions { display:flex; align-items:center; justify-content:space-between; gap:6px; margin-top:13px; padding-top:10px; border-top:1px solid #eadfd8; }
        .detail-link { color:var(--sage); font-size:12px; font-weight:700; text-decoration:none; }
        .remove-button { border:0; background:transparent; color:#b54c2f; font:inherit; font-size:11px; cursor:pointer; }
        .empty { margin-top:12px; padding:55px 20px; border:1px dashed #e2c6b5; border-radius:15px; background:#fffdf9; text-align:center; }
        .empty strong { display:block; margin:8px 0 5px; font-size:18px; }
        .empty p { margin:0 0 17px; color:var(--muted); font-size:13px; }
        .browse { display:inline-block; padding:10px 17px; border-radius:20px; background:var(--orange); color:#fff; font-size:12px; font-weight:800; text-decoration:none; }
        @media (max-width:950px) { .recipe-grid { grid-template-columns:repeat(3,minmax(0,1fr)); } }
        @media (max-width:760px) {
            .liked-shell { display:block; }
            .liked-sidebar { width:100%; padding:14px; border-right:0; border-bottom:1px solid var(--line); }
            .brand { padding-bottom:14px; }
            .menu-label,.side-divider { display:none; }
            .side-nav { grid-template-columns:repeat(2,1fr); }
            .liked-main { padding:20px 14px 35px; }
            .top-user { display:none; }
            .topbar h1 { font-size:28px; }
            .recipe-grid { grid-template-columns:repeat(2,minmax(0,1fr)); }
        }
        @media (max-width:480px) { .recipe-grid { grid-template-columns:1fr; } }
    </style>
</head>
<body>
<div class="liked-shell">
    <aside class="liked-sidebar">
        <a class="brand" href="{{ route('home') }}"><span class="brand-icon">♨</span><span>RESEP<br><span>KITA</span></span></a>
        <p class="menu-label">Menu utama</p>
        <nav class="side-nav">
            <a href="{{ route('home') }}"><span class="nav-icon">⌂</span>Beranda</a>
            <a href="{{ route('dashboard') }}"><span class="nav-icon">▦</span>Dashboard</a>
            <a href="{{ route('recipes.favorites') }}"><span class="nav-icon">♡</span>Resep Favorit</a>
            <a class="active" href="{{ route('recipes.liked') }}"><span class="nav-icon">♥</span>Resep Disukai</a>
            <a href="{{ route('recipes.mine') }}"><span class="nav-icon">♨</span>Resep Saya</a>
        </nav>
        <hr class="side-divider">
        <a href="{{ route('profile.edit') }}" class="side-nav"><span class="nav-icon">⚙</span>Pengaturan Profil</a>
        <form method="POST" action="{{ route('logout') }}" data-logout-form>@csrf<button class="logout" type="submit"><span class="nav-icon">↪</span>Logout</button></form>
    </aside>
    <main class="liked-main">
        <header class="topbar">
            <div><h1>Resep Disukai</h1><p>Riwayat resep yang kamu sukai sebagai bentuk apresiasi.</p></div>
            <div class="top-user"><span>♧</span><span class="avatar">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</span>⌄</div>
        </header>
        <nav class="category-bar" aria-label="Filter kategori">
            <a class="category-chip {{ !$selectedCategory ? 'active' : '' }}" href="{{ route('recipes.liked') }}">Semua</a>
            @foreach ($categories as $category)
                <a class="category-chip {{ $selectedCategory == $category->id ? 'active' : '' }}" href="{{ route('recipes.liked', ['category' => $category->id]) }}">{{ $category->nama_kategori }}</a>
            @endforeach
        </nav>
        <section class="liked-toolbar"><h2><span>♥</span> Resep Disukai</h2><span class="count">{{ $likedRecipes->count() }} resep disukai</span></section>
        @if ($likedRecipes->isNotEmpty())
            <section class="recipe-grid">
                @foreach ($likedRecipes as $liked)
                    @php $recipe = $liked->recipe; $image = $recipe->foto ?: ($imageMap[$recipe->nama_resep] ?? null); @endphp
                    <article class="recipe-card">
                        <div class="recipe-image">
                            @if ($image)<img src="{{ str_starts_with($image,'images/') ? asset($image) : asset('storage/'.$image) }}" alt="{{ $recipe->nama_resep }}">
                            @else<span class="placeholder">🍲</span>@endif
                            <span class="heart">♥</span>
                        </div>
                        <div class="recipe-content">
                            <span class="category">{{ $recipe->category->nama_kategori ?? 'Umum' }}</span>
                            <h3 title="{{ $recipe->nama_resep }}">{{ $recipe->nama_resep }}</h3>
                            <p class="recipe-meta">Waktu memasak: {{ $recipe->waktu_masak }} menit</p>
                            <div class="card-actions">
                                <a class="detail-link" href="{{ route('recipes.show',$recipe) }}">Lihat detail →</a>
                                <form method="POST" action="{{ route('recipes.reaction',$recipe) }}">
                                    @csrf <input type="hidden" name="type" value="like">
                                    <button class="remove-button" type="submit">♡ Batal suka</button>
                                </form>
                            </div>
                        </div>
                    </article>
                @endforeach
            </section>
        @else
            <section class="empty"><div style="font-size:48px">♡</div><strong>Belum ada resep yang disukai</strong><p>Berikan tanda suka sebagai apresiasi untuk resep dari koki atau pengguna lain.</p><a class="browse" href="{{ route('home') }}#resep">Jelajahi Resep</a></section>
        @endif
    </main>
</div>
</body>
</html>
