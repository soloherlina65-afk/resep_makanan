@extends('app')

@section('title', 'Profil ' . $user->name . ' - RACIKARA')

@section('content')
<style>
    .public-profile { min-height: 100vh; padding: 28px 16px 56px; background: #f4f8ef; color: #30402a; }
    .public-profile-shell { width: min(100%, 1120px); margin: 0 auto; }
    .profile-back { display: inline-flex; min-height: 38px; align-items: center; gap: 8px; margin-bottom: 18px; padding: 8px 13px; border: 1px solid #d5e0c5; border-radius: 10px; background: #fff; color: #5f7446; font-size: 13px; font-weight: 700; text-decoration: none; }
    .profile-back:hover { background: #e4ebd8; }
    .profile-cover { position: relative; height: clamp(150px, 23vw, 230px); overflow: hidden; border-radius: 22px 22px 0 0; background: linear-gradient(125deg, #4f6638, #7a8f5c 65%, #a8b88d); }
    .profile-cover-photo { position: absolute; inset: 0 auto 0 0; z-index: 0; width: 56%; height: 100%; object-fit: cover; object-position: center top; }
    .profile-cover::before, .profile-cover::after { position: absolute; border: 1px solid rgba(255,255,255,.2); border-radius: 50%; content: ''; }
    .profile-cover::before { inset: 0 auto 0 30%; z-index: 1; width: 38%; height: auto; border: 0; border-radius: 0; background: linear-gradient(90deg, rgba(79,102,56,0), #708656 78%, #7a8f5c); }
    .profile-cover::after { top: -70px; right: 19%; z-index: 2; width: 220px; height: 220px; }
    .cover-label { position: absolute; z-index: 3; right: 28px; bottom: 22px; color: rgba(255,255,255,.9); font-size: 12px; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; }
    .profile-card { position: relative; margin-bottom: 30px; border: 1px solid #d5e0c5; border-top: 0; border-radius: 0 0 22px 22px; background: #fff; box-shadow: 0 12px 30px rgba(48,64,42,.06); }
    .profile-main { position: relative; z-index: 1; display: grid; grid-template-columns: auto minmax(0,1fr) auto; align-items: end; gap: 20px; padding: 0 30px 22px; }
    .profile-avatar { position: relative; z-index: 2; width: 124px; height: 124px; margin-top: -58px; border: 5px solid #fff; background: #e4ebd8; box-shadow: 0 5px 16px rgba(48,64,42,.12); font-size: 40px; }
    .profile-identity { min-width: 0; padding-bottom: 7px; }
    .profile-identity h1 { margin: 0; color: #30402a; font-size: clamp(24px, 4vw, 34px); font-weight: 800; line-height: 1.15; overflow-wrap: anywhere; }
    .profile-identity p { margin: 7px 0 0; color: #71806a; font-size: 14px; line-height: 1.55; }
    .profile-actions { display: flex; flex-wrap: wrap; gap: 9px; padding-bottom: 8px; }
    .profile-button { display: inline-flex; min-height: 40px; align-items: center; justify-content: center; padding: 9px 16px; border: 1px solid #7a8f5c; border-radius: 999px; background: #7a8f5c; color: #fff; font: inherit; font-size: 13px; font-weight: 700; text-decoration: none; cursor: pointer; transition: background .18s, transform .18s; }
    .profile-button:hover { transform: translateY(-1px); background: #5f7446; color: #fff; }
    .profile-button.secondary { border-color: #d5e0c5; background: #fff; color: #4f6638; }
    .profile-button.secondary:hover { background: #e4ebd8; color: #4f6638; }
    .profile-stats { display: flex; gap: 0; margin: 0 30px; padding: 17px 0; border-top: 1px solid #eef2e8; }
    .profile-stat { min-width: 110px; padding: 0 20px; border-right: 1px solid #eef2e8; color: #71806a; font-size: 12px; text-decoration: none; }
    .profile-stat:first-child { padding-left: 0; }
    .profile-stat:last-child { border-right: 0; }
    .profile-stat strong { display: block; margin-bottom: 2px; color: #30402a; font-size: 18px; }
    .profile-stat:hover strong { color: #5f7446; }
    .recipes-heading { display: flex; align-items: end; justify-content: space-between; gap: 16px; margin: 0 0 16px; }
    .recipes-heading h2 { margin: 0; color: #30402a; font-size: 22px; font-weight: 800; }
    .recipes-heading p { margin: 4px 0 0; color: #71806a; font-size: 13px; }
    .recipes-grid { display: grid; grid-template-columns: repeat(3, minmax(0,1fr)); gap: 18px; }
    .recipe-card { min-width: 0; overflow: hidden; border: 1px solid #d5e0c5; border-radius: 17px; background: #fff; box-shadow: 0 7px 20px rgba(48,64,42,.05); transition: transform .2s, box-shadow .2s; }
    .recipe-card:only-child { grid-column: 1 / -1; width: min(100%, 560px); justify-self: center; }
    .recipe-card:hover { transform: translateY(-3px); box-shadow: 0 12px 25px rgba(48,64,42,.1); }
    .recipe-image { position: relative; display: block; overflow: hidden; aspect-ratio: 16 / 10; background: #e4ebd8; }
    .recipe-image img { width: 100%; height: 100%; object-fit: cover; transition: transform .3s; }
    .recipe-card:hover .recipe-image img { transform: scale(1.035); }
    .recipe-placeholder { display: grid; width: 100%; height: 100%; min-height: 170px; place-items: center; color: #7a8f5c; font-size: 42px; }
    .recipe-category { position: absolute; top: 12px; left: 12px; max-width: calc(100% - 24px); overflow: hidden; padding: 6px 10px; border-radius: 999px; background: rgba(255,255,255,.94); color: #4f6638; font-size: 10px; font-weight: 800; text-overflow: ellipsis; white-space: nowrap; }
    .recipe-body { padding: 15px 16px 16px; }
    .recipe-body h3 { margin: 0; font-size: 17px; line-height: 1.3; }
    .recipe-body h3 a { color: #30402a; text-decoration: none; }
    .recipe-body h3 a:hover { color: #5f7446; }
    .recipe-description { display: -webkit-box; min-height: 40px; overflow: hidden; margin: 7px 0 13px; color: #71806a; font-size: 12px; line-height: 1.6; -webkit-box-orient: vertical; -webkit-line-clamp: 2; }
    .recipe-meta { display: flex; flex-wrap: wrap; gap: 10px 14px; padding-top: 11px; border-top: 1px solid #eef2e8; color: #71806a; font-size: 11px; }
    .recipe-meta strong { color: #4f6638; }
    .recipe-rating { margin-left: auto; color: #7a8f5c; font-weight: 700; }
    .empty-recipes { grid-column: 1 / -1; padding: 48px 20px; border: 1px dashed #b7c8a0; border-radius: 17px; background: rgba(255,255,255,.75); color: #71806a; text-align: center; }
    .empty-recipes strong { display: block; margin-bottom: 6px; color: #30402a; font-size: 17px; }
    @media (max-width: 850px) {
        .profile-main { grid-template-columns: auto minmax(0,1fr); align-items: center; padding-right: 22px; padding-left: 22px; }
        .profile-actions { grid-column: 2; padding: 0; }
        .profile-stats { margin-right: 22px; margin-left: 22px; }
        .recipes-grid { grid-template-columns: repeat(2, minmax(0,1fr)); }
    }
    @media (max-width: 560px) {
        .public-profile { padding: 18px 12px 36px; }
        .profile-cover { height: 135px; border-radius: 17px 17px 0 0; }
        .profile-cover-photo { object-position: center 45%; }
        .cover-label { right: 16px; bottom: 15px; font-size: 9px; }
        .profile-card { border-radius: 0 0 17px 17px; }
        .profile-main { grid-template-columns: 1fr; gap: 10px; padding: 0 17px 17px; }
        .profile-avatar { width: 96px; height: 96px; margin-top: -46px; font-size: 32px; }
        .profile-identity { padding: 0; }
        .profile-identity h1 { font-size: 25px; }
        .profile-identity p { font-size: 13px; }
        .profile-actions { grid-column: 1; }
        .profile-button { min-height: 38px; padding: 8px 13px; }
        .profile-stats { margin: 0 17px; padding: 14px 0; }
        .profile-stat { min-width: 0; flex: 1; padding: 0 10px; text-align: center; }
        .profile-stat strong { font-size: 16px; }
        .profile-stat span { font-size: 10px; }
        .recipes-heading { align-items: flex-start; flex-direction: column; }
        .recipes-grid { grid-template-columns: 1fr; gap: 13px; }
        .recipe-card { display: grid; grid-template-columns: 38% minmax(0,1fr); align-items: stretch; }
        .recipe-image { height: 100%; min-height: 150px; aspect-ratio: auto; }
        .recipe-placeholder { min-height: 150px; }
        .recipe-body { display: flex; min-width: 0; flex-direction: column; justify-content: center; padding: 12px; }
        .recipe-body h3 { font-size: 15px; }
        .recipe-description { min-height: 0; margin-bottom: 10px; font-size: 11px; }
        .recipe-meta { gap: 6px 9px; font-size: 10px; }
    }
    @media (max-width: 360px) {
        .recipe-card { grid-template-columns: 1fr; }
        .recipe-image { height: 175px; min-height: 0; }
        .recipe-placeholder { min-height: 175px; }
    }
</style>

<main class="public-profile">
    <div class="public-profile-shell">
        <a href="{{ route('home') }}" class="profile-back"><span aria-hidden="true">&larr;</span> Kembali ke resep</a>

        <section class="profile-card" aria-label="Profil {{ $user->name }}">
            <div class="profile-cover">
                <img class="profile-cover-photo" src="{{ asset('images/racikara-community-banner.png') }}" alt="Tim koki menyiapkan berbagai hidangan">
                <span class="cover-label">Racikara · Komunitas Resep</span>
            </div>

            <div class="profile-main">
                <x-user-avatar :user="$user" class="profile-avatar" />

                <div class="profile-identity">
                    <h1>{{ $user->name }}</h1>
                    <p>{{ $user->bio ?: 'Berbagi cerita dan inspirasi masakan bersama komunitas Racikara.' }}</p>
                </div>

                <div class="profile-actions">
                    @auth
                        @if (auth()->id() === $user->id)
                            <a class="profile-button secondary" href="{{ route('profile.edit') }}">Edit profil</a>
                        @elseif ($isFollowing)
                            <form action="{{ route('users.unfollow', $user->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="profile-button secondary" type="submit">✓ Mengikuti</button>
                            </form>
                        @else
                            <form action="{{ route('users.follow', $user->id) }}" method="POST">
                                @csrf
                                <button class="profile-button" type="submit">+ Ikuti</button>
                            </form>
                        @endif
                    @else
                        <a class="profile-button" href="{{ route('login') }}">Masuk untuk mengikuti</a>
                    @endauth
                </div>
            </div>

            <div class="profile-stats" aria-label="Statistik profil">
                <div class="profile-stat"><strong>{{ number_format($user->recipes_count) }}</strong><span>Resep</span></div>
                <a class="profile-stat" href="{{ route('users.followers', $user->id) }}"><strong>{{ number_format($user->followers_count) }}</strong><span>Pengikut</span></a>
                <a class="profile-stat" href="{{ route('users.following', $user->id) }}"><strong>{{ number_format($user->following_count) }}</strong><span>Mengikuti</span></a>
            </div>
        </section>

        <section aria-labelledby="profile-recipes-heading">
            <div class="recipes-heading">
                <div>
                    <h2 id="profile-recipes-heading">Resep dari {{ $user->name }}</h2>
                    <p>Jelajahi resep yang dibagikan oleh anggota komunitas ini.</p>
                </div>
            </div>

            <div class="recipes-grid">
                @forelse ($recipes as $recipe)
                    @php
                        $recipeImage = $recipe->foto
                            ? (str_starts_with($recipe->foto, 'images/')
                                ? asset($recipe->foto)
                                : asset('storage/' . $recipe->foto))
                            : null;
                    @endphp
                    <article class="recipe-card">
                        <a href="{{ route('recipes.show', $recipe) }}" class="recipe-image" aria-label="Lihat resep {{ $recipe->nama_resep }}">
                            @if ($recipeImage)
                                <img src="{{ $recipeImage }}" alt="{{ $recipe->nama_resep }}" loading="lazy">
                            @else
                                <span class="recipe-placeholder" aria-hidden="true">♨</span>
                            @endif
                            <span class="recipe-category">{{ $recipe->category?->nama_kategori ?? 'Umum' }}</span>
                        </a>
                        <div class="recipe-body">
                            <h3><a href="{{ route('recipes.show', $recipe) }}">{{ $recipe->nama_resep }}</a></h3>
                            <p class="recipe-description">{{ $recipe->deskripsi ?: 'Resep pilihan dari komunitas Racikara.' }}</p>
                            <div class="recipe-meta">
                                <span>⏱ {{ $recipe->waktu_masak ?: '-' }} menit</span>
                                <span>♥ <strong>{{ number_format($recipe->likes_count) }}</strong></span>
                                <span class="recipe-rating">★ {{ $recipe->visible_reviews_avg_rating ? number_format($recipe->visible_reviews_avg_rating, 1) : 'Baru' }}</span>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="empty-recipes">
                        <strong>Belum ada resep untuk ditampilkan</strong>
                        <span>Resep yang dibagikan {{ $user->name }} akan muncul di sini.</span>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</main>
@endsection
