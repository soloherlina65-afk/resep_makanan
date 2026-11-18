<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Services\ActivityLogger;

class FollowController extends Controller
{
    public function community(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:80'],
        ]);
        $search = trim($validated['search'] ?? '');

        $users = User::query()
            ->where('is_active', true)
            ->when(auth()->check(), fn ($query) => $query->where('id', '!=', auth()->id()))
            ->when($search !== '', fn ($query) => $query->where('name', 'like', '%' . $search . '%'))
            ->withCount([
                'followers',
                'recipes as recipes_count' => fn ($query) => $query->where('is_visible', true),
            ])
            ->orderByDesc('recipes_count')
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $followingUserIds = auth()->check()
            ? auth()->user()->following()->pluck('users.id')->map(fn ($id) => (int) $id)->all()
            : [];

        return view('users.community', compact('users', 'followingUserIds', 'search'));
    }

    public function follow($id, ActivityLogger $activityLogger)
    {
        $user = User::findOrFail($id);

        if (auth()->id() == $user->id) {
            return back()->with('error', 'Tidak bisa mengikuti akun sendiri.');
        }

        $alreadyFollowing = auth()->user()->following()->whereKey($user->id)->exists();
        auth()->user()->following()->syncWithoutDetaching([$user->id]);
        if (! $alreadyFollowing) {
            $activityLogger->log('Mulai mengikuti ' . $user->name, $user);
        }

        return back()->with('success', 'Berhasil mengikuti pengguna.');
    }

    public function unfollow($id, ActivityLogger $activityLogger)
    {
        $user = User::findOrFail($id);

        $wasFollowing = auth()->user()->following()->whereKey($user->id)->exists();
        auth()->user()->following()->detach($user->id);
        if ($wasFollowing) {
            $activityLogger->log('Berhenti mengikuti ' . $user->name, $user);
        }

        return back()->with('success', 'Berhasil berhenti mengikuti pengguna.');
    }

    public function show($id)
    {
        $user = User::withCount(['followers', 'following'])->findOrFail($id);

        $isFollowing = auth()->check()
            ? auth()->user()->following()->where('following_id', $id)->exists()
            : false;

        $canViewHiddenRecipes = auth()->user()?->isAdmin() || auth()->id() === $user->id;
        $recipesQuery = $user->recipes()
            ->when(! $canViewHiddenRecipes, fn ($query) => $query->where('is_visible', true));
        $user->setAttribute('recipes_count', (clone $recipesQuery)->count());

        $recipes = $recipesQuery
            ->with('category')
            ->withCount(['likes', 'visibleReviews'])
            ->withAvg('visibleReviews', 'rating')
            ->latest()
            ->get();

        return view('users.show', compact('user', 'isFollowing', 'recipes'));
    }

    public function followers($id)
    {
        $user = User::findOrFail($id);
        $users = $user->followers()->paginate(12);

        return view('users.followers', compact('user', 'users'));
    }

    public function following($id)
    {
        $user = User::findOrFail($id);
        $users = $user->following()
            ->withCount('recipes')
            ->orderBy('name')
            ->paginate(12);

        return view('users.following', compact('user', 'users'));
    }
}
