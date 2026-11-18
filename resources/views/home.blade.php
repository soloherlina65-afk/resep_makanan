
@extends('app')

@section('title', 'Racikara - Temukan Inspirasi Masakan')

@section('content')

<style>
    .nav-logout-form { margin: 0; }
    .nav-logout-button { border: 0; cursor: pointer; font: inherit; }
    .recipe-social-row { display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-top: 10px; color: #7a8f5c; font-size: 12px; }
    .recipe-owner { min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .recipe-owner a:hover { text-decoration: underline !important; }
    .recipe-social-row form { flex: 0 0 auto; margin: 0; }
    .recipe-follow-button { display: inline-flex; min-height: 28px; align-items: center; justify-content: center; padding: 5px 10px; border: 1px solid #d5e0c5; border-radius: 999px; background: #e4ebd8; color: #4f6638; font: inherit; font-size: 11px; font-weight: 800; text-decoration: none; cursor: pointer; white-space: nowrap; }
    .recipe-follow-button:hover { border-color: #7a8f5c; background: #7a8f5c; color: #fff; }
    .recipe-following-label { flex: 0 0 auto; color: #5f7446; font-size: 11px; font-weight: 800; white-space: nowrap; }
    @media (max-width: 480px) { .recipe-social-row { align-items: flex-start; } }
</style>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="nav-container">

        <a href="/" class="logo">
            <img src="{{ asset('images/racikara-logo.png') }}" alt="Racikara">
        </a>

        <div class="nav-menu" id="primary-navigation">
            <a href="/" class="{{ $hasFilters ? '' : 'active' }}">Beranda</a>
            <a href="#resep" class="{{ $hasFilters ? 'active' : '' }}">Resep</a>
            <a href="#kategori" class="{{ $categoryId !== null ? 'active' : '' }}">Kategori</a>
            <a href="{{ route('users.community') }}">Komunitas</a>
        </div>

        <div class="nav-actions">
            @auth
                <a href="{{ route('dashboard') }}" class="login-btn">Dashboard Saya</a>
                <form method="POST" action="{{ route('logout') }}" class="nav-logout-form">
                    @csrf
                    <button type="submit" class="register-btn nav-logout-button">Keluar</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="login-btn">Masuk</a>
                <a href="{{ route('register') }}" class="register-btn">Daftar</a>
            @endauth
        </div>

        <button class="nav-toggle" type="button" aria-label="Buka menu" aria-expanded="false" aria-controls="primary-navigation" data-mobile-nav-toggle>
            <span></span><span></span><span></span>
        </button>
    </div>
</nav>


<!-- HERO -->
<section class="hero {{ $hasFilters ? 'search-hero' : '' }}">

    <div class="hero-container">

        <div class="hero-content">

            @if (!$hasFilters)
                <div class="hero-label">🍳 SPESIAL HARI INI</div>
            @endif

            <h1>
                TEMUKAN INSPIRASI
                <span>MASAKAN FAVORITMU</span>
            </h1>

            @if (!$hasFilters)
                <p>
                    Temukan berbagai resep masakan lezat,
                    praktis, dan mudah dibuat untuk menemani
                    setiap momen bersama keluarga.
                </p>
            @endif

            <div class="search-autocomplete">
                <form action="{{ route('home') }}" method="GET" class="search-box">
                    <input type="hidden" name="category_id" value="{{ $categoryId }}">
                    <input type="hidden" name="max_cook_time" value="{{ $maxCookTime }}">
                    <input type="hidden" name="difficulty" value="{{ $difficulty }}">
                    <input type="hidden" name="sort" value="{{ $sort }}">
                    <input
                        id="recipe-search"
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Cari nama resep, bahan, atau kategori..."
                        aria-label="Cari nama resep, bahan, atau kategori"
                        aria-autocomplete="list"
                        aria-controls="recipe-autocomplete-results"
                        aria-expanded="false"
                        autocomplete="off"
                        data-suggestions-url="{{ route('recipes.suggestions') }}"
                    >

                    <button type="submit">
                        🔍 Cari Resep
                    </button>
                </form>

                <div
                    id="recipe-autocomplete-results"
                    class="recipe-autocomplete-results"
                    role="listbox"
                    aria-label="Saran resep"
                    hidden
                ></div>
            </div>

        </div>

    </div>

</section>


<!-- RESEP TERBARU -->
<section class="recipe-section {{ $search ? 'search-results-section' : '' }}" id="resep">

    <div class="section-container">

        @if ($hasFilters)
            <div class="search-result-banner">
                <div>
                    <span class="section-label">{{ $search ? 'HASIL PENCARIAN' : 'HASIL FILTER' }}</span>
                    <h2>{{ $search ? 'Resep untuk “' . $search . '”' : 'Resep pilihanmu' }}</h2>
                    <p>{{ $recipes->total() }} resep ditemukan. Pilih resep untuk melihat bahan dan langkah memasaknya.</p>
                </div>
                <a class="clear-search" href="{{ route('home') }}">× Hapus filter</a>
            </div>
        @endif

        <form action="{{ route('home') }}" method="GET" class="recipe-filters">
            <input type="hidden" name="search" value="{{ $search }}">

            <label id="kategori" style="scroll-margin-top: 130px;">
                <span>Kategori</span>
                <select name="category_id">
                    <option value="">Semua kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected($categoryId === $category->id)>
                            {{ $category->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </label>

            <label>
                <span>Waktu maksimal</span>
                <select name="max_cook_time">
                    <option value="">Semua waktu</option>
                    @foreach ([30, 60, 90, 120] as $minutes)
                        <option value="{{ $minutes }}" @selected($maxCookTime === $minutes)>
                            Maks. {{ $minutes }} menit
                        </option>
                    @endforeach
                </select>
            </label>

            <label>
                <span>Tingkat kesulitan</span>
                <select name="difficulty">
                    <option value="">Semua tingkat</option>
                    @foreach (['Mudah', 'Sedang', 'Sulit'] as $level)
                        <option value="{{ $level }}" @selected($difficulty === $level)>{{ $level }}</option>
                    @endforeach
                </select>
            </label>

            <label>
                <span>Urutkan</span>
                <select name="sort">
                    <option value="latest" @selected($sort === 'latest')>Terbaru</option>
                    <option value="rating" @selected($sort === 'rating')>Rating tertinggi</option>
                </select>
            </label>

            <button type="submit" class="filter-submit">Terapkan</button>
        </form>

        <div class="section-header">

            <div>
                <span class="section-label">{{ $hasFilters ? 'PILIHAN RESEP' : 'INSPIRASI MASAK' }}</span>
                <h2>{{ $hasFilters ? 'Temukan Resep Favoritmu' : 'Resep Terbaru' }}</h2>
            </div>

            @if (!$hasFilters)
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

                            <div class="recipe-social-row" onclick="event.stopPropagation()" onkeydown="event.stopPropagation()">
                                <span class="recipe-owner">
                                👤 Diposting oleh
                                <strong style="color:#5f7446;">
                                    @if ($recipe->user)
                                        <a
                                            href="{{ route('users.show', $recipe->user) }}"
                                            onclick="event.stopPropagation()"
                                            onkeydown="event.stopPropagation()"
                                            style="color:inherit; text-decoration:none;"
                                        >{{ $recipe->user->name }}</a>
                                    @else
                                        Tim Racikara
                                    @endif
                                </strong>
                                </span>
                                @if ($recipe->user && auth()->id() !== $recipe->user->id)
                                    @auth
                                        @if (in_array((int) $recipe->user->id, $followingUserIds, true))
                                            <span class="recipe-following-label">✓ Mengikuti</span>
                                        @else
                                            <form method="POST" action="{{ route('users.follow', $recipe->user) }}">
                                                @csrf
                                                <button class="recipe-follow-button" type="submit">+ Ikuti</button>
                                            </form>
                                        @endif
                                    @else
                                        <a class="recipe-follow-button" href="{{ route('login') }}">+ Ikuti</a>
                                    @endauth
                                @endif
                            </div>

                            <div class="recipe-info">

                                <span>
                                    ⏱ {{ $recipe->waktu_masak }} Menit
                                </span>

                                <span>
                                    👤 {{ $recipe->porsi }} Porsi
                                </span>

                            </div>

                            @if ($recipes->hasPages())
                                <div class="recipe-pagination">{{ $recipes->links() }}</div>
                            @endif

                        </div>

                    </div>

                </div>

            @empty

                <div class="empty-recipe">

                    <div style="font-size:50px;">
                        ♨
                    </div>

                    <h3>{{ $hasFilters ? 'Resep Tidak Ditemukan' : 'Belum Ada Resep' }}</h3>

                    <p>
                        @if ($search)
                            Kami tidak menemukan resep yang cocok dengan “{{ $search }}”. Coba kata kunci yang lebih umum.
                        @elseif ($hasFilters)
                            Tidak ada resep yang cocok dengan filter ini. Coba ubah kategori, waktu, atau tingkat kesulitannya.
                        @else
                            Belum ada resep yang tersedia. Resep akan muncul setelah ditambahkan ke database.
                        @endif
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

<script src="{{ asset('js/recipe-search.js') }}" defer></script>

@endsection
