{{--
| Starea liniei (hero /contacte).
|
| Raspunde la intrebarea reala de dinaintea apelului: „e cineva acolo ACUM?”.
| Programul se ia din `config('energix.schedule')` — o singura sursa de adevar,
| aceeasi pe care o foloseste si `openingHoursSpecification` din JSON-LD.
| Textele vin din lang, deci instrumentul vorbeste limba paginii.
--}}
@php
    /*
     | `@json()` sparge expresia pe virgule ca sa gaseasca argumentul `$options`,
     | deci un array literal multi-linie o rupe. Il construim intai in variabile.
     */
    $schedule = config('energix.schedule');
    $i18n = [
        'open' => __('site.line.open'),
        'openDetail' => __('site.line.open_detail'),
        'closed' => __('site.line.closed'),
        'closedDetail' => __('site.line.closed_detail'),
        'closedPlain' => __('site.line.closed_plain'),
        'today' => __('site.line.today'),
        'tomorrow' => __('site.line.tomorrow'),
        'testing' => __('site.line.testing'),
        'days' => __('site.days'),
    ];
@endphp

<div
    class="instrument w-full max-w-sm"
    data-line-status
    data-schedule='@json($schedule)'
    data-i18n='@json($i18n)'
>
    <p class="instrument-head">
        <span>{{ __('site.line.title') }}</span>
        <span class="flex gap-1.5" data-line-leds aria-hidden="true">
            <span class="led"></span>
            <span class="led"></span>
            <span class="led"></span>
        </span>
    </p>

    <div class="p-4">
        <div aria-live="polite">
            <p class="readout text-2xl leading-tight" data-line-headline>&nbsp;</p>
            <p class="mt-1.5 text-sm text-paper-dim" data-line-detail>&nbsp;</p>
        </div>

        <div class="mt-4 flex items-center gap-3 border-t border-line pt-4">
            <button
                type="button"
                data-line-test
                class="rounded-sm border border-line px-4 py-2.5 font-mono text-[0.65rem] tracking-[0.16em] text-paper uppercase transition-colors hover:border-gold hover:text-gold active:translate-y-px"
            >
                {{ __('site.line.test') }}
            </button>

            <a
                href="tel:{{ config('energix.contact.phone_href') }}"
                class="font-mono text-sm text-gold tabular-nums transition hover:brightness-110"
            >
                {{ config('energix.contact.phone') }}
            </a>
        </div>

        <p class="mt-3 text-[0.72rem] leading-snug text-paper-dim">{{ __('site.common.response_time') }}</p>
    </div>
</div>
