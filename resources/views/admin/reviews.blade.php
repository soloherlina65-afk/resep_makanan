<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Moderasi Komentar - Racikara</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --sage: #7a8f5c;
            --sage-dark: #4f6638;
            --ink: #263323;
            --muted: #71806a;
            --line: #dce5d2;
            --surface: #fff;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            background: #f3f6ef;
            color: var(--ink);
            font-family: Figtree, Arial, sans-serif;
        }
        .admin-page {
            width: 100%;
            min-height: 100vh;
            padding: clamp(22px, 4vw, 56px);
        }
        .admin-topbar {
            display: grid;
            gap: 22px;
            margin-bottom: 28px;
        }
        .back-link {
            display: inline-flex;
            width: fit-content;
            align-items: center;
            gap: 9px;
            padding: 10px 15px;
            border: 1px solid var(--line);
            border-radius: 12px;
            background: var(--surface);
            box-shadow: 0 4px 12px rgba(38, 51, 35, .05);
            color: var(--sage-dark);
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            transition: .2s ease;
        }
        .back-link:hover {
            transform: translateX(-2px);
            border-color: #b7c8a0;
            background: #f8faf5;
        }
        .admin-heading {
            display: flex;
            align-items: center;
            gap: 18px;
            padding: clamp(22px, 3vw, 34px);
            border: 1px solid var(--line);
            border-radius: 22px;
            background: linear-gradient(120deg, #fff 5%, #edf3e6 100%);
            box-shadow: 0 12px 30px rgba(38, 51, 35, .06);
        }
        .admin-heading-icon {
            display: grid;
            flex: 0 0 58px;
            width: 58px;
            height: 58px;
            place-items: center;
            border-radius: 18px;
            background: #e4ebd8;
            color: var(--sage-dark);
            font-size: 27px;
            font-weight: 800;
        }
        .admin-heading-copy { flex: 1; min-width: 0; }
        .admin-eyebrow {
            margin: 0 0 5px;
            color: var(--sage);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .12em;
            text-transform: uppercase;
        }
        .admin-heading h1 {
            margin: 0;
            color: var(--ink);
            font-size: clamp(27px, 3vw, 38px);
            line-height: 1.15;
        }
        .admin-heading-copy > p:last-child {
            margin: 8px 0 0;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.6;
        }
        .review-total {
            display: grid;
            min-width: 112px;
            justify-items: center;
            padding: 13px 18px;
            border: 1px solid #dce5d2;
            border-radius: 16px;
            background: rgba(255, 255, 255, .76);
        }
        .review-total strong { color: var(--sage-dark); font-size: 25px; line-height: 1; }
        .review-total span { margin-top: 5px; color: var(--muted); font-size: 11px; }
        .success {
            margin-bottom: 18px;
            padding: 13px 16px;
            border: 1px solid #cde6cb;
            border-radius: 12px;
            background: #eff9ee;
            color: #287835;
            font-size: 13px;
        }
        .review-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(100%, 460px), 1fr));
            gap: 18px;
        }
        .review-card {
            display: flex;
            min-width: 0;
            flex-direction: column;
            padding: 22px;
            border: 1px solid var(--line);
            border-radius: 18px;
            background: var(--surface);
            box-shadow: 0 8px 22px rgba(38, 51, 35, .05);
            transition: transform .2s ease, box-shadow .2s ease;
        }
        .review-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 28px rgba(38, 51, 35, .09);
        }
        .review-card.is-hidden { background: #f8f6f3; opacity: .78; }
        .review-card-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
        }
        .review-card-header strong { color: var(--ink); font-size: 15px; }
        .review-meta { margin: 5px 0 0; color: var(--muted); font-size: 12px; line-height: 1.5; }
        .stars { margin-top: 16px; color: #e4a126; font-size: 17px; letter-spacing: 2px; }
        .review-comment {
            flex: 1;
            margin: 9px 0 18px;
            color: #52604d;
            font-size: 14px;
            line-height: 1.75;
            overflow-wrap: anywhere;
        }
        .review-status {
            flex: 0 0 auto;
            display: inline-flex;
            align-items: center;
            padding: 6px 10px;
            border-radius: 20px;
            background: #e8f2df;
            color: var(--sage-dark);
            font-size: 11px;
            font-weight: 800;
        }
        .review-card.is-hidden .review-status { background: #f5e8df; color: #a3462e; }
        .review-actions { display: flex; gap: 9px; flex-wrap: wrap; margin-top: auto; }
        .review-actions form { margin: 0; }
        .review-actions button,
        .review-actions a {
            display: inline-flex;
            min-height: 38px;
            align-items: center;
            justify-content: center;
            padding: 8px 13px;
            border: 1px solid var(--line);
            border-radius: 10px;
            background: #fff;
            color: var(--sage-dark);
            font: inherit;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: .2s ease;
        }
        .review-actions button:hover,
        .review-actions a:hover { border-color: #b7c8a0; background: #f5f8f0; }
        .review-actions .delete { color: #b54c2f; }
        .review-actions .delete:hover { border-color: #e8b6a7; background: #fff7f4; }
        .empty {
            display: grid;
            min-height: 42vh;
            place-content: center;
            justify-items: center;
            padding: 40px 20px;
            border: 1px dashed #cbd7bd;
            border-radius: 20px;
            background: rgba(255, 255, 255, .7);
            color: var(--muted);
            text-align: center;
        }
        .empty-icon {
            display: grid;
            width: 66px;
            height: 66px;
            margin-bottom: 15px;
            place-items: center;
            border-radius: 20px;
            background: #e8efdf;
            color: var(--sage-dark);
            font-size: 30px;
        }
        .empty strong { margin-bottom: 6px; color: var(--ink); font-size: 17px; }
        .pagination { margin-top: 24px; }
        @media (max-width: 640px) {
            .admin-page { padding: 20px 15px 32px; }
            .admin-topbar { gap: 16px; margin-bottom: 20px; }
            .admin-heading { align-items: flex-start; gap: 13px; flex-wrap: wrap; }
            .admin-heading-icon { flex-basis: 46px; width: 46px; height: 46px; border-radius: 14px; font-size: 22px; }
            .review-total { min-width: 0; grid-auto-flow: column; align-items: center; justify-items: start; gap: 7px; padding: 8px 11px; }
            .review-total strong { font-size: 18px; }
            .review-total span { margin: 0; }
            .review-list { grid-template-columns: 1fr; gap: 13px; }
            .review-card { padding: 17px; }
            .review-card-header { flex-direction: column; }
        }
    </style>
</head>
<body>
<main class="admin-page">
    <header class="admin-topbar">
        <a class="back-link" href="{{ route('admin.dashboard') }}" onclick="if (window.history.length > 1) { window.history.back(); return false; }">
            <span aria-hidden="true">←</span>
            Kembali
        </a>
        <div class="admin-heading">
            <span class="admin-heading-icon" aria-hidden="true">✓</span>
            <div class="admin-heading-copy">
                <p class="admin-eyebrow">Area Admin · Moderasi</p>
                <h1>Moderasi Komentar</h1>
                <p>Kelola rating dan komentar yang tampil di halaman resep.</p>
            </div>
            <div class="review-total" aria-label="{{ $reviews->total() }} komentar">
                <strong>{{ $reviews->total() }}</strong>
                <span>Total komentar</span>
            </div>
        </div>
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
        <div class="empty">
            <span class="empty-icon" aria-hidden="true">✎</span>
            <strong>Belum ada komentar</strong>
            <span>Komentar yang masuk akan tampil di sini untuk dimoderasi.</span>
        </div>
    @endif
</main>
</body>
</html>
