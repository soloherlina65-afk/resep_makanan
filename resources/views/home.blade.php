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

            <a href="#" class="category-card">
                <div class="category-icon">🍚</div>
                <h3>Masakan Indonesia</h3>
                <p>Resep nusantara</p>
            </a>

            <a href="#" class="category-card">
                <div class="category-icon">🍗</div>
                <h3>Ayam</h3>
                <p>Olahan ayam</p>
            </a>

            <a href="#" class="category-card">
                <div class="category-icon">🍜</div>
                <h3>Mie & Pasta</h3>
                <p>Berbagai olahan mie</p>
            </a>

            <a href="#" class="category-card">
                <div class="category-icon">🍰</div>
                <h3>Kue & Dessert</h3>
                <p>Manis dan lezat</p>
            </a>

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

            <!-- RESEP 1 -->
            <div class="recipe-card">

                <div class="recipe-image">
                    <img
                        src="https://images.unsplash.com/photo-1603133872878-684f208fb84b?auto=format&fit=crop&w=700&q=80"
                        alt="Nasi Goreng"
                    >

                    <span class="recipe-badge">
                        Mudah
                    </span>

                    <button class="favorite-btn">
                        ♡
                    </button>
                </div>

                <div class="recipe-content">

                    <span class="recipe-category">
                        MASAKAN INDONESIA
                    </span>

                    <h3>Nasi Goreng Spesial</h3>

                    <p>
                        Nasi goreng sederhana dengan
                        rasa gurih dan nikmat.
                    </p>

                    <div class="recipe-info">
                        <span>⏱ 20 Menit</span>
                        <span>👤 2 Porsi</span>
                    </div>

                </div>

            </div>


            <!-- RESEP 2 -->
            <div class="recipe-card">

                <div class="recipe-image">

                    <img
                        src="https://images.unsplash.com/photo-1559847844-5315695dadae?auto=format&fit=crop&w=700&q=80"
                        alt="Chicken Katsu"
                    >

                    <span class="recipe-badge">
                        Sedang
                    </span>

                    <button class="favorite-btn">
                        ♡
                    </button>

                </div>

                <div class="recipe-content">

                    <span class="recipe-category">
                        AYAM
                    </span>

                    <h3>Chicken Katsu</h3>

                    <p>
                        Ayam crispy dengan tekstur
                        renyah di luar dan lembut di dalam.
                    </p>

                    <div class="recipe-info">
                        <span>⏱ 35 Menit</span>
                        <span>👤 3 Porsi</span>
                    </div>

                </div>

            </div>


            <!-- RESEP 3 -->
            <div class="recipe-card">

                <div class="recipe-image">

                    <img
                        src="https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&w=700&q=80"
                        alt="Mie Goreng"
                    >

                    <span class="recipe-badge">
                        Mudah
                    </span>

                    <button class="favorite-btn">
                        ♡
                    </button>

                </div>

                <div class="recipe-content">

                    <span class="recipe-category">
                        MIE
                    </span>

                    <h3>Mie Goreng Spesial</h3>

                    <p>
                        Mie goreng dengan bumbu
                        sederhana yang menggugah selera.
                    </p>

                    <div class="recipe-info">
                        <span>⏱ 15 Menit</span>
                        <span>👤 2 Porsi</span>
                    </div>

                </div>

            </div>

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
