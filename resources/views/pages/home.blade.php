@php($page = 'home')
@extends('layouts.app')

@section('content')

    {{-- ============================================================ 01 · HERO --}}
    <section class="mx-auto max-w-6xl px-5 pt-12 pb-20 sm:px-8 sm:pt-20 sm:pb-28" aria-labelledby="hero-title">
        <div class="grid items-center gap-14 lg:grid-cols-[1.05fr_1fr] lg:gap-16">
            <div>
                <p class="eyebrow flex items-center gap-2.5">
                    <x-signature.wye :size="13" :live="true" />
                    Servicii electrice · {{ config('energix.contact.city') }}
                </p>

                <h1 id="hero-title" class="mt-6 text-h1 text-paper">
                    Curentul ajunge<br>unde trebuie.
                </h1>

                <p class="mt-6 max-w-xl text-lead text-paper-dim">
                    Instalații, reparații și mentenanță pentru case, apartamente și spații
                    comerciale. În {{ config('energix.contact.city') }} și în toată {{ config('energix.contact.country') }}.
                </p>

                <div class="mt-9 flex flex-col gap-3 sm:flex-row">
                    <a
                        href="tel:{{ config('energix.contact.phone_href') }}"
                        class="inline-flex items-center justify-center gap-3 rounded-sm bg-gold px-6 py-4 font-mono text-sm font-medium tracking-wider text-ink uppercase transition hover:brightness-110 hover:shadow-[0_0_28px_-6px_var(--color-gold)]"
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

                <ul class="mt-10 grid gap-x-6 gap-y-2.5 font-mono text-xs tracking-wide text-paper-dim uppercase sm:grid-cols-2">
                    @foreach (config('energix.promises') as $promise)
                        <li class="flex items-center gap-2.5">
                            <span class="h-1 w-1 shrink-0 rounded-full bg-gold" aria-hidden="true"></span>
                            {{ $promise['label'] }}
                        </li>
                    @endforeach
                </ul>
            </div>

            <x-signature.schematic class="text-paper" />
        </div>
    </section>

    {{-- ================================== 02 · RĂSPUNSURI (banda luminoasă) --}}
    <section class="sheet-surface" aria-labelledby="answers-title">
        <div class="mx-auto max-w-6xl px-5 py-16 sm:px-8 sm:py-24">
            <x-section-header
                :index="2"
                eyebrow="Înainte să suni"
                title="Trei răspunsuri, fără ocolișuri."
                :on-sheet="true"
            />

            <div class="mt-12 grid gap-10 sm:grid-cols-3 sm:gap-8">
                @foreach (config('energix.answers') as $i => $answer)
                    <x-answer-cote
                        :question="$answer['q']"
                        :answer="$answer['a']"
                        :index="$i + 1"
                    />
                @endforeach
            </div>
        </div>
    </section>

    {{-- ======================================================= 03 · SERVICII --}}
    <section class="mx-auto max-w-6xl px-5 py-20 sm:px-8 sm:py-28" aria-labelledby="services-title">
        <x-section-header
            :index="3"
            eyebrow="Ce facem"
            title="Trei servicii. Duse până la capăt."
            intro="Nu facem de toate. Facem bine ce facem, și îți lăsăm în urmă o instalație pe care o poate citi orice electrician care vine după noi."
        />

        {{-- șina pe care se clipsează modulele --}}
        <div class="mt-14">
            <div class="hidden h-px w-full bg-line lg:block" aria-hidden="true"></div>

            <div class="grid gap-6 lg:mt-0 lg:grid-cols-3">
                @foreach (config('energix.services') as $i => $service)
                    <x-service-card :service="$service" :index="$i + 1" />
                @endforeach
            </div>
        </div>

        <p class="mt-10">
            <a href="{{ route('services') }}" class="inline-flex items-center gap-2 font-mono text-sm tracking-wider text-gold uppercase transition hover:brightness-110">
                Vezi detaliile
                <span aria-hidden="true">&rarr;</span>
            </a>
        </p>
    </section>

    {{-- ========================================================= 04 · PROCES --}}
    <section class="border-t border-line" aria-labelledby="process-title">
        <div class="mx-auto max-w-6xl px-5 py-20 sm:px-8 sm:py-28">
            <x-section-header
                :index="4"
                eyebrow="Cum lucrăm"
                title="De la telefon la lumină aprinsă."
            />

            <ol class="mt-14 max-w-2xl">
                @foreach (config('energix.process') as $i => $step)
                    <x-process-step
                        :step="$step"
                        :index="$i + 1"
                        :last="$loop->last"
                    />
                @endforeach
            </ol>
        </div>
    </section>

    {{-- ====================================================== 05 · ÎNCREDERE --}}
    <section class="border-t border-line bg-ink-raised" aria-labelledby="trust-title">
        <div class="mx-auto max-w-6xl px-5 py-20 sm:px-8 sm:py-28">
            <div class="grid gap-14 lg:grid-cols-[0.9fr_1.1fr] lg:gap-20">
                <x-section-header
                    :index="5"
                    eyebrow="De ce noi"
                    title="Fapte, nu insigne."
                    intro="Nu îți fluturăm nimic pe perete. Îți spunem ce ne asumăm și ne ținem de cuvânt."
                />

                <div>
                    @foreach (config('energix.promises') as $promise)
                        <x-promise-item :promise="$promise" />
                    @endforeach

                    <p class="border-t border-line pt-6 text-paper-dim" data-reveal>
                        În spatele fiecărei lucrări e
                        <strong class="font-normal text-paper">{{ config('energix.founder.name') }}</strong>,
                        {{ mb_strtolower(config('energix.founder.role')) }}.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ========================================================== 06 · UNDE --}}
    <section class="border-t border-line" aria-labelledby="where-title">
        <div class="mx-auto max-w-6xl px-5 py-20 sm:px-8 sm:py-28">
            <div class="grid gap-12 lg:grid-cols-2 lg:gap-20">
                <x-section-header
                    :index="6"
                    eyebrow="Unde lucrăm"
                    title="Chișinău și toată Moldova."
                    intro="Pentru urgențe în oraș ajungem în aceeași zi. În restul țării, stabilim ziua la telefon."
                />

                <div data-reveal>
                    <h3 class="eyebrow">Program</h3>
                    <ul class="mt-4 divide-y divide-line border-y border-line">
                        @foreach (config('energix.hours') as $slot)
                            <li class="flex items-center justify-between gap-6 py-4">
                                <span class="text-paper-dim">{{ $slot['days'] }}</span>
                                <span class="readout text-paper">{{ $slot['time'] }}</span>
                            </li>
                        @endforeach
                        <li class="flex items-center justify-between gap-6 py-4">
                            <span class="text-paper">Urgențe</span>
                            <span class="readout text-gold">24/7</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- ========================================================== 07 · CTA --}}
    <x-cta-band />

@endsection
