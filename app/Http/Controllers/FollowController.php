<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Services\ActivityLogger;

class FollowController extends Controller
{
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
        $user = User::withCount(['followers', 'following', 'recipes'])
            ->findOrFail($id);

        $isFollowing = auth()->check()
            ? auth()->user()->following()->where('following_id', $id)->exists()
            : false;

        $recipes = $user->recipes()->latest()->get();

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
        $users = $user->following()->paginate(12);

        return view('users.following', compact('user', 'users'));
    }
}
