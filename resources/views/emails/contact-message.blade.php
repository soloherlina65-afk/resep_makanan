<h1>Pesan Baru dari Formulir Kontak Racikara</h1>

<p><strong>Nama:</strong> {{ $name }}</p>
<p><strong>Email:</strong> {{ $email }}</p>
<p><strong>Subjek:</strong> {{ $subjectLabel }}</p>

<h2>Pesan</h2>
<p>{!! nl2br(e($messageBody)) !!}</p>
