<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/**
 * Mesajul trimis catre firma cand cineva completeaza formularul de contact.
 *
 * NU implementeaza ShouldQueue: hostul e shared hosting Hostinger, fara supervisor,
 * deci nu ruleaza niciun worker. Mailul pleaca sincron. Vezi
 * .claude/context/DEPLOY-HOSTINGER.md.
 */
class ContactMessage extends Mailable
{
    /**
     * @param  array{name: string, prenume: string, phone: string, email: string, message: string}  $data
     */
    public function __construct(public readonly array $data) {}

    public function envelope(): Envelope
    {
        $sender = trim($this->data['prenume'].' '.$this->data['name']);

        return new Envelope(
            subject: 'Cerere nouă de pe energix.md — '.$sender,
            // Raspunzi direct clientului cu „Reply”, dar plicul ramane trimis de pe
            // adresa proprie, ca sa nu pice verificarea SPF/DMARC a domeniului.
            replyTo: [new Address($this->data['email'], $sender)],
        );
    }

    /**
     * HTML + text, NU markdown.
     *
     * Un sablon Markdown ar interpreta continutul campului `message` ca sintaxa:
     * `[click](http://evil.example)` din mesajul unui vizitator devenea un link real
     * in emailul primit de firma. Cu `view:`, Blade escapeaza si nu se parseaza nimic.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.contact',
            text: 'emails.contact-text',
            with: ['data' => $this->data],
        );
    }
}
