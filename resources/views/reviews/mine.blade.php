<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $commentsOnly ? 'Komentar Saya' : 'Rating Saya' }} - Racikara</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --orange:#7a8f5c; --orange-dark:#5f7446; --cream:#f4f8ef; --ink:#30402a; --muted:#5f7446; --line:#d5e0c5; }
        * { box-sizing:border-box; }
        body { margin:0; min-height:100vh; background:var(--cream); color:var(--ink); font-family:Figtree,Arial,sans-serif; }
        .shell { display:flex; width:100%; min-height:100vh; }
        .sidebar { width:230px; flex:0 0 230px; padding:24px 16px; border-right:1px solid var(--line); background:#fff; }
        .brand { display:block; margin:0 8px 26px; }
        .brand img { width:78px; height:78px; border-radius:50%; object-fit:cover; }
        .nav { display:grid; gap:5px; }
        .nav a { display:flex; gap:10px; padding:10px 12px; border-radius:10px; color:var(--muted); font-size:13px; font-weight:600; text-decoration:none; }
        .nav a:hover,.nav a.active { background:#e4ebd8; color:var(--orange-dark); }
        .main { flex:1; min-width:0; width:auto; max-width:1100px; margin:0 auto; padding:30px; }
        .heading { display:flex; align-items:center; justify-content:space-between; gap:16px; margin-bottom:22px; }
        h1 { margin:0; font-size:clamp(25px,4vw,36px); }
        .heading p { margin:5px 0 0; color:var(--muted); }
        .card { min-width:0; margin-bottom:14px; padding:20px; border:1px solid var(--line); border-radius:15px; background:#fff; }
        .card h2 { margin:0 0 7px; font-size:18px; }
        .meta { color:var(--muted); font-size:13px; }
        .stars { color:#e4a126; letter-spacing:2px; }
        .edit-form { display:grid; grid-template-columns: minmax(150px, 220px) 1fr; gap:10px; margin-top:14px; }
        select,textarea { width:100%; padding:10px 12px; border:1px solid var(--line); border-radius:9px; background:#fff; color:var(--ink); font:inherit; }
        textarea { min-height:76px; resize:vertical; }
        .actions { display:flex; flex-wrap:wrap; gap:8px; grid-column:1 / -1; }
        .button { padding:9px 13px; border:0; border-radius:9px; background:var(--orange); color:#fff; font:inherit; font-size:12px; font-weight:700; cursor:pointer; text-decoration:none; }
        .button:hover { background:var(--orange-dark); }
        .button.danger { background:#a53d32; }
        .empty { padding:36px 20px; color:var(--muted); text-align:center; }
        .notice { margin-bottom:14px; padding:12px 14px; border-radius:10px; background:#eff9ee; color:#287835; }
        .error { color:#a33a2d; font-size:12px; }
        .pagination { margin-top:18px; }
        @media (max-width:800px) {
            .shell { display:block; }
            .sidebar { width:100%; padding:12px 14px; border-right:0; border-bottom:1px solid var(--line); }
            .brand { margin:0 8px 12px; }
            .brand img { width:58px; height:58px; }
            .nav { grid-template-columns:repeat(2,minmax(0,1fr)); }
            .main { width:100%; max-width:none; padding:24px clamp(14px,4vw,28px); }
        }
        @media (max-width:600px) {
            .heading { align-items:flex-start; flex-direction:column; }
            .heading .button { align-self:flex-start; }
            .edit-form { grid-template-columns:1fr; }
            .actions { grid-column:auto; }
            .card { padding:16px; }
        }
    </style>
</head>
<body>
<div class="shell">
    <aside class="sidebar">
        <a class="brand" href="{{ route('home') }}"><img src="{{ asset('images/racikara-logo.png') }}" alt="Racikara"></a>
        <nav class="nav" aria-label="Navigasi dashboard pengguna">
            <a href="{{ route('dashboard') }}">Dashboard Saya</a>
            <a href="{{ route('recipes.mine') }}">Resep Saya</a>
            <a href="{{ route('recipes.favorites') }}">Resep Favorit</a>
            <a href="{{ route('recipes.liked') }}">Resep Disukai</a>
            <a class="{{ $commentsOnly ? 'active' : '' }}" href="{{ route('reviews.mine.comments') }}">Komentar Saya</a>
            <a class="{{ !$commentsOnly ? 'active' : '' }}" href="{{ route('reviews.mine.ratings') }}">Rating Saya</a>
            <a href="{{ route('users.following', auth()->id()) }}">Pengguna Diikuti</a>
            <a href="{{ route('profile.edit') }}">Profil Saya</a>
        </nav>
    </aside>
    <main class="main">
        <header class="heading">
            <div>
                <h1>{{ $commentsOnly ? 'Komentar Saya' : 'Rating Saya' }}</h1>
                <p>{{ $commentsOnly ? 'Lihat dan kelola komentar serta rating yang kamu berikan.' : 'Riwayat rating yang kamu berikan pada resep.' }}</p>
            </div>
            <a class="button" href="{{ route('dashboard') }}">Kembali ke Dashboard</a>
        </header>
        @if (session('success'))<div class="notice" role="status">{{ session('success') }}</div>@endif
        @if ($errors->any())
            <div class="notice error" role="alert">@foreach ($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>
        @endif
        @forelse ($reviews as $review)
            <article class="card">
                <h2><a href="{{ route('recipes.show', $review->recipe) }}">{{ $review->recipe->nama_resep }}</a></h2>
                <p class="meta">{{ $review->recipe->category?->nama_kategori ?? 'Tanpa kategori' }} · {{ $review->created_at->format('d M Y') }}</p>
                <p class="stars" aria-label="Rating {{ $review->rating }} dari 5">
                    @for ($star = 1; $star <= 5; $star++){{ $star <= $review->rating ? '★' : '☆' }}@endfor
                    <span class="meta">{{ $review->rating }}/5</span>
                </p>
                <form class="edit-form" method="POST" action="{{ route('reviews.update', $review) }}">
                    @csrf @method('PATCH')
                    <label>
                        Rating
                        <select name="rating" required>
                            @for ($star = 1; $star <= 5; $star++)
                                <option value="{{ $star }}" @selected($review->rating === $star)>{{ $star }} dari 5</option>
                            @endfor
                        </select>
                    </label>
                    <label>
                        Komentar
                        <textarea name="comment" maxlength="1000" placeholder="Tulis komentar (opsional)">{{ $review->comment }}</textarea>
                    </label>
                    <div class="actions">
                        <button class="button" type="submit">Simpan perubahan</button>
                        <button class="button danger" type="submit" form="delete-review-{{ $review->id }}">Hapus</button>
                    </div>
                </form>
                <form id="delete-review-{{ $review->id }}" method="POST" action="{{ route('reviews.destroy', $review) }}" onsubmit="return confirm('Hapus rating dan komentar ini?')">
                    @csrf @method('DELETE')
                </form>
            </article>
        @empty
            <section class="card empty">{{ $commentsOnly ? 'Kamu belum menulis komentar.' : 'Kamu belum memberi rating.' }}</section>
        @endforelse
        @if ($reviews->hasPages())<div class="pagination">{{ $reviews->links() }}</div>@endif
    </main>
</div>
</body>
</html>
