@php($page = 'home')
@extends('layouts.app')

@section('content')

    {{-- ============================================================ 01 · SURSA --}}
    <section class="relative overflow-hidden" aria-labelledby="hero-title">
        <div class="blueprint absolute inset-0 opacity-25 [mask-image:radial-gradient(80%_70%_at_50%_35%,black,transparent)]" aria-hidden="true"></div>

        <div class="relative mx-auto flex min-h-[calc(100svh-4.5rem)] max-w-6xl flex-col px-5 pt-8 pb-16 sm:px-8">
            <x-job-sheet code="TD-01" :name="__('site.home.sheet_source')" index="01/05" />

            <div class="flex flex-1 flex-col items-center justify-center pt-10 text-center">
                <p class="eyebrow flex items-center gap-2.5">
                    <x-signature.wye :size="13" :live="true" />
                    {{ __('site.home.eyebrow') }}
                </p>

                {{--
                | H1 poarta si linia de brand, si expresia-cheie. Linia poetica ramane
                | dominanta vizual; sub-linia da motoarelor „instalatii electrice Chisinau”.
                --}}
                <h1 id="hero-title" class="mt-6 max-w-4xl">
                    <span class="block text-h1 text-paper">{{ __('site.home.h1') }}</span>
                    <span class="mt-5 block text-lead font-normal text-gold">{{ __('site.home.h1_sub') }}</span>
                </h1>

                <p class="mt-6 max-w-2xl text-lead text-paper-dim">{{ __('site.home.lead') }}</p>

                <div class="mt-8 flex w-full flex-col justify-center gap-3 sm:w-auto sm:flex-row">
                    <a
                        href="tel:{{ config('energix.contact.phone_href') }}"
                        class="energize-sweep inline-flex items-center justify-center gap-3 rounded-sm bg-gold px-6 py-4 font-mono text-sm font-medium tracking-wider text-ink uppercase"
                    >
                        {{ __('site.common.call_now') }}
                        <span class="tabular-nums normal-case tracking-normal">{{ config('energix.contact.phone') }}</span>
                    </a>
                    <a
                        href="{{ URL::localized('contact') }}"
                        class="inline-flex items-center justify-center rounded-sm border border-line px-6 py-4 font-mono text-sm tracking-wider text-paper uppercase transition hover:border-gold hover:text-gold"
                    >
                        {{ __('site.common.get_offer') }}
                    </a>
                </div>

                <x-signature.panel class="mt-12 w-full max-w-3xl text-left" data-reveal />
            </div>

            <ul class="mt-12 grid grid-cols-2 gap-px overflow-hidden rounded-sm border border-line bg-line sm:grid-cols-4">
                @foreach (__('site.promises') as $i => $promise)
                    <li
                        class="bg-ink px-4 py-3.5 text-center font-mono text-[0.62rem] tracking-[0.12em] text-paper-dim uppercase transition-colors hover:text-paper"
                        data-reveal
                        style="--reveal-delay: {{ $i * 80 }}ms"
                    >
                        {{ $promise['label'] }}
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    {{-- ============================================== 02 · CIRCUITE (segmente) --}}
    <section class="border-t border-line" aria-labelledby="services-title">
        <div class="mx-auto max-w-6xl px-5 py-16 sm:px-8 sm:py-24">
            <x-job-sheet code="TD-02" :name="__('site.home.sheet_circuits')" index="02/05" class="mb-12" />

            <div class="grid gap-10 lg:grid-cols-[0.9fr_2fr] lg:gap-16">
                <x-section-header
                    :eyebrow="__('site.home.services_eyebrow')"
                    :title="__('site.home.services_title')"
                    :intro="__('site.home.services_intro')"
                />

                <div data-reveal>
                    @foreach (config('energix.services') as $i => $service)
                        <x-segment-row :service="$service" :index="$i + 1" />
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ================================================= 03 · ETAPE (interactiv) --}}
    <section class="border-t border-line bg-ink-raised/40" aria-labelledby="stages-title">
        <div class="mx-auto max-w-6xl px-5 py-16 sm:px-8 sm:py-24">
            <x-job-sheet code="TD-03" :name="__('site.home.sheet_exec')" index="03/05" class="mb-12" />

            <x-section-header
                :eyebrow="__('site.home.stages_eyebrow')"
                :title="__('site.home.stages_title')"
                :intro="__('site.home.stages_intro')"
            />

            <x-signature.stages class="mt-12" data-reveal />
        </div>
    </section>

    {{-- ========================= 04 · ÎNTREBĂRI FRECVENTE (banda luminoasă) --}}
    {{-- AEO: răspunsuri scurte, autonome, marcate FAQPage în JSON-LD. --}}
    <section class="sheet-surface" aria-labelledby="faq-title">
        <div class="mx-auto max-w-6xl px-5 py-16 sm:px-8 sm:py-24">
            <x-section-header
                :eyebrow="__('site.home.answers_eyebrow')"
                :title="__('site.home.answers_title')"
                :on-sheet="true"
            />

            <x-faq class="mt-12" />
        </div>
    </section>

    {{-- ================================================= 05 · PLĂCUȚA (încredere) --}}
    <section class="border-t border-line" aria-labelledby="trust-title">
        <div class="mx-auto max-w-6xl px-5 py-16 sm:px-8 sm:py-24">
            <x-job-sheet code="TD-04" :name="__('site.home.sheet_warranty')" index="04/05" class="mb-12" />

            <div class="grid gap-12 lg:grid-cols-[0.9fr_1.1fr] lg:gap-20">
                <div>
                    <x-section-header
                        :eyebrow="__('site.home.trust_eyebrow')"
                        :title="__('site.home.trust_title')"
                        :intro="__('site.home.trust_intro')"
                    />

                    <p class="mt-8 flex items-baseline gap-4" data-reveal>
                        <span class="readout text-meter leading-none text-gold">{{ config('energix.experience_years') }}</span>
                        <span class="max-w-40 font-mono text-xs tracking-[0.14em] text-paper-dim uppercase">
                            {{ __('site.common.experience_label') }}
                        </span>
                    </p>
                </div>

                <div class="nameplate grid content-start gap-px overflow-hidden bg-line sm:grid-cols-2" data-reveal>
                    @foreach (__('site.promises') as $promise)
                        <div class="bg-ink-raised p-6">
                            <h3 class="flex items-center gap-2.5 font-display text-base text-paper">
                                <x-signature.wye :size="14" />
                                {{ $promise['label'] }}
                            </h3>
                            <p class="mt-2 text-sm text-paper-dim">{{ $promise['body'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ================================================= 06 · UNDE LUCRĂM (local) --}}
    <section class="border-t border-line" aria-labelledby="areas-title">
        <div class="mx-auto max-w-6xl px-5 py-16 sm:px-8 sm:py-24">
            <div class="grid gap-12 lg:grid-cols-2 lg:gap-20">
                <div>
                    <x-section-header
                        :eyebrow="__('site.home.areas_eyebrow')"
                        :title="__('site.home.areas_title')"
                        :intro="__('site.home.areas_intro')"
                    />

                    {{-- GEO: sumarul-entitate, scris ca să poată fi citat întreg. --}}
                    <div class="mt-8 rounded-sm border border-line bg-ink-raised p-6" data-reveal>
                        <h3 class="eyebrow">{{ __('site.home.summary_title') }}</h3>
                        <p class="mt-3 text-sm text-paper-dim">{{ __('site.home.summary') }}</p>
                    </div>
                </div>

                <div class="grid content-start gap-8" data-reveal>
                    <div>
                        <h3 class="eyebrow">{{ __('site.home.areas_sectors') }}</h3>
                        <ul class="mt-3 flex flex-wrap gap-2">
                            @foreach (config('energix.areas.sectors') as $sector)
                                <li class="rounded-sm border border-line px-3 py-1.5 font-mono text-xs text-paper-dim">{{ $sector }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <div>
                        <h3 class="eyebrow">{{ __('site.home.areas_cities') }}</h3>
                        <ul class="mt-3 flex flex-wrap gap-2">
                            @foreach (config('energix.areas.cities') as $city)
                                <li class="rounded-sm border border-line px-3 py-1.5 font-mono text-xs text-paper-dim">{{ $city }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <div>
                        <h3 class="eyebrow">{{ __('site.common.schedule') }}</h3>
                        <ul class="mt-3 divide-y divide-line border-y border-line">
                            @foreach (__('site.hours') as $slot)
                                <li class="flex items-center justify-between gap-6 py-3">
                                    <span class="text-paper-dim">{{ $slot['days'] }}</span>
                                    <span class="readout text-paper">{{ $slot['time'] }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <x-cta-band />

@endsection
