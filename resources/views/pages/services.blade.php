@php($page = 'services')
@extends('layouts.app')

@section('content')

    <header class="border-b border-line">
        <div class="mx-auto max-w-6xl px-5 pt-12 pb-16 sm:px-8 sm:pt-20 sm:pb-20">
            <p class="eyebrow flex items-center gap-2.5">
                <x-signature.wye :size="13" :live="true" />
                Servicii
            </p>
            <h1 class="mt-6 max-w-3xl text-h1 text-paper">Ce facem, pe îndelete.</h1>
            <p class="mt-6 max-w-2xl text-lead text-paper-dim">
                Trei servicii, duse până la capăt. Fiecare lucrare pleacă de la o evaluare
                gratuită și se termină cu o instalație pe care o poți înțelege.
            </p>
        </div>
    </header>

    @foreach (config('energix.services') as $i => $service)
        <section
            id="{{ $service['slug'] }}"
            @class(['border-b border-line', 'bg-ink-raised' => $i % 2 === 1])
            aria-labelledby="service-{{ $service['slug'] }}"
        >
            <div class="mx-auto max-w-6xl px-5 py-20 sm:px-8 sm:py-28">
                <div @class([
                    'grid items-center gap-12 lg:grid-cols-2 lg:gap-20',
                    'lg:[&>*:first-child]:order-2' => $i % 2 === 1,
                ])>
                    <div data-reveal>
                        <p class="eyebrow flex items-center gap-2.5">
                            <x-signature.wye :size="13" />
                            <span>{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</span>
                            <span aria-hidden="true">·</span>
                            <span>{{ $service['tagline'] }}</span>
                        </p>

                        <h2 id="service-{{ $service['slug'] }}" class="mt-4 text-h2 text-paper">
                            {{ $service['title'] }}
                        </h2>

                        <p class="mt-5 text-lead text-paper-dim">{{ $service['intro'] }}</p>

                        <ul class="mt-8 divide-y divide-line border-y border-line">
                            @foreach ($service['features'] as $feature)
                                <li class="flex items-start gap-4 py-4">
                                    <span class="mt-2.5 h-1 w-1 shrink-0 rounded-full bg-gold" aria-hidden="true"></span>
                                    <span class="text-paper">{{ $feature }}</span>
                                </li>
                            @endforeach
                        </ul>

                        <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                            <a
                                href="tel:{{ config('energix.contact.phone_href') }}"
                                class="inline-flex items-center justify-center gap-3 rounded-sm bg-gold px-6 py-3.5 font-mono text-sm font-medium tracking-wider text-ink uppercase transition hover:brightness-110"
                            >
                                Sună acum
                            </a>
                            <a
                                href="{{ route('contact') }}"
                                class="inline-flex items-center justify-center rounded-sm border border-line px-6 py-3.5 font-mono text-sm tracking-wider text-paper uppercase transition hover:border-gold hover:text-gold"
                            >
                                Cere o ofertă
                            </a>
                        </div>
                    </div>

                    <div class="overflow-hidden rounded-sm border border-line" data-reveal>
                        <img
                            src="{{ asset($service['image']) }}"
                            alt=""
                            width="960"
                            height="640"
                            loading="lazy"
                            decoding="async"
                            aria-hidden="true"
                            class="h-64 w-full object-cover opacity-70 sm:h-80"
                        >
                    </div>
                </div>
            </div>
        </section>
    @endforeach

    <section class="border-b border-line" aria-labelledby="process-title">
        <div class="mx-auto max-w-6xl px-5 py-20 sm:px-8 sm:py-28">
            <x-section-header
                :index="4"
                eyebrow="Cum lucrăm"
                title="Patru pași, fără surprize."
            />

            <ol class="mt-14 max-w-2xl">
                @foreach (config('energix.process') as $i => $step)
                    <x-process-step :step="$step" :index="$i + 1" :last="$loop->last" />
                @endforeach
            </ol>
        </div>
    </section>

    <x-cta-band title="Spune-ne ce ai nevoie." />

@endsection
