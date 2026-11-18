@extends('admin.layout')

@section('title', 'Detail Pesan')
@section('heading', 'Detail Pesan')

@section('content')
<section class="panel">
    <h2>{{ $message->subject }}</h2>
    <p><strong>Nama:</strong> {{ $message->name }}</p>
    <p><strong>Email:</strong> <a href="mailto:{{ $message->email }}">{{ $message->email }}</a></p>
    <p><strong>Diterima:</strong> {{ $message->created_at->format('d M Y H:i') }}</p>
    <p style="white-space:pre-wrap">{{ $message->message }}</p>
</section>
<section class="panel">
    <h2>Status pesan</h2>
    <form class="inline" method="POST" action="{{ route('admin.messages.status', $message) }}">
        @csrf @method('PATCH')
        <label for="message-status">Status</label>
        <select id="message-status" name="status" required>
            <option value="new" @selected($message->status === 'new')>Baru</option>
            <option value="read" @selected($message->status === 'read')>Dibaca</option>
            <option value="replied" @selected($message->status === 'replied')>Dibalas</option>
        </select>
        <button type="submit">Simpan status</button>
    </form>
</section>
<a class="button" href="{{ route('admin.messages.index') }}">Kembali ke pesan masuk</a>
@endsection
