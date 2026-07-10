{{--
| Starea liniei (hero /contacte).
|
| Raspunde la intrebarea reala de dinaintea apelului: „e cineva acolo ACUM?”.
| Programul e calculat pe loc, in browser; cand e inchis, spune exact cand
| revenim. Butonul „Testeaza linia” face o verificare ceremoniala cu LED-uri.
|
| ⚠️ Orarul de mai jos oglindeste config('energix.hours') — daca programul se
| schimba in config, se schimba si aici. Index 0 = duminica (JS getDay()).
--}}
@php
    $schedule = [
        0 => null,      // duminică — închis
        1 => [8, 20],   // luni
        2 => [8, 20],
        3 => [8, 20],
        4 => [8, 20],
        5 => [8, 20],   // vineri
        6 => [9, 17],   // sâmbătă
    ];
@endphp

<div class="instrument w-full max-w-sm" data-line-status data-schedule='@json($schedule)'>
    <p class="instrument-head">
        <span>Starea liniei</span>
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
                Testează linia
            </button>

            <a
                href="tel:{{ config('energix.contact.phone_href') }}"
                class="font-mono text-sm text-gold tabular-nums transition hover:brightness-110"
            >
                {{ config('energix.contact.phone') }}
            </a>
        </div>

        <p class="mt-3 text-[0.72rem] leading-snug text-paper-dim">
            {{ config('energix.response_time') }}
        </p>
    </div>
</div>
