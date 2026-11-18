@extends('app')

@section('title', $recipe->nama_resep . ' - Racikara')

@section('content')

<style>
    .detail-page {
        background: #fdfbf7;
        padding: 45px 20px 70px;
        min-height: 100vh;
    }

    .detail-container {
        max-width: 1050px;
        margin: auto;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        min-height: 38px;
        padding: 0 14px;
        border: 1px solid #d5e0c5;
        color: #5f7446;
        text-decoration: none;
        font-weight: 700;
        font-size: 13px;
        background: #fff;
        border-radius: 10px;
        margin-bottom: 25px;
        transition: background .2s, border-color .2s, transform .2s;
    }

    .back-link:hover {
        transform: translateX(-2px);
        border-color: #b7c8a0;
        background: #f4f8ef;
    }
    .back-link:focus-visible { outline: 3px solid rgba(122, 143, 92, .3); outline-offset: 2px; }

    .recipe-header {
        margin-bottom: 25px;
    }

    .recipe-category {
        display: inline-block;
        background: #feebe1;
        color: #d96523;
        padding: 7px 16px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 15px;
    }

    .recipe-header h1 {
        color: #2d1e17;
        font-size: 38px;
        margin: 0 0 12px;
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    .recipe-header p {
        color: #6b5c53;
        line-height: 1.8;
        max-width: 750px;
        font-size: 14px;
    }

    .recipe-author {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-top: 8px;
        padding: 8px 13px;
        border-radius: 18px;
        background: #e4ebd8;
        color: #5f7446;
        font-size: 12px;
    }

    .recipe-author strong {
        color: #5f7446;
    }
    .recipe-author a {
        color: inherit;
        text-decoration: none;
    }
    .recipe-author a:hover { text-decoration: underline; }

    .recipe-image {
        width: 100%;
        height: 430px;
        object-fit: cover;
        border-radius: 24px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(80, 50, 20, 0.08);
        border: 1px solid #f3e6de;
    }

    .recipe-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        margin: -10px 0 30px;
    }

    .recipe-actions form {
        margin: 0;
    }

    .recipe-actions button,
    .reaction-login,
    .recipe-tool-button {
        display: inline-flex;
        align-items: center;
        min-height: 38px;
        padding: 8px 16px;
        border: 1px solid #f0d8c8;
        border-radius: 20px;
        background: #fff;
        color: #7a8f5c;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
    }

    .recipe-tool-button:hover { background: #e4ebd8; }

    .meal-plan-add {
        margin: 0 0 28px;
        padding: 18px;
        border: 1px solid #d5e0c5;
        border-radius: 18px;
        background: #f7f9f3;
    }

    .meal-plan-add h2 { margin: 0 0 6px; color: #30402a; font-size: 17px; }
    .meal-plan-add > p { margin: 0 0 13px; color: #6f8060; font-size: 12px; }
    .meal-plan-form { display: flex; align-items: end; gap: 10px; flex-wrap: wrap; }
    .meal-plan-form label { display: grid; gap: 5px; color: #5f7446; font-size: 11px; font-weight: 700; }
    .meal-plan-form input,
    .meal-plan-form select {
        min-height: 38px;
        padding: 0 10px;
        border: 1px solid #d5e0c5;
        border-radius: 9px;
        background: #fff;
        color: #30402a;
        font: inherit;
        font-size: 12px;
    }
    .meal-plan-form button,
    .meal-plan-login {
        display: inline-flex;
        min-height: 38px;
        align-items: center;
        justify-content: center;
        padding: 0 14px;
        border: 0;
        border-radius: 9px;
        background: #7a8f5c;
        color: #fff;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
    }
    .meal-plan-form button:hover,
    .meal-plan-login:hover { background: #5f7446; color: #fff; }

    .recipe-actions button:hover,
    .recipe-actions button.active {
        background: #7a8f5c;
        border-color: #7a8f5c;
        color: #fff;
    }

    .reaction-count {
        color: #8c644d;
        font-size: 12px;
    }

    .no-image {
        height: 280px;
        background: linear-gradient(135deg, #fceee3, #f7dfd0);
        border-radius: 24px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #8c644d;
        margin-bottom: 30px;
        font-size: 15px;
        font-weight: 600;
        gap: 8px;
        border: 1px dashed #e8cbba;
    }

    .recipe-info {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 35px;
    }

    .info-card {
        background: white;
        padding: 22px;
        border-radius: 20px;
        border: 1px solid #f2e6dc;
        text-align: center;
        box-shadow: 0 4px 15px rgba(80, 50, 20, 0.03);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .info-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(80, 50, 20, 0.06);
    }

    .info-card .icon {
        font-size: 26px;
        margin-bottom: 8px;
    }

    .info-card small {
        display: block;
        color: #9c8a7e;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .info-card strong {
        color: #2d1e17;
        font-size: 16px;
        font-weight: 700;
    }

    .detail-card {
        background: white;
        padding: 32px;
        border-radius: 24px;
        margin-bottom: 25px;
        border: 1px solid #f2e6dc;
        box-shadow: 0 8px 25px rgba(80, 50, 20, 0.04);
    }

    .detail-card h2 {
        color: #2d1e17;
        font-size: 20px;
        margin: 0 0 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #f4eae2;
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .description {
        color: #615249;
        line-height: 1.9;
        font-size: 14px;
    }

    .ingredient-list,
    .step-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .ingredient-list li {
        padding: 14px 16px;
        background: #faf6f0;
        border: 1px solid #f3eae2;
        border-radius: 14px;
        margin-bottom: 10px;
        color: #51443d;
        font-size: 14px;
        font-weight: 500;
        display: flex;
        align-items: center;
    }

    .ingredient-list li input,
    .step-check {
        width: 18px;
        height: 18px;
        margin: 0 12px 0 0;
        flex: 0 0 auto;
        accent-color: #7a8f5c;
        cursor: pointer;
    }

    .ingredient-list li span,
    .step-list li > span:last-child { flex: 1; }
    .ingredient-list li input:checked + span,
    .step-list li .step-check:checked ~ span:last-child { color: #89937f; text-decoration: line-through; }

    .ingredient-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin: -5px 0 16px;
        color: #6f8060;
        font-size: 12px;
    }
    .ingredient-toolbar label { display: inline-flex; align-items: center; gap: 8px; color: #5f7446; font-weight: 700; }
    .ingredient-toolbar input {
        width: 76px;
        min-height: 36px;
        padding: 0 8px;
        border: 1px solid #d5e0c5;
        border-radius: 8px;
        color: #30402a;
        font: inherit;
    }

    .ingredient-list li:last-child {
        margin-bottom: 0;
    }

    .step-list li {
        display: flex;
        gap: 18px;
        padding: 16px;
        background: #faf6f0;
        border: 1px solid #f3eae2;
        border-radius: 16px;
        margin-bottom: 12px;
        color: #51443d;
        line-height: 1.8;
        font-size: 14px;
    }

    .step-list li:last-child {
        margin-bottom: 0;
    }

    .step-number {
        flex-shrink: 0;
        width: 34px;
        height: 34px;
        background: #e56b27;
        color: white;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 13px;
        box-shadow: 0 3px 10px rgba(229, 107, 39, 0.2);
    }

    .empty-message {
        color: #a89587;
        font-style: italic;
        font-size: 14px;
        text-align: center;
        padding: 15px;
    }

    .review-summary {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0 0 20px;
        color: #8c644d;
        font-size: 13px;
    }

    .review-stars {
        color: #e5a227;
        font-size: 21px;
        letter-spacing: 2px;
    }

    .review-form {
        padding: 18px;
        border-radius: 16px;
        background: #faf6f0;
        border: 1px solid #f3eae2;
        margin-bottom: 22px;
    }

    .review-success {
        padding: 10px 12px;
        border-radius: 9px;
        background: #eff9ee;
        color: #287835;
        font-size: 13px;
    }

    .report-form {
        margin: 14px 0;
        padding: 12px 14px;
        border: 1px solid #f0dfd1;
        border-radius: 12px;
        background: #fff9f3;
    }

    .report-form summary {
        color: #b95622;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
    }

    .report-form form { display: grid; gap: 8px; margin-top: 12px; }
    .report-form label { color: #51443d; font-size: 12px; font-weight: 700; }
    .report-form select {
        min-height: 38px;
        padding: 8px 10px;
        border: 1px solid #eadfd5;
        border-radius: 9px;
        background: #fff;
        color: #51443d;
        font: inherit;
        font-size: 12px;
    }

    .review-form label {
        display: block;
        margin-bottom: 8px;
        color: #51443d;
        font-size: 13px;
        font-weight: 700;
    }

    .rating-options {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
        gap: 3px;
        margin-bottom: 14px;
    }

    .rating-options input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .rating-options label {
        margin: 0;
        color: #d7c7b9;
        cursor: pointer;
        font-size: 28px;
        line-height: 1;
    }

    .rating-options label:hover,
    .rating-options label:hover ~ label,
    .rating-options input:checked ~ label {
        color: #e5a227;
    }

    .review-textarea {
        width: 100%;
        min-height: 88px;
        resize: vertical;
        padding: 11px 13px;
        border: 1px solid #eadfd5;
        border-radius: 10px;
        outline: 0;
        background: #fff;
        color: #51443d;
        font: inherit;
        font-size: 13px;
    }

    .review-textarea:focus {
        border-color: #7a8f5c;
        box-shadow: 0 0 0 3px rgba(122, 143, 92, .14);
    }

    .review-submit {
        margin-top: 10px;
        padding: 10px 17px;
        border: 0;
        border-radius: 18px;
        background: #7a8f5c;
        color: #fff;
        font: inherit;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
    }

    .review-login {
        color: #7a8f5c;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
    }

    .review-item {
        padding: 15px 0;
        border-top: 1px solid #f1e5dc;
    }

    .review-item:first-of-type {
        padding-top: 0;
        border-top: 0;
    }

    .review-item-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 6px;
    }

    .review-item-header strong {
        color: #432017;
        font-size: 13px;
    }

    .review-item-date {
        color: #a89587;
        font-size: 11px;
    }

    .review-item .review-stars {
        font-size: 15px;
        letter-spacing: 1px;
    }

    .review-comment {
        margin: 7px 0 0;
        color: #615249;
        font-size: 13px;
        line-height: 1.7;
    }

    .review-actions {
        display: flex;
        gap: 8px;
        margin-top: 9px;
    }

    .review-actions button {
        padding: 5px 10px;
        border: 1px solid #eadfd5;
        border-radius: 14px;
        background: #fff;
        color: #7a8f5c;
        font: inherit;
        font-size: 11px;
        cursor: pointer;
    }

    .review-actions .delete-review {
        color: #b54c2f;
    }

    .review-edit-form {
        display: none;
        margin-top: 12px;
        padding: 14px;
        border-radius: 12px;
        background: #faf6f0;
    }

    .review-edit-form.is-visible {
        display: block;
    }

    .review-edit-form select {
        width: 100%;
        height: 35px;
        margin-bottom: 8px;
        padding: 0 9px;
        border: 1px solid #eadfd5;
        border-radius: 8px;
        background: #fff;
        color: #51443d;
        font: inherit;
        font-size: 12px;
    }

    @media (max-width: 650px) {
        .recipe-header h1 {
            font-size: 26px;
        }

        .recipe-image {
            height: 260px;
        }

        .recipe-info {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .detail-card {
            padding: 20px;
        }
    }
</style>

<section class="detail-page">
    <div class="detail-container">

        <a href="{{ url('/') }}" class="back-link" onclick="if (window.history.length > 1) { window.history.back(); return false; }">
            &larr; Kembali
        </a>

        <div class="recipe-header">
            <span class="recipe-category">
                {{ $recipe->category->nama_kategori ?? 'Umum' }}
            </span>

            <h1>{{ $recipe->nama_resep }}</h1>

            <p>
                {{ $recipe->deskripsi ?? 'Temukan cara membuat hidangan lezat ini dengan mudah dan praktis.' }}
            </p>

            <div class="recipe-author">
                <span>👤</span>
                <span>Diposting oleh
                    @if ($recipe->user)
                        <a href="{{ route('users.show', $recipe->user) }}"><strong>{{ $recipe->user->name }}</strong></a>
                    @else
                        <strong>Tim Racikara</strong>
                    @endif
                </span>
            </div>
        </div>

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
                class="recipe-image"
            >
        @else
            <div class="no-image">
                <span style="font-size: 28px;">🍲</span>
                Foto resep belum tersedia
            </div>
        @endif

        @php
            $userLike = auth()->check() && $recipe->likes()->where('user_id', auth()->id())->exists();
            $userFavorite = auth()->check() && $recipe->favorites()->where('user_id', auth()->id())->exists();
        @endphp

        <div class="recipe-actions">
            @auth
                <form method="POST" action="{{ route('recipes.reaction', $recipe) }}">
                    @csrf
                    <input type="hidden" name="type" value="like">
                    <button type="submit" class="{{ $userLike ? 'active' : '' }}">
                        {{ $userLike ? '♥ Disukai' : '♡ Suka' }}
                    </button>
                </form>
                <form method="POST" action="{{ route('recipes.reaction', $recipe) }}">
                    @csrf
                    <input type="hidden" name="type" value="favorite">
                    <button type="submit" class="{{ $userFavorite ? 'active' : '' }}">
                        {{ $userFavorite ? '★ Tersimpan' : '☆ Favorit' }}
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="reaction-login">Masuk untuk menyukai atau menyimpan resep</a>
            @endauth
            <span class="reaction-count">♥ {{ $recipe->likes()->count() }} suka</span>
            <button type="button" class="recipe-tool-button" data-share-recipe>Bagikan</button>
            <button type="button" class="recipe-tool-button" onclick="window.print()">Cetak resep</button>
        </div>

        @auth
            <section class="meal-plan-add">
                <h2>Tambahkan ke menu mingguan</h2>
                <p>Jika slot waktu yang sama sudah terisi, resep sebelumnya akan diganti.</p>
                @if ($errors->any())
                    <p class="auth-error">{{ $errors->first() }}</p>
                @endif
                <form class="meal-plan-form" method="POST" action="{{ route('meal-plans.store') }}">
                    @csrf
                    <input type="hidden" name="recipe_id" value="{{ $recipe->id }}">
                    <label>
                        Tanggal
                        <input
                            type="date"
                            name="planned_date"
                            value="{{ now()->toDateString() }}"
                            min="{{ now()->toDateString() }}"
                            max="{{ now()->endOfWeek()->toDateString() }}"
                            required
                        >
                    </label>
                    <label>
                        Waktu makan
                        <select name="meal_type" required>
                            <option value="breakfast">Sarapan</option>
                            <option value="lunch">Makan siang</option>
                            <option value="dinner">Makan malam</option>
                            <option value="snack">Camilan</option>
                        </select>
                    </label>
                    <button type="submit">＋ Rencanakan</button>
                    <a class="meal-plan-login" href="{{ route('meal-plans.index') }}">Lihat menu</a>
                </form>
            </section>
        @else
            <a class="meal-plan-login" href="{{ route('login') }}">Masuk untuk merencanakan menu</a>
        @endauth

        <div class="recipe-info">

            <div class="info-card">
                <div class="icon">⏱️</div>
                <small>Waktu Memasak</small>
                <strong>{{ $recipe->waktu_masak ?? '-' }} Menit</strong>
            </div>

            <div class="info-card">
                <div class="icon">🍽️</div>
                <small>Jumlah Porsi</small>
                <strong>{{ $recipe->porsi ?? '-' }} Orang</strong>
            </div>

            <div class="info-card">
                <div class="icon">⭐</div>
                <small>Tingkat Kesulitan</small>
                <strong>{{ $recipe->tingkat_kesulitan ?? '-' }}</strong>
            </div>

        </div>

        <div class="detail-card">
            <h2>📝 Deskripsi Resep</h2>

            <p class="description">
                {{ $recipe->deskripsi ?? 'Belum ada deskripsi untuk resep ini.' }}
            </p>
        </div>

        <div class="detail-card">
            <h2>🥕 Bahan-bahan</h2>

            @if ($recipe->bahan->count() > 0)
                <div class="ingredient-toolbar">
                    <span>Centang bahan yang sudah disiapkan.</span>
                    <label>
                        Porsi
                        <input
                            type="number"
                            min="1"
                            max="100"
                            value="{{ $recipe->porsi ?: 1 }}"
                            data-servings-control
                            data-base-servings="{{ $recipe->porsi ?: 1 }}"
                            aria-label="Sesuaikan jumlah porsi"
                        >
                    </label>
                </div>
            @endif

            @if ($recipe->bahan->count() > 0)
                <ul class="ingredient-list" data-recipe-checklist data-recipe-id="{{ $recipe->id }}">
                    @foreach ($recipe->bahan as $b)
                        <li>
                            <input type="checkbox" data-check-item="ingredient-{{ $b->id }}" aria-label="Tandai bahan {{ $b->nama_bahan }}">
                            <span data-ingredient-text data-original="{{ $b->nama_bahan }}">{{ $b->nama_bahan }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="empty-message">
                    Informasi bahan belum ditambahkan.
                </p>
            @endif
        </div>

        <div class="detail-card">
            <h2>👩‍🍳 Langkah Memasak</h2>

            @if ($recipe->steps->count() > 0)
                <ol class="step-list" data-recipe-checklist data-recipe-id="{{ $recipe->id }}">
                    @foreach ($recipe->steps as $step)
                        <li>
                            <span class="step-number">
                                {{ $step->urutan }}
                            </span>
                            <input class="step-check" type="checkbox" data-check-item="step-{{ $step->id }}" aria-label="Tandai langkah {{ $step->urutan }} selesai">

                            <span style="padding-top: 5px;">
                                {{ $step->deskripsi }}
                            </span>
                        </li>
                    @endforeach
                </ol>
            @else
                <p class="empty-message">
                    Langkah memasak belum ditambahkan.
                </p>
            @endif
        </div>

        @php
            $averageRating = $recipe->visibleReviews->avg('rating');
            $userReview = auth()->check()
                ? $recipe->visibleReviews->firstWhere('user_id', auth()->id())
                : null;
        @endphp

        <div class="detail-card">
            <h2>⭐ Rating dan Komentar</h2>
            @if (session('success'))<p class="review-success" role="status">{{ session('success') }}</p>@endif
            @if (session('error'))<p class="auth-error" role="alert">{{ session('error') }}</p>@endif

            <div class="review-summary">
                <span class="review-stars" aria-label="{{ $averageRating ? number_format($averageRating, 1) . ' dari 5' : 'Belum ada rating' }}">
                    @for ($star = 1; $star <= 5; $star++)
                        {{ $averageRating && $star <= round($averageRating) ? '★' : '☆' }}
                    @endfor
                </span>
                <span>
                    {{ $averageRating ? number_format($averageRating, 1) . '/5' : 'Belum ada rating' }}
                    ({{ $recipe->visibleReviews->count() }} ulasan)
                </span>
            </div>

            @auth
                <form class="review-form" method="POST" action="{{ route('recipes.reviews.store', $recipe) }}">
                    @csrf
                    <label>Berikan rating kamu</label>
                    <div class="rating-options">
                        @for ($star = 5; $star >= 1; $star--)
                            <input id="rating-{{ $star }}" type="radio" name="rating" value="{{ $star }}"
                                @checked((int) old('rating', $userReview?->rating) === $star) required>
                            <label for="rating-{{ $star }}" title="{{ $star }} bintang">★</label>
                        @endfor
                    </div>
                    @error('rating') <p class="auth-error">{{ $message }}</p> @enderror
                    <label for="review-comment">Komentar (opsional)</label>
                    <textarea class="review-textarea" id="review-comment" name="comment"
                        maxlength="1000" placeholder="Bagikan pendapatmu tentang resep ini...">{{ old('comment', $userReview?->comment) }}</textarea>
                    @error('comment') <p class="auth-error">{{ $message }}</p> @enderror
                    <button class="review-submit" type="submit">
                        {{ $userReview ? 'Perbarui ulasan' : 'Kirim ulasan' }}
                    </button>
                </form>
            @else
                <p><a class="review-login" href="{{ route('login') }}">Masuk untuk memberi rating dan komentar.</a></p>
            @endauth

            @auth
                @if ($recipe->user_id !== auth()->id())
                    <details class="report-form">
                        <summary>Laporkan resep ini</summary>
                        <form method="POST" action="{{ route('recipes.reports.store', $recipe) }}">
                            @csrf
                            <label for="recipe-report-reason">Alasan laporan</label>
                            <select id="recipe-report-reason" name="reason" required>
                                <option value="">Pilih alasan</option>
                                <option value="spam">Spam atau promosi</option>
                                <option value="inappropriate">Konten tidak pantas</option>
                                <option value="misinformation">Informasi menyesatkan</option>
                                <option value="other">Lainnya</option>
                            </select>
                            <textarea class="review-textarea" name="details" maxlength="1000" placeholder="Detail tambahan (opsional)"></textarea>
                            @error('reason') <p class="auth-error">{{ $message }}</p> @enderror
                            <button class="review-submit" type="submit">Kirim laporan</button>
                        </form>
                    </details>
                @endif
            @else
                <p><a class="review-login" href="{{ route('login') }}">Masuk untuk melaporkan konten.</a></p>
            @endauth

            @forelse ($recipe->visibleReviews as $review)
                <article class="review-item">
                    <div class="review-item-header">
                        <strong>{{ $review->user->name }}</strong>
                        <span class="review-item-date">{{ $review->created_at->format('d M Y') }}</span>
                    </div>
                    <span class="review-stars" aria-label="{{ $review->rating }} dari 5">
                        @for ($star = 1; $star <= 5; $star++)
                            {{ $star <= $review->rating ? '★' : '☆' }}
                        @endfor
                    </span>
                    @if ($review->comment)
                        <p class="review-comment">{{ $review->comment }}</p>
                    @endif
                    @auth
                        @if ($review->user_id === auth()->id())
                            <div class="review-actions">
                                <button type="button" data-review-toggle="{{ $review->id }}">Edit</button>
                                <form method="POST" action="{{ route('reviews.destroy', $review) }}"
                                    onsubmit="return confirm('Hapus komentar ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="delete-review" type="submit">Hapus</button>
                                </form>
                            </div>
                            <form id="review-edit-{{ $review->id }}" class="review-edit-form"
                                method="POST" action="{{ route('reviews.update', $review) }}">
                                @csrf
                                @method('PATCH')
                                <label for="review-rating-{{ $review->id }}">Rating</label>
                                <select id="review-rating-{{ $review->id }}" name="rating" required>
                                    @for ($star = 5; $star >= 1; $star--)
                                        <option value="{{ $star }}" @selected($review->rating === $star)>
                                            {{ $star }} bintang
                                        </option>
                                    @endfor
                                </select>
                                <textarea class="review-textarea" name="comment" maxlength="1000"
                                    placeholder="Tulis komentar...">{{ $review->comment }}</textarea>
                                <button class="review-submit" type="submit">Simpan perubahan</button>
                            </form>
                        @else
                            <details class="report-form">
                                <summary>Laporkan komentar</summary>
                                <form method="POST" action="{{ route('reviews.reports.store', $review) }}">
                                    @csrf
                                    <label for="review-report-reason-{{ $review->id }}">Alasan laporan</label>
                                    <select id="review-report-reason-{{ $review->id }}" name="reason" required>
                                        <option value="">Pilih alasan</option>
                                        <option value="spam">Spam atau promosi</option>
                                        <option value="inappropriate">Konten tidak pantas</option>
                                        <option value="misinformation">Informasi menyesatkan</option>
                                        <option value="other">Lainnya</option>
                                    </select>
                                    <textarea class="review-textarea" name="details" maxlength="1000" placeholder="Detail tambahan (opsional)"></textarea>
                                    <button class="review-submit" type="submit">Kirim laporan</button>
                                </form>
                            </details>
                        @endif
                    @endauth
                </article>
            @empty
                <p class="empty-message">Belum ada rating atau komentar.</p>
            @endforelse
        </div>

    </div>
</section>

<script>
    document.querySelectorAll('[data-review-toggle]').forEach(function (button) {
        button.addEventListener('click', function () {
            const form = document.getElementById('review-edit-' + button.dataset.reviewToggle);
            form.classList.toggle('is-visible');
        });
    });
</script>

<script>
    const recipeUrl = window.location.href;
    const shareButton = document.querySelector('[data-share-recipe]');
    if (shareButton) {
        shareButton.addEventListener('click', async () => {
            try {
                if (navigator.share) {
                    await navigator.share({ title: document.title, url: recipeUrl });
                    return;
                }

                await navigator.clipboard.writeText(recipeUrl);
                shareButton.textContent = 'Tautan disalin';
            } catch (error) {
                if (error.name !== 'AbortError') {
                    shareButton.textContent = 'Gagal membagikan';
                }
            }
        });
    }

    const checklists = document.querySelectorAll('[data-recipe-checklist]');
    checklists.forEach((list) => {
        const key = `racikara-recipe-${list.dataset.recipeId}-${list.classList.contains('ingredient-list') ? 'ingredients' : 'steps'}`;
        let checkedItems = [];
        try {
            const storedItems = JSON.parse(localStorage.getItem(key) || '[]');
            checkedItems = Array.isArray(storedItems) ? storedItems : [];
        } catch (error) {
            console.error('Tidak dapat membaca progres checklist resep:', error);
        }

        list.querySelectorAll('[data-check-item]').forEach((checkbox) => {
            checkbox.checked = checkedItems.includes(checkbox.dataset.checkItem);
            checkbox.addEventListener('change', () => {
                const next = new Set(checkedItems);
                if (checkbox.checked) next.add(checkbox.dataset.checkItem);
                else next.delete(checkbox.dataset.checkItem);
                checkedItems = Array.from(next);
                localStorage.setItem(key, JSON.stringify(checkedItems));
            });
        });
    });

    const servingsControl = document.querySelector('[data-servings-control]');
    if (servingsControl) {
        const baseServings = Number(servingsControl.dataset.baseServings);
        const ingredients = document.querySelectorAll('[data-ingredient-text]');

        const toNumber = (value) => {
            if (value.includes('/')) {
                const [numerator, denominator] = value.split('/').map(Number);
                return denominator ? numerator / denominator : null;
            }
            return Number(value.replace(',', '.'));
        };

        servingsControl.addEventListener('input', () => {
            const targetServings = Number(servingsControl.value);
            if (!Number.isInteger(targetServings) || targetServings < 1 || targetServings > 100) return;

            ingredients.forEach((ingredient) => {
                const original = ingredient.dataset.original;
                const match = original.match(/^(\d+\s+\d+\/\d+|\d+\/\d+|\d+(?:[.,]\d+)?)(?=\s|$)/);
                if (!match) {
                    ingredient.textContent = original;
                    return;
                }

                const quantity = match[1];
                const parts = quantity.split(/\s+/);
                const amount = parts.length === 2
                    ? Number(parts[0]) + toNumber(parts[1])
                    : toNumber(quantity);

                if (amount === null || Number.isNaN(amount)) {
                    ingredient.textContent = original;
                    return;
                }

                const scaled = (amount * targetServings / baseServings)
                    .toFixed(2)
                    .replace(/\.?0+$/, '')
                    .replace('.', ',');
                ingredient.textContent = original.replace(match[1], scaled);
            });
        });
    }
</script>
<style>
    @media print {
        .navbar, .recipe-actions, .meal-plan-add, .review-form, .review-actions, .back-link { display:none !important; }
        .detail-page { padding:0; background:#fff; }
        .detail-card, .info-card { break-inside:avoid; box-shadow:none; }
    }
</style>

@endsection