<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class FollowController extends Controller
{
    public function follow($id)
    {
        $user = User::findOrFail($id);

        if (auth()->id() == $user->id) {
            return back()->with('error', 'Tidak bisa mengikuti akun sendiri.');
        }

        auth()->user()->following()->syncWithoutDetaching([$user->id]);

        return back()->with('success', 'Berhasil mengikuti pengguna.');
    }

    public function unfollow($id)
    {
        $user = User::findOrFail($id);

        auth()->user()->following()->detach($user->id);

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
