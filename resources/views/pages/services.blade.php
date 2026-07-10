@php($page = 'services')
@extends('layouts.app')

@section('content')

    <header class="relative overflow-hidden border-b border-line">
        <div class="blueprint absolute inset-0 opacity-20 [mask-image:radial-gradient(70%_90%_at_40%_20%,black,transparent)]" aria-hidden="true"></div>

        <div class="relative mx-auto max-w-6xl px-5 pt-8 pb-14 sm:px-8 sm:pb-18">
            <x-job-sheet code="SRV-02" name="Servicii" index="02/05" />

            <h1 class="mt-10 max-w-3xl text-h1 text-paper">Instalația completă, pentru spațiul tău.</h1>
            <p class="mt-6 max-w-2xl text-lead text-paper-dim">
                Același ciclu complet — proiect, cablare, tablou, montaj, verificare —
                adaptat la tipul construcției. Alege circuitul.
            </p>
        </div>
    </header>

    {{-- ================================ consola de segmente ================================ --}}
    <section class="mx-auto max-w-6xl px-5 py-14 sm:px-8 sm:py-20" aria-label="Alege tipul de spațiu" data-seg-switcher>
        <div role="tablist" aria-label="Tipul de spațiu" class="grid gap-3 sm:grid-cols-3">
            @foreach (config('energix.services') as $i => $service)
                <button
                    type="button"
                    role="tab"
                    id="seg-tab-{{ $service['slug'] }}"
                    aria-controls="seg-panel-{{ $service['slug'] }}"
                    aria-selected="{{ $i === 0 ? 'true' : 'false' }}"
                    tabindex="{{ $i === 0 ? '0' : '-1' }}"
                    class="seg-switch"
                >
                    <span class="led" aria-hidden="true"></span>
                    <span>
                        <span class="block font-mono text-[0.6rem] tracking-[0.16em] text-paper-dim uppercase">
                            Circuit {{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}
                        </span>
                        <span class="mt-0.5 block font-display text-base text-paper">{{ ucfirst($service['slug']) }}</span>
                    </span>
                </button>
            @endforeach
        </div>

        @foreach (config('energix.services') as $i => $service)
            <div
                role="tabpanel"
                id="seg-panel-{{ $service['slug'] }}"
                data-slug="{{ $service['slug'] }}"
                aria-labelledby="seg-tab-{{ $service['slug'] }}"
                @if ($i !== 0) hidden @endif
                class="pt-10"
            >
                {{-- ancora veche (#apartamente etc.) ramane functionala --}}
                <span id="{{ $service['slug'] }}" class="block" aria-hidden="true"></span>

                <div class="grid items-start gap-10 lg:grid-cols-[1.15fr_1fr] lg:gap-16">
                    <div>
                        <p class="eyebrow flex items-center gap-2.5">
                            <x-signature.wye :size="13" :live="true" />
                            {{ $service['tagline'] }}
                        </p>

                        <h2 class="mt-4 text-h2 text-paper">{{ $service['title'] }}</h2>
                        <p class="mt-5 text-lead text-paper-dim">{{ $service['intro'] }}</p>

                        <ul class="mt-8 divide-y divide-line border-y border-line">
                            @foreach ($service['features'] as $j => $feature)
                                <li class="flex items-center gap-4 py-3.5">
                                    <span class="readout text-xs text-gold">{{ str_pad((string) ($j + 1), 2, '0', STR_PAD_LEFT) }}</span>
                                    <span class="text-paper">{{ $feature }}</span>
                                </li>
                            @endforeach
                        </ul>

                        <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                            <a
                                href="tel:{{ config('energix.contact.phone_href') }}"
                                class="energize-sweep inline-flex items-center justify-center gap-3 rounded-sm bg-gold px-6 py-3.5 font-mono text-sm font-medium tracking-wider text-ink uppercase"
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
                            width="800"
                            height="533"
                            loading="lazy"
                            decoding="async"
                            aria-hidden="true"
                            class="h-64 w-full object-cover opacity-70 sm:h-96"
                        >
                    </div>
                </div>
            </div>
        @endforeach
    </section>

    {{-- ==================================== etapele ==================================== --}}
    <section class="border-t border-line bg-ink-raised/40" aria-labelledby="stages-title">
        <div class="mx-auto max-w-6xl px-5 py-16 sm:px-8 sm:py-24">
            <x-section-header
                eyebrow="Execuția"
                title="Cinci etape, indiferent de spațiu."
            />
            <x-signature.stages class="mt-12" data-reveal />
        </div>
    </section>

    {{-- ============================== drumul clientului ============================== --}}
    <section class="border-t border-line" aria-labelledby="process-title">
        <div class="mx-auto max-w-6xl px-5 py-16 sm:px-8 sm:py-24">
            <x-section-header
                eyebrow="Cum lucrăm"
                title="Patru pași, fără surprize."
            />

            <ol class="mt-12 max-w-2xl">
                @foreach (config('energix.process') as $i => $step)
                    <x-process-step :step="$step" :index="$i + 1" :last="$loop->last" />
                @endforeach
            </ol>
        </div>
    </section>

    <x-cta-band title="Spune-ne despre spațiul tău." />

@endsection
