
@extends('app')

@section('title', 'Racikara - Temukan Inspirasi Masakan')

@section('content')

<!-- NAVBAR -->
<nav class="navbar">
    <div class="nav-container">

        <a href="/" class="logo">
            <img src="{{ asset('images/racikara-logo.png') }}" alt="Racikara">
        </a>

        <div class="nav-menu">
            <a href="/" class="{{ $search ? '' : 'active' }}">Beranda</a>
            <a href="#resep" class="{{ $search ? 'active' : '' }}">Resep</a>
            <a href="#kategori">Kategori</a>
        </div>

        <div class="nav-actions">
            <a href="{{ route('login') }}" class="login-btn">Masuk</a>
            <a href="{{ route('register') }}" class="register-btn">Daftar</a>
        </div>

    </div>
</nav>


<!-- HERO -->
<section class="hero {{ $search ? 'search-hero' : '' }}">

    <div class="hero-container">

        <div class="hero-content">

            @if (!$search)
                <div class="hero-label">🍳 SPESIAL HARI INI</div>
            @endif

            <h1>
                TEMUKAN INSPIRASI
                <span>MASAKAN FAVORITMU</span>
            </h1>

            @if (!$search)
                <p>
                    Temukan berbagai resep masakan lezat,
                    praktis, dan mudah dibuat untuk menemani
                    setiap momen bersama keluarga.
                </p>
            @endif

            <form action="{{ route('home') }}" method="GET" class="search-box">
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Cari resep masakan..."
                    aria-label="Cari resep masakan"
                >

                <button type="submit">
                    🔍 Cari Resep
                </button>
            </form>

        </div>

        <div class="hero-image">
            <div class="hero-circle"></div>

            <div class="food-emoji">
                🍛
            </div>
        </div>

    </div>

</section>


<!-- KATEGORI -->
@if (!$search)
<section class="category-section" id="kategori">

    <div class="section-container">

        <div class="section-header">

            <div>
                <span class="section-label">JELAJAHI</span>
                <h2>Kategori Masakan</h2>
            </div>

            <a href="#kategori" class="see-all">
                Lihat Semua →
            </a>

        </div>

        <div class="category-grid">

            @forelse ($categories as $category)

                <a href="{{ route('home', ['search' => $category->nama_kategori]) }}#resep" class="category-card">

                    <div class="category-icon" aria-hidden="true">
                        ♨
                    </div>

                    <h3>{{ $category->nama_kategori }}</h3>

                    <p>Jelajahi resep pilihan</p>

                </a>

            @empty

                <p>Belum ada kategori masakan.</p>

            @endforelse

        </div>

    </div>

</section>
@endif


