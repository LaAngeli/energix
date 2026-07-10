@php($page = 'contact')
@php($contact = config('energix.contact'))
@extends('layouts.app')

@section('content')

    <header class="relative overflow-hidden border-b border-line">
        <div class="blueprint absolute inset-0 opacity-20 [mask-image:radial-gradient(70%_90%_at_40%_20%,black,transparent)]" aria-hidden="true"></div>

        <div class="relative mx-auto max-w-6xl px-5 pt-8 pb-14 sm:px-8 sm:pb-18">
            <x-job-sheet code="CNT-05" name="Contact" index="05/05" />

            <h1 class="mt-10 max-w-3xl text-h1 text-paper">Sună. E cel mai rapid.</h1>
            <p class="mt-6 max-w-2xl text-lead text-paper-dim">
                {{ config('energix.response_time') }}
            </p>
        </div>
    </header>

    <section class="mx-auto max-w-6xl px-5 py-14 sm:px-8 sm:py-20">
        <div class="grid gap-14 lg:grid-cols-[0.85fr_1.15fr] lg:gap-20">

            {{-- Coloana cu date: telefonul primeste greutatea vizuala. --}}
            <div>
                <div class="nameplate bg-ink-raised p-6" data-reveal>
                    <p class="eyebrow">Telefon</p>
                    <a
                        href="tel:{{ $contact['phone_href'] }}"
                        class="readout mt-3 block text-readout text-gold transition hover:brightness-110"
                    >{{ $contact['phone'] }}</a>
                    <p class="mt-3 text-sm text-paper-dim">{{ config('energix.response_time') }}</p>
                </div>

                <div class="mt-6 space-y-6" data-reveal>
                    <div>
                        <p class="eyebrow">Email</p>
                        <a href="mailto:{{ $contact['email'] }}" class="mt-2 block text-paper transition hover:text-gold">
                            {{ $contact['email'] }}
                        </a>
                    </div>

                    <div>
                        <p class="eyebrow">Unde suntem</p>
                        <p class="mt-2 text-paper">{{ $contact['city'] }}, {{ $contact['country'] }}</p>
                        <p class="text-sm text-paper-dim">Deservim toată țara.</p>
                    </div>

                    <div>
                        <p class="eyebrow">Program</p>
                        <ul class="mt-2 divide-y divide-line border-y border-line">
                            @foreach (config('energix.hours') as $slot)
                                <li class="flex items-center justify-between gap-6 py-3">
                                    <span class="text-paper-dim">{{ $slot['days'] }}</span>
                                    <span class="readout text-sm text-paper">{{ $slot['time'] }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div>
                        <p class="eyebrow">Ne găsești și pe</p>
                        <x-social-links class="mt-3" :size="21" />
                    </div>
                </div>
            </div>

            <div data-reveal>
                <h2 class="text-h2 text-paper">Sau închide circuitul în scris.</h2>
                <p class="mt-3 text-paper-dim">
                    Completează câmpurile — fiecare închide un segment. Cu cât ne spui mai
                    multe despre proiect, cu atât oferta e mai exactă.
                </p>

                <x-contact-form class="mt-8" />
            </div>
        </div>
    </section>

@endsection
