@php($page = 'home')
@extends('layouts.app')

@section('content')

    {{-- ============================================================ 01 · SURSA --}}
    {{-- Hero pe toata inaltimea: titlul deasupra, TABLOUL ca piesa centrala.
         De aici pleaca curentul care „alimenteaza” restul paginii. --}}
    <section class="relative overflow-hidden" aria-labelledby="hero-title">
        <div class="blueprint absolute inset-0 opacity-25 [mask-image:radial-gradient(80%_70%_at_50%_35%,black,transparent)]" aria-hidden="true"></div>

        <div class="relative mx-auto flex min-h-[calc(100svh-4.5rem)] max-w-6xl flex-col px-5 pt-8 pb-16 sm:px-8">
            <x-job-sheet code="TD-01" name="Sursa" index="01/05" />

            <div class="flex flex-1 flex-col items-center justify-center pt-10 text-center">
                <p class="eyebrow flex items-center gap-2.5">
                    <x-signature.wye :size="13" :live="true" />
                    Instalații electrice · {{ config('energix.contact.city') }} · toată {{ config('energix.contact.country') }}
                </p>

                <h1 id="hero-title" class="mt-6 max-w-4xl text-h1 text-paper">
                    Curentul ajunge unde trebuie.
                </h1>

                <p class="mt-6 max-w-2xl text-lead text-paper-dim">
                    Instalația electrică completă, de la proiect până la punere sub tensiune —
                    apartamente, case, spații industriale.
                </p>

                <div class="mt-8 flex w-full flex-col justify-center gap-3 sm:w-auto sm:flex-row">
                    <a
                        href="tel:{{ config('energix.contact.phone_href') }}"
                        class="energize-sweep inline-flex items-center justify-center gap-3 rounded-sm bg-gold px-6 py-4 font-mono text-sm font-medium tracking-wider text-ink uppercase"
                    >
                        Sună acum
                        <span class="tabular-nums normal-case tracking-normal">{{ config('energix.contact.phone') }}</span>
                    </a>
                    <a
                        href="{{ route('contact') }}"
                        class="inline-flex items-center justify-center rounded-sm border border-line px-6 py-4 font-mono text-sm tracking-wider text-paper uppercase transition hover:border-gold hover:text-gold"
                    >
                        Cere o ofertă
                    </a>
                </div>

                {{-- Tabloul: sursa intregii pagini. --}}
                <x-signature.panel class="mt-12 w-full max-w-3xl text-left" data-reveal />
            </div>

            {{-- specificatiile, ca un rand de placute pe sina — se aprind esalonat --}}
            <ul class="mt-12 grid grid-cols-2 gap-px overflow-hidden rounded-sm border border-line bg-line sm:grid-cols-4">
                @foreach (config('energix.promises') as $promise)
                    <li
                        class="bg-ink px-4 py-3.5 text-center font-mono text-[0.62rem] tracking-[0.12em] text-paper-dim uppercase transition-colors hover:text-paper"
                        data-reveal
                        style="--reveal-delay: {{ $loop->index * 80 }}ms"
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
            <x-job-sheet code="TD-02" name="Circuite" index="02/05" class="mb-12" />

            <div class="grid gap-10 lg:grid-cols-[0.9fr_2fr] lg:gap-16">
                <x-section-header
                    eyebrow="Ce facem"
                    title="Instalația completă, de la zero."
                    intro="Un singur serviciu, dus până la capăt, pentru trei tipuri de spații. Deschide un circuit."
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
            <x-job-sheet code="TD-03" name="Execuție" index="03/05" class="mb-12" />

            <x-section-header
                eyebrow="Cum se construiește o instalație"
                title="Cinci etape. În ordinea asta."
                intro="Fiecare ofertă acoperă toate cele cinci. Apasă pe o etapă ca să vezi ce se întâmplă în ea."
            />

            <x-signature.stages class="mt-12" data-reveal />
        </div>
    </section>

    {{-- ================================== 04 · MĂSURĂTORI (banda luminoasă) --}}
    <section class="sheet-surface" aria-labelledby="answers-title">
        <div class="mx-auto max-w-6xl px-5 py-16 sm:px-8 sm:py-24">
            <x-section-header
                eyebrow="Fișa de măsurători · înainte să suni"
                title="Trei răspunsuri, fără ocolișuri."
                :on-sheet="true"
            />

            <div class="mt-12 grid gap-10 sm:grid-cols-3 sm:gap-8">
                @foreach (config('energix.answers') as $i => $answer)
                    <x-answer-cote
                        :question="$answer['q']"
                        :answer="$answer['a']"
                        :index="$i + 1"
                        style="--reveal-delay: {{ $i * 100 }}ms"
                    />
                @endforeach
            </div>
        </div>
    </section>

    {{-- ================================================= 05 · PLĂCUȚA (încredere) --}}
    <section class="border-t border-line" aria-labelledby="trust-title">
        <div class="mx-auto max-w-6xl px-5 py-16 sm:px-8 sm:py-24">
            <x-job-sheet code="TD-04" name="Garanții" index="04/05" class="mb-12" />

            <div class="grid gap-12 lg:grid-cols-[0.9fr_1.1fr] lg:gap-20">
                <div>
                    <x-section-header
                        eyebrow="De ce noi"
                        title="Fapte, nu insigne."
                        intro="Nu îți fluturăm nimic pe perete. Îți spunem ce ne asumăm și ne ținem de cuvânt."
                    />

                    <p class="mt-8 flex items-baseline gap-4" data-reveal>
                        <span class="readout text-meter leading-none text-gold">{{ config('energix.experience.years') }}</span>
                        <span class="max-w-40 font-mono text-xs tracking-[0.14em] text-paper-dim uppercase">
                            {{ config('energix.experience.label') }}
                        </span>
                    </p>
                </div>

                <div class="nameplate grid content-start gap-px overflow-hidden bg-line sm:grid-cols-2" data-reveal>
                    @foreach (config('energix.promises') as $promise)
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

    {{-- ========================================================== 05 · CTA --}}
    <x-cta-band />

@endsection
