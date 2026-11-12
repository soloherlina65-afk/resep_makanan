@extends('app')

@section('title', 'Kontak - ResepKita')

@section('content')
<nav class="navbar">
    <div class="nav-container">
        <a href="{{ route('home') }}" class="logo">RESEP<span>KITA</span></a>
        <div class="nav-menu">
            <a href="{{ route('home') }}">Beranda</a>
            <a href="{{ route('home') }}#resep">Resep</a>
            <a href="{{ route('home') }}#kategori">Kategori</a>
            <a href="{{ route('about') }}">Tentang Kami</a>
            <a href="{{ route('contact') }}" class="active">Kontak</a>
        </div>
        <div class="nav-actions">
            @auth
                <a href="{{ route('dashboard') }}" class="login-btn">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="login-btn">Masuk</a>
                <a href="{{ route('register') }}" class="register-btn">Daftar</a>
            @endauth
        </div>
    </div>
</nav>

<main class="contact-page">
    <section class="contact-hero">
        <div class="contact-container">
            <span class="about-label">HUBUNGI KAMI</span>
            <h1>ADA PERTANYAAN?<br><span>KAMI SIAP MEMBANTU</span></h1>
            <p>Punya saran resep, kendala akun, atau ingin berkolaborasi? Tulis pesanmu dan tim ResepKita akan membalas secepatnya.</p>
        </div>
    </section>

    <section class="contact-container contact-main">
        <div class="contact-details">
            <article><span class="contact-icon">✉</span><div><strong>Email</strong><p>halo@resepkita.id<br><small>Dibalas dalam 1×24 jam</small></p></div></article>
            <article><span class="contact-icon">◌</span><div><strong>WhatsApp</strong><p>+62 812-0000-0000<br><small>Chat langsung dengan admin</small></p></div></article>
            <article><span class="contact-icon">⌖</span><div><strong>Alamat</strong><p>Jl. Contoh No. 123<br><small>Kota Anda, Indonesia</small></p></div></article>
            <article><span class="contact-icon">◷</span><div><strong>Jam Layanan</strong><p>Senin – Jumat, 09.00 – 17.00 WIB<br><small>Sabtu, 09.00 – 13.00 WIB</small></p></div></article>
            <div class="contact-socials"><a href="#" aria-label="Instagram">◎</a><a href="#" aria-label="YouTube">▶</a><a href="#" aria-label="Facebook">f</a></div>
        </div>

        <form class="contact-form" action="mailto:halo@resepkita.id" method="post" enctype="text/plain">
            <h2>Kirim Pesan</h2>
            <p>Isi formulir di bawah ini dan kami akan menghubungi lewat email.</p>
            <div class="contact-form-grid">
                <label>Nama Lengkap<input type="text" name="nama" placeholder="Contoh: Sarah Abdullah" required></label>
                <label>Alamat Email<input type="email" name="email" placeholder="nama@email.com" required></label>
            </div>
            <label>Subjek<select name="subjek"><option>Saran resep</option><option>Kendala akun</option><option>Kerja sama</option><option>Pertanyaan umum</option></select></label>
            <label>Pesan<textarea name="pesan" rows="5" placeholder="Tulis pesanmu di sini..." required></textarea></label>
            <button type="submit">➤ &nbsp; Kirim Pesan</button>
            <small>Data kamu aman dan hanya dipakai untuk membalas pesan.</small>
        </form>
    </section>

    <section class="contact-container faq-section" id="faq">
        <div class="about-section-heading"><span class="about-label">PERTANYAAN UMUM</span><h2>Mungkin jawabannya ada di sini</h2></div>
        <div class="faq-list">
            <details open><summary>Bagaimana cara mengirim resep buatanku?<span>−</span></summary><p>Masuk ke akunmu, buka menu My Recipes, lalu pilih Tambah Resep. Isi bahan, langkah, dan foto, kemudian kirim untuk ditinjau tim kami.</p></details>
            <details><summary>Saya lupa password, apa yang harus dilakukan?<span>+</span></summary><p>Pilih menu Masuk lalu klik Lupa Password. Ikuti petunjuk yang dikirim ke alamat email akunmu.</p></details>
            <details><summary>Apakah ResepKita bisa diajak kerja sama?<span>+</span></summary><p>Tentu. Kirimkan detail kerja sama melalui formulir kontak di atas.</p></details>
        </div>
    </section>

    <section class="contact-container contact-join">
        <div><h2>Belum punya akun?</h2><p>Daftar gratis dan mulai simpan resep favoritmu hari ini.</p></div>
        <a href="{{ route('register') }}">Daftar Sekarang</a>
    </section>
</main>

<footer>
    <div class="footer-container"><div class="footer-brand"><div class="footer-logo">RESEP<span>KITA</span></div><p>Temukan inspirasi masakan lezat untuk setiap hari.</p></div><div class="footer-column"><h3>Menu</h3><a href="{{ route('home') }}">Beranda</a><a href="{{ route('home') }}#resep">Resep</a><a href="{{ route('home') }}#kategori">Kategori</a></div><div class="footer-column"><h3>Bantuan</h3><a href="{{ route('about') }}">Tentang Kami</a><a href="{{ route('contact') }}">Kontak</a><a href="#faq">FAQ</a></div></div>
    <div class="footer-bottom">© 2026 ResepKita. Semua hak dilindungi.</div>
</footer>
@endsection
