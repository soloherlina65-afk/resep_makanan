<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Notifikasi - Racikara</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f4f8ef] text-[#30402a]">
    <main class="mx-auto max-w-3xl px-4 py-8 sm:px-6">
        <div class="mb-6 flex items-center justify-between gap-4">
            <div>
                <a class="text-sm font-semibold text-[#5f7446]" href="{{ route('dashboard') }}">← Kembali ke dashboard</a>
                <h1 class="mt-3 text-3xl font-bold">Notifikasi Aktivitas</h1>
            </div>
            @if ($notifications->whereNull('read_at')->isNotEmpty())
                <form method="POST" action="{{ route('notifications.read-all') }}">
                    @csrf
                    <button class="rounded-full bg-[#7a8f5c] px-4 py-2 text-sm font-bold text-white" type="submit">
                        Tandai semua dibaca
                    </button>
                </form>
            @endif
        </div>

        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-100 px-4 py-3 text-sm text-green-800">{{ session('success') }}</div>
        @endif

        <div class="space-y-3">
            @forelse ($notifications as $notification)
                <div class="flex items-start gap-4 rounded-xl border border-[#d5e0c5] bg-white p-4 {{ $notification->read_at ? '' : 'border-l-4 border-l-[#7a8f5c]' }}">
                    <span class="text-2xl">
                        {{ ['like' => '♥', 'favorite' => '★', 'review' => '✎'][$notification->data['activity'] ?? 'review'] ?? '♧' }}
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="font-medium">{{ $notification->data['message'] ?? 'Ada aktivitas baru pada resep kamu.' }}</p>
                        <p class="mt-1 text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</p>
                        <form class="mt-3" method="POST" action="{{ route('notifications.read', $notification->id) }}">
                            @csrf
                            <button class="text-sm font-bold text-[#5f7446]" type="submit">Lihat resep →</button>
                        </form>
                    </div>
                    @if (! $notification->read_at)
                        <span class="rounded-full bg-[#e4ebd8] px-2 py-1 text-xs font-bold text-[#5f7446]">Baru</span>
                    @endif
                </div>
            @empty
                <div class="rounded-xl border border-dashed border-[#b7c8a0] bg-white p-10 text-center text-sm text-[#5f7446]">
                    Belum ada notifikasi aktivitas.
                </div>
            @endforelse
        </div>

        <div class="mt-6">{{ $notifications->links() }}</div>
    </main>
</body>
</html>
