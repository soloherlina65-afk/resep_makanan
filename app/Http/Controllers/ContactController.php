<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessageMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Mail\PendingMail;
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

        $recipient = config('mail.contact_to');
        if (! is_string($recipient) || filter_var($recipient, FILTER_VALIDATE_EMAIL) === false) {
            return back()->withInput()->with(
                'error',
                'Formulir kontak belum siap menerima pesan. Alamat penerima perlu dikonfigurasi oleh pengelola.',
            );
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

            return back()->withInput()->with(
                'error',
                'Email belum dapat dikirim. Periksa konfigurasi SMTP; untuk Gmail, gunakan App Password, bukan password akun biasa.',
            );
        }

        return redirect()->route('contact')
            ->with('success', 'Pesan berhasil dikirim. Terima kasih sudah menghubungi Racikara.');
    }
}
