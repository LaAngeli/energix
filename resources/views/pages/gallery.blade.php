@php($page = 'gallery')
@extends('layouts.app')

@section('content')

    <header class="border-b border-line">
        <div class="mx-auto max-w-6xl px-5 pt-12 pb-16 sm:px-8 sm:pt-20 sm:pb-20">
            <p class="eyebrow flex items-center gap-2.5">
                <x-signature.wye :size="13" :live="true" />
                Lucrări
            </p>
            <h1 class="mt-6 max-w-3xl text-h1 text-paper">Tipurile de lucrări pe care le facem.</h1>
            <p class="mt-6 max-w-2xl text-lead text-paper-dim">
                De la un tablou de apartament până la cablarea unei hale. Filtrează după
                categoria care te interesează.
            </p>

            {{--
            | Onestitate, nu marketing. Imaginile sunt ilustrative, nu fotografii ale
            | lucrarilor noastre. Intr-o nisa care se vinde pe incredere, o galerie
            | falsa prezentata drept portofoliu face mai mult rau decat lipsa ei.
            --}}
            <p class="mt-8 flex max-w-2xl gap-3 rounded-sm border border-line bg-ink-raised p-4 text-sm text-paper-dim">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="mt-0.5 shrink-0 text-gold" aria-hidden="true">
                    <circle cx="12" cy="12" r="9" />
                    <path d="M12 8h.01M11 12h1v4h1" stroke-linecap="round" />
                </svg>
                <span>
                    Imaginile de mai jos sunt ilustrative și arată tipul lucrării, nu proiecte
                    executate de noi. Pregătim fotografii de pe șantierele proprii.
                </span>
            </p>
        </div>
    </header>

    <section class="mx-auto max-w-6xl px-5 py-16 sm:px-8 sm:py-20" aria-label="Tipuri de lucrări">
        <x-gallery-grid
            :items="config('energix.gallery')"
            :categories="config('energix.gallery_categories')"
        />
    </section>

    <x-cta-band title="Ai un proiect asemănător?" />

@endsection
