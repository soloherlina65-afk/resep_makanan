<?php

namespace App\Http\Controllers;

use App\Models\TeamMember;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeamMemberController extends Controller
{
    public function update(Request $request, ActivityLogger $activityLogger): RedirectResponse
    {
        $validated = $request->validate([
            'members' => ['required', 'array', 'min:1'],
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
            $activityLogger->log('Memperbarui informasi anggota tim ' . $member->name, $member);
        }

        return back()->with('status', 'Data anggota dan divisi berhasil diperbarui.');
    }

    public function store(Request $request, ActivityLogger $activityLogger): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'division' => ['required', 'string', 'max:100'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);
        $position = (int) TeamMember::max('position') + 1;
        if ($position > 255) {
            return back()->with('error', 'Jumlah anggota tim sudah mencapai batas.');
        }

        $member = TeamMember::create([
            'position' => $position,
            'initial' => mb_strtoupper(mb_substr($validated['name'], 0, 1)),
            'name' => $validated['name'],
            'division' => $validated['division'],
            'photo' => $request->hasFile('photo')
                ? $request->file('photo')->store('team-members', 'public')
                : null,
        ]);
        $activityLogger->log('Menambahkan anggota tim ' . $member->name, $member);

        return back()->with('success', 'Anggota tim berhasil ditambahkan.');
    }

    public function destroy(TeamMember $teamMember, ActivityLogger $activityLogger): RedirectResponse
    {
        $name = $teamMember->name;

        if ($teamMember->photo) {
            Storage::disk('public')->delete($teamMember->photo);
        }

        $teamMember->delete();
        $activityLogger->log('Menghapus anggota tim ' . $name, $teamMember);

        return back()->with('success', 'Anggota tim berhasil dihapus.');
    }
}
