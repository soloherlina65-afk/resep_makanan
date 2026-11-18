@extends('app')

@section('title', 'Komunitas Racikara')

@section('content')
<style>
    .community-page { min-height: 100vh; padding: 28px 16px 56px; background: #f4f8ef; color: #30402a; }
    .community-shell { width: min(100%, 1120px); margin: 0 auto; }
    .community-back { display: inline-flex; min-height: 38px; align-items: center; gap: 8px; margin-bottom: 20px; padding: 8px 13px; border: 1px solid #d5e0c5; border-radius: 10px; background: #fff; color: #5f7446; font-size: 13px; font-weight: 700; text-decoration: none; }
    .community-back:hover { background: #e4ebd8; }
    .community-hero { display: flex; align-items: center; justify-content: space-between; gap: 20px; margin-bottom: 22px; padding: 26px 28px; border: 1px solid #d5e0c5; border-radius: 18px; background: linear-gradient(120deg, #fff 30%, #e4ebd8); }
    .community-eyebrow { margin: 0 0 6px; color: #7a8f5c; font-size: 11px; font-weight: 800; letter-spacing: .1em; text-transform: uppercase; }
    .community-hero h1 { margin: 0; color: #30402a; font-size: clamp(26px, 4vw, 36px); font-weight: 800; }
    .community-hero p:last-child { margin: 7px 0 0; color: #5f7446; font-size: 14px; }
    .community-count { flex: 0 0 auto; padding: 9px 13px; border: 1px solid #d5e0c5; border-radius: 999px; background: #fff; color: #4f6638; font-size: 12px; font-weight: 800; }
    .community-search { display: flex; gap: 8px; margin: 0 0 18px; }
    .community-search input { width: min(100%, 420px); min-height: 42px; padding: 9px 13px; border: 1px solid #d5e0c5; border-radius: 10px; background: #fff; color: #30402a; font: inherit; font-size: 13px; }
    .community-search button { min-height: 42px; padding: 9px 16px; border: 0; border-radius: 10px; background: #7a8f5c; color: #fff; font: inherit; font-size: 13px; font-weight: 700; cursor: pointer; }
    .community-search button:hover { background: #5f7446; }
    .community-grid { display: grid; grid-template-columns: repeat(3, minmax(0,1fr)); gap: 16px; }
    .community-card { min-width: 0; padding: 18px; border: 1px solid #d5e0c5; border-radius: 16px; background: #fff; box-shadow: 0 7px 20px rgba(48,64,42,.05); }
    .community-person { display: flex; min-width: 0; align-items: center; gap: 13px; color: inherit; text-decoration: none; }
    .community-avatar { width: 58px; height: 58px; flex: 0 0 58px; border: 2px solid #e4ebd8; background: #e4ebd8; color: #5f7446; font-size: 20px; }
    .community-person-copy { min-width: 0; }
    .community-person-copy h2 { overflow: hidden; margin: 0; color: #30402a; font-size: 16px; font-weight: 800; text-overflow: ellipsis; white-space: nowrap; }
    .community-person-copy p { overflow: hidden; margin: 5px 0 0; color: #71806a; font-size: 12px; text-overflow: ellipsis; white-space: nowrap; }
    .community-stats { display: flex; gap: 16px; margin: 16px 0; padding: 12px 0; border-top: 1px solid #eef2e8; border-bottom: 1px solid #eef2e8; color: #71806a; font-size: 11px; }
    .community-stats strong { color: #30402a; }
    .community-actions { display: flex; gap: 8px; }
    .community-actions form { display: flex; flex: 1; margin: 0; }
    .community-button { display: inline-flex; min-height: 36px; flex: 1; align-items: center; justify-content: center; padding: 8px 10px; border: 1px solid #7a8f5c; border-radius: 999px; background: #7a8f5c; color: #fff; font: inherit; font-size: 12px; font-weight: 700; text-decoration: none; cursor: pointer; }
    .community-actions .community-button { width: 100%; }
    .community-button:hover { background: #5f7446; color: #fff; }
    .community-button.secondary { border-color: #d5e0c5; background: #fff; color: #4f6638; }
    .community-button.secondary:hover { background: #e4ebd8; }
    .community-empty { padding: 48px 20px; border: 1px dashed #b7c8a0; border-radius: 16px; background: rgba(255,255,255,.7); color: #71806a; text-align: center; }
    .community-empty h2 { margin: 0 0 7px; color: #30402a; font-size: 19px; }
    .community-empty p { margin: 0 auto 16px; max-width: 520px; font-size: 13px; line-height: 1.6; }
    .community-pagination { display: flex; align-items: center; justify-content: center; gap: 14px; margin-top: 20px; color: #71806a; font-size: 12px; }
    .community-pagination a { padding: 8px 12px; border: 1px solid #d5e0c5; border-radius: 9px; background: #fff; color: #4f6638; font-weight: 700; text-decoration: none; }
    .community-pagination a[aria-disabled="true"] { color: #aab49f; pointer-events: none; }
    @media (max-width: 850px) { .community-grid { grid-template-columns: repeat(2, minmax(0,1fr)); } }
    @media (max-width: 560px) {
        .community-page { padding: 18px 12px 36px; }
        .community-hero { align-items: flex-start; flex-direction: column; padding: 20px; }
        .community-search input { min-width: 0; flex: 1; }
        .community-grid { grid-template-columns: 1fr; gap: 12px; }
    }
</style>

<main class="community-page">
    <div class="community-shell">
        <a href="{{ route('home') }}" class="community-back"><span aria-hidden="true">&larr;</span> Kembali ke beranda</a>

        <header class="community-hero">
            <div>
                <p class="community-eyebrow">Racikara · Komunitas</p>
                <h1>Temukan teman memasak</h1>
                <p>Ikuti pengguna lain dan lihat resep yang mereka bagikan.</p>
            </div>
            <span class="community-count">{{ number_format($users->total()) }} anggota</span>
        </header>

        <form class="community-search" method="GET" action="{{ route('users.community') }}">
            <input type="search" name="search" value="{{ $search }}" placeholder="Cari nama pengguna..." aria-label="Cari nama pengguna">
            <button type="submit">Cari</button>
        </form>

        <div class="community-grid">
            @forelse ($users as $member)
                <article class="community-card">
                    <a href="{{ route('users.show', $member) }}" class="community-person">
                        <x-user-avatar :user="$member" class="community-avatar" />
                        <span class="community-person-copy">
                            <h2>{{ $member->name }}</h2>
                            <p>{{ $member->bio ?: 'Berbagi inspirasi masakan di Racikara.' }}</p>
                        </span>
                    </a>
                    <div class="community-stats">
                        <span><strong>{{ number_format($member->recipes_count) }}</strong> resep</span>
                        <span><strong>{{ number_format($member->followers_count) }}</strong> pengikut</span>
                    </div>
                    <div class="community-actions">
                        <a href="{{ route('users.show', $member) }}" class="community-button secondary">Lihat profil</a>
                        @auth
                            @if (in_array((int) $member->id, $followingUserIds, true))
                                <form method="POST" action="{{ route('users.unfollow', $member) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="community-button secondary">Berhenti mengikuti</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('users.follow', $member) }}">
                                    @csrf
                                    <button type="submit" class="community-button">+ Ikuti</button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="community-button">+ Ikuti</a>
                        @endauth
                    </div>
                </article>
            @empty
                <section class="community-empty">
                    <h2>{{ $search ? 'Pengguna tidak ditemukan' : 'Belum ada anggota lain' }}</h2>
                    <p>
                        @if ($search)
                            Coba kata kunci nama yang berbeda.
                        @else
                            Akun yang sedang digunakan tidak ditampilkan di daftar ini. Minta pengguna lain mendaftar, lalu mereka akan muncul di sini.
                        @endif
                    </p>
                    @if ($search)
                        <a href="{{ route('users.community') }}" class="community-button secondary">Lihat semua anggota</a>
                    @elseif (! auth()->check())
                        <a href="{{ route('register') }}" class="community-button">Daftar sebagai pengguna</a>
                    @endif
                </section>
            @endforelse
        </div>

        @if ($users->hasPages())
            <nav class="community-pagination" aria-label="Navigasi anggota">
                <a href="{{ $users->previousPageUrl() ?: '#' }}" @if ($users->onFirstPage()) aria-disabled="true" @endif>Sebelumnya</a>
                <span>Halaman {{ $users->currentPage() }} dari {{ $users->lastPage() }}</span>
                <a href="{{ $users->nextPageUrl() ?: '#' }}" @unless ($users->hasMorePages()) aria-disabled="true" @endunless>Berikutnya</a>
            </nav>
        @endif
    </div>
</main>
@endsection
