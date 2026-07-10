@php($page = 'gallery')
@extends('layouts.app')

@section('content')

    <header class="relative overflow-hidden border-b border-line">
        <div class="blueprint absolute inset-0 opacity-20 [mask-image:radial-gradient(70%_90%_at_40%_20%,black,transparent)]" aria-hidden="true"></div>

        <div class="relative mx-auto max-w-6xl px-5 pt-8 pb-14 sm:px-8 sm:pb-18">
            <x-job-sheet code="GAL-03" :name="__('site.nav.gallery')" index="03/05" />

            <x-breadcrumbs :page="$page" class="mt-6" />

            <div class="mt-10 grid gap-10 lg:grid-cols-[1.1fr_auto] lg:items-center">
                <div>
                    <h1 class="max-w-3xl text-h1 text-paper">{{ __('site.gallery_page.h1') }}</h1>
                    <p class="mt-6 max-w-2xl text-lead text-paper-dim">{{ __('site.gallery_page.lead') }}</p>

                    {{-- Onestitate, nu marketing: imaginile sunt ilustrative. --}}
                    <p class="mt-8 flex max-w-2xl gap-3 rounded-sm border border-line bg-ink-raised p-4 text-sm text-paper-dim">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="mt-0.5 shrink-0 text-gold" aria-hidden="true">
                            <circle cx="12" cy="12" r="9" />
                            <path d="M12 8h.01M11 12h1v4h1" stroke-linecap="round" />
                        </svg>
                        <span>{{ __('site.gallery.disclaimer') }}</span>
                    </p>
                </div>

                <div class="hidden lg:block" data-reveal>
                    <x-hero-instrument.works-counter />
                </div>
            </div>
        </div>
    </header>

    <section class="mx-auto max-w-6xl px-5 py-14 sm:px-8 sm:py-20" aria-label="{{ __('site.gallery_page.h1') }}">
        <x-gallery-grid />
    </section>

    <x-related-segments />

    <x-cta-band :title="__('site.gallery_page.cta')" />

@endsection
