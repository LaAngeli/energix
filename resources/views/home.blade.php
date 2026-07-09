@extends('layouts.app')

@section('title', 'Energix — Instalații electrice în Chișinău și toată Moldova')

@section('content')
    <main class="mx-auto max-w-5xl px-5 py-16 sm:px-8 sm:py-24">

        {{-- ================= HERO ================= --}}
        <section aria-labelledby="hero-title">
            <p class="eyebrow">Servicii electrice · Chișinău</p>

            <h1 id="hero-title" class="mt-5 text-h1 text-paper">
                Curentul ajunge<br>unde trebuie.
            </h1>

            <p class="mt-6 max-w-xl text-lead text-paper-dim">
                Instalații, reparații și mentenanță pentru case, apartamente și spații
                comerciale. În Chișinău și în toată Republica Moldova.
            </p>

            <div class="mt-9 flex flex-col gap-3 sm:flex-row sm:items-center">
                <a href="tel:+37368582016"
                   class="inline-flex items-center justify-center gap-3 rounded-sm bg-live px-6 py-4 font-mono text-sm font-medium tracking-wider text-ink uppercase transition hover:brightness-110">
                    Sună acum
                    <span class="tabular-nums normal-case tracking-normal">+373 68 582 016</span>
                </a>

                <a href="#"
                   class="inline-flex items-center justify-center rounded-sm border border-line px-6 py-4 font-mono text-sm font-medium tracking-wider text-paper uppercase transition hover:border-arc hover:text-arc">
                    Cere o ofertă
                </a>
            </div>

            {{-- Fapte, nu insigne. Inlocuiesc semnalul de incredere pierdut. --}}
            <ul class="mt-10 flex flex-wrap gap-x-6 gap-y-2 font-mono text-xs tracking-wide text-paper-dim uppercase">
                <li>Garanție pentru lucrări</li>
                <li aria-hidden="true" class="hidden text-line sm:block">/</li>
                <li>Intervenții urgente 24/7</li>
                <li aria-hidden="true" class="hidden text-line sm:block">/</li>
                <li>Consultație gratuită</li>
                <li aria-hidden="true" class="hidden text-line sm:block">/</li>
                <li>Preț fix în ofertă</li>
            </ul>
        </section>

        {{-- ========= SEMNATURA: schema monofilara ========= --}}
        <section class="mt-20 border-t border-line pt-10" aria-labelledby="schema-title">
            <h2 id="schema-title" class="sr-only">Schema unui tablou electric</h2>
            <p class="eyebrow mb-6">Ce montăm, de fapt</p>

            <div class="overflow-x-auto">
                <svg viewBox="0 0 660 250" class="schema w-full min-w-[560px]" role="img"
                     aria-label="Schemă monofilară: intrare 230 V, siguranță generală, diferențial 30 mA și patru circuite: iluminat, prize, bucătărie, boiler.">
                    <g fill="none" stroke="currentColor" stroke-width="1.5" class="text-line">
                        {{-- traseul principal --}}
                        <path class="schema-path" d="M20 40 H120 M180 40 H240 M300 40 H620 M340 40 V90 M440 40 V90 M540 40 V90 M620 40 V90"/>
                        {{-- picioare circuite --}}
                        <path class="schema-path" d="M340 130 V200 M440 130 V200 M540 130 V200 M620 130 V200"/>
                    </g>

                    {{-- siguranta generala --}}
                    <rect x="120" y="26" width="60" height="28" fill="none" stroke="currentColor" stroke-width="1.5" class="text-line"/>
                    {{-- diferential --}}
                    <rect x="240" y="26" width="60" height="28" fill="none" stroke="currentColor" stroke-width="1.5" class="text-arc"/>
                    <circle cx="270" cy="14" r="4" class="schema-led fill-live"/>

                    {{-- module circuite --}}
                    <g class="text-line">
                        <rect x="322" y="90" width="36" height="40" fill="none" stroke="currentColor" stroke-width="1.5"/>
                        <rect x="422" y="90" width="36" height="40" fill="none" stroke="currentColor" stroke-width="1.5"/>
                        <rect x="522" y="90" width="36" height="40" fill="none" stroke="currentColor" stroke-width="1.5"/>
                        <rect x="602" y="90" width="36" height="40" fill="none" stroke="currentColor" stroke-width="1.5"/>
                    </g>

                    <g font-family="var(--font-mono)" font-size="11" letter-spacing="1" class="fill-paper-dim">
                        <text x="20" y="28">230 V</text>
                        <text x="122" y="72">SIGURANȚĂ</text>
                        <text x="242" y="72" class="fill-arc">DIFERENȚIAL 30 mA</text>
                        <text x="322" y="220">ILUMINAT</text>
                        <text x="422" y="220">PRIZE</text>
                        <text x="522" y="220">BUCĂTĂRIE</text>
                        <text x="596" y="220">BOILER</text>
                    </g>
                </svg>
            </div>
        </section>

        {{-- ========= SPECIMEN: verificare tipografie ========= --}}
        <section class="mt-20 border-t border-line pt-10">
            <p class="eyebrow mb-6">Specimen · verificare diacritice</p>

            <p class="text-h2 text-paper">Șase țevi, două prăjini, un întrerupător.</p>
            <p class="mt-4 font-mono text-lead text-arc">ăâîșț ĂÂÎȘȚ — 0123456789</p>

            <div class="mt-10 grid gap-8 sm:grid-cols-2">
                <div>
                    <p class="eyebrow mb-3">Corp de text · IBM Plex Sans</p>
                    <p class="text-paper-dim">
                        Verificăm instalația, îți spunem exact ce trebuie schimbat și cât costă,
                        apoi lucrăm curat. Fără surprize la final și fără costuri ascunse în
                        devizul de plată.
                    </p>
                </div>
                <div>
                    <p class="eyebrow mb-3">Cifre de instrument · IBM Plex Mono</p>
                    <p class="readout">500</p>
                    <p class="mt-1 font-mono text-xs tracking-wider text-paper-dim uppercase">
                        Proiecte finalizate
                    </p>
                </div>
            </div>
        </section>

        {{-- ========= Paleta ========= --}}
        <section class="mt-20 border-t border-line pt-10">
            <p class="eyebrow mb-6">Paletă · contrast pe fundal</p>
            <dl class="grid grid-cols-2 gap-4 font-mono text-xs sm:grid-cols-3">
                @foreach ([
                    ['paper', '#e8edf5', '16.4:1'],
                    ['paper-dim', '#94a3b8', '7.4:1'],
                    ['arc', '#00d4ff', '10.9:1'],
                    ['live', '#ffb800', '11.9:1'],
                    ['line', '#1e2a3d', '—'],
                    ['ink-raised', '#101624', '—'],
                ] as [$name, $hex, $ratio])
                    <div class="border border-line p-3">
                        <div class="h-10 w-full" style="background-color: {{ $hex }}"></div>
                        <dt class="mt-3 text-paper">{{ $name }}</dt>
                        <dd class="text-paper-dim">{{ $hex }}</dd>
                        <dd class="text-paper-dim">{{ $ratio }}</dd>
                    </div>
                @endforeach
            </dl>
        </section>

    </main>
@endsection
