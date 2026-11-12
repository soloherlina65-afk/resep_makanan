@extends('app')

@section('title', $recipe->nama_resep . ' - Resep Kita')

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

    .recipe-image {
        width: 100%;
        height: 430px;
        object-fit: cover;
        border-radius: 24px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(80, 50, 20, 0.08);
        border: 1px solid #f3e6de;
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
        </div>

        @if ($recipe->foto)
            <img
                src="{{ asset('storage/' . $recipe->foto) }}"
                alt="{{ $recipe->nama_resep }}"
                class="recipe-image"
            >
        @else
            <div class="no-image">
                <span style="font-size: 28px;">🍲</span>
                Foto resep belum tersedia
            </div>
        @endif

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

    </div>
</section>

@endsection