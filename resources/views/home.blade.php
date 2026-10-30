
@extends('app')

@section('title', 'Resep Kita - Temukan Inspirasi Masakan')

@section('content')

<!-- NAVBAR -->
<nav class="navbar">
    <div class="nav-container">

        <a href="/" class="logo">
            RESEP<span>KITA</span>
        </a>

        <div class="nav-menu">
            <a href="/" class="active">Beranda</a>
            <a href="#resep">Resep</a>
            <a href="#kategori">Kategori</a>
        </div>

        <div class="nav-actions">
            <a href="#" class="login-btn">Masuk</a>
            <a href="#" class="register-btn">Daftar</a>
        </div>

    </div>
</nav>


<!-- HERO -->
<section class="hero">

    <div class="hero-container">

        <div class="hero-content">

            <div class="hero-label">
                🍳 SPESIAL HARI INI
            </div>

            <h1>
                TEMUKAN INSPIRASI
                <span>MASAKAN FAVORITMU</span>
            </h1>

            <p>
                Temukan berbagai resep masakan lezat,
                praktis, dan mudah dibuat untuk menemani
                setiap momen bersama keluarga.
            </p>

            <form action="#resep" method="GET" class="search-box">
                <input
                    type="text"
                    name="search"
                    placeholder="Cari resep masakan..."
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

                <a href="#resep" class="category-card">

                    <div class="category-icon">
                        🍽️
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


<!-- RESEP TERBARU -->
<section class="recipe-section" id="resep">

    <div class="section-container">

        <div class="section-header">

            <div>
                <span class="section-label">INSPIRASI MASAK</span>
                <h2>Resep Terbaru</h2>
            </div>

            <a href="#resep" class="see-all">
                Lihat Semua →
            </a>

        </div>


        <div class="recipe-grid">

            @forelse ($recipes as $recipe)

                <!-- KARTU RESEP -->
                <a
                    href="{{ route('recipes.show', $recipe->id) }}"
                    class="recipe-card-link"
                    style="text-decoration: none; color: inherit; display: block;"
                >

                    <div class="recipe-card">

                        <div class="recipe-image">

                            @if ($recipe->foto)

                                <img
                                    src="{{ asset('storage/' . $recipe->foto) }}"
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
                                ♡
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

                </a>

            @empty

                <div class="empty-recipe">

                    <div style="font-size:50px;">
                        🍲
                    </div>

                    <h3>Belum Ada Resep</h3>

                    <p>
                        Belum ada resep yang tersedia.
                        Resep akan muncul setelah ditambahkan ke database.
                    </p>

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

        <a href="#" class="cta-button">
            Tambahkan Resep →
        </a>

    </div>

</section>


<!-- FOOTER -->
<footer>

    <div class="footer-container">

        <div class="footer-brand">

            <div class="footer-logo">
                RESEP<span>KITA</span>
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

            <a href="#">Tentang Kami</a>
            <a href="#">Kontak</a>
            <a href="#">FAQ</a>

        </div>

    </div>


    <div class="footer-bottom">
        © 2026 Resep Kita. Semua hak dilindungi.
    </div>

</footer>

@endsection
