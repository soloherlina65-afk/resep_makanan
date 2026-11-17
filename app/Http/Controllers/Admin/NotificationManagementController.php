<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\View\View;

class NotificationManagementController extends Controller
{
    public function index(): View
    {
        return view('admin.notifications', [
            'notifications' => DatabaseNotification::with('notifiable')->latest()->paginate(30),
        ]);
    }

    public function destroy(string $notification): RedirectResponse
    {
        $record = DatabaseNotification::findOrFail($notification);
        $record->delete();

        return back()->with('success', 'Notifikasi berhasil dihapus.');
    }
}
