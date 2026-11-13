<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Moderasi Komentar - Racikara</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --sage:#7a8f5c; --sage-dark:#5f7446; --brown:#542313; --muted:#806b61; --line:#ead9cc; }
        * { box-sizing:border-box; }
        body { margin:0; background:#fffaf5; color:var(--brown); font-family:Figtree,Arial,sans-serif; }
        .admin-page { width:min(100%,1100px); margin:0 auto; padding:35px 22px 50px; }
        .admin-topbar { display:flex; align-items:center; justify-content:space-between; gap:15px; margin-bottom:22px; }
        .admin-topbar h1 { margin:0; font-size:30px; }
        .admin-topbar p { margin:5px 0 0; color:var(--muted); font-size:13px; }
        .back-link { color:var(--sage-dark); font-size:13px; font-weight:700; text-decoration:none; }
        .success { margin-bottom:16px; padding:11px 14px; border-radius:9px; background:#e9f8e9; color:#287835; font-size:13px; }
        .review-list { display:grid; gap:14px; }
        .review-card { padding:18px; border:1px solid var(--line); border-radius:15px; background:#fff; box-shadow:0 5px 16px rgba(104,51,15,.04); }
        .review-card.is-hidden { opacity:.65; background:#f7f3ef; }
        .review-card-header { display:flex; align-items:flex-start; justify-content:space-between; gap:12px; }
        .review-card-header strong { font-size:15px; }
        .review-meta { margin:4px 0 0; color:var(--muted); font-size:12px; }
        .stars { margin-top:12px; color:#e5a227; letter-spacing:2px; }
        .review-comment { margin:8px 0 15px; color:#615249; font-size:13px; line-height:1.7; }
        .review-status { display:inline-block; padding:4px 9px; border-radius:12px; background:#e4ebd8; color:var(--sage-dark); font-size:11px; font-weight:700; }
        .review-card.is-hidden .review-status { background:#f4dfd7; color:#a3462e; }
        .review-actions { display:flex; gap:8px; flex-wrap:wrap; margin-top:12px; }
        .review-actions button, .review-actions a { padding:8px 12px; border:1px solid var(--line); border-radius:16px; background:#fff; color:var(--sage-dark); font:inherit; font-size:11px; font-weight:700; text-decoration:none; cursor:pointer; }
        .review-actions .delete { color:#b54c2f; }
        .empty { padding:60px 20px; border:1px dashed var(--line); border-radius:15px; color:var(--muted); text-align:center; }
        .pagination { margin-top:20px; }
        @media (max-width:600px) { .admin-topbar { align-items:flex-start; flex-direction:column; } }
    </style>
</head>
<body>
<main class="admin-page">
    <header class="admin-topbar">
        <div>
            <h1>Moderasi Komentar</h1>
            <p>Kelola rating dan komentar yang tampil di halaman resep.</p>
        </div>
        <a class="back-link" href="{{ route('dashboard') }}">← Kembali ke dashboard</a>
    </header>

    @if (session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    @if ($reviews->count())
        <section class="review-list">
            @foreach ($reviews as $review)
                <article class="review-card {{ $review->is_visible ? '' : 'is-hidden' }}">
                    <div class="review-card-header">
                        <div>
                            <strong>{{ $review->user->name }}</strong>
                            <p class="review-meta">Pada resep: {{ $review->recipe->nama_resep }} · {{ $review->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <span class="review-status">{{ $review->is_visible ? 'Tampil' : 'Disembunyikan' }}</span>
                    </div>
                    <div class="stars" aria-label="{{ $review->rating }} dari 5">
                        @for ($star = 1; $star <= 5; $star++)
                            {{ $star <= $review->rating ? '★' : '☆' }}
                        @endfor
                    </div>
                    <p class="review-comment">{{ $review->comment ?: 'Tanpa komentar.' }}</p>
                    <div class="review-actions">
                        <form method="POST" action="{{ route('admin.reviews.visibility', $review) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit">{{ $review->is_visible ? 'Sembunyikan' : 'Tampilkan' }}</button>
                        </form>
                        <a href="{{ route('recipes.show', $review->recipe) }}">Lihat resep</a>
                        <form method="POST" action="{{ route('reviews.destroy', $review) }}" onsubmit="return confirm('Hapus komentar ini secara permanen?')">
                            @csrf
                            @method('DELETE')
                            <button class="delete" type="submit">Hapus permanen</button>
                        </form>
                    </div>
                </article>
            @endforeach
        </section>
        <div class="pagination">{{ $reviews->links() }}</div>
    @else
        <div class="empty">Belum ada komentar untuk dimoderasi.</div>
    @endif
</main>
</body>
</html>
