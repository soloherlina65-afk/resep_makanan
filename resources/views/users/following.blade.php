@extends('app')

@section('title', 'Pengguna yang Diikuti - RACIKARA')

@section('content')
<style>
    .following-page { min-height: 100vh; padding: clamp(24px, 5vw, 52px) 16px; background: #f4f8ef; color: #30402a; }
    .following-shell { width: min(100%, 900px); margin: 0 auto; }
    .following-back { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 22px; padding: 9px 14px; border: 1px solid #d5e0c5; border-radius: 10px; background: #fff; color: #5f7446; font-size: 13px; font-weight: 700; text-decoration: none; transition: background .2s, border-color .2s, transform .2s; }
    .following-back:hover { transform: translateX(-2px); border-color: #b7c8a0; background: #f4f8ef; }
    .following-back:focus-visible, .following-action:focus-visible, .following-profile:focus-visible, .following-page-link:focus-visible { outline: 3px solid rgba(122, 143, 92, .3); outline-offset: 2px; }
    .following-heading { display: flex; align-items: flex-end; justify-content: space-between; gap: 18px; margin-bottom: 20px; }
    .following-kicker { margin: 0 0 5px; color: #7a8f5c; font-size: 11px; font-weight: 800; letter-spacing: .1em; text-transform: uppercase; }
    .following-heading h1 { margin: 0; color: #30402a; font-size: clamp(25px, 4vw, 34px); font-weight: 800; line-height: 1.15; }
    .following-heading p { margin: 7px 0 0; color: #5f7446; font-size: 14px; }
    .following-count { flex: 0 0 auto; padding: 8px 13px; border: 1px solid #d5e0c5; border-radius: 999px; background: #fff; color: #5f7446; font-size: 12px; font-weight: 700; }
    .following-card { overflow: hidden; border: 1px solid #d5e0c5; border-radius: 18px; background: #fff; box-shadow: 0 12px 30px rgba(48, 64, 42, .07); }
    .following-row { display: flex; align-items: center; justify-content: space-between; gap: 18px; padding: 18px 22px; border-bottom: 1px solid #eef2e8; }
    .following-row:last-of-type { border-bottom: 0; }
    .following-person { display: flex; min-width: 0; align-items: center; gap: 15px; color: inherit; text-decoration: none; }
    .following-avatar { width: 58px; height: 58px; flex: 0 0 58px; overflow: hidden; border: 2px solid #e4ebd8; border-radius: 50%; object-fit: cover; }
    .following-person-copy { min-width: 0; }
    .following-person-copy h2 { overflow: hidden; margin: 0; color: #30402a; font-size: 16px; font-weight: 750; text-overflow: ellipsis; white-space: nowrap; }
    .following-person-copy p { overflow: hidden; max-width: 560px; margin: 5px 0 0; color: #71806a; font-size: 13px; text-overflow: ellipsis; white-space: nowrap; }
    .following-recipes { display: inline-block; margin-top: 6px; color: #7a8f5c; font-size: 11px; font-weight: 700; }
    .following-row-actions { display: flex; flex: 0 0 auto; align-items: center; gap: 8px; }
    .following-profile, .following-action { display: inline-flex; min-height: 36px; align-items: center; justify-content: center; padding: 8px 13px; border: 1px solid #d5e0c5; border-radius: 999px; background: #fff; color: #4f6638; font: inherit; font-size: 12px; font-weight: 700; text-decoration: none; cursor: pointer; transition: background .18s, border-color .18s; }
    .following-profile:hover { border-color: #7a8f5c; background: #f4f8ef; }
    .following-action { border-color: #e4ebd8; background: #e4ebd8; }
    .following-action:hover { border-color: #d5e0c5; background: #d5e0c5; }
    .following-empty { padding: 54px 24px; text-align: center; }
    .following-empty-icon { display: grid; width: 58px; height: 58px; margin: 0 auto 15px; place-items: center; border-radius: 50%; background: #e4ebd8; color: #5f7446; font-size: 26px; }
    .following-empty h2 { margin: 0; color: #30402a; font-size: 18px; }
    .following-empty p { max-width: 430px; margin: 8px auto 18px; color: #71806a; font-size: 13px; line-height: 1.6; }
    .following-pagination { display: flex; align-items: center; justify-content: center; gap: 14px; padding: 18px; border-top: 1px solid #eef2e8; color: #71806a; font-size: 12px; }
    .following-page-link { padding: 7px 11px; border: 1px solid #d5e0c5; border-radius: 9px; color: #4f6638; font-weight: 700; text-decoration: none; }
    .following-page-link:hover { background: #f4f8ef; }
    .following-page-link.is-disabled { color: #aab49f; pointer-events: none; }
    @media (max-width: 760px) {
        .following-page { padding: 22px 14px 34px; }
        .following-heading { align-items: flex-start; flex-direction: column; gap: 12px; }
        .following-row { align-items: flex-start; flex-direction: column; padding: 16px; }
        .following-person { width: 100%; }
        .following-person-copy p { white-space: normal; }
        .following-row-actions { padding-left: 73px; }
    }
    @media (max-width: 420px) {
        .following-row-actions { width: 100%; padding-left: 0; }
        .following-row-actions form { flex: 1; }
        .following-profile, .following-action { width: 100%; padding-right: 9px; padding-left: 9px; font-size: 11px; }
    }
</style>

<main class="following-page">
    <div class="following-shell">
        <a href="{{ route('users.show', $user->id) }}" class="following-back">
            <span aria-hidden="true">&larr;</span>
            <span>Kembali ke profil</span>
        </a>

        <header class="following-heading">
            <div>
                <p class="following-kicker">Komunitas Racikara</p>
                <h1>Pengguna yang Diikuti</h1>
                <p>Daftar akun yang diikuti oleh {{ $user->name }}.</p>
            </div>
            <span class="following-count">{{ number_format($users->total()) }} pengguna</span>
        </header>

        <section class="following-card" aria-label="Daftar pengguna yang diikuti">
            @forelse ($users as $followedUser)
                <article class="following-row">
                    <a href="{{ route('users.show', $followedUser->id) }}" class="following-person">
                        <x-user-avatar :user="$followedUser" class="following-avatar" />
                        <span class="following-person-copy">
                            <h2>{{ $followedUser->name }}</h2>
                            <p>{{ $followedUser->bio ?: 'Pengguna RACIKARA yang berbagi inspirasi masakan.' }}</p>
                            <span class="following-recipes">{{ number_format($followedUser->recipes_count) }} resep</span>
                        </span>
                    </a>

                    <div class="following-row-actions">
                        <a href="{{ route('users.show', $followedUser->id) }}" class="following-profile">Lihat profil</a>
                        @auth
                            @if (auth()->id() === $user->id)
                                <form action="{{ route('users.unfollow', $followedUser->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="following-action">Berhenti mengikuti</button>
                                </form>
                            @endif
                        @endauth
                    </div>
                </article>
            @empty
                <div class="following-empty">
                    <span class="following-empty-icon" aria-hidden="true">♧</span>
                    <h2>Belum mengikuti siapa pun</h2>
                    <p>Saat {{ auth()->id() === $user->id ? 'kamu' : $user->name }} mengikuti pengguna lain, akun yang diikuti akan tampil di halaman ini.</p>
                    <a href="{{ route('home') }}" class="following-profile">Jelajahi Racikara</a>
                </div>
            @endforelse

            @if ($users->hasPages())
                <nav class="following-pagination" aria-label="Navigasi halaman">
                    <a href="{{ $users->previousPageUrl() ?: '#' }}" class="following-page-link {{ $users->onFirstPage() ? 'is-disabled' : '' }}" @if ($users->onFirstPage()) aria-disabled="true" @endif>Sebelumnya</a>
                    <span>Halaman {{ $users->currentPage() }} dari {{ $users->lastPage() }}</span>
                    <a href="{{ $users->nextPageUrl() ?: '#' }}" class="following-page-link {{ $users->hasMorePages() ? '' : 'is-disabled' }}" @unless ($users->hasMorePages()) aria-disabled="true" @endunless>Berikutnya</a>
                </nav>
            @endif
        </section>
    </div>
</main>
@endsection
