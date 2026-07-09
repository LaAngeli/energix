@php($page = 'home')
@extends('layouts.app')

@section('content')
    <section class="mx-auto flex min-h-[60vh] max-w-2xl flex-col items-center justify-center px-5 py-24 text-center sm:px-8">
        <p class="readout text-readout text-gold">404</p>

        <h1 class="mt-6 text-h2 text-paper">Circuit întrerupt.</h1>

        <p class="mt-4 text-lead text-paper-dim">
            Pagina pe care o cauți nu există sau s-a mutat. Hai înapoi la lumină.
        </p>

        <div class="mt-10 flex flex-col gap-3 sm:flex-row">
            <a
                href="{{ route('home') }}"
                class="inline-flex items-center justify-center rounded-sm bg-gold px-6 py-3.5 font-mono text-sm font-medium tracking-wider text-ink uppercase transition hover:brightness-110"
            >
                Pagina principală
            </a>
            <a
                href="tel:{{ config('energix.contact.phone_href') }}"
                class="inline-flex items-center justify-center rounded-sm border border-line px-6 py-3.5 font-mono text-sm tracking-wider text-paper uppercase transition hover:border-gold hover:text-gold"
            >
                Sună-ne
            </a>
        </div>
    </section>
@endsection
