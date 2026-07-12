<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/**
 * Confirmarea trimisa INAPOI clientului, imediat dupa o trimitere reusita.
 *
 * Sincron, ca si notificarea (shared hosting fara worker — vezi DEPLOY-HOSTINGER.md).
 * In controller e best-effort: daca pica (ex. email inexistent), lead-ul e deja
 * capturat, deci nu blocheaza raspunsul si nu sperie userul.
 *
 * Blade (`view:` + `text:`), NU markdown: ecoul mesajului clientului e continut de
 * utilizator si nu trebuie interpretat ca sintaxa. Acelasi motiv ca la ContactMessage.
 */
class ContactThankYou extends Mailable
{
    /**
     * @param  array{name: string, prenume: string, phone: string, email: string, message: string}  $data
     */
    public function __construct(public readonly array $data) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('site.email.thanks.subject'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.thanks',
            text: 'emails.thanks-text',
            with: ['data' => $this->data],
        );
    }
}
