<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Recipe;
use App\Models\ResepBahan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipeDiscoveryTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_search_matches_ingredients_and_applies_recipe_filters(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['nama_kategori' => 'Masakan Nusantara']);
        $matchingRecipe = $this->createRecipe($user, $category, [
            'nama_resep' => 'Tumis Sayur',
            'waktu_masak' => 25,
            'tingkat_kesulitan' => 'Mudah',
        ]);
        ResepBahan::create([
            'recipe_id' => $matchingRecipe->id,
            'nama_bahan' => '2 siung bawang putih',
        ]);
        $this->createRecipe($user, $category, [
            'nama_resep' => 'Sup Bawang',
            'waktu_masak' => 45,
            'tingkat_kesulitan' => 'Sedang',
        ]);

        $this->get(route('home', [
            'search' => 'bawang',
            'category_id' => $category->id,
            'max_cook_time' => 30,
            'difficulty' => 'Mudah',
        ]))
            ->assertOk()
            ->assertViewHas('recipes', function ($recipes) use ($matchingRecipe) {
                return $recipes->total() === 1
                    && $recipes->getCollection()->first()->is($matchingRecipe);
            });
    }

    public function test_home_hides_category_cards_but_search_still_matches_category_names(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['nama_kategori' => 'Masakan Nusantara']);
        $recipe = $this->createRecipe($user, $category, ['nama_resep' => 'Tumis Sayur']);

        $this->get(route('home'))
            ->assertOk()
            ->assertDontSee('category-card', false)
            ->assertSee('href="#kategori"', false)
            ->assertSee('id="kategori"', false)
            ->assertSee('Masakan Nusantara');

        $this->get(route('home', ['search' => 'Nusantara']))
            ->assertOk()
            ->assertSee('Tumis Sayur')
            ->assertViewHas('recipes', function ($recipes) use ($recipe) {
                return $recipes->total() === 1
                    && $recipes->getCollection()->first()->is($recipe);
            });
    }

    public function test_filtered_results_are_paginated_and_keep_query_parameters(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['nama_kategori' => 'Masakan']);

        for ($index = 1; $index <= 10; $index++) {
            $this->createRecipe($user, $category, ['nama_resep' => 'Resep '.$index]);
        }

        $this->get(route('home', ['category_id' => $category->id, 'page' => 2]))
            ->assertOk()
            ->assertViewHas('recipes', function ($recipes) {
                return $recipes->total() === 10
                    && $recipes->perPage() === 9
                    && $recipes->currentPage() === 2
                    && $recipes->count() === 1;
            })
            ->assertSee('category_id='.$category->id, false);
    }

    public function test_search_suggestions_match_ingredients(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['nama_kategori' => 'Minuman']);
        $recipe = $this->createRecipe($user, $category, ['nama_resep' => 'Wedang Hangat']);
        ResepBahan::create([
            'recipe_id' => $recipe->id,
            'nama_bahan' => 'jahe segar',
        ]);

        $this->getJson(route('recipes.suggestions', ['q' => 'jahe']))
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment([
                'name' => 'Wedang Hangat',
                'category' => 'Minuman',
                'url' => route('recipes.show', $recipe),
            ]);
    }

    public function test_pancake_and_udang_padang_recipes_have_ingredients_and_cooking_steps(): void
    {
        foreach (['Pancake Klasik Madu', 'Udang Saus Padang'] as $name) {
            $recipe = Recipe::with(['bahan', 'steps'])
                ->where('nama_resep', $name)
                ->firstOrFail();

            $this->assertNotEmpty($recipe->deskripsi);
            $this->assertNotEmpty($recipe->foto);
            $this->assertGreaterThanOrEqual(6, $recipe->bahan->count());
            $this->assertGreaterThanOrEqual(5, $recipe->steps->count());
            $this->assertSame(
                range(1, $recipe->steps->count()),
                $recipe->steps->pluck('urutan')->all(),
            );
        }
    }

    private function createRecipe(User $user, Category $category, array $attributes = []): Recipe
    {
        return Recipe::create(array_merge([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'nama_resep' => 'Resep Uji',
            'deskripsi' => 'Resep untuk pengujian.',
            'waktu_masak' => 30,
            'porsi' => 2,
            'tingkat_kesulitan' => 'Mudah',
        ], $attributes));
    }
}
