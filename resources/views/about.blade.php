@extends('app')

@section('title', 'Tentang Kami - ResepKita')

@section('content')
<nav class="navbar">
    <div class="nav-container">
        <a href="{{ route('home') }}" class="logo">RESEP<span>KITA</span></a>
        <div class="nav-menu">
            <a href="{{ route('home') }}">Beranda</a>
            <a href="{{ route('home') }}#resep">Resep</a>
            <a href="{{ route('home') }}#kategori">Kategori</a>
            <a href="{{ route('about') }}" class="active">Tentang Kami</a>
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

<main class="about-page">
    <section class="about-hero">
        <div class="about-container about-hero-grid">
            <div>
                <span class="about-label">TENTANG KAMI</span>
                <h1>BERBAGI RESEP,<br><span>MENYATUKAN KELUARGA</span></h1>
                <p>ResepKita lahir dari satu keyakinan sederhana: masakan rumahan yang enak seharusnya mudah dibuat siapa saja. Kami mengumpulkan resep praktis dari dapur Nusantara agar setiap momen bersama keluarga terasa lebih hangat.</p>
                <div class="about-actions">
                    <a class="about-primary-button" href="{{ route('home') }}#resep">Jelajahi Resep</a>
                    <a class="about-secondary-button" href="{{ route('register') }}">Gabung Komunitas</a>
                </div>
            </div>
            <div class="about-illustration" aria-hidden="true">
                <div class="about-plate">🍲</div>
            </div>
        </div>
    </section>

    <section class="about-container about-stats">
        <div><strong>1.200+</strong><span>Resep Pilihan</span></div>
        <div><strong>50 rb+</strong><span>Pengguna Aktif</span></div>
        <div><strong>15</strong><span>Kategori Masakan</span></div>
        <div><strong>4,8/5</strong><span>Rating Pengguna</span></div>
    </section>

    <section class="about-container about-story">
        <div class="story-visual"><div class="story-plate">🍛</div><span><strong>Sejak 2024</strong><br>Dari dapur kecil, untuk dapur semua orang</span></div>
        <div class="story-copy">
            <span class="about-label">CERITA KAMI</span>
            <h2>Dimulai dari meja makan keluarga</h2>
            <p>ResepKita dibuat oleh sekelompok pecinta masakan yang sering kesulitan menemukan resep yang jelas, takarannya pas, dan bahannya mudah dicari. Dari sana kami mulai menyusun koleksi resep yang diuji langsung di dapur.</p>
            <p>Kini ResepKita menjadi tempat berbagi bagi siapa pun yang ingin memasak, mencoba, dan menginspirasi orang lain.</p>
        </div>
    </section>

    <section class="about-container about-values">
        <div class="about-section-heading"><span class="about-label">NILAI KAMI</span><h2>Yang kami pegang setiap hari</h2></div>
        <div class="value-grid">
            <article><span class="value-icon">◷</span><h3>Praktis & Jelas</h3><p>Langkah ringkas, takaran pasti, dan waktu memasak yang realistis supaya pemula pun percaya diri.</p></article>
            <article><span class="value-icon">♧</span><h3>Segar & Sehat</h3><p>Kami mengutamakan bahan segar yang mudah ditemukan di pasar terdekat dan ramah di kantong.</p></article>
            <article><span class="value-icon">♧</span><h3>Komunitas Hangat</h3><p>Setiap orang bisa berbagi, menyimpan, dan memberi apresiasi pada resep favorit satu sama lain.</p></article>
        </div>
    </section>

    <section class="about-container about-team">
        <div class="about-section-heading"><span class="about-label">TIM KAMI</span><h2>Orang-orang di balik ResepKita</h2><p>Tim kecil yang gemar memasak, mencoba, dan berbagi.</p></div>
        <div class="team-grid">
            @foreach ($teamMembers as $member)
                <article>
            @if ($member->photo)
                <img class="team-member-photo" src="{{ asset('storage/' . $member->photo) }}" alt="Foto {{ $member->name }}">
            @else
                <span>{{ $member->initial }}</span>
            @endif
            <strong>{{ $member->name }}</strong>
            <small>{{ $member->division }}</small>
                </article>
            @endforeach
        </div>

        @auth
            @if (auth()->user()->is_admin)
            @if (session('status'))
                <p class="team-status">{{ session('status') }}</p>
            @endif

            <form method="POST" action="{{ route('team-members.update') }}" class="team-edit-form" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <h3>Edit anggota dan divisi</h3>
                <div class="team-edit-grid">
                    @foreach ($teamMembers as $index => $member)
                        <div class="team-edit-card">
                            <input type="hidden" name="members[{{ $index }}][id]" value="{{ $member->id }}">
                            <label>
                                Nama anggota
                                <input type="text" name="members[{{ $index }}][name]" value="{{ old("members.$index.name", $member->name) }}" maxlength="100" required>
                            </label>
                            <label>
                                Divisi
                                <input type="text" name="members[{{ $index }}][division]" value="{{ old("members.$index.division", $member->division) }}" maxlength="100" required>
                            </label>
                            <label>
                                Foto anggota
                                <input type="file" name="members[{{ $index }}][photo]" accept="image/jpeg,image/png,image/webp">
                            </label>
                        </div>
                    @endforeach
                </div>
                <button type="submit">Simpan perubahan</button>
            </form>
            @endif
        @endauth
    </section>

    <section class="about-container about-join">
        <div><h2>Siap memasak bersama kami?</h2><p>Daftar gratis, simpan resep favoritmu, dan bagikan masakan terbaikmu dengan komunitas.</p></div>
        <a href="{{ route('register') }}">Daftar Sekarang</a>
    </section>
</main>

<footer>
    <div class="footer-container"><div class="footer-brand"><div class="footer-logo">RESEP<span>KITA</span></div><p>Temukan inspirasi masakan lezat untuk setiap hari.</p></div><div class="footer-column"><h3>Menu</h3><a href="{{ route('home') }}">Beranda</a><a href="{{ route('home') }}#resep">Resep</a><a href="{{ route('home') }}#kategori">Kategori</a></div><div class="footer-column"><h3>Bantuan</h3><a href="{{ route('about') }}">Tentang Kami</a><a href="{{ route('contact') }}">Kontak</a><a href="#">FAQ</a></div></div>
    <div class="footer-bottom">© 2026 ResepKita. Semua hak dilindungi.</div>
</footer>
@endsection
