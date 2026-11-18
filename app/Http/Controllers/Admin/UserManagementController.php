<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'role' => ['nullable', Rule::in([User::ROLE_USER, User::ROLE_ADMIN])],
            'status' => ['nullable', Rule::in(['active', 'inactive'])],
        ]);
        $search = trim($filters['search'] ?? '');
        $users = User::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%');
                });
            })
            ->when(($filters['role'] ?? null) === User::ROLE_ADMIN, fn ($query) => $query->where(function ($query) {
                $query->where('role', User::ROLE_ADMIN)->orWhere('is_admin', true);
            }))
            ->when(($filters['role'] ?? null) === User::ROLE_USER, fn ($query) => $query
                ->where('role', User::ROLE_USER)
                ->where('is_admin', false))
            ->when(isset($filters['status']), fn ($query) => $query->where(
                'is_active',
                $filters['status'] === 'active',
            ))
            ->orderBy('name')
            ->paginate(25)
            ->withQueryString();

        return view('admin.users', [
            'users' => $users,
            'search' => $search,
            'role' => $filters['role'] ?? '',
            'status' => $filters['status'] ?? '',
        ]);
    }

    public function show(User $user): View
    {
        $user->loadCount(['recipes', 'reviews', 'favorites', 'followers', 'following']);

        return view('admin.user-show', compact('user'));
    }

    public function updateDetails(Request $request, User $user, ActivityLogger $activityLogger): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email:rfc', 'max:254', Rule::unique('users', 'email')->ignore($user->id)],
        ]);
        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();
        $activityLogger->log('Memperbarui data pengguna '.$user->name, $user);

        return back()->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function updateStatus(Request $request, User $user, ActivityLogger $activityLogger): RedirectResponse
    {
        $validated = $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);
        $isActive = $request->boolean('is_active');

        if (! $isActive && $request->user()->is($user)) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun yang sedang digunakan.');
        }

        if (! $isActive && $user->isAdmin() && $this->adminUsers()->where('id', '<>', $user->id)->count() === 0) {
            return back()->with('error', 'Admin terakhir tidak dapat dinonaktifkan.');
        }

        $user->forceFill(['is_active' => $isActive])->save();
        $activityLogger->log(
            ($isActive ? 'Mengaktifkan' : 'Menonaktifkan').' akun '.$user->name,
            $user,
        );

        return back()->with('success', 'Status akun berhasil diperbarui.');
    }

    public function updateRole(Request $request, User $user, ActivityLogger $activityLogger): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', Rule::in([User::ROLE_USER, User::ROLE_ADMIN])],
        ]);
        $newRole = $validated['role'];

        if ($request->user()->is($user) && $newRole !== User::ROLE_ADMIN) {
            return back()->with('error', 'Anda tidak dapat menurunkan role akun yang sedang digunakan.');
        }

        if ($user->isAdmin() && $newRole !== User::ROLE_ADMIN
            && $this->adminUsers()->where('id', '<>', $user->id)->count() === 0) {
            return back()->with('error', 'Setidaknya harus ada satu akun admin.');
        }

        $user->forceFill([
            'role' => $newRole,
            'is_admin' => $newRole === User::ROLE_ADMIN,
        ])->save();
        $activityLogger->log('Mengubah role '.$user->name.' menjadi '.$newRole, $user);

        return back()->with('success', 'Role pengguna berhasil diperbarui.');
    }

    public function destroy(Request $request, User $user, ActivityLogger $activityLogger): RedirectResponse
    {
        if ($request->user()->is($user)) {
            return back()->with('error', 'Anda tidak dapat menghapus akun yang sedang digunakan.');
        }

        if ($user->isAdmin() && $this->adminUsers()->where('id', '<>', $user->id)->count() === 0) {
            return back()->with('error', 'Admin terakhir tidak dapat dihapus.');
        }

        $name = $user->name;
        $activityLogger->log('Menghapus akun pengguna '.$name, $user);
        $user->delete();

        return back()->with('success', 'Akun pengguna berhasil dihapus.');
    }

    private function adminUsers()
    {
        return User::query()->where(function ($query) {
            $query->where('role', User::ROLE_ADMIN)
                ->orWhere('is_admin', true);
        });
    }
}
