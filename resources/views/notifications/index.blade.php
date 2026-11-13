<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Notifikasi - Racikara</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f4f8ef] font-sans text-[#30402a]">
    <main class="mx-auto max-w-4xl px-4 py-6 sm:px-6 sm:py-8">
        <header class="mb-8 flex items-center justify-between rounded-2xl border border-[#d5e0c5] bg-white px-5 py-4 shadow-sm">
            <a class="flex items-center gap-3" href="{{ route('dashboard') }}">
                <img class="h-12 w-12 rounded-full object-cover" src="{{ asset('images/racikara-logo.png') }}" alt="Racikara">
                <span class="text-xl font-black tracking-tight text-[#30402a]">Raci<span class="text-[#7a8f5c]">kara</span></span>
            </a>
            <a class="rounded-full bg-[#e4ebd8] px-4 py-2 text-sm font-bold text-[#5f7446] transition hover:bg-[#d5e0c5]" href="{{ route('dashboard') }}">
                Dashboard
            </a>
        </header>

        <div class="mb-6 flex items-end justify-between gap-4">
            <div>
                <p class="mb-1 text-xs font-extrabold uppercase tracking-widest text-[#7a8f5c]">Aktivitas terbaru</p>
                <h1 class="text-3xl font-black text-[#30402a]">Notifikasi</h1>
                <p class="mt-1 text-sm text-[#5f7446]">Ikuti kabar terbaru dari resep yang kamu bagikan.</p>
            </div>
            @if ($notifications->whereNull('read_at')->isNotEmpty())
                <form class="shrink-0" method="POST" action="{{ route('notifications.read-all') }}">
                    @csrf
                    <button class="rounded-full bg-[#7a8f5c] px-4 py-2 text-sm font-bold text-white shadow-sm transition hover:bg-[#5f7446]" type="submit">
                        Tandai semua dibaca
                    </button>
                </form>
            @endif
        </div>

        @if (session('success'))
            <div class="mb-4 rounded-xl border border-[#b7c8a0] bg-[#e4ebd8] px-4 py-3 text-sm font-medium text-[#3f5635]">{{ session('success') }}</div>
        @endif

        <div class="space-y-3">
            @forelse ($notifications as $notification)
                <div class="flex items-start gap-4 rounded-2xl border border-[#d5e0c5] bg-white p-4 shadow-sm transition hover:border-[#b7c8a0] {{ $notification->read_at ? '' : 'border-l-4 border-l-[#7a8f5c]' }}">
                    <span class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-[#e4ebd8] text-xl text-[#5f7446]">
                        {{ ['like' => '♥', 'favorite' => '★', 'review' => '✎'][$notification->data['activity'] ?? 'review'] ?? '♧' }}
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="font-semibold text-[#30402a]">{{ $notification->data['message'] ?? 'Ada aktivitas baru pada resep kamu.' }}</p>
                        <p class="mt-1 text-xs text-[#6f8060]">{{ $notification->created_at->diffForHumans() }}</p>
                        <form class="mt-3" method="POST" action="{{ route('notifications.read', $notification->id) }}">
                            @csrf
                            <button class="text-sm font-bold text-[#7a8f5c] transition hover:text-[#5f7446]" type="submit">Lihat resep →</button>
                        </form>
                    </div>
                    @if (! $notification->read_at)
                        <span class="rounded-full bg-[#e4ebd8] px-2 py-1 text-xs font-bold text-[#5f7446]">Baru</span>
                    @endif
                </div>
            @empty
                <div class="rounded-2xl border border-dashed border-[#b7c8a0] bg-white p-10 text-center text-sm text-[#5f7446] shadow-sm">
                    Belum ada notifikasi aktivitas.
                </div>
            @endforelse
        </div>

        <div class="mt-6">{{ $notifications->links() }}</div>
    </main>
</body>
</html>
