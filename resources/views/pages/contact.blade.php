@php($page = 'contact')
@php($contact = config('energix.contact'))
@extends('layouts.app')

@section('content')

    <header class="border-b border-line">
        <div class="mx-auto max-w-6xl px-5 pt-12 pb-16 sm:px-8 sm:pt-20 sm:pb-20">
            <p class="eyebrow flex items-center gap-2.5">
                <x-signature.wye :size="13" :live="true" />
                Contact
            </p>
            <h1 class="mt-6 max-w-3xl text-h1 text-paper">Sună. E cel mai rapid.</h1>
            <p class="mt-6 max-w-2xl text-lead text-paper-dim">
                {{ config('energix.response_time') }}
            </p>
        </div>
    </header>

    <section class="mx-auto max-w-6xl px-5 py-16 sm:px-8 sm:py-24">
        <div class="grid gap-14 lg:grid-cols-[0.85fr_1.15fr] lg:gap-20">

            {{-- Coloana cu date. Telefonul primeste greutatea vizuala. --}}
            <div>
                <div class="rounded-sm border border-line bg-ink-raised p-6" data-reveal>
                    <p class="eyebrow">Telefon</p>
                    <a
                        href="tel:{{ $contact['phone_href'] }}"
                        class="readout mt-3 block text-readout text-gold transition hover:brightness-110"
                    >{{ $contact['phone'] }}</a>
                    <p class="mt-3 text-sm text-paper-dim">{{ config('energix.response_time') }}</p>
                </div>

                <div class="mt-5 space-y-5" data-reveal>
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
                        <ul class="mt-3 flex flex-wrap gap-2">
                            @foreach (config('energix.social') as $item)
                                <li @class(['hidden sm:list-item' => str_starts_with($item['url'], 'viber:')])>
                                    <a
                                        href="{{ $item['url'] }}"
                                        @if (str_starts_with($item['url'], 'https://')) target="_blank" rel="noopener noreferrer" @endif
                                        class="inline-flex rounded-sm border border-line px-3 py-2 font-mono text-xs tracking-wider text-paper-dim uppercase transition hover:border-gold hover:text-gold"
                                    >{{ $item['name'] }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <div data-reveal>
                <h2 class="text-h2 text-paper">Sau scrie-ne.</h2>
                <p class="mt-3 text-paper-dim">
                    Completează formularul și te sunăm noi. Cu cât ne spui mai multe despre
                    lucrare, cu atât oferta e mai exactă.
                </p>

                <x-contact-form class="mt-8" />
            </div>
        </div>
    </section>

@endsection
