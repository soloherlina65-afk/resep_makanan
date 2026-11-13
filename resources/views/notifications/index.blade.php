<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Notifikasi - Racikara</title>
    <style>
        :root {
            --cream: #f4f8ef;
            --sage: #7a8f5c;
            --sage-dark: #5f7446;
            --sage-light: #e4ebd8;
            --brown: #30402a;
            --muted: #6f8060;
            --line: #d5e0c5;
        }
        * { box-sizing: border-box; }
        body { min-height: 100vh; margin: 0; background: var(--cream); color: var(--brown); font-family: Figtree, Arial, sans-serif; }
        .page { width: min(100% - 32px, 860px); margin: 0 auto; padding: 28px 0 48px; }
        .topbar { display: flex; align-items: center; justify-content: space-between; gap: 20px; padding: 18px 22px; border: 1px solid var(--line); border-radius: 16px; background: #fff; box-shadow: 0 8px 24px rgba(48, 64, 42, .06); }
        .brand { color: var(--brown); font-size: 19px; font-weight: 850; }
        .brand span { color: var(--sage); }
        .back-link, .read-all, .recipe-link { border: 0; border-radius: 999px; font-weight: 800; text-decoration: none; cursor: pointer; }
        .back-link { padding: 10px 17px; background: var(--sage-light); color: var(--sage-dark); font-size: 13px; }
        .heading { display: flex; align-items: end; justify-content: space-between; gap: 18px; margin: 34px 3px 18px; }
        .kicker { margin: 0 0 6px; color: var(--sage); font-size: 11px; font-weight: 850; letter-spacing: .12em; text-transform: uppercase; }
        h1 { margin: 0; font-size: clamp(28px, 5vw, 36px); letter-spacing: -.03em; }
        .subtitle { margin: 7px 0 0; color: var(--muted); font-size: 14px; }
        .read-all { padding: 11px 16px; background: var(--sage); color: #fff; font-size: 12px; }
        .read-all:hover { background: var(--sage-dark); }
        .success { margin-bottom: 14px; padding: 12px 15px; border: 1px solid #b7c8a0; border-radius: 12px; background: var(--sage-light); color: #3f5635; font-size: 13px; }
        .notification-list { display: grid; gap: 12px; }
        .notification-card { display: flex; align-items: flex-start; gap: 14px; padding: 17px; border: 1px solid var(--line); border-radius: 16px; background: #fff; box-shadow: 0 5px 16px rgba(48, 64, 42, .045); }
        .notification-card.unread { border-left: 4px solid var(--sage); padding-left: 14px; }
        .activity-icon { display: grid; place-items: center; width: 44px; height: 44px; flex: 0 0 44px; border-radius: 50%; background: var(--sage-light); color: var(--sage-dark); font-size: 21px; }
        .notification-content { min-width: 0; flex: 1; }
        .message { margin: 1px 0 5px; color: var(--brown); font-size: 14px; font-weight: 700; line-height: 1.45; }
        .time { color: var(--muted); font-size: 11px; }
        .recipe-link { display: inline-block; margin-top: 12px; color: var(--sage-dark); font-size: 12px; }
        .new-badge { padding: 5px 9px; border-radius: 999px; background: var(--sage-light); color: var(--sage-dark); font-size: 10px; font-weight: 850; }
        .empty { padding: 52px 20px; border: 1px dashed #b7c8a0; border-radius: 16px; background: rgba(255,255,255,.7); text-align: center; }
        .empty-icon { margin-bottom: 10px; color: var(--sage); font-size: 34px; }
        .empty strong { display: block; font-size: 16px; }
        .empty p { margin: 6px 0 0; color: var(--muted); font-size: 13px; }
        .pagination { margin-top: 22px; }
        @media (max-width: 560px) {
            .page { width: min(100% - 22px, 860px); padding-top: 14px; }
            .topbar { padding: 15px; }
            .heading { align-items: flex-start; flex-direction: column; margin-top: 26px; }
            .read-all { align-self: stretch; }
            .notification-card { padding: 14px; }
        }
    </style>
</head>
<body>
    <main class="page">
        <header class="topbar">
            <div class="brand">Notifikasi <span>Aktivitas</span></div>
            <a class="back-link" href="{{ route('dashboard') }}">← Dashboard</a>
        </header>

        <section class="heading">
            <div>
                <p class="kicker">Aktivitas terbaru</p>
                <h1>Notifikasi</h1>
                <p class="subtitle">Kabar terbaru dari resep yang kamu bagikan.</p>
            </div>
            @if ($notifications->whereNull('read_at')->isNotEmpty())
                <form method="POST" action="{{ route('notifications.read-all') }}">
                    @csrf
                    <button class="read-all" type="submit">Tandai semua dibaca</button>
                </form>
            @endif
        </section>

        @if (session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        <section class="notification-list">
            @forelse ($notifications as $notification)
                <article class="notification-card {{ $notification->read_at ? '' : 'unread' }}">
                    <span class="activity-icon">{{ ['like' => '♥', 'favorite' => '★', 'review' => '✎'][$notification->data['activity'] ?? 'review'] ?? '♧' }}</span>
                    <div class="notification-content">
                        <p class="message">{{ $notification->data['message'] ?? 'Ada aktivitas baru pada resep kamu.' }}</p>
                        <span class="time">{{ $notification->created_at->diffForHumans() }}</span>
                        <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                            @csrf
                            <button class="recipe-link" type="submit">Lihat resep →</button>
                        </form>
                    </div>
                    @if (! $notification->read_at)
                        <span class="new-badge">Baru</span>
                    @endif
                </article>
            @empty
                <div class="empty">
                    <div class="empty-icon">♧</div>
                    <strong>Belum ada aktivitas</strong>
                    <p>Notifikasi like, favorit, dan komentar akan muncul di sini.</p>
                </div>
            @endforelse
        </section>

        <div class="pagination">{{ $notifications->links() }}</div>
    </main>
</body>
</html>
