@php($page = 'services')
@extends('layouts.app')

@section('content')

    <header class="relative overflow-hidden border-b border-line">
        <div class="blueprint absolute inset-0 opacity-20 [mask-image:radial-gradient(70%_90%_at_40%_20%,black,transparent)]" aria-hidden="true"></div>

        <div class="relative mx-auto max-w-6xl px-5 pt-8 pb-14 sm:px-8 sm:pb-18">
            <x-job-sheet code="SRV-02" :name="__('site.nav.services')" index="02/05" />

            <x-breadcrumbs :page="$page" class="mt-6" />

            <div class="mt-10 grid gap-10 lg:grid-cols-[1.1fr_auto] lg:items-center">
                <div>
                    <h1 class="max-w-3xl text-h1 text-paper">{{ __('site.services_page.h1') }}</h1>
                    <p class="mt-6 max-w-2xl text-lead text-paper-dim">{{ __('site.services_page.lead') }}</p>
                </div>

                <div class="hidden lg:block" data-reveal>
                    <x-hero-instrument.circuits-calc />
                </div>
            </div>
        </div>
    </header>

    {{--
    | Pagină-pilon: trimite mai departe, nu ține conținutul.
    |
    | Cele trei segmente aveau tab-uri aici. Un fragment (`#apartamente`) nu se
    | rankează ca pagină, deci trei intenții comerciale se băteau pe un URL.
    | Acum fiecare are pagina lui, iar aceasta le leagă.
    --}}
    <section class="mx-auto max-w-6xl px-5 py-14 sm:px-8 sm:py-20" aria-label="{{ __('site.services_page.space_type') }}" data-segment-hash>
        <x-section-header
            :eyebrow="__('site.services_page.segments_eyebrow')"
            :title="__('site.services_page.segments_title')"
            :intro="__('site.services_page.segments_intro')"
        />

        <div class="mt-12 grid gap-6 lg:grid-cols-3">
            @foreach (config('energix.services') as $i => $service)
                @php($t = __("site.services.{$service['slug']}"))
                <article
                    class="group flex flex-col overflow-hidden rounded-sm border border-line bg-ink-raised/40 transition hover:border-gold"
                    data-segment-card="{{ $service['slug'] }}"
                    data-reveal
                    style="--reveal-delay: {{ $i * 90 }}ms"
                >
                    <img
                        src="{{ asset($service['image']) }}"
                        alt="{{ $t['title'] }} — Energix, {{ __('site.common.city') }}"
                        width="800"
                        height="533"
                        loading="lazy"
                        decoding="async"
                        class="h-48 w-full object-cover opacity-70 transition duration-500 group-hover:opacity-100"
                    >

                    <div class="flex flex-1 flex-col p-6">
                        <p class="eyebrow flex items-center gap-2.5">
                            <x-signature.wye :size="12" :live="true" />
                            {{ __('site.services_page.circuit') }} {{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}
                        </p>

                        <h3 class="mt-3 font-display text-h3 text-paper">{{ $t['title'] }}</h3>
                        <p class="mt-4 text-paper-dim">{{ $t['intro'] }}</p>

                        <ul class="mt-6 grid gap-2 text-sm text-paper-dim">
                            @foreach (array_slice($t['features'], 0, 3) as $feature)
                                <li class="flex gap-3">
                                    <span class="mt-2 h-1 w-1 shrink-0 rounded-full bg-gold" aria-hidden="true"></span>
                                    {{ $feature }}
                                </li>
                            @endforeach
                        </ul>

                        {{-- Textul ancorei descrie pagina-țintă, nu acțiunea. --}}
                        <a
                            href="{{ URL::localized("services.{$service['slug']}") }}"
                            class="mt-auto inline-flex items-center gap-2 pt-8 font-mono text-sm tracking-wider text-gold uppercase transition hover:brightness-110"
                        >
                            {{ $t['anchor'] }}
                            <span aria-hidden="true">&rarr;</span>
                        </a>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    <section class="border-t border-line bg-ink-raised/40" aria-labelledby="stages-title">
        <div class="mx-auto max-w-6xl px-5 py-16 sm:px-8 sm:py-24">
            <x-section-header
                :eyebrow="__('site.services_page.stages_eyebrow')"
                :title="__('site.services_page.stages_title')"
            />
            <x-signature.stages class="mt-12" data-reveal />
        </div>
    </section>

    <section class="border-t border-line" aria-labelledby="process-title">
        <div class="mx-auto max-w-6xl px-5 py-16 sm:px-8 sm:py-24">
            <x-section-header
                :eyebrow="__('site.services_page.process_eyebrow')"
                :title="__('site.services_page.process_title')"
            />

            <div class="mt-12 grid gap-12 lg:grid-cols-2 lg:items-center lg:gap-16">
                <ol>
                    @foreach (__('site.process') as $i => $step)
                        <x-process-step :step="$step" :index="$i + 1" :last="$loop->last" />
                    @endforeach
                </ol>

                {{-- Ilustrația trăiește doar unde există golul: coloana din dreapta pe lg+. --}}
                <div class="hidden lg:block" data-reveal>
                    <x-process-flow />
                </div>
            </div>
        </div>
    </section>

    <x-cta-band :title="__('site.services_page.cta')" />

@endsection
