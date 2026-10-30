@extends('app')

@section('title', $recipe->nama_resep . ' - Resep Kita')

@section('content')

<!-- DETAIL RESEP SECTION -->
<section class="recipe-detail-section" style="padding: 40px 0;">
    <div class="container" style="max-width: 800px; margin: auto; padding: 0 20px;">
        
        <!-- Tombol Kembali -->
        <a href="{{ url('/') }}" style="text-decoration: none; color: #b52b2b; font-weight: bold; display: inline-block; margin-bottom: 20px;">
            &larr; Kembali ke Beranda
        </a>

        <!-- Judul & Kategori -->
        <h1 style="color: #7d1d1d; margin-bottom: 10px;">{{ $recipe->nama_resep }}</h1>
        <p style="color: #555; margin-bottom: 15px;">
            <strong>Kategori:</strong> {{ $recipe->category->nama_kategori ?? 'Umum' }}
        </p>

        <!-- Informasi Singkat (Waktu, Porsi, Kesulitan) -->
        <div class="recipe-meta-info" style="display: flex; gap: 20px; background: #fff8e7; padding: 15px; border-radius: 8px; margin-bottom: 25px; border-left: 4px solid #b52b2b;">
            <span>⏱ <strong>Waktu Masak:</strong> {{ $recipe->waktu_masak }} Menit</span>
            <span>👤 <strong>Porsi:</strong> {{ $recipe->porsi }} Orang</span>
            <span>⭐ <strong>Tingkat Kesulitan:</strong> {{ $recipe->tingkat_kesulitan }}</span>
        </div>

        <!-- Gambar Resep (Jika ada) -->
        @if ($recipe->foto)
            <div style="margin-bottom: 25px; text-align: center;">
                <img src="{{ asset('storage/' . $recipe->foto) }}" alt="{{ $recipe->nama_resep }}" style="max-width: 100%; height: auto; border-radius: 8px; max-height: 400px; object-fit: cover;">
            </div>
        @endif

        <!-- Deskripsi -->
        <div style="margin-bottom: 30px;">
            <h3 style="color: #7d1d1d; margin-bottom: 10px; border-bottom: 2px solid #eee; padding-bottom: 5px;">Deskripsi</h3>
            <p style="line-height: 1.6; color: #333;">{{ $recipe->deskripsi ?? 'Belum ada deskripsi untuk resep ini.' }}</p>
        </div>

       <!-- Bahan-bahan -->
        <div style="margin-bottom: 30px;">
            <h3 style="color: #7d1d1d; margin-bottom: 10px; border-bottom: 2px solid #eee; padding-bottom: 5px;">Bahan-bahan</h3>
            <ul style="line-height: 1.8; padding-left: 20px; color: #333;">
                @if(isset($recipe->bahan) && $recipe->bahan->count() > 0)
                    @foreach($recipe->bahan as $b)
                        <li>{{ $b->nama_bahan }}</li>
                    @endforeach
                @else
                    <li>Informasi bahan belum ditambahkan.</li>
                @endif
            </ul>
        </div>

    </div>
</section>

@endsection