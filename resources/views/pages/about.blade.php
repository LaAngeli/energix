@php($page = 'about')
@extends('layouts.app')

@section('content')

    <header class="border-b border-line">
        <div class="mx-auto max-w-6xl px-5 pt-12 pb-16 sm:px-8 sm:pt-20 sm:pb-20">
            <p class="eyebrow flex items-center gap-2.5">
                <x-signature.wye :size="13" :live="true" />
                Despre
            </p>
            <h1 class="mt-6 max-w-3xl text-h1 text-paper">Oameni care își pun numele pe lucrare.</h1>
            <p class="mt-6 max-w-2xl text-lead text-paper-dim">
                Energix e o echipă mică de electricieni din {{ config('energix.contact.city') }}.
                Facem instalații, reparăm ce s-a stricat și întreținem ce am montat.
            </p>
        </div>
    </header>

    {{-- Semnalul uman: raspundere cu nume. Nicio afirmatie de certificare. --}}
    <section class="border-b border-line" aria-labelledby="founder-title">
        <div class="mx-auto max-w-6xl px-5 py-20 sm:px-8 sm:py-24">
            <div class="grid gap-12 lg:grid-cols-[1fr_1.2fr] lg:gap-20">
                <div data-reveal>
                    <p class="eyebrow">Cine răspunde</p>
                    <h2 id="founder-title" class="mt-4 text-h2 text-paper">{{ config('energix.founder.name') }}</h2>
                    <p class="mt-2 font-mono text-sm tracking-wide text-gold">{{ config('energix.founder.role') }}</p>
                </div>

                <div class="space-y-5 text-lead text-paper-dim" data-reveal>
                    <p>
                        Când suni la Energix, nu ajungi la un call-center. Ajungi la echipa care
                        vine efectiv la tine acasă.
                    </p>
                    <p>
                        Am pornit de la o idee simplă: o instalație electrică se face o dată, bine.
                        Ce se ascunde în perete rămâne acolo douăzeci de ani, iar clientul nu are
                        cum să verifice singur ce e în spatele tencuielii. De asta lucrăm ca și cum
                        cineva ar urma să deschidă peretele mâine.
                    </p>
                    <p class="text-paper">
                        Îți arătăm ce am găsit, îți explicăm de ce trebuie schimbat și cât costă.
                        Apoi facem exact ce am spus.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="border-b border-line bg-ink-raised" aria-labelledby="values-title">
        <div class="mx-auto max-w-6xl px-5 py-20 sm:px-8 sm:py-28">
            <x-section-header
                :index="2"
                eyebrow="Valorile noastre"
                title="Ce ne ține în frâu."
            />

            <div class="mt-14 grid gap-5 sm:grid-cols-2">
                @foreach (config('energix.values') as $i => $value)
                    <x-value-item :value="$value" :index="$i + 1" />
                @endforeach
            </div>
        </div>
    </section>

    <section class="border-b border-line" aria-labelledby="trust-title">
        <div class="mx-auto max-w-6xl px-5 py-20 sm:px-8 sm:py-28">
            <div class="grid gap-14 lg:grid-cols-[0.9fr_1.1fr] lg:gap-20">
                <x-section-header
                    :index="3"
                    eyebrow="Ce îți garantăm"
                    title="Fapte, nu insigne."
                    intro="Patru lucruri pe care ni le asumăm în scris, la fiecare lucrare."
                />

                <div>
                    @foreach (config('energix.promises') as $promise)
                        <x-promise-item :promise="$promise" />
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <x-cta-band title="Hai să vorbim despre lucrarea ta." />

@endsection
