@extends('admin.layout')

@section('title', 'Kelola Pengguna')
@section('heading', 'Kelola Pengguna')

@section('content')
<section class="panel">
    <form class="inline" method="GET" action="{{ route('admin.users.index') }}">
        <label for="user-search">Cari nama atau email</label>
        <input id="user-search" name="search" value="{{ $search }}" maxlength="100">
        <label for="user-role">Role</label>
        <select id="user-role" name="role">
            <option value="">Semua role</option>
            <option value="user" @selected($role === 'user')>User</option>
            <option value="admin" @selected($role === 'admin')>Admin</option>
        </select>
        <label for="user-status">Status</label>
        <select id="user-status" name="status">
            <option value="">Semua status</option>
            <option value="active" @selected($status === 'active')>Aktif</option>
            <option value="inactive" @selected($status === 'inactive')>Nonaktif</option>
        </select>
        <button type="submit">Terapkan</button>
        @if ($search || $role || $status)<a class="button" href="{{ route('admin.users.index') }}">Reset</a>@endif
    </form>
</section>
<section class="panel">
    @if ($users->isEmpty())
        <p class="empty">Pengguna tidak ditemukan.</p>
    @else
        <div class="table-wrap">
            <table>
                <thead><tr><th>Nama</th><th>Email</th><th>Role</th><th>Status</th><th>Bergabung</th><th>Aksi</th></tr></thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td><a href="{{ route('admin.users.show', $user) }}">{{ $user->name }}</a></td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->isAdmin() ? 'Admin' : 'User' }}</td>
                            <td>{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                            <td>{{ $user->created_at->format('d M Y') }}</td>
                            <td>
                                @if (auth()->id() !== $user->id)
                                    <form class="inline" method="POST" action="{{ route('admin.users.status', $user) }}">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="is_active" value="{{ $user->is_active ? 0 : 1 }}">
                                        <button class="{{ $user->is_active ? 'danger' : '' }}" type="submit">
                                            {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                    <form class="inline" method="POST" action="{{ route('admin.users.role', $user) }}">
                                        @csrf @method('PATCH')
                                        <select name="role" aria-label="Role {{ $user->name }}">
                                            <option value="user" @selected(!$user->isAdmin())>User</option>
                                            <option value="admin" @selected($user->isAdmin())>Admin</option>
                                        </select>
                                        <button type="submit">Simpan role</button>
                                    </form>
                                    <form class="inline" method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Hapus akun pengguna ini?')">
                                        @csrf @method('DELETE')
                                        <button class="danger" type="submit">Hapus</button>
                                    </form>
                                @else
                                    <span>Akun yang sedang digunakan</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination">{{ $users->links() }}</div>
    @endif
</section>
@endsection
