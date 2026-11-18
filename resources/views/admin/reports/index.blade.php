@extends('admin.layout')

@section('title', 'Laporan Pengguna')
@section('heading', 'Laporan Pengguna')

@section('content')
<section class="panel">
    <form class="inline" method="GET" action="{{ route('admin.reports.index') }}">
        <label for="report-status">Status</label>
        <select id="report-status" name="status">
            <option value="">Semua status</option>
            <option value="new" @selected($status === 'new')>Baru</option>
            <option value="reviewing" @selected($status === 'reviewing')>Ditinjau</option>
            <option value="resolved" @selected($status === 'resolved')>Selesai</option>
            <option value="dismissed" @selected($status === 'dismissed')>Ditolak</option>
        </select>
        <button type="submit">Filter</button>
        @if ($status)<a class="button" href="{{ route('admin.reports.index') }}">Reset</a>@endif
    </form>
</section>
<section class="panel">
    @if ($reports->isEmpty())
        <p class="empty">Belum ada laporan.</p>
    @else
        <div class="table-wrap">
            <table>
                <thead><tr><th>Pelapor</th><th>Konten</th><th>Alasan</th><th>Status</th><th>Dikirim</th><th>Aksi</th></tr></thead>
                <tbody>
                    @foreach ($reports as $report)
                        <tr>
                            <td>{{ $report->user?->name ?? 'Pengguna dihapus' }}</td>
                            <td>
                                @if ($report->reportable instanceof \App\Models\Recipe)
                                    <a href="{{ route('recipes.show', $report->reportable) }}">{{ $report->reportable->nama_resep }}</a>
                                @elseif ($report->reportable instanceof \App\Models\Review)
                                    {{ $report->reportable->recipe?->nama_resep ?? 'Komentar pada resep yang dihapus' }}
                                @else
                                    Konten #{{ $report->reportable_id }} (dihapus)
                                @endif
                            </td>
                            <td>{{ \App\Models\Report::REASONS[$report->reason] ?? $report->reason }}</td>
                            <td>{{ ucfirst($report->status) }}</td>
                            <td>{{ $report->created_at->format('d M Y H:i') }}</td>
                            <td><a class="button" href="{{ route('admin.reports.show', $report) }}">Detail</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination">{{ $reports->links() }}</div>
    @endif
</section>
@endsection
