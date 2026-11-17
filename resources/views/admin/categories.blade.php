@extends('admin.layout')

@section('title', 'Kelola Kategori')
@section('heading', 'Kelola Kategori')

@section('content')
<section class="panel">
    <h2>Tambah kategori</h2>
    <form class="inline" method="POST" action="{{ route('admin.categories.store') }}">
        @csrf
        <label for="new-category">Nama kategori</label>
        <input id="new-category" name="nama_kategori" maxlength="255" required>
        <button type="submit">Tambah</button>
    </form>
</section>
<section class="panel">
    <h2>Daftar kategori</h2>
    @if ($categories->isEmpty())
        <p class="empty">Belum ada kategori.</p>
    @else
        <div class="table-wrap">
            <table>
                <thead><tr><th>Kategori</th><th>Jumlah resep</th><th>Aksi</th></tr></thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <td>
                                <form class="inline" method="POST" action="{{ route('admin.categories.update', $category) }}">
                                    @csrf @method('PATCH')
                                    <input name="nama_kategori" value="{{ $category->nama_kategori }}" maxlength="255" required aria-label="Nama kategori">
                                    <button type="submit">Simpan</button>
                                </form>
                            </td>
                            <td>{{ $category->recipes_count }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Hapus kategori ini?')">
                                    @csrf @method('DELETE')
                                    <button class="danger" type="submit" @disabled($category->recipes_count > 0)>Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination">{{ $categories->links() }}</div>
    @endif
</section>
@endsection
