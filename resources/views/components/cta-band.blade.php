@props(['title' => 'Punem proiectul tău sub tensiune?', 'body' => null])

@php
    $body ??= config('energix.response_time');
@endphp

{{--
| Finalul fiecarei pagini e momentul de „punere sub tensiune”: un comutator mare
| pe care vizitatorul il poate trage, iar butonul de telefon primeste bloom auriu.
| Pur ceremonial — telefonul e apelabil oricand, comutatorul nu blocheaza nimic.
--}}
<section {{ $attributes->class('border-t border-line bg-ink-raised/50') }} aria-labelledby="cta-title" data-arm-zone>
    <div class="mx-auto max-w-6xl px-5 py-16 sm:px-8 sm:py-24">
        <div class="grid items-center gap-10 lg:grid-cols-[auto_1fr_auto] lg:gap-14" data-reveal>
            <button
                type="button"
                data-arm
                role="switch"
                aria-checked="false"
                class="group mx-auto flex flex-col items-center gap-3 lg:mx-0"
            >
                <span class="big-switch-track" aria-hidden="true"><span class="big-switch-knob"></span></span>
                <span class="font-mono text-[0.6rem] tracking-[0.18em] text-paper-dim uppercase group-aria-checked:text-gold">
                    Punere sub tensiune
                </span>
            </button>

            <div class="text-center lg:text-left">
                <h2 id="cta-title" class="text-h2 text-paper">{{ $title }}</h2>
                <p class="mx-auto mt-4 max-w-lg text-lead text-paper-dim lg:mx-0">{{ $body }}</p>
            </div>

            <div class="flex flex-col items-center gap-3 lg:items-end">
                <a
                    href="tel:{{ config('energix.contact.phone_href') }}"
                    class="armed-target inline-flex w-full items-center justify-center gap-3 rounded-sm bg-gold px-7 py-4 font-mono text-sm font-medium tracking-wider text-ink uppercase transition hover:brightness-110 sm:w-auto"
                >
                    Sună acum
                    <span class="tabular-nums normal-case tracking-normal">{{ config('energix.contact.phone') }}</span>
                </a>

                <a
                    href="{{ route('contact') }}"
                    class="inline-flex w-full items-center justify-center rounded-sm border border-line px-7 py-4 font-mono text-sm tracking-wider text-paper uppercase transition hover:border-gold hover:text-gold sm:w-auto"
                >
                    Cere o ofertă
                </a>
            </div>
        </div>
    </div>
</section>
