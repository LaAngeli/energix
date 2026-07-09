<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Throwable;

/**
 * Validarea formularului de contact.
 *
 * Predecesorul (`send_email_php.php` din site-ul vechi) accepta orice POST de pe orice
 * origine, fara CSRF, fara rate limit, fara validare server-side. Era, efectiv, un relay
 * de spam deschis. Vezi .claude/context/SECURITY-NOTES.md.
 */
class ContactRequest extends FormRequest
{
    /** Campul-capcana. Utilizatorii nu il vad; botii il completeaza. */
    public const HONEYPOT = 'website';

    /** Momentul randarii formularului, criptat. */
    public const TIMESTAMP = 'rendered_at';

    /** Un om nu completeaza si nu trimite un formular mai repede de atat. */
    private const MIN_SECONDS_TO_SUBMIT = 3;

    /** Peste atat, formularul e vechi si se cere reincarcat. */
    private const MAX_SECONDS_TO_SUBMIT = 12 * 3600;

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:50'],
            'prenume' => ['required', 'string', 'min:2', 'max:50'],
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9+()\s-]{6,20}$/'],
            'email' => ['required', 'string', 'email:rfc', 'max:100'],
            'message' => ['required', 'string', 'min:10', 'max:2000'],

            self::HONEYPOT => ['prohibited'],
            self::TIMESTAMP => ['required', 'string'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Spune-ne cum te cheamă.',
            'prenume.required' => 'Ne trebuie și prenumele.',
            'phone.required' => 'Fără număr de telefon nu te putem suna înapoi.',
            'phone.regex' => 'Numărul de telefon nu pare valid.',
            'email.required' => 'Ne trebuie un email ca să îți trimitem oferta.',
            'email.email' => 'Adresa de email nu pare validă.',
            'message.required' => 'Descrie-ne pe scurt ce ai nevoie.',
            'message.min' => 'Scrie câteva cuvinte în plus, ca să înțelegem ce îți trebuie.',
            self::HONEYPOT.'.prohibited' => 'Mesajul nu a putut fi trimis.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'numele',
            'prenume' => 'prenumele',
            'phone' => 'telefonul',
            'email' => 'emailul',
            'message' => 'mesajul',
        ];
    }

    protected function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if (! $this->wasRenderedLongEnoughAgo()) {
                $validator->errors()->add('message', 'Mesajul nu a putut fi trimis. Reîncarcă pagina și încearcă din nou.');
            }
        });
    }

    /**
     * Timestamp-ul e criptat cu APP_KEY, deci un bot nu il poate fabrica pentru a ocoli
     * verificarea. Daca decriptarea esueaza, cererea e respinsa.
     */
    private function wasRenderedLongEnoughAgo(): bool
    {
        try {
            $renderedAt = (int) decrypt($this->string(self::TIMESTAMP)->toString());
        } catch (Throwable) {
            return false;
        }

        $elapsed = time() - $renderedAt;

        return $elapsed >= self::MIN_SECONDS_TO_SUBMIT
            && $elapsed <= self::MAX_SECONDS_TO_SUBMIT;
    }
}
