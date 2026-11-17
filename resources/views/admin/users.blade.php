@extends('admin.layout')

@section('title', 'Kelola Pengguna')
@section('heading', 'Kelola Pengguna')

@section('content')
<section class="panel">
    <form class="inline" method="GET" action="{{ route('admin.users.index') }}">
        <label for="user-search">Cari nama atau email</label>
        <input id="user-search" name="search" value="{{ $search }}" maxlength="255">
        <button type="submit">Cari</button>
    </form>
</section>
<section class="panel">
    @if ($users->isEmpty())
        <p class="empty">Pengguna tidak ditemukan.</p>
    @else
        <div class="table-wrap">
            <table>
                <thead><tr><th>Nama</th><th>Email</th><th>Role</th><th>Bergabung</th><th>Aksi</th></tr></thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role === \App\Models\User::ROLE_ADMIN ? 'Admin' : 'User' }}</td>
                            <td>{{ $user->created_at->format('d M Y') }}</td>
                            <td>
                                @if (auth()->id() !== $user->id)
                                    <form class="inline" method="POST" action="{{ route('admin.users.role', $user) }}">
                                        @csrf @method('PATCH')
                                        <select name="role" aria-label="Role {{ $user->name }}">
                                            <option value="user" @selected($user->role === 'user')>User</option>
                                            <option value="admin" @selected($user->role === 'admin')>Admin</option>
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
