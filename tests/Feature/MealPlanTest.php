<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\MealPlan;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MealPlanTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_and_replace_a_recipe_in_a_meal_slot(): void
    {
        $user = User::factory()->create();
        $firstRecipe = $this->createRecipe($user);
        $replacementRecipe = $this->createRecipe($user);
        $date = now()->toDateString();

        $this->actingAs($user)
            ->post(route('meal-plans.store'), [
                'recipe_id' => $firstRecipe->id,
                'planned_date' => $date,
                'meal_type' => 'lunch',
            ])
            ->assertRedirect(route('meal-plans.index'))
            ->assertSessionHasNoErrors();

        $this->actingAs($user)
            ->post(route('meal-plans.store'), [
                'recipe_id' => $replacementRecipe->id,
                'planned_date' => $date,
                'meal_type' => 'lunch',
            ])
            ->assertRedirect(route('meal-plans.index'))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseCount('meal_plans', 1);
        $this->assertSame(
            $replacementRecipe->id,
            MealPlan::where('user_id', $user->id)
                ->whereDate('planned_date', $date)
                ->where('meal_type', 'lunch')
                ->value('recipe_id'),
        );
    }

    public function test_user_cannot_remove_another_users_meal_plan(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $recipe = $this->createRecipe($owner);
        $plan = MealPlan::create([
            'user_id' => $owner->id,
            'recipe_id' => $recipe->id,
            'planned_date' => now()->toDateString(),
            'meal_type' => 'dinner',
        ]);

        $this->actingAs($otherUser)
            ->delete(route('meal-plans.destroy', $plan))
            ->assertForbidden();

        $this->assertDatabaseHas('meal_plans', ['id' => $plan->id]);
    }

    private function createRecipe(User $user): Recipe
    {
        $category = Category::create(['nama_kategori' => 'Masakan']);

        return Recipe::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'nama_resep' => 'Resep Uji',
            'deskripsi' => 'Resep untuk pengujian.',
            'waktu_masak' => 30,
            'porsi' => 2,
            'tingkat_kesulitan' => 'Mudah',
        ]);
    }
}