<!-- RESEP TERBARU -->
<section class="recipe-section {{ $search ? 'search-results-section' : '' }}" id="resep">

    <div class="section-container">

        @if ($search)
            <div class="search-result-banner">
                <div>
                    <span class="section-label">HASIL PENCARIAN</span>
                    <h2>Resep untuk “{{ $search }}”</h2>
                    <p>{{ $recipes->count() }} resep ditemukan. Pilih resep untuk melihat bahan dan langkah memasaknya.</p>
                </div>
                <a class="clear-search" href="{{ route('home') }}">× Hapus pencarian</a>
            </div>
        @endif

        <div class="section-header">

            <div>
                <span class="section-label">{{ $search ? 'PILIHAN RESEP' : 'INSPIRASI MASAK' }}</span>
                <h2>{{ $search ? 'Temukan Resep Favoritmu' : 'Resep Terbaru' }}</h2>
            </div>

            @if (!$search)
                <a href="#resep" class="see-all">Lihat Semua →</a>
            @endif

        </div>


        <div class="recipe-grid">

            @forelse ($recipes as $recipe)

                <!-- KARTU RESEP -->
                <div
                    class="recipe-card-link"
                    role="link"
                    tabindex="0"
                    data-href="{{ route('recipes.show', $recipe->id) }}"
                    onclick="window.location.href = this.dataset.href"
                    onkeydown="if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); window.location.href = this.dataset.href; }"
                    style="text-decoration: none; color: inherit; display: block; cursor: pointer;"
                >

                    <div class="recipe-card">

                        <div class="recipe-image">

                            @php
                                $recipeImage = $recipe->foto;
                                if (!$recipeImage && strcasecmp(trim($recipe->nama_resep), 'Ayam Kecap') === 0) {
                                    $recipeImage = 'images/ayamkecap.png';
                                }
                                if (!$recipeImage && strcasecmp(trim($recipe->nama_resep), 'Udang Saus Padang') === 0) {
                                    $recipeImage = 'images/udangsauspadang.png';
                                }
                                if (!$recipeImage && strcasecmp(trim($recipe->nama_resep), 'Nasi Goreng Spesial') === 0) {
                                    $recipeImage = 'images/nasigorengspesial.png';
                                }
                                if (!$recipeImage && strcasecmp(trim($recipe->nama_resep), 'Pancake Klasik Madu') === 0) {
                                    $recipeImage = 'images/pancakeklasikmadu.png';
                                }
                                if (!$recipeImage && strcasecmp(trim($recipe->nama_resep), 'Mocalatte Spesial') === 0) {
                                    $recipeImage = 'images/mocalatte.png';
                                }
                                if (!$recipeImage && strcasecmp(trim($recipe->nama_resep), 'Waterlemon') === 0) {
                                    $recipeImage = 'images/waterlemon.png';
                                }
                            @endphp

                            @if ($recipeImage)

                                <img
                                    src="{{ str_starts_with($recipeImage, 'images/') ? asset($recipeImage) : asset('storage/' . $recipeImage) }}"
                                    alt="{{ $recipe->nama_resep }}"
                                >

                            @else

                                <div class="recipe-placeholder"
                                    style="
                                        display:flex;
                                        align-items:center;
                                        justify-content:center;
                                        height:220px;
                                        background:#f3f4f6;
                                        font-size:3rem;
                                    ">
                                    🍲
                                </div>

                            @endif

                            <span class="recipe-badge">
                                {{ $recipe->tingkat_kesulitan }}
                            </span>

                            <span class="favorite-btn">
                                @auth
                                    <form method="POST" action="{{ route('recipes.reaction', $recipe) }}"
                                        onclick="event.stopPropagation()">
                                        @csrf
                                        <input type="hidden" name="type" value="favorite">
                                        <button type="submit" aria-label="Simpan ke favorit">♡</button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" onclick="event.stopPropagation()">♡</a>
                                @endauth
                            </span>

                        </div>


                        <div class="recipe-content">

                            <span class="recipe-category">
                                {{ $recipe->category->nama_kategori ?? 'Umum' }}
                            </span>

                            <h3>
                                {{ $recipe->nama_resep }}
                            </h3>

                            <p>
                                {{ $recipe->deskripsi ?? 'Resep masakan pilihan untuk dicoba di rumah.' }}
                            </p>

                            <div style="margin-top:10px; color:#7a8f5c; font-size:12px;">
                                👤 Diposting oleh
                                <strong style="color:#5f7446;">
                                    {{ $recipe->user?->name ?? 'Tim Racikara' }}
                                </strong>
                            </div>

                            <div class="recipe-info">

                                <span>
                                    ⏱ {{ $recipe->waktu_masak }} Menit
                                </span>

                                <span>
                                    👤 {{ $recipe->porsi }} Porsi
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            @empty

                <div class="empty-recipe">

                    <div style="font-size:50px;">
                        ♨
                    </div>

                    <h3>{{ $search ? 'Resep Tidak Ditemukan' : 'Belum Ada Resep' }}</h3>

                    <p>
                        {{ $search
                            ? 'Kami tidak menemukan resep yang cocok dengan “' . $search . '”. Coba periksa ejaan atau gunakan kata kunci yang lebih umum.'
                            : 'Belum ada resep yang tersedia. Resep akan muncul setelah ditambahkan ke database.' }}
                    </p>

                    @if ($search)
                        <div class="search-suggestions">
                            <strong>COBA KATA KUNCI LAIN</strong>
                            <div>
                                @foreach (['ayam', 'telur', 'tempe', 'sayur', 'sambal', 'kue'] as $suggestion)
                                    <a href="{{ route('home', ['search' => $suggestion]) }}">⌕ {{ $suggestion }}</a>
                                @endforeach
                            </div>
                        </div>
                        <div class="search-tips">
                            <strong>Tips pencarian</strong>
                            <p>✓ Periksa kembali ejaan kata kunci.</p>
                            <p>✓ Gunakan nama bahan atau kategori, misalnya “ikan” atau “sarapan”.</p>
                            <p>✓ Coba kata yang lebih umum agar hasil lebih banyak.</p>
                        </div>
                        <a class="clear-search empty-search-link" href="{{ route('home') }}">Hapus pencarian</a>
                        <a class="browse-categories-link" href="{{ route('home') }}#kategori">Lihat semua kategori</a>
                    @endif

                </div>

            @endforelse

        </div>

    </div>

</section>


<!-- CTA -->
<section class="cta-section">

    <div class="cta-content">

        <span>🍴 PUNYA RESEP SENDIRI?</span>

        <h2>
            Bagikan Resep Favoritmu
        </h2>

        <p>
            Bagikan resep masakan andalanmu
            dan bantu orang lain menemukan
            inspirasi masakan baru.
        </p>

        <a href="{{ auth()->check() ? route('recipes.create') : route('login') }}" class="cta-button">
            Tambahkan Resep →
        </a>

    </div>

</section>


<!-- FOOTER -->
<footer>

    <div class="footer-container">

        <div class="footer-brand">

            <div class="footer-logo">
                <img src="{{ asset('images/racikara-logo.png') }}" alt="Racikara">
            </div>

            <p>
                Temukan inspirasi masakan lezat
                untuk setiap hari.
            </p>

        </div>


        <div class="footer-column">

            <h3>Menu</h3>

            <a href="/">Beranda</a>
            <a href="#resep">Resep</a>
            <a href="#kategori">Kategori</a>

        </div>


        <div class="footer-column">

            <h3>Bantuan</h3>

            <a href="{{ route('about') }}">Tentang Kami</a>
            <a href="{{ route('contact') }}">Kontak</a>
            <a href="#">FAQ</a>

        </div>

    </div>


    <div class="footer-bottom">
        © 2026 Racikara. Semua hak dilindungi.
    </div>

</footer>

@endsection
