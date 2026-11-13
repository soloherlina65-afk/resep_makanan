<?php

namespace App\Http\Controllers;

use App\Models\TeamMember;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeamMemberController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'members' => ['required', 'array', 'size:4'],
            'members.*.id' => ['required', 'integer', 'exists:team_members,id'],
            'members.*.name' => ['required', 'string', 'max:100'],
            'members.*.division' => ['required', 'string', 'max:100'],
            'members.*.photo' => ['nullable', 'image', 'max:2048'],
        ]);

        foreach ($validated['members'] as $index => $memberData) {
            $member = TeamMember::findOrFail($memberData['id']);
            $updates = [
                'name' => $memberData['name'],
                'division' => $memberData['division'],
                'initial' => mb_strtoupper(mb_substr($memberData['name'], 0, 1)),
            ];

            if ($request->hasFile("members.$index.photo")) {
                if ($member->photo) {
                    Storage::disk('public')->delete($member->photo);
                }

                $updates['photo'] = $request->file("members.$index.photo")->store('team-members', 'public');
            }

            $member->update($updates);
        }

        return back()->with('status', 'Data anggota dan divisi berhasil diperbarui.');
    }
}
