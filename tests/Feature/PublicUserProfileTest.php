<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicUserProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_user_profiles_and_follow_lists(): void
    {
        $profileUser = User::factory()->create(['name' => 'Pemilik Profil']);
        $follower = User::factory()->create(['name' => 'Pengikut Publik']);
        $followedUser = User::factory()->create(['name' => 'Diikuti Publik']);
        $follower->following()->attach($profileUser);
        $profileUser->following()->attach($followedUser);

        $this->get(route('users.show', $profileUser))
            ->assertOk()
            ->assertSee('Pemilik Profil');

        $this->get(route('users.followers', $profileUser))
            ->assertOk()
            ->assertSee('Pengikut Publik');

        $this->get(route('users.following', $profileUser))
            ->assertOk()
            ->assertSee('Diikuti Publik');
    }

    public function test_follow_and_unfollow_actions_still_require_authentication(): void
    {
        $user = User::factory()->create();

        $this->post(route('users.follow', $user))
            ->assertRedirect(route('login'));

        $this->delete(route('users.unfollow', $user))
            ->assertRedirect(route('login'));
    }

    public function test_user_can_view_and_manage_their_following_list(): void
    {
        $viewer = User::factory()->create();
        $followedUser = User::factory()->create(['name' => 'Akun yang Diikuti']);
        $viewer->following()->attach($followedUser);

        $this->actingAs($viewer)
            ->get(route('users.following', $viewer))
            ->assertOk()
            ->assertSee('Pengguna yang Diikuti')
            ->assertSee('Akun yang Diikuti')
            ->assertSee('Berhenti mengikuti');
    }

    public function test_public_profiles_link_to_visible_recipes_and_support_following(): void
    {
        $owner = User::factory()->create(['name' => 'Kreator Racikara']);
        $viewer = User::factory()->create();
        $category = Category::create(['nama_kategori' => 'Masakan']);
        $visibleRecipe = Recipe::create([
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'nama_resep' => 'Resep Komunitas',
            'deskripsi' => 'Resep yang dibagikan.',
            'waktu_masak' => 25,
            'porsi' => 2,
            'tingkat_kesulitan' => 'Mudah',
            'is_visible' => true,
        ]);
        Recipe::create([
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'nama_resep' => 'Resep Tersembunyi',
            'deskripsi' => 'Resep yang dimoderasi.',
            'waktu_masak' => 30,
            'porsi' => 2,
            'tingkat_kesulitan' => 'Mudah',
            'is_visible' => false,
        ]);

        $this->get(route('users.show', $owner))
            ->assertOk()
            ->assertSee('Resep Komunitas')
            ->assertDontSee('Resep Tersembunyi')
            ->assertSee(route('recipes.show', $visibleRecipe), false);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee(route('users.show', $owner), false);

        $this->actingAs($viewer)
            ->post(route('users.follow', $owner))
            ->assertRedirect();

        $this->get(route('users.show', $owner))
            ->assertOk()
            ->assertSee('Mengikuti');
    }

    public function test_community_directory_and_home_cards_offer_follow_actions(): void
    {
        $viewer = User::factory()->create(['name' => 'Pengguna Aktif']);
        $creator = User::factory()->create(['name' => 'Kreator Resep']);
        $inactiveUser = User::factory()->create([
            'name' => 'Pengguna Nonaktif',
            'is_active' => false,
        ]);
        $category = Category::create(['nama_kategori' => 'Masakan']);
        Recipe::create([
            'user_id' => $creator->id,
            'category_id' => $category->id,
            'nama_resep' => 'Resep untuk Diikuti',
            'deskripsi' => 'Resep komunitas.',
            'waktu_masak' => 20,
            'porsi' => 2,
            'tingkat_kesulitan' => 'Mudah',
            'is_visible' => true,
        ]);

        $this->actingAs($viewer)
            ->get(route('users.community'))
            ->assertOk()
            ->assertSee('Kreator Resep')
            ->assertDontSee('Pengguna Aktif')
            ->assertDontSee('Pengguna Nonaktif')
            ->assertSee('+ Ikuti');

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Resep untuk Diikuti')
            ->assertSee('+ Ikuti');

        $this->post(route('users.follow', $creator))
            ->assertRedirect();

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('✓ Mengikuti');
    }
}
