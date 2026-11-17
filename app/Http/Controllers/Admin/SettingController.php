<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings', [
            'contactEmail' => SiteSetting::valueFor('contact_to_address', config('mail.contact_to')),
        ]);
    }

    public function update(Request $request, ActivityLogger $activityLogger): RedirectResponse
    {
        $validated = $request->validate([
            'contact_email' => ['nullable', 'email:rfc', 'max:254'],
        ]);
        SiteSetting::updateOrCreate(
            ['key' => 'contact_to_address'],
            ['value' => $validated['contact_email'] ?: null],
        );
        $activityLogger->log('Memperbarui alamat email penerima formulir kontak.');

        return back()->with('success', 'Pengaturan aplikasi berhasil disimpan.');
    }
}
