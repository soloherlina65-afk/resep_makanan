<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Menu Mingguan - Racikara</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --ink:#30402a; --muted:#6f8060; --sage:#7a8f5c; --pale:#e4ebd8; --line:#d5e0c5; }
        * { box-sizing:border-box; }
        body { margin:0; min-height:100vh; background:#f4f8ef; color:var(--ink); font-family:Figtree,Arial,sans-serif; }
        .planner { width:100%; min-height:100vh; padding:clamp(20px,4vw,54px); }
        .planner-header { display:flex; align-items:flex-end; justify-content:space-between; gap:20px; margin-bottom:24px; }
        .planner-header h1 { margin:0; font-size:clamp(28px,4vw,40px); }
        .planner-header p { margin:7px 0 0; color:var(--muted); font-size:14px; }
        .planner-links { display:flex; gap:10px; flex-wrap:wrap; }
        .planner-link { display:inline-flex; min-height:40px; align-items:center; padding:0 15px; border:1px solid var(--line); border-radius:11px; background:#fff; color:var(--sage); font-size:13px; font-weight:700; text-decoration:none; }
        .planner-link:hover { background:#edf3e6; }
        .planner-link.primary { border-color:var(--sage); background:var(--sage); color:#fff; }
        .planner-range { margin:0 0 18px; color:var(--muted); font-size:13px; font-weight:700; }
        .success { margin-bottom:16px; padding:12px 15px; border:1px solid #cfe5c8; border-radius:11px; background:#eff9ec; color:#36753b; font-size:13px; }
        .week-grid { display:grid; grid-template-columns:repeat(7,minmax(210px,1fr)); gap:13px; overflow-x:auto; padding:2px 2px 14px; }
        .day-card { min-height:290px; padding:15px; border:1px solid var(--line); border-radius:16px; background:#fff; box-shadow:0 7px 20px rgba(48,64,42,.04); }
        .day-card h2 { margin:0 0 14px; font-size:15px; }
        .day-card.today { border-color:#9caf7e; box-shadow:0 0 0 2px #e4ebd8; }
        .meal-slot { padding:11px 0; border-top:1px solid #edf1e8; }
        .meal-slot:first-of-type { border-top:0; }
        .meal-slot-label { display:block; margin-bottom:6px; color:var(--muted); font-size:10px; font-weight:800; letter-spacing:.04em; text-transform:uppercase; }
        .planned-recipe { display:flex; align-items:flex-start; justify-content:space-between; gap:7px; }
        .planned-recipe a { color:var(--ink); font-size:12px; font-weight:700; line-height:1.45; text-decoration:none; }
        .planned-recipe a:hover { color:var(--sage); }
        .remove-plan { flex:0 0 auto; border:0; background:transparent; color:#a3462e; font-size:17px; cursor:pointer; }
        .empty-slot { color:#a0aa98; font-size:11px; }
        .shopping-panel { margin-top:24px; padding:22px; border:1px solid var(--line); border-radius:18px; background:#fff; }
        .shopping-panel h2 { margin:0; font-size:21px; }
        .shopping-panel > p { margin:6px 0 16px; color:var(--muted); font-size:12px; line-height:1.6; }
        .shopping-list { display:grid; grid-template-columns:repeat(auto-fill,minmax(260px,1fr)); gap:9px; }
        .shopping-item { display:flex; align-items:flex-start; gap:10px; padding:11px; border:1px solid #edf1e8; border-radius:11px; background:#fbfcf9; }
        .shopping-item input { margin-top:3px; accent-color:var(--sage); }
        .shopping-item span { font-size:12px; line-height:1.5; }
        .shopping-item small { display:block; margin-top:3px; color:var(--muted); font-size:10px; }
        .shopping-item:has(input:checked) span { color:#8a9782; text-decoration:line-through; }
        .empty-shopping { padding:22px; border:1px dashed var(--line); border-radius:12px; color:var(--muted); text-align:center; font-size:13px; }
        @media (max-width:700px) {
            .planner-header { align-items:flex-start; flex-direction:column; }
            .week-grid { grid-template-columns:repeat(7,minmax(245px,1fr)); }
            .shopping-list { grid-template-columns:1fr; }
        }
    </style>
</head>
<body>
<main class="planner">
    <header class="planner-header">
        <div>
            <h1>Menu Mingguan</h1>
            <p>Rencanakan masakanmu dan siapkan bahan belanja untuk minggu ini.</p>
        </div>
        <div class="planner-links">
            <a class="planner-link" href="{{ route('dashboard') }}">← Dashboard</a>
            <a class="planner-link primary" href="{{ route('home') }}#resep">Cari resep</a>
        </div>
    </header>

    <p class="planner-range">{{ $weekStart->translatedFormat('d M') }} – {{ $weekEnd->translatedFormat('d M Y') }}</p>

    @if (session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <section class="week-grid" aria-label="Rencana menu tujuh hari">
        @foreach ($weekDays as $day)
            @php($dayPlans = $plansByDay->get($day->toDateString(), collect()))
            <article class="day-card {{ $day->isToday() ? 'today' : '' }}">
                <h2>{{ $day->translatedFormat('l, d M') }}</h2>
                @foreach ($mealTypes as $type => $label)
                    @php($plan = $dayPlans->firstWhere('meal_type', $type))
                    <div class="meal-slot">
                        <span class="meal-slot-label">{{ $label }}</span>
                        @if ($plan)
                            <div class="planned-recipe">
                                <a href="{{ route('recipes.show', $plan->recipe) }}">{{ $plan->recipe->nama_resep }}</a>
                                <form method="POST" action="{{ route('meal-plans.destroy', $plan) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="remove-plan" type="submit" aria-label="Hapus {{ $plan->recipe->nama_resep }} dari rencana menu">×</button>
                                </form>
                            </div>
                        @else
                            <span class="empty-slot">Belum ada resep</span>
                        @endif
                    </div>
                @endforeach
            </article>
        @endforeach
    </section>

    <section class="shopping-panel">
        <h2>Daftar Belanja</h2>
        <p>Bahan dari resep yang direncanakan digabungkan. Nama bahan yang sama digabung; takaran tetap mengikuti teks bahan resep.</p>
        @if ($shoppingItems->isNotEmpty())
            <div class="shopping-list" data-shopping-list data-week="{{ $weekStart->toDateString() }}">
                @foreach ($shoppingItems as $index => $item)
                    <label class="shopping-item">
                        <input type="checkbox" data-shopping-key="{{ sha1($item['name']) }}">
                        <span>
                            {{ $item['name'] }}
                            <small>{{ implode(', ', array_values($item['recipes'])) }}</small>
                        </span>
                    </label>
                @endforeach
            </div>
        @else
            <div class="empty-shopping">Tambahkan resep ke menu mingguan untuk membuat daftar belanja.</div>
        @endif
    </section>
</main>
<script>
    const shoppingList = document.querySelector('[data-shopping-list]');
    if (shoppingList) {
        const storageKey = `racikara-shopping-${shoppingList.dataset.week}`;
        let checkedItems = [];

        try {
            checkedItems = JSON.parse(localStorage.getItem(storageKey) || '[]');
            if (!Array.isArray(checkedItems)) checkedItems = [];
        } catch (error) {
            localStorage.removeItem(storageKey);
        }

        shoppingList.querySelectorAll('[data-shopping-key]').forEach((checkbox) => {
            checkbox.checked = checkedItems.includes(checkbox.dataset.shoppingKey);
            checkbox.addEventListener('change', () => {
                const next = new Set(checkedItems);
                if (checkbox.checked) next.add(checkbox.dataset.shoppingKey);
                else next.delete(checkbox.dataset.shoppingKey);
                checkedItems = Array.from(next);
                localStorage.setItem(storageKey, JSON.stringify(checkedItems));
            });
        });
    }
</script>
</body>
</html>
