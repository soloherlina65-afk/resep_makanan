@extends('admin.layout')

@section('title', 'Kelola Notifikasi')
@section('heading', 'Kelola Notifikasi')

@section('content')
<section class="panel">
    @if ($notifications->isEmpty())
        <p class="empty">Belum ada notifikasi.</p>
    @else
        <div class="table-wrap">
            <table>
                <thead><tr><th>Penerima</th><th>Isi</th><th>Status</th><th>Waktu</th><th>Aksi</th></tr></thead>
                <tbody>
                    @foreach ($notifications as $notification)
                        <tr>
                            <td>{{ $notification->notifiable?->name ?? 'Pengguna dihapus' }}</td>
                            <td>{{ $notification->data['message'] ?? 'Notifikasi tanpa pesan.' }}</td>
                            <td>{{ $notification->read_at ? 'Dibaca' : 'Belum dibaca' }}</td>
                            <td>{{ $notification->created_at->diffForHumans() }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.notifications.destroy', $notification->id) }}" onsubmit="return confirm('Hapus notifikasi ini?')">
                                    @csrf @method('DELETE')
                                    <button class="danger" type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination">{{ $notifications->links() }}</div>
    @endif
</section>
@endsection
