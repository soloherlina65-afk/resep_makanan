
@extends('app')

@section('title', $recipe->nama_resep . ' - Resep Kita')

@section('content')

<div class="detail-container">

    <a href="/" class="back-button">← Kembali ke Beranda</a>

    <div class="detail-header">

        <div class="detail-image">
            @if ($recipe->foto)
                <img
                    src="{{ asset('storage/' . $recipe->foto) }}"
                    alt="{{ $recipe->nama_resep }}"
                >
            @else
                <div class="detail-placeholder">🍲</div>
            @endif
        </div>

        <div class="detail-information">

            <span class="detail-category">
                {{ $recipe->category->nama_kategori ?? 'Umum' }}
            </span>

            <h1>{{ $recipe->nama_resep }}</h1>

            <p>{{ $recipe->deskripsi }}</p>

            <div class="detail-stats">
                <div>
                    <strong>⏱ {{ $recipe->waktu_masak }}</strong>
                    <span>Menit</span>
                </div>

                <div>
                    <strong>👥 {{ $recipe->porsi }}</strong>
                    <span>Porsi</span>
                </div>

                <div>
                    <strong>{{ $recipe->tingkat_kesulitan }}</strong>
                    <span>Kesulitan</span>
                </div>
            </div>

        </div>

    </div>


    <div class="detail-content">

        <section class="detail-section">

            <h2>🥕 Bahan-Bahan</h2>

            @forelse ($recipe->ingredients as $ingredient)

                <div class="ingredient-item">
                    <span>{{ $ingredient->nama_bahan }}</span>

                    <strong>
                        {{ $ingredient->pivot->jumlah }}
                        {{ $ingredient->pivot->satuan }}
                    </strong>
                </div>

            @empty

                <p>Belum ada bahan yang ditambahkan.</p>

            @endforelse

        </section>


        <section class="detail-section">

            <h2>👩‍🍳 Langkah Memasak</h2>

            @forelse ($recipe->steps->sortBy('nomor_langkah') as $step)

                <div class="step-item">

                    <div class="step-number">
                        {{ $step->nomor_langkah }}
                    </div>

                    <p>{{ $step->instruksi }}</p>

                </div>

            @empty

                <p>Belum ada langkah memasak.</p>

            @endforelse

        </section>

    </div>

</div>

@endsection
