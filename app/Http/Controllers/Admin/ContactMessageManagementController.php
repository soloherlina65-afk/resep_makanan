<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ContactMessageManagementController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'status' => ['nullable', Rule::in([
                ContactMessage::STATUS_NEW,
                ContactMessage::STATUS_READ,
                ContactMessage::STATUS_REPLIED,
            ])],
        ]);
        $messages = ContactMessage::query()
            ->when(isset($filters['status']), fn ($query) => $query->where('status', $filters['status']))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.messages.index', [
            'messages' => $messages,
            'status' => $filters['status'] ?? '',
        ]);
    }

    public function show(ContactMessage $contactMessage): View
    {
        return view('admin.messages.show', ['message' => $contactMessage]);
    }

    public function updateStatus(
        Request $request,
        ContactMessage $contactMessage,
        ActivityLogger $activityLogger,
    ): RedirectResponse {
        $validated = $request->validate([
            'status' => ['required', Rule::in([
                ContactMessage::STATUS_NEW,
                ContactMessage::STATUS_READ,
                ContactMessage::STATUS_REPLIED,
            ])],
        ]);
        $contactMessage->update($validated);
        $activityLogger->log('Memperbarui status pesan kontak #'.$contactMessage->id, $contactMessage);

        return back()->with('success', 'Status pesan berhasil diperbarui.');
    }
}
