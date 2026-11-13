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
    <title>Resep Saya - Racikara</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --orange:#7a8f5c; --orange-dark:#5f7446; --sage:#7a8f5c; --sage-light:#e4ebd8; --brown:#542313; --muted:#806b61; --line:#ead9cc; }
        * { box-sizing:border-box; }
        body { margin:0; background:#fffaf5; color:var(--brown); font-family:Figtree,Arial,sans-serif; }
        .recipes-shell { display:flex; min-height:100vh; }
        .recipes-sidebar { width:218px; flex:0 0 218px; padding:27px 16px; background:#fff; border-right:1px solid var(--line); }
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
        .recipes-main { width:min(100%,1200px); margin:0 auto; padding:28px 30px 45px; }
        .topbar { display:flex; align-items:center; justify-content:space-between; margin-bottom:15px; }
        .topbar h1 { margin:0; color:var(--brown); font-size:34px; }
        .topbar p { margin:4px 0 0; color:var(--muted); font-size:14px; }
        .top-user { display:flex; align-items:center; gap:10px; }
        .avatar { display:grid; place-items:center; width:38px; height:38px; border-radius:50%; background:#ffe0c5; color:#ad4b1b; font-weight:800; }
        .category-bar { display:flex; gap:9px; overflow-x:auto; padding:3px 0 14px; }
        .category-chip { flex:0 0 auto; padding:9px 15px; border:1px solid var(--line); border-radius:20px; background:#fffdf9; color:#5f4438; font-size:12px; text-decoration:none; }
        .category-chip:hover,.category-chip.active { border-color:var(--sage); background:var(--sage-light); color:#5f7446; }
        .recipe-search { display:flex; gap:9px; margin-bottom:14px; }
        .recipe-search input { flex:1; min-width:0; height:40px; padding:0 14px; border:1px solid var(--line); border-radius:10px; background:#fff; color:var(--brown); outline:0; font:inherit; font-size:13px; }
        .recipe-search input:focus { border-color:var(--sage); box-shadow:0 0 0 3px rgba(122,143,92,.14); }
        .recipe-search button { border:0; border-radius:10px; padding:0 18px; background:var(--sage); color:#fff; font:inherit; font-size:13px; font-weight:700; cursor:pointer; }
        .recipe-search button:hover { background:var(--orange-dark); }
        .recipes-toolbar { display:flex; align-items:center; justify-content:space-between; gap:15px; padding:14px 18px; border:1px solid var(--line); border-radius:15px; background:#fffdf9; }
        .recipes-toolbar h2 { margin:0; font-size:17px; }
        .recipes-toolbar h2 span { margin-right:8px; color:var(--orange); font-size:24px; }
        .add-recipe { padding:9px 16px; border-radius:9px; background:var(--orange); color:#fff; font-size:12px; font-weight:800; text-decoration:none; }
        .add-recipe:hover { background:var(--orange-dark); }
        .success { margin:12px 0; padding:10px 13px; border-radius:8px; background:#e9f8e9; color:#287835; font-size:12px; }
        .recipe-grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:15px; margin-top:12px; }
        .recipe-card { overflow:hidden; border:1px solid var(--line); border-radius:15px; background:#fffdf9; box-shadow:0 5px 16px rgba(104,51,15,.04); }
        .recipe-image { height:205px; background:#ffe2c4; }
        .recipe-image img { width:100%; height:100%; object-fit:cover; }
        .recipe-image-placeholder { display:grid; place-items:center; width:100%; height:100%; color:var(--orange); font-size:65px; }
        .recipe-content { padding:12px 14px 13px; }
        .category { display:inline-block; padding:5px 10px; border-radius:16px; background:var(--sage-light); color:#5f7446; font-size:11px; }
        .recipe-content h3 { overflow:hidden; margin:9px 0 5px; color:#432017; font-size:19px; text-overflow:ellipsis; white-space:nowrap; }
        .recipe-meta { margin:0; color:#674d40; font-size:13px; }
        .card-actions { display:flex; gap:12px; margin-top:14px; padding-top:11px; border-top:1px solid #eadfd8; }
        .card-actions a,.card-actions button { border:0; background:transparent; color:var(--sage); font:inherit; font-size:13px; cursor:pointer; text-decoration:none; }
        .card-actions button { color:#b54c2f; }
        .empty { display:flex; flex-direction:column; align-items:center; justify-content:center; min-height:calc(100vh - 220px); margin-top:12px; padding:20px; text-align:center; }
        .empty strong { display:block; margin:8px 0 5px; font-size:18px; }
        .empty p { margin:0 0 17px; color:var(--muted); font-size:13px; }
        @media (max-width:850px) { .recipe-grid { grid-template-columns:repeat(2,minmax(0,1fr)); } }
        @media (max-width:650px) {
            .recipes-shell { display:block; }
            .recipes-sidebar { width:100%; padding:14px; border-right:0; border-bottom:1px solid var(--line); }
            .brand { padding-bottom:14px; }
            .menu-label,.side-divider { display:none; }
            .side-nav { grid-template-columns:repeat(2,1fr); }
            .recipes-main { padding:20px 14px 35px; }
            .top-user { display:none; }
            .topbar h1 { font-size:28px; }
            .recipe-grid { grid-template-columns:1fr; }
        }
    </style>
</head>
<body>
<div class="recipes-shell">
    <aside class="recipes-sidebar">
        <a class="brand" href="{{ route('home') }}"><img class="sidebar-logo" style="width: 82px; height: 82px; border-radius: 50%; object-fit: cover; object-position: center;" src="{{ asset('images/racikara-logo.png') }}" alt="Racikara"></a>
        <p class="menu-label">Menu utama</p>
        <nav class="side-nav">
            <a href="{{ route('home') }}"><span class="nav-icon">⌂</span>Beranda</a>
            <a href="{{ route('dashboard') }}"><span class="nav-icon">▦</span>Dashboard</a>
            <a href="{{ route('recipes.favorites') }}"><span class="nav-icon">♡</span>Resep Favorit</a>
            <a href="{{ route('recipes.liked') }}"><span class="nav-icon">♥</span>Resep Disukai</a>
            <a class="active" href="{{ route('recipes.mine') }}"><span class="nav-icon">♨</span>Resep Saya</a>
        </nav>
        <hr class="side-divider">
        <a href="{{ route('profile.edit') }}" class="side-nav"><span class="nav-icon">⚙</span>Pengaturan Profil</a>
        <form method="POST" action="{{ route('logout') }}" data-logout-form>@csrf<button class="logout" type="submit"><span class="nav-icon">↪</span>Logout</button></form>
    </aside>
    <main class="recipes-main">
        <header class="topbar">
            <div><h1>Resep Saya</h1><p>Kelola dan atur resep masakan yang kamu buat.</p></div>
            <div class="top-user"><span>♧</span><span class="avatar">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</span>⌄</div>
        </header>
        <nav class="category-bar" aria-label="Filter kategori">
            <a class="category-chip {{ !$selectedCategory ? 'active' : '' }}" href="{{ route('recipes.mine', $search ? ['search' => $search] : []) }}">Semua</a>
            @foreach ($categories as $category)
                <a class="category-chip {{ $selectedCategory == $category->id ? 'active' : '' }}" href="{{ route('recipes.mine', array_filter(['category' => $category->id, 'search' => $search])) }}">{{ $category->nama_kategori }}</a>
            @endforeach
        </nav>
        <form class="recipe-search" method="GET" action="{{ route('recipes.mine') }}">
            <input type="search" name="search" value="{{ $search }}" placeholder="Cari resep buatanmu..." aria-label="Cari resep buatanmu">
            @if ($selectedCategory)<input type="hidden" name="category" value="{{ $selectedCategory }}">@endif
            <button type="submit">Cari</button>
        </form>
        <section class="recipes-toolbar"><h2><span>▣</span>Resep Buatan Saya</h2><a class="add-recipe" href="{{ route('recipes.create') }}">＋ Tambah Resep Baru</a></section>
        @if (session('success'))<div class="success">{{ session('success') }}</div>@endif
        @if ($recipes->isNotEmpty())
            <section class="recipe-grid">
                @foreach ($recipes as $recipe)
                    @php $image = $recipe->foto ?: ($imageMap[$recipe->nama_resep] ?? null); @endphp
                    <article class="recipe-card">
                        <div class="recipe-image">
                            @if ($image)<img src="{{ str_starts_with($image,'images/') ? asset($image) : asset('storage/'.$image) }}" alt="{{ $recipe->nama_resep }}">
                            @else<span class="recipe-image-placeholder">🍲</span>@endif
                        </div>
                        <div class="recipe-content">
                            <span class="category">{{ $recipe->category->nama_kategori ?? 'Umum' }}</span>
                            <h3 title="{{ $recipe->nama_resep }}">{{ $recipe->nama_resep }}</h3>
                            <p class="recipe-meta">Waktu memasak: {{ $recipe->waktu_masak }} menit</p>
                            <div class="card-actions">
                                <a href="{{ route('recipes.show',$recipe) }}">✎ Lihat resep</a>
                                <form method="POST" action="{{ route('recipes.destroy',$recipe) }}" onsubmit="return confirm('Hapus resep ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit">♜ Hapus</button>
                                </form>
                            </div>
                        </div>
                    </article>
                @endforeach
            </section>
        @else
            <section class="empty"><div style="font-size:48px">🍳</div><strong>Belum ada resep buatanmu</strong><p>Bagikan resep andalanmu dan jadikan inspirasi untuk orang lain.</p><a class="add-recipe" href="{{ route('recipes.create') }}">＋ Posting Resep Pertama</a></section>
        @endif
    </main>
</div>
</body>
</html>
