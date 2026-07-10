@php($page = 'legal.privacy')
@extends('layouts.app')

@section('content')

    <section class="mx-auto max-w-3xl px-5 py-20 sm:px-8 sm:py-28" aria-labelledby="privacy-title">

        <p class="eyebrow flex items-center gap-2.5">
            <x-signature.wye :size="13" />
            <span>Confidențialitate</span>
        </p>

        <h1 id="privacy-title" class="mt-6 text-h1 text-paper">
            Politica de confidențialitate
        </h1>

        <p class="mt-6 text-lead text-paper-dim">
            Pe scurt: nu avem conturi de utilizator, nu vindem nimic online și nu ținem o bază
            de date cu datele tale. Singurul moment în care îți colectăm datele este atunci când
            ne scrii prin formularul de contact.
        </p>

        {{-- ---------------------------------------------------------------- --}}
        <h2 class="text-h3 text-paper mt-12">Cine este operatorul</h2>
        <p class="mt-4 text-paper-dim">
            Site-ul <strong class="font-normal text-paper">energix.md</strong> este administrat de
            Energix, din {{ config('energix.contact.city') }}, {{ config('energix.contact.country') }}.
            Ne poți scrie oricând la
            <a href="mailto:{{ config('energix.contact.email') }}" class="text-gold underline-offset-4 hover:underline">{{ config('energix.contact.email') }}</a>
            sau ne poți suna la
            <a href="tel:{{ config('energix.contact.phone_href') }}" class="text-gold underline-offset-4 hover:underline">{{ config('energix.contact.phone') }}</a>.
        </p>

        {{-- ---------------------------------------------------------------- --}}
        <h2 class="text-h3 text-paper mt-12">Ce date colectăm</h2>
        <p class="mt-4 text-paper-dim">
            Doar datele pe care ni le dai tu în formularul de contact:
        </p>
        <ul class="mt-4 list-disc pl-5 space-y-2 text-paper-dim">
            <li>numele și prenumele;</li>
            <li>numărul de telefon;</li>
            <li>adresa de email;</li>
            <li>mesajul tău.</li>
        </ul>
        <p class="mt-4 text-paper-dim">
            Nu îți cerem și nu strângem alte date despre tine. Nu creezi cont și nu îți salvăm
            un profil.
        </p>

        {{-- ---------------------------------------------------------------- --}}
        <h2 class="text-h3 text-paper mt-12">De ce le folosim</h2>
        <p class="mt-4 text-paper-dim">
            Ca să îți răspundem. Datele din formular ne trebuie ca să te putem contacta înapoi,
            să înțelegem ce ai nevoie și să îți pregătim o ofertă. Nu le folosim pentru reclame
            și nu îți trimitem mesaje pe care nu le-ai cerut.
        </p>

        {{-- ---------------------------------------------------------------- --}}
        <h2 class="text-h3 text-paper mt-12">Pe ce temei</h2>
        <p class="mt-4 text-paper-dim">
            Când completezi și trimiți formularul, ne dai consimțământul să folosim datele
            respective ca să îți răspundem. Interesul nostru legitim este simplu: să putem
            purta o discuție cu un potențial client care ne-a scris primul. Îți poți retrage
            consimțământul oricând (vezi mai jos).
        </p>

        {{-- ---------------------------------------------------------------- --}}
        <h2 class="text-h3 text-paper mt-12">Cine le vede și unde ajung</h2>
        <p class="mt-4 text-paper-dim">
            Mesajul trimis prin formular ajunge direct pe email-ul nostru de lucru. Nu se
            salvează într-o bază de date pe site — site-ul nu are așa ceva. Datele tale rămân
            în firmă și le văd doar oamenii din Energix care se ocupă de cererea ta. Nu le
            vindem, nu le închiriem și nu le dăm mai departe unor terți în scopuri comerciale.
        </p>

        {{-- ---------------------------------------------------------------- --}}
        <h2 class="text-h3 text-paper mt-12">Cât le păstrăm</h2>
        <p class="mt-4 text-paper-dim">
            Păstrăm mesajul cât timp e nevoie ca să ducem discuția până la capăt și, dacă lucrăm
            împreună, pe durata colaborării. După ce nu mai are rost să le ținem, le ștergem.
            Ne poți cere ștergerea și mai devreme.
        </p>

        {{-- ---------------------------------------------------------------- --}}
        <h2 class="text-h3 text-paper mt-12">Transferuri și instrumente terțe</h2>
        <p class="mt-4 text-paper-dim">
            Folosim Google Tag Manager ca să înțelegem cum este folosit site-ul, dar numai
            <strong class="font-normal text-paper">după ce accepți</strong> din banner-ul de
            cookie. Dacă nu accepți, aceste instrumente nu se încarcă și nu se transmite nimic.
            Detaliile despre ce se pune pe dispozitivul tău le găsești în
            <a href="{{ route('legal.cookies') }}" class="text-gold underline-offset-4 hover:underline">Politica de cookie</a>.
        </p>

        {{-- ---------------------------------------------------------------- --}}
        <h2 class="text-h3 text-paper mt-12">Drepturile tale</h2>
        <p class="mt-4 text-paper-dim">
            Datele sunt ale tale. Poți oricând să ceri:
        </p>
        <ul class="mt-4 list-disc pl-5 space-y-2 text-paper-dim">
            <li><strong class="font-normal text-paper">acces</strong> — să afli ce date avem despre tine;</li>
            <li><strong class="font-normal text-paper">rectificare</strong> — să corectăm ce e greșit;</li>
            <li><strong class="font-normal text-paper">ștergere</strong> — să ștergem datele tale;</li>
            <li><strong class="font-normal text-paper">opoziție</strong> — să nu mai folosim datele într-un anumit scop.</li>
        </ul>

        <p class="mt-4 text-paper-dim">
            Dacă îți refuzăm o cerere sau nu ești mulțumit de răspunsul nostru, ai dreptul să
            depui o plângere la <strong class="font-normal text-paper">Centrul Național pentru
            Protecția Datelor cu Caracter Personal</strong> — str. Serghei Lazo 48, Chișinău,
            <a href="mailto:centru@datepersonale.md" class="text-gold underline-offset-4 hover:underline">centru@datepersonale.md</a>.
        </p>

        {{-- ---------------------------------------------------------------- --}}
        <h2 class="text-h3 text-paper mt-12">Cum îți exerciți drepturile</h2>
        <p class="mt-4 text-paper-dim">
            Ne scrii un email la
            <a href="mailto:{{ config('energix.contact.email') }}" class="text-gold underline-offset-4 hover:underline">{{ config('energix.contact.email') }}</a>
            și ne spui ce vrei. Îți răspundem și rezolvăm în timp rezonabil. E gratuit.
        </p>

        {{-- ---------------------------------------------------------------- --}}
        <h2 class="text-h3 text-paper mt-12">Securitate</h2>
        <p class="mt-4 text-paper-dim">
            Site-ul funcționează pe o conexiune criptată HTTPS, așa că ce trimiți prin formular
            circulă protejat între dispozitivul tău și noi. Accesul la mesaje îl au doar
            persoanele care au drept de acces din firmă. Niciun sistem nu e perfect, dar ținem
            datele tale strict cât ne trebuie și doar pentru scopul pentru care ni le-ai dat.
        </p>

        {{-- ---------------------------------------------------------------- --}}
        <h2 class="text-h3 text-paper mt-12">Contact</h2>
        <p class="mt-4 text-paper-dim">
            Ai o întrebare despre datele tale sau despre această politică? Scrie-ne la
            <a href="mailto:{{ config('energix.contact.email') }}" class="text-gold underline-offset-4 hover:underline">{{ config('energix.contact.email') }}</a>
            sau sună la
            <a href="tel:{{ config('energix.contact.phone_href') }}" class="text-gold underline-offset-4 hover:underline">{{ config('energix.contact.phone') }}</a>.
        </p>

        <p class="mt-12 border-t border-line pt-6 font-mono text-xs tracking-wide text-paper-dim uppercase">
            Ultima actualizare: iulie 2026
        </p>

    </section>

@endsection
