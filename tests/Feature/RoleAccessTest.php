<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Recipe;
use App\Models\Review;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_regular_users_are_redirected_from_admin_pages_with_an_error(): void
    {
        $user = User::factory()->create();

        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('login'));

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('error', 'Anda tidak memiliki izin untuk mengakses halaman tersebut.');

        $this->actingAs($user)
            ->get(route('admin.reviews.index'))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('error');
    }

    public function test_admin_dashboard_link_is_only_shown_to_logged_in_admins(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertDontSee('Dashboard Admin')
            ->assertDontSee('Moderasi Komentar');

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Dashboard Admin')
            ->assertSee('admin-dashboard-link');
    }

    public function test_regular_users_are_rejected_from_admin_management_routes(): void
    {
        $user = User::factory()->create();

        foreach ([
            'admin.recipes.index',
            'admin.categories.index',
            'admin.users.index',
            'admin.notifications.index',
            'admin.activities.index',
            'admin.settings.edit',
        ] as $routeName) {
            $this->actingAs($user)
                ->get(route($routeName))
                ->assertRedirect(route('dashboard'))
                ->assertSessionHas('error');
        }
    }

    public function test_regular_users_can_only_edit_and_delete_their_own_recipes_and_reviews(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $recipe = $this->createRecipe($owner);
        $review = Review::create([
            'user_id' => $owner->id,
            'recipe_id' => $recipe->id,
            'rating' => 4,
            'comment' => 'Komentar pemilik.',
        ]);

        $this->actingAs($otherUser)
            ->get(route('recipes.edit', $recipe))
            ->assertForbidden();

        $this->actingAs($otherUser)
            ->delete(route('recipes.destroy', $recipe))
            ->assertForbidden();

        $this->actingAs($otherUser)
            ->patch(route('reviews.update', $review), ['rating' => 1, 'comment' => 'Diubah'])
            ->assertForbidden();

        $this->actingAs($otherUser)
            ->delete(route('reviews.destroy', $review))
            ->assertForbidden();

        $this->assertDatabaseHas('recipes', ['id' => $recipe->id, 'user_id' => $owner->id]);
        $this->assertDatabaseHas('reviews', ['id' => $review->id, 'comment' => 'Komentar pemilik.']);
    }

    public function test_admin_can_access_admin_dashboard_and_manage_another_users_content(): void
    {
        $admin = User::factory()->create();
        $admin->role = User::ROLE_ADMIN;
        $admin->save();

        $owner = User::factory()->create();
        $recipe = $this->createRecipe($owner);
        $review = Review::create([
            'user_id' => $owner->id,
            'recipe_id' => $recipe->id,
            'rating' => 4,
            'comment' => 'Komentar.',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Dashboard Admin');

        $this->actingAs($admin)
            ->get(route('admin.recipes.index'))
            ->assertOk()
            ->assertSee('Resep Uji');

        $this->actingAs($admin)
            ->get(route('admin.categories.index'))
            ->assertOk()
            ->assertViewHas('categories', function ($categories) {
                return $categories->total() > 0;
            });

        $this->actingAs($admin)
            ->get(route('admin.users.index'))
            ->assertOk()
            ->assertSee($owner->email);

        $this->actingAs($admin)
            ->get(route('admin.notifications.index'))
            ->assertOk();

        $this->actingAs($admin)
            ->get(route('admin.activities.index'))
            ->assertOk();

        $this->actingAs($admin)
            ->get(route('admin.settings.edit'))
            ->assertOk();

        $this->actingAs($admin)
            ->get(route('recipes.edit', $recipe))
            ->assertOk();

        $this->actingAs($admin)
            ->patch(route('admin.reviews.visibility', $review))
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('reviews', ['id' => $review->id, 'is_visible' => false]);

        $this->actingAs($admin)
            ->delete(route('recipes.destroy', $recipe))
            ->assertRedirect(route('admin.recipes.index'));

        $this->assertDatabaseMissing('recipes', ['id' => $recipe->id]);
    }

    public function test_admin_dashboard_uses_real_recipes_and_filters_by_search_and_category(): void
    {
        $admin = User::factory()->create();
        $admin->role = User::ROLE_ADMIN;
        $admin->save();
        $owner = User::factory()->create(['name' => 'Pemilik Khusus']);
        $category = Category::create(['nama_kategori' => 'Kategori Khusus']);
        $matchingRecipe = $this->createRecipe($owner, $category, ['nama_resep' => 'Resep Istimewa']);
        $otherRecipe = $this->createRecipe($owner, Category::create(['nama_kategori' => 'Kategori Lain']), [
            'nama_resep' => 'Resep Berbeda',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Dashboard Admin')
            ->assertSee('Resep Istimewa')
            ->assertSee('Resep Berbeda')
            ->assertDontSee('Nasi Liwet Solo Komplit Daun Pisang');

        $this->actingAs($admin)
            ->get(route('admin.dashboard', ['search' => 'Pemilik Khusus']))
            ->assertOk()
            ->assertViewHas('latestRecipes', function ($recipes) use ($matchingRecipe, $otherRecipe) {
                return $recipes->total() === 2
                    && $recipes->getCollection()->contains(fn (Recipe $recipe) => $recipe->is($matchingRecipe))
                    && $recipes->getCollection()->contains(fn (Recipe $recipe) => $recipe->is($otherRecipe));
            });

        $this->actingAs($admin)
            ->get(route('admin.dashboard', ['category_id' => $category->id]))
            ->assertOk()
            ->assertViewHas('latestRecipes', function ($recipes) use ($matchingRecipe) {
                return $recipes->total() === 1
                    && $recipes->getCollection()->first()->is($matchingRecipe);
            });
    }

    public function test_admin_dashboard_displays_bundled_and_uploaded_recipe_photos(): void
    {
        $admin = User::factory()->create();
        $admin->role = User::ROLE_ADMIN;
        $admin->save();
        $owner = User::factory()->create();
        $category = Category::create(['nama_kategori' => 'Masakan']);
        $bundledPhotoRecipe = $this->createRecipe($owner, $category, [
            'nama_resep' => 'Ayam Kecap',
        ]);
        $uploadedPhotoRecipe = $this->createRecipe($owner, $category, [
            'nama_resep' => 'Resep Foto Unggahan',
            'foto' => 'recipes/foto-unggahan.jpg',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('src="'.asset('images/ayamkecap.png').'"', false)
            ->assertSee('src="'.asset('storage/recipes/foto-unggahan.jpg').'"', false)
            ->assertSee($bundledPhotoRecipe->nama_resep)
            ->assertSee($uploadedPhotoRecipe->nama_resep);
    }

    public function test_only_admins_can_change_user_roles_and_last_admin_is_preserved(): void
    {
        $admin = User::factory()->create();
        $admin->role = User::ROLE_ADMIN;
        $admin->save();
        $user = User::factory()->create();

        $this->actingAs($user)
            ->patch(route('admin.users.role', $user), ['role' => User::ROLE_ADMIN])
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('error');

        $this->actingAs($admin)
            ->patch(route('admin.users.role', $user), ['role' => User::ROLE_ADMIN])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertSame(User::ROLE_ADMIN, $user->fresh()->role);
        $this->assertTrue($user->fresh()->is_admin);

        $this->actingAs($admin)
            ->patch(route('admin.users.role', $admin), ['role' => User::ROLE_USER])
            ->assertRedirect()
            ->assertSessionHas('error');
    }

    public function test_regular_user_cannot_update_team_members_or_escalate_role_in_profile(): void
    {
        $user = User::factory()->create();
        $member = TeamMember::firstOrFail();

        $this->actingAs($user)
            ->patch(route('team-members.update'), [
                'members' => TeamMember::all()->map(fn (TeamMember $teamMember) => [
                    'id' => $teamMember->id,
                    'name' => $teamMember->name,
                    'division' => $teamMember->division,
                ])->all(),
            ])
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('error');

        $this->actingAs($user)
            ->patch(route('profile.update'), [
                'name' => 'User Test',
                'email' => $user->email,
                'role' => User::ROLE_ADMIN,
                'is_admin' => true,
            ])
            ->assertRedirect(route('profile.edit'));

        $this->assertSame(User::ROLE_USER, $user->fresh()->role);
        $this->assertDatabaseHas('team_members', ['id' => $member->id, 'name' => $member->name]);
    }

    public function test_admin_can_create_update_and_delete_team_members_and_categories(): void
    {
        $admin = User::factory()->create();
        $admin->role = User::ROLE_ADMIN;
        $admin->save();
        $member = TeamMember::firstOrFail();

        $this->actingAs($admin)
            ->post(route('team-members.store'), [
                'name' => 'Anggota Baru',
                'division' => 'Koki',
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();
        $this->assertDatabaseHas('team_members', ['name' => 'Anggota Baru', 'division' => 'Koki']);

        $this->actingAs($admin)
            ->patch(route('team-members.update'), [
                'members' => [[
                    'id' => $member->id,
                    'name' => 'Anggota Diperbarui',
                    'division' => $member->division,
                ]],
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();
        $this->assertDatabaseHas('team_members', ['id' => $member->id, 'name' => 'Anggota Diperbarui']);

        $newMember = TeamMember::where('name', 'Anggota Baru')->firstOrFail();
        $this->actingAs($admin)
            ->delete(route('team-members.destroy', $newMember))
            ->assertRedirect();
        $this->assertDatabaseMissing('team_members', ['id' => $newMember->id]);

        $category = Category::create(['nama_kategori' => 'Kategori Uji']);
        $this->actingAs($admin)
            ->patch(route('admin.categories.update', $category), ['nama_kategori' => 'Kategori Diperbarui'])
            ->assertRedirect()
            ->assertSessionHasNoErrors();
        $this->assertDatabaseHas('categories', ['id' => $category->id, 'nama_kategori' => 'Kategori Diperbarui']);

        $this->actingAs($admin)
            ->delete(route('admin.categories.destroy', $category))
            ->assertRedirect()
            ->assertSessionHasNoErrors();
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    private function createRecipe(User $user, ?Category $category = null, array $attributes = []): Recipe
    {
        $category ??= Category::create(['nama_kategori' => 'Masakan']);

        return Recipe::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'nama_resep' => 'Resep Uji',
            'deskripsi' => 'Resep untuk pengujian.',
            'waktu_masak' => 30,
            'porsi' => 2,
            'tingkat_kesulitan' => 'Mudah',
            ...$attributes,
        ]);
    }
}
