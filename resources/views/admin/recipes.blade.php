@extends('admin.layout')

@section('title', 'Kelola Resep')
@section('heading', 'Kelola Resep')

@section('content')
<section class="panel">
    <form class="inline" method="GET" action="{{ route('admin.recipes.index') }}">
        <label for="recipe-search">Cari nama resep</label>
        <input id="recipe-search" name="search" value="{{ $search }}" maxlength="100">
        <label for="recipe-category">Kategori</label>
        <select id="recipe-category" name="category_id">
            <option value="">Semua kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected((string) $categoryId === (string) $category->id)>{{ $category->nama_kategori }}</option>
            @endforeach
        </select>
        <label for="recipe-visibility">Status</label>
        <select id="recipe-visibility" name="visibility">
            <option value="">Semua status</option>
            <option value="visible" @selected($visibility === 'visible')>Tampil</option>
            <option value="hidden" @selected($visibility === 'hidden')>Disembunyikan</option>
        </select>
        <button type="submit">Terapkan</button>
        @if ($search || $categoryId || $visibility)<a class="button" href="{{ route('admin.recipes.index') }}">Reset</a>@endif
    </form>
</section>
<section class="panel">
    <h2>Semua resep</h2>
    @if ($recipes->isEmpty())
        <p class="empty">Belum ada resep.</p>
    @else
        <div class="table-wrap">
            <table>
                <thead><tr><th>Nama resep</th><th>Kategori</th><th>Pemilik</th><th>Rating rata-rata</th><th>Status</th><th>Dibuat</th><th>Aksi</th></tr></thead>
                <tbody>
                    @foreach ($recipes as $recipe)
                        <tr>
                            <td><a href="{{ route('recipes.show', $recipe) }}">{{ $recipe->nama_resep }}</a></td>
                            <td>{{ $recipe->category?->nama_kategori ?? 'Tanpa kategori' }}</td>
                            <td>{{ $recipe->user?->name ?? 'Pengguna dihapus' }}</td>
                            <td>{{ $recipe->reviews_count ? number_format($recipe->reviews_avg_rating, 1).'/5 ('.$recipe->reviews_count.')' : 'Belum ada rating' }}</td>
                            <td>{{ $recipe->is_visible ? 'Tampil' : 'Disembunyikan' }}</td>
                            <td>{{ $recipe->created_at->format('d M Y') }}</td>
                            <td>
                                <a class="button" href="{{ route('recipes.edit', $recipe) }}">Edit</a>
                                <form class="inline" method="POST" action="{{ route('admin.recipes.visibility', $recipe) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit">{{ $recipe->is_visible ? 'Sembunyikan' : 'Tampilkan' }}</button>
                                </form>
                                <form class="inline" method="POST" action="{{ route('admin.recipes.destroy', $recipe) }}" onsubmit="return confirm('Hapus resep ini?')">
                                    @csrf @method('DELETE')
                                    <button class="danger" type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination">{{ $recipes->links() }}</div>
    @endif
</section>
@endsection
