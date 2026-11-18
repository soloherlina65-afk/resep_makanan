@extends('admin.layout')

@section('title', 'Detail Pengguna')
@section('heading', 'Detail Pengguna')

@section('content')
<section class="panel">
    <h2>{{ $user->name }}</h2>
    <p class="empty">{{ $user->email }}</p>
    <div class="table-wrap">
        <table>
            <tbody>
                <tr><th>Role</th><td>{{ $user->isAdmin() ? 'Admin' : 'User' }}</td></tr>
                <tr><th>Status akun</th><td>{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</td></tr>
                <tr><th>Resep</th><td>{{ $user->recipes_count }}</td></tr>
                <tr><th>Rating dan komentar</th><td>{{ $user->reviews_count }}</td></tr>
                <tr><th>Favorit</th><td>{{ $user->favorites_count }}</td></tr>
                <tr><th>Pengikut / mengikuti</th><td>{{ $user->followers_count }} / {{ $user->following_count }}</td></tr>
                <tr><th>Bergabung</th><td>{{ $user->created_at->format('d M Y H:i') }}</td></tr>
            </tbody>
        </table>
    </div>
</section>

<section class="panel">
    <h2>Edit data pengguna</h2>
    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf @method('PATCH')
        <label for="user-name">Nama</label>
        <input id="user-name" name="name" value="{{ old('name', $user->name) }}" maxlength="255" required>
        <label for="user-email">Email</label>
        <input id="user-email" type="email" name="email" value="{{ old('email', $user->email) }}" maxlength="254" required>
        <button type="submit">Simpan data</button>
    </form>
</section>

<section class="panel">
    <h2>Akses akun</h2>
    @if (auth()->id() !== $user->id)
        <form class="inline" method="POST" action="{{ route('admin.users.role', $user) }}">
            @csrf @method('PATCH')
            <label for="user-role">Role</label>
            <select id="user-role" name="role">
                <option value="user" @selected(!$user->isAdmin())>User</option>
                <option value="admin" @selected($user->isAdmin())>Admin</option>
            </select>
            <button type="submit">Simpan role</button>
        </form>
        <form class="inline" method="POST" action="{{ route('admin.users.status', $user) }}">
            @csrf @method('PATCH')
            <input type="hidden" name="is_active" value="{{ $user->is_active ? 0 : 1 }}">
            <button class="{{ $user->is_active ? 'danger' : '' }}" type="submit">
                {{ $user->is_active ? 'Nonaktifkan akun' : 'Aktifkan akun' }}
            </button>
        </form>
        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Hapus akun ini secara permanen?')">
            @csrf @method('DELETE')
            <button class="danger" type="submit">Hapus akun</button>
        </form>
    @else
        <p class="empty">Role dan status akun yang sedang digunakan tidak dapat diubah dari halaman ini.</p>
    @endif
</section>
<a class="button" href="{{ route('admin.users.index') }}">Kembali ke daftar pengguna</a>
@endsection
