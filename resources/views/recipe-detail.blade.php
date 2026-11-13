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
        gap: 6px;
        color: #e56b27;
        text-decoration: none;
        font-weight: 600;
        font-size: 13px;
        background: #fff3ec;
        padding: 8px 16px;
        border-radius: 12px;
        margin-bottom: 25px;
        transition: background 0.2s;
    }

    .back-link:hover {
        background: #ffe6db;
    }

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
    .reaction-login {
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

    .ingredient-list li:last-child {
        margin-bottom: 0;
    }

    .ingredient-list li::before {
        content: "✓";
        background: #feebe1;
        color: #e56b27;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: bold;
        margin-right: 14px;
        flex-shrink: 0;
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

        <a href="{{ url('/') }}" class="back-link">
            &larr; Kembali ke Beranda
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
                <span>Diposting oleh <strong>{{ $recipe->user?->name ?? 'Tim Racikara' }}</strong></span>
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
        </div>

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
                <ul class="ingredient-list">
                    @foreach ($recipe->bahan as $b)
                        <li>{{ $b->nama_bahan }}</li>
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
                <ol class="step-list">
                    @foreach ($recipe->steps as $step)
                        <li>
                            <span class="step-number">
                                {{ $step->urutan }}
                            </span>

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
            $averageRating = $recipe->reviews->avg('rating');
            $userReview = auth()->check()
                ? $recipe->reviews->firstWhere('user_id', auth()->id())
                : null;
        @endphp

        <div class="detail-card">
            <h2>⭐ Rating dan Komentar</h2>

            <div class="review-summary">
                <span class="review-stars" aria-label="{{ $averageRating ? number_format($averageRating, 1) . ' dari 5' : 'Belum ada rating' }}">
                    @for ($star = 1; $star <= 5; $star++)
                        {{ $averageRating && $star <= round($averageRating) ? '★' : '☆' }}
                    @endfor
                </span>
                <span>
                    {{ $averageRating ? number_format($averageRating, 1) . '/5' : 'Belum ada rating' }}
                    ({{ $recipe->reviews->count() }} ulasan)
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

            @forelse ($recipe->reviews as $review)
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
                </article>
            @empty
                <p class="empty-message">Belum ada rating atau komentar.</p>
            @endforelse
        </div>

    </div>
</section>

@endsection