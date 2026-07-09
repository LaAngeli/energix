@php($page = 'legal.cookies')
@extends('layouts.app')

@section('content')

    <div class="mx-auto max-w-3xl px-5 py-20 sm:px-8 sm:py-28">

        <p class="eyebrow flex items-center gap-2.5">
            <x-signature.wye :size="13" :live="true" />
            <span>Informații legale</span>
        </p>

        <h1 class="mt-6 text-h1 text-paper">Politica de cookie</h1>

        <p class="mt-4 font-mono text-xs tracking-wide text-paper-dim uppercase">
            Ultima actualizare: iulie 2026
        </p>

        <div class="mt-12 space-y-4 text-paper-dim">

            <p>
                Această pagină îți explică ce sunt cookie-urile, ce fișiere de acest fel
                folosim pe energix.md și cum îți poți exprima sau retrage consimțământul.
                Site-ul nostru este un site de prezentare: nu ai cont, nu cumperi nimic
                online, iar datele tale nu ajung într-o bază de date.
            </p>

            <h2 class="text-h3 text-paper mt-12">Ce sunt cookie-urile</h2>

            <p>
                Cookie-urile sunt fișiere text mici pe care site-ul le pune în browserul
                tău când îl vizitezi. Ele ajută pagina să funcționeze corect și, atunci
                când îți dai acordul, ne ajută să înțelegem cum este folosit site-ul.
            </p>

            <h2 class="text-h3 text-paper mt-12">Ce folosim</h2>

            <h3 class="mt-8 font-mono text-sm tracking-wide text-paper uppercase">
                1. Strict necesare
            </h3>

            <p class="mt-2">
                Sunt fișierele fără de care site-ul nu ar merge. Se pun automat și nu
                depind de acordul tău, pentru că nu te urmăresc și nu construiesc niciun
                profil despre tine. La noi sunt două:
            </p>

            <ul class="mt-3 list-disc pl-5 space-y-2">
                <li>
                    <strong class="font-normal text-paper">cookie-ul de sesiune</strong> —
                    ține minte vizita ta cât timp navighezi pe pagini;
                </li>
                <li>
                    <strong class="font-normal text-paper">token-ul CSRF</strong> —
                    protejează formularul de contact împotriva trimiterilor falsificate.
                </li>
            </ul>

            <h3 class="mt-8 font-mono text-sm tracking-wide text-paper uppercase">
                2. Analiză
            </h3>

            <p class="mt-2">
                Folosim Google Tag Manager pentru a măsura, în mod anonim, cum este
                folosit site-ul. Aceste fișiere se încarcă
                <strong class="font-normal text-paper">doar după ce apeși „Accept”</strong>
                în banner-ul afișat la prima ta vizită. Până atunci nu rulează nimic de
                acest fel.
            </p>

            <h2 class="text-h3 text-paper mt-12">Refuzul nu strică nimic</h2>

            <p>
                Dacă refuzi fișierele de analiză, site-ul funcționează normal, la fel de
                bine ca și cu ele acceptate. Toate paginile, formularul de contact și
                numerele de telefon rămân disponibile. Renunți doar la măsurătorile care
                ne ajută pe noi să îmbunătățim pagina.
            </p>

            <h2 class="text-h3 text-paper mt-12">Cum îți retragi consimțământul</h2>

            <p>
                Îți poți schimba oricând alegerea ștergând datele site-ului din browserul
                tău. În mod normal găsești opțiunea în setările browserului, la secțiunea
                de confidențialitate:
            </p>

            <ul class="mt-3 list-disc pl-5 space-y-2">
                <li>șterge cookie-urile și datele salvate pentru energix.md;</li>
                <li>la următoarea vizită îți apare din nou banner-ul, cu alegerea de la zero.</li>
            </ul>

            <p>
                Poți, de asemenea, să blochezi complet cookie-urile din setările
                browserului. Reține că blocarea celor strict necesare poate împiedica
                trimiterea formularului de contact.
            </p>

            <h2 class="text-h3 text-paper mt-12">Cât trăiesc</h2>

            <ul class="mt-3 list-disc pl-5 space-y-2">
                <li>
                    Fișierele strict necesare durează, de regulă, până închizi browserul
                    sau expiră sesiunea.
                </li>
                <li>
                    Fișierele de analiză puse de Google pot rămâne mai mult, până la
                    câteva luni, conform regulilor Google.
                </li>
            </ul>

            <h2 class="text-h3 text-paper mt-12">Mai multe despre Google</h2>

            <p>
                Detaliile despre fișierele folosite de Google le găsești în documentele lor:
            </p>

            <ul class="mt-3 list-disc pl-5 space-y-2">
                <li>
                    <a href="https://policies.google.com/privacy" target="_blank" rel="noopener noreferrer" class="text-gold underline underline-offset-4 transition hover:brightness-110">
                        Politica de confidențialitate Google
                    </a>
                </li>
                <li>
                    <a href="https://policies.google.com/technologies/cookies" target="_blank" rel="noopener noreferrer" class="text-gold underline underline-offset-4 transition hover:brightness-110">
                        Cum folosește Google cookie-urile
                    </a>
                </li>
            </ul>

            <h2 class="text-h3 text-paper mt-12">Contact</h2>

            <p>
                Ai o întrebare despre această pagină sau despre datele tale? Scrie-ne la
                <a href="mailto:{{ config('energix.contact.email') }}" class="text-gold underline underline-offset-4 transition hover:brightness-110">{{ config('energix.contact.email') }}</a>
                sau sună la
                <a href="tel:{{ config('energix.contact.phone_href') }}" class="text-gold underline underline-offset-4 transition hover:brightness-110">{{ config('energix.contact.phone') }}</a>.
                Vezi și
                <a href="{{ route('legal.privacy') }}" class="text-gold underline underline-offset-4 transition hover:brightness-110">politica de confidențialitate</a>.
            </p>

        </div>
    </div>

@endsection
