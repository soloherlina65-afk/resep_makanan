<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Favorite;
use App\Models\Recipe;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_shows_counts_from_the_authenticated_users_activity(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $otherUser = User::factory()->create();
        $ownedRecipe = $this->createRecipe($user, 'Resep Milik User');
        $reviewedRecipe = $this->createRecipe($otherUser, 'Resep Direview');

        Favorite::create([
            'user_id' => $user->id,
            'recipe_id' => $reviewedRecipe->id,
            'type' => 'favorite',
        ]);
        Favorite::create([
            'user_id' => $user->id,
            'recipe_id' => $reviewedRecipe->id,
            'type' => 'like',
        ]);
        Favorite::create([
            'user_id' => $otherUser->id,
            'recipe_id' => $ownedRecipe->id,
            'type' => 'like',
        ]);
        Review::create([
            'user_id' => $user->id,
            'recipe_id' => $reviewedRecipe->id,
            'rating' => 5,
            'comment' => 'Komentar saya.',
        ]);
        $user->following()->attach($otherUser->id);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertViewHas('favoritesCount', 1)
            ->assertViewHas('likedRecipesCount', 1)
            ->assertViewHas('userRecipesCount', 1)
            ->assertViewHas('commentsCount', 1)
            ->assertViewHas('ratingsCount', 1)
            ->assertViewHas('likesReceivedCount', 1)
            ->assertViewHas('followingCount', 1)
            ->assertSee('Komentar Saya')
            ->assertSee('Rating Saya')
            ->assertSee('Pengguna Diikuti');
    }

    public function test_users_only_see_their_own_comments_and_ratings(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $otherUser = User::factory()->create();
        $recipeOne = $this->createRecipe($otherUser, 'Resep Pertama');
        $recipeTwo = $this->createRecipe($otherUser, 'Resep Kedua');
        Review::create([
            'user_id' => $user->id,
            'recipe_id' => $recipeOne->id,
            'rating' => 4,
            'comment' => 'Komentar milik saya.',
        ]);
        Review::create([
            'user_id' => $otherUser->id,
            'recipe_id' => $recipeTwo->id,
            'rating' => 2,
            'comment' => 'Komentar milik orang lain.',
        ]);

        $this->actingAs($user)
            ->get(route('reviews.mine.comments'))
            ->assertOk()
            ->assertSee('Komentar milik saya.')
            ->assertDontSee('Komentar milik orang lain.');

        $this->actingAs($user)
            ->get(route('reviews.mine.ratings'))
            ->assertOk()
            ->assertSee('Rating Saya')
            ->assertSee('Resep Pertama')
            ->assertDontSee('Resep Kedua');
    }

    private function createRecipe(User $user, string $name): Recipe
    {
        return Recipe::create([
            'user_id' => $user->id,
            'category_id' => Category::create(['nama_kategori' => 'Masakan'])->id,
            'nama_resep' => $name,
            'deskripsi' => 'Resep untuk tes dashboard.',
            'waktu_masak' => 30,
            'porsi' => 2,
            'tingkat_kesulitan' => 'Mudah',
        ]);
    }
}
