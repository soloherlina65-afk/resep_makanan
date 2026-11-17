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
        $search = trim((string) $request->query('search', ''));
        $users = User::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%');
                });
            })
            ->orderBy('name')
            ->paginate(25)
            ->withQueryString();

        return view('admin.users', compact('users', 'search'));
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

        if ($user->isAdmin() && $newRole !== User::ROLE_ADMIN && User::where('role', User::ROLE_ADMIN)->count() <= 1) {
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

        if ($user->isAdmin() && User::where('role', User::ROLE_ADMIN)->count() <= 1) {
            return back()->with('error', 'Admin terakhir tidak dapat dihapus.');
        }

        $name = $user->name;
        $activityLogger->log('Menghapus akun pengguna '.$name, $user);
        $user->delete();

        return back()->with('success', 'Akun pengguna berhasil dihapus.');
    }
}
