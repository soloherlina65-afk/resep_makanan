<?php

namespace App\Http\Controllers;

use App\Models\MealPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MealPlanController extends Controller
{
    private const MEAL_TYPES = [
        'breakfast' => 'Sarapan',
        'lunch' => 'Makan siang',
        'dinner' => 'Makan malam',
        'snack' => 'Camilan',
    ];

    public function index(Request $request): View
    {
        $weekStart = now()->startOfWeek();
        $weekEnd = now()->endOfWeek();
        $plans = $request->user()->mealPlans()
            ->whereBetween('planned_date', [$weekStart->toDateString(), $weekEnd->toDateString()])
            ->with(['recipe.category', 'recipe.bahan'])
            ->orderBy('planned_date')
            ->orderBy('meal_type')
            ->get();

        $items = [];
        foreach ($plans as $plan) {
            foreach ($plan->recipe->bahan as $ingredient) {
                $name = trim($ingredient->nama_bahan);
                if ($name === '') {
                    continue;
                }

                $key = mb_strtolower($name);
                $items[$key]['name'] = $items[$key]['name'] ?? $name;
                $items[$key]['recipes'][$plan->recipe_id] = $plan->recipe->nama_resep;
            }
        }

        $weekDays = collect(range(0, 6))->map(
            fn (int $offset) => $weekStart->copy()->addDays($offset),
        );

        return view('meal-plans.index', [
            'weekStart' => $weekStart,
            'weekEnd' => $weekEnd,
            'weekDays' => $weekDays,
            'plansByDay' => $plans->groupBy(fn (MealPlan $plan) => $plan->planned_date->toDateString()),
            'mealTypes' => self::MEAL_TYPES,
            'shoppingItems' => collect($items)->values(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $weekEnd = now()->endOfWeek()->toDateString();
        $validated = $request->validate([
            'recipe_id' => ['required', 'integer', 'exists:recipes,id'],
            'planned_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today', 'before_or_equal:' . $weekEnd],
            'meal_type' => ['required', 'in:' . implode(',', array_keys(self::MEAL_TYPES))],
        ]);

        $request->user()->mealPlans()
            ->whereDate('planned_date', $validated['planned_date'])
            ->where('meal_type', $validated['meal_type'])
            ->updateOrCreate([], $validated);

        return redirect()->route('meal-plans.index')
            ->with('success', 'Resep berhasil ditambahkan ke rencana menu.');
    }

    public function destroy(Request $request, MealPlan $mealPlan): RedirectResponse
    {
        abort_unless($mealPlan->user_id === $request->user()->id, 403);

        $mealPlan->delete();

        return back()->with('success', 'Resep dihapus dari rencana menu.');
    }
}
