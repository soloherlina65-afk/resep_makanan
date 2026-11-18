@extends('admin.layout')

@section('title', 'Pesan Masuk')
@section('heading', 'Pesan Masuk')

@section('content')
<section class="panel">
    <form class="inline" method="GET" action="{{ route('admin.messages.index') }}">
        <label for="message-status">Status</label>
        <select id="message-status" name="status">
            <option value="">Semua status</option>
            <option value="new" @selected($status === 'new')>Baru</option>
            <option value="read" @selected($status === 'read')>Dibaca</option>
            <option value="replied" @selected($status === 'replied')>Dibalas</option>
        </select>
        <button type="submit">Filter</button>
        @if ($status)<a class="button" href="{{ route('admin.messages.index') }}">Reset</a>@endif
    </form>
</section>
<section class="panel">
    @if ($messages->isEmpty())
        <p class="empty">Belum ada pesan masuk.</p>
    @else
        <div class="table-wrap">
            <table>
                <thead><tr><th>Nama</th><th>Email</th><th>Subjek</th><th>Status</th><th>Diterima</th><th>Aksi</th></tr></thead>
                <tbody>
                    @foreach ($messages as $message)
                        <tr>
                            <td>{{ $message->name }}</td>
                            <td><a href="mailto:{{ $message->email }}">{{ $message->email }}</a></td>
                            <td>{{ $message->subject }}</td>
                            <td>{{ ucfirst($message->status) }}</td>
                            <td>{{ $message->created_at->format('d M Y H:i') }}</td>
                            <td><a class="button" href="{{ route('admin.messages.show', $message) }}">Baca</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination">{{ $messages->links() }}</div>
    @endif
</section>
@endsection
