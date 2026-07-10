@php($page = 'about')
@extends('layouts.app')

@section('content')

    <header class="relative overflow-hidden border-b border-line">
        <div class="blueprint absolute inset-0 opacity-20 [mask-image:radial-gradient(70%_90%_at_40%_20%,black,transparent)]" aria-hidden="true"></div>

        <div class="relative mx-auto max-w-6xl px-5 pt-8 pb-14 sm:px-8 sm:pb-18">
            <x-job-sheet code="DSP-04" :name="__('site.nav.about')" index="04/05" />

            <div class="mt-10 grid gap-10 lg:grid-cols-[1fr_26rem] lg:items-center lg:gap-8">
                <div>
                    <h1 class="max-w-2xl text-h1 text-paper">{{ __('site.about_page.h1') }}</h1>
                    <p class="mt-6 max-w-xl text-lead text-paper-dim">{{ __('site.about_page.lead') }}</p>
                </div>

                {{-- Sigla se energizează o dată, la intrarea în cadru. Doar pe desktop:
                     pe mobil coloana nu există, iar `loading="lazy"` ține imaginea nedescărcată. --}}
                <div class="hidden lg:block" data-reveal>
                    <x-signature.logo-charge />
                </div>
            </div>
        </div>
    </header>

    <section class="border-b border-line" aria-labelledby="experience-title">
        <div class="mx-auto max-w-6xl px-5 py-16 sm:px-8 sm:py-24">
            <div class="grid items-center gap-12 lg:grid-cols-[1fr_1.2fr] lg:gap-20">
                <div data-reveal>
                    <p class="eyebrow">{{ __('site.about_page.meter') }}</p>
                    <p class="mt-4 flex items-baseline gap-4">
                        <span id="experience-title" class="readout text-meter leading-none text-gold">
                            {{ config('energix.experience_years') }}
                        </span>
                        <span class="max-w-40 font-mono text-xs tracking-[0.14em] text-paper-dim uppercase">
                            {{ __('site.common.experience_label') }}
                        </span>
                    </p>
                    <div class="meter-scale mt-6" aria-hidden="true"></div>
                    <p class="mt-3 font-mono text-xs tracking-wide text-paper-dim">{{ __('site.common.area_served') }}</p>
                </div>

                <div class="space-y-5 text-lead text-paper-dim" data-reveal>
                    <p>{{ __('site.about_page.p1') }}</p>
                    <p>{{ __('site.about_page.p2') }}</p>
                    <p class="text-paper">{{ __('site.about_page.p3') }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="border-b border-line bg-ink-raised/40" aria-labelledby="values-title">
        <div class="mx-auto max-w-6xl px-5 py-16 sm:px-8 sm:py-24">
            <x-section-header
                :eyebrow="__('site.about_page.values_eyebrow')"
                :title="__('site.about_page.values_title')"
                :intro="__('site.about_page.values_intro')"
            />

            <div class="mt-12 grid gap-5 sm:grid-cols-2">
                @foreach (__('site.values') as $i => $value)
                    <x-value-item
                        :value="$value"
                        :index="$i + 1"
                        data-reveal
                        style="--reveal-delay: {{ $i * 90 }}ms"
                    />
                @endforeach
            </div>
        </div>
    </section>

    <section class="border-b border-line" aria-labelledby="trust-title">
        <div class="mx-auto max-w-6xl px-5 py-16 sm:px-8 sm:py-24">
            <div class="grid gap-12 lg:grid-cols-[0.9fr_1.1fr] lg:gap-20">
                <x-section-header
                    :eyebrow="__('site.about_page.trust_eyebrow')"
                    :title="__('site.about_page.trust_title')"
                    :intro="__('site.about_page.trust_intro')"
                />

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

    <x-cta-band :title="__('site.about_page.cta')" />

@endsection
