@props(['title' => 'Ai nevoie de un electrician?', 'body' => null])

@php
    $body ??= config('energix.response_time');
@endphp

{{-- Aici se termina circuitul: curentul ajunge la sarcina. --}}
<section {{ $attributes->class('border-t border-line bg-ink-raised') }} aria-labelledby="cta-title">
    <div class="mx-auto max-w-6xl px-5 py-16 text-center sm:px-8 sm:py-20" data-reveal>
        <x-signature.wye :size="28" class="mx-auto" />

        <h2 id="cta-title" class="mt-6 text-h2 text-paper">{{ $title }}</h2>
        <p class="mx-auto mt-4 max-w-lg text-lead text-paper-dim">{{ $body }}</p>

        <div class="mt-9 flex flex-col items-center justify-center gap-3 sm:flex-row">
            <a
                href="tel:{{ config('energix.contact.phone_href') }}"
                class="inline-flex w-full items-center justify-center gap-3 rounded-sm bg-gold px-7 py-4 font-mono text-sm font-medium tracking-wider text-ink uppercase transition hover:brightness-110 hover:shadow-[0_0_28px_-6px_var(--color-gold)] sm:w-auto"
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
</section>
