@extends('admin.layout')

@section('title', 'Detail Laporan')
@section('heading', 'Detail Laporan #'.$report->id)

@section('content')
<section class="panel">
    <h2>Informasi laporan</h2>
    <p><strong>Pelapor:</strong> {{ $report->user?->name ?? 'Pengguna dihapus' }}</p>
    <p><strong>Konten:</strong> {{ class_basename($report->reportable_type) }} #{{ $report->reportable_id }}</p>
    @if ($report->reportable instanceof \App\Models\Recipe)
        <p><strong>Resep:</strong> <a href="{{ route('recipes.show', $report->reportable) }}">{{ $report->reportable->nama_resep }}</a></p>
    @elseif ($report->reportable instanceof \App\Models\Review)
        <p><strong>Resep:</strong> <a href="{{ route('recipes.show', $report->reportable->recipe_id) }}">{{ $report->reportable->recipe?->nama_resep ?? 'Resep dihapus' }}</a></p>
        <p><strong>Komentar:</strong> {{ $report->reportable->comment ?: 'Tanpa komentar' }}</p>
    @else
        <p class="empty">Konten yang dilaporkan sudah tidak tersedia.</p>
    @endif
    <p><strong>Alasan:</strong> {{ \App\Models\Report::REASONS[$report->reason] ?? $report->reason }}</p>
    <p><strong>Detail:</strong> {{ $report->details ?: 'Tidak ada detail tambahan.' }}</p>
    <p><strong>Dikirim:</strong> {{ $report->created_at->format('d M Y H:i') }}</p>
</section>
<section class="panel">
    <h2>Tindak lanjut</h2>
    <form class="inline" method="POST" action="{{ route('admin.reports.status', $report) }}">
        @csrf @method('PATCH')
        <label for="report-status">Status</label>
        <select id="report-status" name="status" required>
            <option value="new" @selected($report->status === 'new')>Baru</option>
            <option value="reviewing" @selected($report->status === 'reviewing')>Ditinjau</option>
            <option value="resolved" @selected($report->status === 'resolved')>Selesai</option>
            <option value="dismissed" @selected($report->status === 'dismissed')>Ditolak</option>
        </select>
        <button type="submit">Simpan status</button>
    </form>
</section>
<a class="button" href="{{ route('admin.reports.index') }}">Kembali ke laporan</a>
@endsection
