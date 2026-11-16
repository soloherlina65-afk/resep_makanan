<?php

namespace Tests\Feature;

use App\Mail\ContactMessageMail;
use Illuminate\Mail\PendingMail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportException;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    public function test_contact_page_renders_the_application_form_instead_of_a_mailto_link(): void
    {
        Config::set('mail.contact_to', null);

        $this->get(route('contact'))
            ->assertOk()
            ->assertSee(route('contact.send'), false)
            ->assertDontSee('mailto:halo@resepkita.id')
            ->assertSee('Alamat email belum dikonfigurasi');
    }

    public function test_contact_form_sends_a_message_to_the_configured_recipient(): void
    {
        Mail::fake();
        Config::set('mail.contact_to', 'support@racikara.test');

        $this->post(route('contact.send'), [
            'nama' => 'Sarah Abdullah',
            'email' => 'sarah@example.test',
            'subjek' => 'recipe_suggestion',
            'pesan' => 'Saya ingin menyarankan resep baru.',
        ])
            ->assertRedirect(route('contact'))
            ->assertSessionHasNoErrors()
            ->assertSessionHas('success');

        Mail::assertSent(ContactMessageMail::class, function (ContactMessageMail $mail) {
            $content = $mail->render();

            return $mail->name === 'Sarah Abdullah'
                && $mail->subjectLabel === 'Saran resep'
                && $mail->hasTo('support@racikara.test')
                && $mail->hasReplyTo('sarah@example.test')
                && str_contains($content, 'Saya ingin menyarankan resep baru.');
        });
    }

    public function test_contact_form_rejects_invalid_input_without_sending_mail(): void
    {
        Mail::fake();

        $this->from(route('contact'))
            ->post(route('contact.send'), [
                'nama' => '',
                'email' => 'bukan-email',
                'subjek' => 'tidak-valid',
                'pesan' => '',
            ])
            ->assertRedirect(route('contact'))
            ->assertSessionHasErrors(['nama', 'email', 'subjek', 'pesan']);

        Mail::assertNothingSent();
    }

    public function test_contact_form_does_not_claim_success_when_no_recipient_is_configured(): void
    {
        Mail::fake();
        Config::set('mail.contact_to', null);

        $this->from(route('contact'))
            ->post(route('contact.send'), [
                'nama' => 'Sarah Abdullah',
                'email' => 'sarah@example.test',
                'subjek' => 'general',
                'pesan' => 'Halo Racikara.',
            ])
            ->assertRedirect(route('contact'))
            ->assertSessionHas('error');

        Mail::assertNothingSent();
    }

    public function test_contact_form_shows_a_friendly_error_when_smtp_authentication_fails(): void
    {
        Config::set('mail.contact_to', 'support@racikara.test');

        $pendingMail = \Mockery::mock(PendingMail::class);
        $pendingMail->shouldReceive('send')
            ->once()
            ->with(\Mockery::type(ContactMessageMail::class))
            ->andThrow(new TransportException('SMTP authentication failed.'));

        Mail::shouldReceive('to')
            ->once()
            ->with('support@racikara.test')
            ->andReturn($pendingMail);

        $this->from(route('contact'))
            ->post(route('contact.send'), [
                'nama' => 'Sarah Abdullah',
                'email' => 'sarah@example.test',
                'subjek' => 'general',
                'pesan' => 'Halo Racikara.',
            ])
            ->assertRedirect(route('contact'))
            ->assertSessionHas('error', 'Email belum dapat dikirim. Periksa konfigurasi SMTP; untuk Gmail, gunakan App Password, bukan password akun biasa.')
            ->assertSessionHasInput('pesan', 'Halo Racikara.');
    }
}
