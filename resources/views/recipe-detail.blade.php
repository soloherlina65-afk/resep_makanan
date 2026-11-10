
@extends('app')

@section('title', $recipe->nama_resep . ' - Resep Kita')

@section('content')

<style>
    .detail-page {
        background: #fffaf4;
        padding: 45px 20px 70px;
        min-height: 100vh;
    }

    .detail-container {
        max-width: 1050px;
        margin: auto;
    }

    .back-link {
        display: inline-block;
        color: #e56b27;
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 25px;
    }

    .recipe-header {
        margin-bottom: 25px;
    }

    .recipe-category {
        display: inline-block;
        background: #fff0df;
        color: #d96523;
        padding: 8px 16px;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 15px;
    }

    .recipe-header h1 {
        color: #34251d;
        font-size: 38px;
        margin: 0 0 12px;
        font-weight: 800;
    }

    .recipe-header p {
        color: #777;
        line-height: 1.7;
        max-width: 750px;
    }

    .recipe-image {
        width: 100%;
        height: 430px;
        object-fit: cover;
        border-radius: 22px;
        margin-bottom: 25px;
        background: #f2e5d8;
    }

    .no-image {
        height: 250px;
        background: #f5e8dc;
        border-radius: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #9a7862;
        margin-bottom: 25px;
        font-size: 18px;
    }

    .recipe-info {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        margin-bottom: 35px;
    }

    .info-card {
        background: white;
        padding: 20px;
        border-radius: 16px;
        border: 1px solid #f1e5da;
        text-align: center;
    }

    .info-card .icon {
        font-size: 25px;
        margin-bottom: 8px;
    }

    .info-card small {
        display: block;
        color: #999;
        margin-bottom: 6px;
    }

    .info-card strong {
        color: #3d2b20;
        font-size: 16px;
    }

    .detail-card {
        background: white;
        padding: 28px;
        border-radius: 18px;
        margin-bottom: 22px;
        border: 1px solid #f1e8df;
        box-shadow: 0 5px 20px rgba(80, 50, 20, 0.04);
    }

    .detail-card h2 {
        color: #3d2b20;
        font-size: 23px;
        margin: 0 0 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #f0e7df;
    }

    .description {
        color: #71665e;
        line-height: 1.9;
    }

    .ingredient-list,
    .step-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .ingredient-list li {
        padding: 13px 15px;
        border-bottom: 1px solid #f2eee9;
        color: #62564e;
    }

    .ingredient-list li:last-child {
        border-bottom: none;
    }

    .ingredient-list li::before {
        content: "✓";
        color: #e56b27;
        font-weight: bold;
        margin-right: 12px;
    }

    .step-list li {
        display: flex;
        gap: 16px;
        padding: 15px 0;
        border-bottom: 1px solid #f2eee9;
        color: #62564e;
        line-height: 1.8;
    }

    .step-list li:last-child {
        border-bottom: none;
    }

    .step-number {
        flex-shrink: 0;
        width: 34px;
        height: 34px;
        background: #fff0df;
        color: #d96523;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }

    .empty-message {
        color: #999;
        font-style: italic;
    }

    @media (max-width: 650px) {
        .recipe-header h1 {
            font-size: 28px;
        }

        .recipe-image {
            height: 260px;
        }

        .recipe-info {
            grid-template-columns: 1fr;
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
                {{ $recipe->deskripsi ?? 'Temukan cara membuat hidangan lezat ini.' }}
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

                            <span>
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
