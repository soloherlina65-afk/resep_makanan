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
            <a href="#">Resep</a>
            <a href="#">Kategori</a>
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

            <!-- SEARCH -->
            <div class="search-box">
                <input
                    type="text"
                    placeholder="Cari resep masakan..."
                >

                <button>
                    🔍 Cari Resep
                </button>
            </div>

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
<section class="category-section">

    <div class="section-container">

        <div class="section-header">
            <div>
                <span class="section-label">JELAJAHI</span>
                <h2>Kategori Masakan</h2>
            </div>

            <a href="#" class="see-all">
                Lihat Semua →
            </a>
        </div>


        <div class="category-grid">

            @forelse ($categories as $category)

                <a href="#" class="category-card">

                    <div class="category-icon">🍽️</div>

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
<section class="recipe-section">

    <div class="section-container">

        <div class="section-header">

            <div>
                <span class="section-label">INSPIRASI MASAK</span>
                <h2>Resep Terbaru</h2>
            </div>

            <a href="#" class="see-all">
                Lihat Semua →
            </a>

        </div>


        <div class="recipe-grid">

            @forelse ($recipes as $recipe)

                <div class="recipe-card">

                    <div class="recipe-image">

                        @if ($recipe->foto)
                            <img
                                src="{{ asset('storage/' . $recipe->foto) }}"
                                alt="{{ $recipe->nama_resep }}"
                            >
                        @else
                            <div class="recipe-placeholder" style="display: flex; align-items: center; justify-content: center; height: 200px; background: #f3f4f6; font-size: 3rem;">
                                🍲
                            </div>
                        @endif

                        <span class="recipe-badge">
                            {{ $recipe->tingkat_kesulitan }}
                        </span>

                        <button class="favorite-btn">
                            ♡
                        </button>

                    </div>

                    <div class="recipe-content">

                        <span class="recipe-category">
                            {{ $recipe->category->nama_kategori ?? 'Umum' }}
                        </span>

                        <h3>{{ $recipe->nama_resep }}</h3>

                        <p>
                            {{ $recipe->deskripsi ?? 'Resep masakan pilihan untuk dicoba di rumah.' }}
                        </p>

                        <div class="recipe-info">
                            <span>⏱ {{ $recipe->waktu_masak }} Menit</span>
                            <span>👤 {{ $recipe->porsi }} Porsi</span>
                        </div>

                    </div>

                </div>

            @empty

                <p>
                    Belum ada resep yang tersedia.
                    Resep akan muncul setelah ditambahkan ke database.
                </p>

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
            <a href="#">Resep</a>
            <a href="#">Kategori</a>

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