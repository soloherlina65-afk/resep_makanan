@extends('admin.layout')

@section('title', 'Aktivitas Pengguna')
@section('heading', 'Aktivitas Pengguna')

@section('content')
<section class="panel">
    @if ($activities->isEmpty())
        <p class="empty">Belum ada aktivitas tercatat. Aktivitas baru akan muncul setelah pengguna melakukan perubahan.</p>
    @else
        <div class="table-wrap">
            <table>
                <thead><tr><th>Pengguna</th><th>Aktivitas</th><th>Waktu</th></tr></thead>
                <tbody>
                    @foreach ($activities as $activity)
                        <tr>
                            <td>{{ $activity->user?->name ?? 'Sistem/pengguna dihapus' }}</td>
                            <td>{{ $activity->description }}</td>
                            <td>{{ $activity->created_at->diffForHumans() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination">{{ $activities->links() }}</div>
    @endif
</section>
@endsection
