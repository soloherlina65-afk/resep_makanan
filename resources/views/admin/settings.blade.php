@extends('admin.layout')

@section('title', 'Pengaturan Aplikasi')
@section('heading', 'Pengaturan Aplikasi')

@section('content')
<section class="panel">
    <h2>Formulir kontak</h2>
    <p class="empty">Tentukan alamat penerima pesan dari formulir kontak publik. Kosongkan untuk menggunakan konfigurasi CONTACT_TO_ADDRESS di file .env.</p>
    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf @method('PATCH')
        <label for="contact-email">Email penerima pesan</label>
        <input id="contact-email" type="email" name="contact_email" value="{{ old('contact_email', $contactEmail) }}" maxlength="254">
        <button type="submit">Simpan pengaturan</button>
    </form>
</section>
@endsection
