<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Throwable;

/**
 * Validarea formularului de contact.
 *
 * Predecesorul (`send_email_php.php` din site-ul vechi) accepta orice POST de pe orice
 * origine, fara CSRF, fara rate limit, fara validare server-side. Era, efectiv, un relay
 * de spam deschis. Vezi .claude/context/SECURITY-NOTES.md.
 *
 * Straturile anti-bot/spam, in ordinea in care lovesc:
 *   1. `throttle:5,1` pe ruta (in routes/web.php) — volumul.
 *   2. Honeypot: camp invizibil pe care doar botii il completeaza.
 *   3. Capcana de timp: momentul randarii, criptat cu APP_KEY — sub 3s = bot,
 *      peste 12h = formular expirat. Un bot nu poate fabrica timestamp-ul.
 *   4. Validare stricta de continut: nume doar din litere, telefon cu cifre
 *      reale, mesaj fara linkuri (semnatura tipica a spamului).
 * Mesajele de eroare vin din `lang/`, deci apar in limba paginii.
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

    /**
     * Nume de persoana: grupuri de litere (orice alfabet — diacritice romanesti,
     * chirilice) legate de un singur spatiu, cratima sau apostrof. Respinge cifre,
     * simboluri, separatori dublati sau la margini.
     */
    private const NAME_PATTERN = "/^[\pL\pM]+(?:[ '\x{2019}-][\pL\pM]+)*$/u";

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, list<Closure|string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:50', 'regex:'.self::NAME_PATTERN],
            'prenume' => ['required', 'string', 'min:2', 'max:50', 'regex:'.self::NAME_PATTERN],
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9+()\s-]{6,20}$/', $this->phoneHasRealDigits(...)],
            'email' => ['required', 'string', 'email:rfc', 'max:100'],
            'message' => ['required', 'string', 'min:10', 'max:2000', $this->messageIsNotLinkSpam(...)],

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
            'name.required' => __('site.form.errors.name'),
            'name.regex' => __('site.form.errors.name_format'),
            'prenume.required' => __('site.form.errors.prenume'),
            'prenume.regex' => __('site.form.errors.prenume_format'),
            'phone.required' => __('site.form.errors.phone'),
            'phone.regex' => __('site.form.errors.phone_format'),
            'email.required' => __('site.form.errors.email'),
            'email.email' => __('site.form.errors.email_format'),
            'message.required' => __('site.form.errors.message'),
            'message.min' => __('site.form.errors.message_min'),
            self::HONEYPOT.'.prohibited' => __('site.form.errors.blocked'),
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => __('site.form.name'),
            'prenume' => __('site.form.surname'),
            'phone' => __('site.form.phone'),
            'email' => __('site.form.email'),
            'message' => __('site.form.message'),
        ];
    }

    protected function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if (! $this->wasRenderedLongEnoughAgo()) {
                $validator->errors()->add('message', __('site.form.errors.stale'));
            }
        });
    }

    /**
     * Charset-ul din regex nu e de ajuns: `----------` sau `+() -` ar trece.
     * Un numar real are intre 8 si 15 cifre (E.164; Moldova: 8 local, 11 cu +373).
     */
    private function phoneHasRealDigits(string $attribute, mixed $value, Closure $fail): void
    {
        $digits = preg_match_all('/[0-9]/', is_string($value) ? $value : '');

        if ($digits < 8 || $digits > 15) {
            $fail(__('site.form.errors.phone_digits'));
        }
    }

    /**
     * Semnatura tipica a spamului de formular: linkuri. Un client care descrie un
     * apartament sau o hala nu trimite URL-uri; botii aproape intotdeauna trimit.
     * Respingem si BBCode-ul ([url=...]), pe care doar botii il folosesc.
     */
    private function messageIsNotLinkSpam(string $attribute, mixed $value, Closure $fail): void
    {
        $text = is_string($value) ? $value : '';

        $links = preg_match_all('#https?://|www\.#i', $text);
        $bbcode = (bool) preg_match('/\[(?:url|link)[=\]]/i', $text);

        if ($links > 0 || $bbcode) {
            $fail(__('site.form.errors.message_links'));
        }
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
