@extends('admin.layout')

@section('title', 'Kelola Resep')
@section('heading', 'Kelola Resep')

@section('content')
<section class="panel">
    <h2>Semua resep</h2>
    @if ($recipes->isEmpty())
        <p class="empty">Belum ada resep.</p>
    @else
        <div class="table-wrap">
            <table>
                <thead><tr><th>Nama resep</th><th>Kategori</th><th>Pemilik</th><th>Dibuat</th><th>Aksi</th></tr></thead>
                <tbody>
                    @foreach ($recipes as $recipe)
                        <tr>
                            <td><a href="{{ route('recipes.show', $recipe) }}">{{ $recipe->nama_resep }}</a></td>
                            <td>{{ $recipe->category?->nama_kategori ?? 'Tanpa kategori' }}</td>
                            <td>{{ $recipe->user?->name ?? 'Pengguna dihapus' }}</td>
                            <td>{{ $recipe->created_at->format('d M Y') }}</td>
                            <td>
                                <a class="button" href="{{ route('recipes.edit', $recipe) }}">Edit</a>
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
