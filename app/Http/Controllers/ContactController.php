<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessageMail;
use App\Models\ContactMessage;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class ContactController extends Controller
{
    private const SUBJECTS = [
        'recipe_suggestion' => 'Saran resep',
        'account_issue' => 'Kendala akun',
        'partnership' => 'Kerja sama',
        'general' => 'Pertanyaan umum',
    ];

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:254'],
            'subjek' => ['required', 'in:' . implode(',', array_keys(self::SUBJECTS))],
            'pesan' => ['required', 'string', 'max:5000'],
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Masukkan alamat email yang valid.',
            'subjek.in' => 'Pilih subjek pesan yang tersedia.',
            'pesan.required' => 'Pesan wajib diisi.',
            'pesan.max' => 'Pesan maksimal 5.000 karakter.',
        ]);

        ContactMessage::create([
            'name' => $validated['nama'],
            'email' => $validated['email'],
            'subject' => self::SUBJECTS[$validated['subjek']],
            'message' => $validated['pesan'],
        ]);
        $recipient = SiteSetting::valueFor('contact_to_address', config('mail.contact_to'));
        if (! is_string($recipient) || filter_var($recipient, FILTER_VALIDATE_EMAIL) === false) {
            return redirect()->route('contact')
                ->with('success', 'Pesan tersimpan di kotak masuk Racikara.')
                ->with('error', 'Notifikasi email belum dikirim karena alamat penerima belum dikonfigurasi.');
        }

        try {
            Mail::to($recipient)->send(new ContactMessageMail(
                name: $validated['nama'],
                email: $validated['email'],
                subjectLabel: self::SUBJECTS[$validated['subjek']],
                messageBody: $validated['pesan'],
            ));
        } catch (TransportExceptionInterface $exception) {
            report($exception);

            return redirect()->route('contact')
                ->with('success', 'Pesan tersimpan di kotak masuk Racikara.')
                ->with('error', 'Notifikasi email gagal dikirim. Admin tetap dapat membaca pesan di dashboard.');
        }

        return redirect()->route('contact')
            ->with('success', 'Pesan berhasil dikirim dan tersimpan. Terima kasih sudah menghubungi Racikara.');
    }
}
