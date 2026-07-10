@php($page = '404')
@extends('layouts.app')

@section('content')
    <section class="relative overflow-hidden">
        <div class="blueprint absolute inset-0 opacity-20 [mask-image:radial-gradient(70%_80%_at_50%_30%,black,transparent)]" aria-hidden="true"></div>

        <div class="relative mx-auto max-w-6xl px-5 pt-8 sm:px-8">
            {{-- Singura pagina de pe site unde LED-ul fisei e stins. --}}
            <x-job-sheet code="ERR-404" name="Necunoscut" status="Circuit întrerupt" :live="false" />
        </div>

        <div class="relative mx-auto flex min-h-[55vh] max-w-2xl flex-col items-center justify-center px-5 py-20 text-center sm:px-8">
            <p class="readout text-meter leading-none text-gold">404</p>

            <h1 class="mt-6 text-h2 text-paper">Circuit întrerupt.</h1>

            <p class="mt-4 text-lead text-paper-dim">
                Pagina pe care o cauți nu există sau s-a mutat. Hai înapoi la sursă.
            </p>

            <div class="mt-10 flex flex-col gap-3 sm:flex-row">
                <a
                    href="{{ route('home') }}"
                    class="energize-sweep inline-flex items-center justify-center rounded-sm bg-gold px-6 py-3.5 font-mono text-sm font-medium tracking-wider text-ink uppercase"
                >
                    Înapoi la sursă
                </a>
                <a
                    href="tel:{{ config('energix.contact.phone_href') }}"
                    class="inline-flex items-center justify-center rounded-sm border border-line px-6 py-3.5 font-mono text-sm tracking-wider text-paper uppercase transition hover:border-gold hover:text-gold active:translate-y-px"
                >
                    Sună-ne
                </a>
            </div>
        </div>
    </section>
@endsection
