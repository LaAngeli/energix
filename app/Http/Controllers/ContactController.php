<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Mail\ContactMessage;
use App\Mail\ContactThankYou;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class ContactController extends Controller
{
    /**
     * Trimite doua emailuri la fiecare trimitere reusita:
     *   1. notificarea catre firma (lead-ul) — CRITICA;
     *   2. confirmarea inapoi la client — best-effort.
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

        // Emailurile se randeaza in limba paginii de pe care s-a trimis formularul.
        $locale = app()->getLocale();

        try {
            Mail::to(config('energix.mail_to'))->send((new ContactMessage($data))->locale($locale));
        } catch (Throwable $e) {
            Log::error('Formularul de contact nu a putut trimite notificarea.', [
                'exception' => $e->getMessage(),
                'lead' => $data,
            ]);

            return back()
                ->withInput()
                ->with('contact.error', __('site.form.error', ['phone' => config('energix.contact.phone')]));
        }

        /*
         | Confirmarea catre client e best-effort: lead-ul e deja capturat. Daca
         | pica (ex. email inexistent), o notam in log, dar userul vede tot succes —
         | mesajul LUI a ajuns la firma, ce conteaza pentru el.
         */
        try {
            Mail::to($data['email'])->send((new ContactThankYou($data))->locale($locale));
        } catch (Throwable $e) {
            Log::warning('Emailul de confirmare catre client nu a plecat.', [
                'exception' => $e->getMessage(),
                'email' => $data['email'],
            ]);
        }

        return back()->with('contact.success', __('site.form.success'));
    }
}
