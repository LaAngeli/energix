<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Mail\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class ContactController extends Controller
{
    /**
     * Trimite mesajul formularului catre firma.
     *
     * Sincron, nu prin coada: hostul e shared hosting fara worker.
     *
     * Nu exista baza de date, deci daca SMTP-ul pica, cererea clientului s-ar pierde.
     * Compromisul asumat e sa o scriem in log, ca sa poata fi recuperata manual.
     * Vezi .claude/context/DEPLOY-HOSTINGER.md.
     */
    public function store(ContactRequest $request): RedirectResponse
    {
        /** @var array{name: string, prenume: string, phone: string, email: string, message: string} $data */
        $data = $request->safe()->only(['name', 'prenume', 'phone', 'email', 'message']);

        try {
            Mail::to(config('energix.mail_to'))->send(new ContactMessage($data));
        } catch (Throwable $e) {
            Log::error('Formularul de contact nu a putut trimite emailul.', [
                'exception' => $e->getMessage(),
                'lead' => $data,
            ]);

            return back()
                ->withInput()
                ->with('contact.error', 'Nu am reușit să trimitem mesajul. Sună-ne direct la '.config('energix.contact.phone').'.');
        }

        return back()
            ->with('contact.success', 'Am primit mesajul. Te contactăm în cel mai scurt timp.');
    }
}
