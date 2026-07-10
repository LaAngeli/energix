@php
    /*
    | Pagina unui singur segment: /servicii/apartamente, /servicii/case, /servicii/industriale.
    |
    | Anterior, cele trei trăiau ca tab-uri pe /servicii — un singur <title>, un singur
    | H1, un singur URL pentru trei intenții comerciale distincte. Google nu rankează
    | fragmente (`#apartamente`) ca pagini separate.
    */
    $services = collect(config('energix.services'));
    $service = $services->firstWhere('slug', $segment);
    $others = $services->where('slug', '!=', $segment);

    $t = __("site.services.{$segment}");
    $page = "services.{$segment}";
    $index = $services->search(fn (array $s): bool => $s['slug'] === $segment) + 1;
@endphp

@extends('layouts.app')

@section('content')

    <header class="relative overflow-hidden border-b border-line">
        <div class="blueprint absolute inset-0 opacity-20 [mask-image:radial-gradient(70%_90%_at_40%_20%,black,transparent)]" aria-hidden="true"></div>

        <div class="relative mx-auto max-w-6xl px-5 pt-8 pb-14 sm:px-8 sm:pb-18">
            <x-job-sheet code="SRV-02.{{ str_pad((string) $index, 2, '0', STR_PAD_LEFT) }}" :name="$t['nav']" index="0{{ $index }}/03" />

            {{-- Firimituri vizibile, nu doar în JSON-LD: Google le cere pe amândouă. --}}
            <nav class="mt-8 font-mono text-xs tracking-wider text-paper-dim uppercase" aria-label="{{ __('site.nav.main') }}">
                <ol class="flex flex-wrap items-center gap-2">
                    <li><a href="{{ URL::localized('home') }}" class="transition hover:text-gold">{{ __('site.nav.home') }}</a></li>
                    <li aria-hidden="true" class="text-line">/</li>
                    <li><a href="{{ URL::localized('services') }}" class="transition hover:text-gold">{{ __('site.nav.services') }}</a></li>
                    <li aria-hidden="true" class="text-line">/</li>
                    <li class="text-paper" aria-current="page">{{ $t['nav'] }}</li>
                </ol>
            </nav>

            <div class="mt-8 grid gap-10 lg:grid-cols-[1.15fr_1fr] lg:items-center lg:gap-16">
                <div>
                    <p class="eyebrow flex items-center gap-2.5">
                        <x-signature.wye :size="13" :live="true" />
                        {{ $t['tagline'] }}
                    </p>

                    <h1 class="mt-4 max-w-2xl text-h1 text-paper">{{ $t['title'] }}</h1>
                    <p class="mt-6 max-w-xl text-lead text-paper-dim">{{ $t['intro'] }}</p>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <a
                            href="tel:{{ config('energix.contact.phone_href') }}"
                            class="energize-sweep inline-flex items-center justify-center gap-3 rounded-sm bg-gold px-6 py-3.5 font-mono text-sm font-medium tracking-wider text-ink uppercase"
                        >
                            {{ __('site.common.call_now') }}
                        </a>
                        <a
                            href="{{ URL::localized('contact') }}"
                            class="inline-flex items-center justify-center rounded-sm border border-line px-6 py-3.5 font-mono text-sm tracking-wider text-paper uppercase transition hover:border-gold hover:text-gold"
                        >
                            {{ __('site.common.get_offer') }}
                        </a>
                    </div>
                </div>

                <div class="overflow-hidden rounded-sm border border-line" data-reveal>
                    <img
                        src="{{ asset($service['image']) }}"
                        alt="{{ $t['title'] }} — Energix, {{ __('site.common.city') }}"
                        width="800"
                        height="533"
                        loading="lazy"
                        decoding="async"
                        class="h-64 w-full object-cover opacity-75 lg:h-80"
                    >
                </div>
            </div>
        </div>
    </header>

    {{-- ================================ corpul paginii ================================ --}}
    <section class="border-b border-line">
        <div class="mx-auto max-w-6xl px-5 py-16 sm:px-8 sm:py-24">
            <div class="grid gap-12 lg:grid-cols-[1.2fr_1fr] lg:gap-20">
                <div class="space-y-6 text-lead text-paper-dim" data-reveal>
                    @foreach ($t['body'] as $paragraph)
                        <p>{{ $paragraph }}</p>
                    @endforeach
                </div>

                <div data-reveal>
                    <h2 class="text-h3 text-paper">{{ __('site.segment_page.includes') }}</h2>

                    <ul class="mt-6 divide-y divide-line border-y border-line">
                        @foreach ($t['features'] as $j => $feature)
                            <li class="flex items-center gap-4 py-3.5">
                                <span class="readout text-xs text-gold">{{ str_pad((string) ($j + 1), 2, '0', STR_PAD_LEFT) }}</span>
                                <span class="text-paper">{{ $feature }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <p class="mt-6 text-sm text-paper-dim">{{ __('site.segment_page.stages_note') }}</p>

                    <a href="{{ URL::localized('services') }}" class="mt-3 inline-flex items-center gap-2 font-mono text-sm tracking-wider text-gold uppercase transition hover:brightness-110">
                        {{ __('site.segment_page.stages_link') }}
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================ întrebări specifice ============================ --}}
    <section class="border-b border-line bg-ink-raised/40" aria-labelledby="segment-faq-title">
        <div class="mx-auto max-w-3xl px-5 py-16 sm:px-8 sm:py-24">
            <h2 id="segment-faq-title" class="text-h2 text-paper">{{ __('site.segment_page.faq_title') }}</h2>

            <div class="mt-10 divide-y divide-line border-y border-line">
                @foreach ($t['faq'] as $item)
                    <div class="py-6" data-reveal>
                        <h3 class="text-lg text-paper">{{ $item['q'] }}</h3>
                        <p class="mt-3 text-paper-dim">{{ $item['a'] }}</p>
                    </div>
                @endforeach
            </div>

            <a href="{{ URL::localized('gallery') }}" class="mt-10 inline-flex items-center gap-2 font-mono text-sm tracking-wider text-gold uppercase transition hover:brightness-110">
                {{ __('site.segment_page.gallery_link') }}
                <span aria-hidden="true">&rarr;</span>
            </a>
        </div>
    </section>

    {{-- ========================== celelalte două segmente ========================== --}}
    <section class="border-b border-line" aria-labelledby="others-title">
        <div class="mx-auto max-w-6xl px-5 py-16 sm:px-8 sm:py-24">
            <h2 id="others-title" class="text-h3 text-paper">{{ __('site.segment_page.others_title') }}</h2>

            <div class="mt-8 grid gap-5 sm:grid-cols-2">
                @foreach ($others as $other)
                    <x-segment-link :service="$other" />
                @endforeach
            </div>
        </div>
    </section>

    <x-cta-band :title="__('site.services_page.cta')" />

@endsection
