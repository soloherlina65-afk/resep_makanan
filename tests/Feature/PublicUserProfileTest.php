<?php

namespace Tests\Feature;

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
}
