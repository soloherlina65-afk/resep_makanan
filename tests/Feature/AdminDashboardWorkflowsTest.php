<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Recipe;
use App\Models\Report;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AdminDashboardWorkflowsTest extends TestCase
{
    use RefreshDatabase;

    public function test_deactivated_users_cannot_log_in_or_continue_using_an_existing_session(): void
    {
        $user = User::factory()->create(['is_active' => false]);

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertSessionHasErrors('email');

        $this->actingAs($user->fresh())
            ->get(route('dashboard'))
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_admin_can_filter_edit_and_deactivate_users(): void
    {
        $admin = $this->createAdmin();
        $user = User::factory()->create(['name' => 'Dina User', 'email' => 'dina@example.test']);

        $this->actingAs($admin)
            ->get(route('admin.users.index', ['search' => 'dina', 'role' => 'user', 'status' => 'active']))
            ->assertOk()
            ->assertSee('Dina User')
            ->assertViewHas('users', fn ($users) => $users->total() === 1);

        $this->actingAs($admin)
            ->get(route('admin.users.show', $user))
            ->assertOk()
            ->assertSee('Edit data pengguna');

        $this->actingAs($admin)
            ->patch(route('admin.users.update', $user), [
                'name' => 'Dina Diperbarui',
                'email' => 'dina-updated@example.test',
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Dina Diperbarui',
            'email' => 'dina-updated@example.test',
            'email_verified_at' => null,
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.users.status', $user), ['is_active' => 0])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', ['id' => $user->id, 'is_active' => false]);
    }

    public function test_admin_can_moderate_recipe_visibility_and_hidden_recipes_are_not_public(): void
    {
        $admin = $this->createAdmin();
        $owner = User::factory()->create();
        $recipe = $this->createRecipe($owner, 'Resep Dimoderasi');

        $this->actingAs($admin)
            ->patch(route('admin.recipes.visibility', $recipe))
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('recipes', ['id' => $recipe->id, 'is_visible' => false]);

        Auth::logout();
        $this->get(route('recipes.show', $recipe))->assertForbidden();
        $this->actingAs($owner)->get(route('recipes.show', $recipe))->assertOk();
        $this->actingAs($admin)->get(route('recipes.show', $recipe))->assertOk();

        $this->actingAs($admin)
            ->get(route('admin.recipes.index', ['search' => 'Dimoderasi', 'visibility' => 'hidden']))
            ->assertOk()
            ->assertSee('Resep Dimoderasi');
    }

    public function test_users_can_report_content_and_admins_can_update_report_status(): void
    {
        $admin = $this->createAdmin();
        $reporter = User::factory()->create();
        $owner = User::factory()->create();
        $recipe = $this->createRecipe($owner, 'Resep Dilaporkan');

        $this->actingAs($reporter)
            ->post(route('recipes.reports.store', $recipe), [
                'reason' => 'spam',
                'details' => 'Berisi promosi berulang.',
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors()
            ->assertSessionHas('success');

        $report = Report::query()->firstOrFail();
        $this->assertSame($recipe->id, $report->reportable_id);
        $this->assertSame(Recipe::class, $report->reportable_type);

        $this->actingAs($admin)
            ->get(route('admin.reports.index', ['status' => Report::STATUS_NEW]))
            ->assertOk()
            ->assertSee('Resep Dilaporkan');

        $this->actingAs($admin)
            ->get(route('admin.reports.show', $report))
            ->assertOk()
            ->assertSee('Berisi promosi berulang.');

        $this->actingAs($admin)
            ->patch(route('admin.reports.status', $report), ['status' => Report::STATUS_RESOLVED])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('reports', ['id' => $report->id, 'status' => Report::STATUS_RESOLVED]);
    }

    public function test_admin_can_read_and_update_contact_message_status(): void
    {
        $admin = $this->createAdmin();
        $message = ContactMessage::create([
            'name' => 'Pengunjung',
            'email' => 'visitor@example.test',
            'subject' => 'Pertanyaan umum',
            'message' => 'Apakah resep bisa dibagikan?',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.messages.index', ['status' => ContactMessage::STATUS_NEW]))
            ->assertOk()
            ->assertSee('visitor@example.test');

        $this->actingAs($admin)
            ->get(route('admin.messages.show', $message))
            ->assertOk()
            ->assertSee('Apakah resep bisa dibagikan?');

        $this->actingAs($admin)
            ->patch(route('admin.messages.status', $message), ['status' => ContactMessage::STATUS_REPLIED])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('contact_messages', [
            'id' => $message->id,
            'status' => ContactMessage::STATUS_REPLIED,
        ]);
    }

    public function test_regular_users_cannot_access_new_admin_management_pages(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.reports.index'))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('error');

        $this->actingAs($user)
            ->get(route('admin.messages.index'))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('error');
    }

    private function createAdmin(): User
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'is_active' => true,
        ]);
        $admin->is_admin = true;
        $admin->save();

        return $admin;
    }

    private function createRecipe(User $user, string $name): Recipe
    {
        return Recipe::create([
            'user_id' => $user->id,
            'category_id' => Category::create(['nama_kategori' => 'Masakan'])->id,
            'nama_resep' => $name,
            'deskripsi' => 'Resep untuk tes dashboard admin.',
            'waktu_masak' => 30,
            'porsi' => 2,
            'tingkat_kesulitan' => 'Mudah',
        ]);
    }
}
