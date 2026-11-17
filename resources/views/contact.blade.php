@extends('app')

@section('title', 'Kontak - Racikara')

@section('content')
<nav class="navbar">
    <div class="nav-container">
        <a href="{{ route('home') }}" class="logo"><img src="{{ asset('images/racikara-logo.png') }}" alt="Racikara"></a>
        <div class="nav-menu" id="primary-navigation">
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
        <button class="nav-toggle" type="button" aria-label="Buka menu" aria-expanded="false" aria-controls="primary-navigation" data-mobile-nav-toggle>
            <span></span><span></span><span></span>
        </button>
    </div>
</nav>

<main class="contact-page">
    <section class="contact-hero">
        <div class="contact-container">
            <span class="about-label">HUBUNGI KAMI</span>
            <h1>ADA PERTANYAAN?<br><span>KAMI SIAP MEMBANTU</span></h1>
            <p>Punya saran resep, kendala akun, atau ingin berkolaborasi? Tulis pesanmu dan tim Racikara akan membalas secepatnya.</p>
        </div>
    </section>

    <section class="contact-container contact-main">
        <div class="contact-details">
            <article>
                <span class="contact-icon">✉</span>
                <div>
                    <strong>Email Racikara</strong>
                    <p>
                        @if ($contactEmail)
                            <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a>
                        @else
                            <span>Alamat email belum dikonfigurasi</span>
                        @endif
                        <br><small>Kirim pesan melalui formulir di samping.</small>
                    </p>
                </div>
            </article>
            <article>
                <span class="contact-icon">↗</span>
                <div>
                    <strong>Pesan langsung ke tim</strong>
                    <p>Pesan formulir dikirim ke email pengelola dan balasan dapat dikirim langsung ke alamat emailmu.</p>
                </div>
            </article>
        </div>

        <form class="contact-form" action="{{ route('contact.send') }}" method="POST">
            @csrf
            <h2>Kirim Pesan</h2>
            <p>Isi formulir ini. Pesan akan dikirim melalui email Racikara.</p>
            @if (session('success'))
                <div class="contact-alert contact-alert-success" role="status">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="contact-alert contact-alert-error" role="alert">{{ session('error') }}</div>
            @endif
            <div class="contact-form-grid">
                <label>
                    Nama Lengkap
                    <input type="text" name="nama" value="{{ old('nama') }}" maxlength="120" autocomplete="name" placeholder="Contoh: Sarah Abdullah" required>
                    @error('nama') <small class="contact-field-error">{{ $message }}</small> @enderror
                </label>
                <label>
                    Alamat Email
                    <input type="email" name="email" value="{{ old('email') }}" maxlength="254" autocomplete="email" placeholder="nama@email.com" required>
                    @error('email') <small class="contact-field-error">{{ $message }}</small> @enderror
                </label>
            </div>
            <label>
                Subjek
                <select name="subjek" required>
                    <option value="">Pilih subjek pesan</option>
                    <option value="recipe_suggestion" @selected(old('subjek') === 'recipe_suggestion')>Saran resep</option>
                    <option value="account_issue" @selected(old('subjek') === 'account_issue')>Kendala akun</option>
                    <option value="partnership" @selected(old('subjek') === 'partnership')>Kerja sama</option>
                    <option value="general" @selected(old('subjek') === 'general')>Pertanyaan umum</option>
                </select>
                @error('subjek') <small class="contact-field-error">{{ $message }}</small> @enderror
            </label>
            <label>
                Pesan
                <textarea name="pesan" rows="5" maxlength="5000" placeholder="Tulis pesanmu di sini..." required>{{ old('pesan') }}</textarea>
                @error('pesan') <small class="contact-field-error">{{ $message }}</small> @enderror
            </label>
            <button type="submit">➤ &nbsp; Kirim Pesan</button>
            <small>Informasi yang kamu kirim digunakan untuk menanggapi pesan ini.</small>
        </form>
    </section>

    <section class="contact-container faq-section" id="faq">
        <div class="about-section-heading"><span class="about-label">PERTANYAAN UMUM</span><h2>Mungkin jawabannya ada di sini</h2></div>
        <div class="faq-list">
            <details open><summary>Bagaimana cara mengirim resep buatanku?<span>−</span></summary><p>Masuk ke akunmu, buka menu My Recipes, lalu pilih Tambah Resep. Isi bahan, langkah, dan foto, kemudian kirim untuk ditinjau tim kami.</p></details>
            <details><summary>Saya lupa password, apa yang harus dilakukan?<span>+</span></summary><p>Pilih menu Masuk lalu klik Lupa Password. Ikuti petunjuk yang dikirim ke alamat email akunmu.</p></details>
            <details><summary>Apakah Racikara bisa diajak kerja sama?<span>+</span></summary><p>Tentu. Kirimkan detail kerja sama melalui formulir kontak di atas.</p></details>
        </div>
    </section>

    <section class="contact-container contact-join">
        <div><h2>Belum punya akun?</h2><p>Daftar gratis dan mulai simpan resep favoritmu hari ini.</p></div>
        <a href="{{ route('register') }}">Daftar Sekarang</a>
    </section>
</main>

<footer>
    <div class="footer-container"><div class="footer-brand"><div class="footer-logo"><img src="{{ asset('images/racikara-logo.png') }}" alt="Racikara"></div><p>Temukan inspirasi masakan lezat untuk setiap hari.</p></div><div class="footer-column"><h3>Menu</h3><a href="{{ route('home') }}">Beranda</a><a href="{{ route('home') }}#resep">Resep</a><a href="{{ route('home') }}#kategori">Kategori</a></div><div class="footer-column"><h3>Bantuan</h3><a href="{{ route('about') }}">Tentang Kami</a><a href="{{ route('contact') }}">Kontak</a><a href="#faq">FAQ</a></div></div>
    <div class="footer-bottom">© 2026 Racikara. Semua hak dilindungi.</div>
</footer>
@endsection
