@php($page = 'about')
@extends('layouts.app')

@section('content')

    <header class="relative overflow-hidden border-b border-line">
        <div class="blueprint absolute inset-0 opacity-20 [mask-image:radial-gradient(70%_90%_at_40%_20%,black,transparent)]" aria-hidden="true"></div>

        <div class="relative mx-auto max-w-6xl px-5 pt-8 pb-14 sm:px-8 sm:pb-18">
            <x-job-sheet code="DSP-04" name="Despre" index="04/05" />

            <div class="mt-10 grid gap-10 lg:grid-cols-[1.1fr_auto] lg:items-center">
                <div>
                    <h1 class="max-w-3xl text-h1 text-paper">Un singur lucru, făcut bine, de zece ani.</h1>
                    <p class="mt-6 max-w-2xl text-lead text-paper-dim">
                        Energix e o echipă de electricieni din {{ config('energix.contact.city') }}.
                        Montăm instalații electrice complete pentru construcții: apartamente, case,
                        spații industriale. Doar asta.
                    </p>
                </div>

                <div class="hidden lg:block" data-reveal>
                    <x-hero-instrument.level />
                </div>
            </div>
        </div>
    </header>

    {{-- ============================ contorul de vechime ============================ --}}
    <section class="border-b border-line" aria-labelledby="experience-title">
        <div class="mx-auto max-w-6xl px-5 py-16 sm:px-8 sm:py-24">
            <div class="grid items-center gap-12 lg:grid-cols-[1fr_1.2fr] lg:gap-20">
                <div data-reveal>
                    <p class="eyebrow">Contor de vechime</p>
                    <p class="mt-4 flex items-baseline gap-4">
                        <span id="experience-title" class="readout text-meter leading-none text-gold">
                            {{ config('energix.experience.years') }}
                        </span>
                        <span class="max-w-40 font-mono text-xs tracking-[0.14em] text-paper-dim uppercase">
                            {{ config('energix.experience.label') }}
                        </span>
                    </p>
                    <div class="meter-scale mt-6" aria-hidden="true"></div>
                    <p class="mt-3 font-mono text-xs tracking-wide text-paper-dim">
                        {{ config('energix.contact.city') }} și toată {{ config('energix.contact.country') }}
                    </p>
                </div>

                <div class="space-y-5 text-lead text-paper-dim" data-reveal>
                    <p>
                        Când suni la Energix, nu ajungi la un call-center. Ajungi la echipa care
                        vine efectiv pe șantierul tău.
                    </p>
                    <p>
                        Am pornit de la o idee simplă: o instalație electrică se face o dată, bine.
                        Ce se ascunde în perete rămâne acolo douăzeci de ani, iar clientul nu are
                        cum să verifice singur ce e în spatele tencuielii. De asta lucrăm ca și cum
                        cineva ar urma să deschidă peretele mâine.
                    </p>
                    <p class="text-paper">
                        Îți arătăm schema înainte să începem, îți explicăm fiecare alegere și cât
                        costă. Apoi facem exact ce am spus.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== valorile, ca aparataj de protecție ==================== --}}
    <section class="border-b border-line bg-ink-raised/40" aria-labelledby="values-title">
        <div class="mx-auto max-w-6xl px-5 py-16 sm:px-8 sm:py-24">
            <x-section-header
                eyebrow="Aparatajul de protecție"
                title="Ce ne ține în frâu."
                intro="Patru dispozitive care nu se scot din schemă, indiferent de lucrare."
            />

            <div class="mt-12 grid gap-5 sm:grid-cols-2">
                @foreach (config('energix.values') as $i => $value)
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

    {{-- =============================== garanțiile =============================== --}}
    <section class="border-b border-line" aria-labelledby="trust-title">
        <div class="mx-auto max-w-6xl px-5 py-16 sm:px-8 sm:py-24">
            <div class="grid gap-12 lg:grid-cols-[0.9fr_1.1fr] lg:gap-20">
                <x-section-header
                    eyebrow="Ce îți garantăm"
                    title="Fapte, nu insigne."
                    intro="Patru lucruri pe care ni le asumăm în scris, la fiecare lucrare."
                />

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

    <x-cta-band title="Hai să vorbim despre lucrarea ta." />

@endsection
