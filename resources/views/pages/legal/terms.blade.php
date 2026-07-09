@php($page = 'legal.terms')
@extends('layouts.app')

@section('content')

    <section class="mx-auto max-w-3xl px-5 py-20 sm:px-8 sm:py-28" aria-labelledby="terms-title">

        <p class="eyebrow flex items-center gap-2.5">
            <x-signature.wye :size="13" />
            Document legal
        </p>

        <h1 id="terms-title" class="mt-6 text-h1 text-paper">
            Termeni și condiții
        </h1>

        <p class="mt-4 font-mono text-xs tracking-wide text-paper-dim uppercase">
            Ultima actualizare: iulie 2026
        </p>

        <p class="mt-8 text-lead text-paper-dim">
            Prin folosirea site-ului {{ config('energix.seo.site_name') }}
            ({{ str_replace('https://', '', config('energix.seo.canonical')) }})
            ești de acord cu termenii de mai jos. Dacă nu ești de acord cu ei,
            te rugăm să nu folosești site-ul.
        </p>

        <h2 class="text-h3 text-paper mt-12">Obiectul site-ului</h2>
        <p class="mt-4 text-paper-dim">
            Acest site are rol de prezentare. Îți arată serviciile electrice pe care le oferim
            în {{ config('energix.contact.city') }} și în toată {{ config('energix.contact.country') }},
            felul în care lucrăm și modul în care ne poți contacta. Site-ul nu are cont de utilizator,
            nu vinde nimic online și nu procesează plăți. Orice colaborare se stabilește direct cu noi,
            prin telefon sau prin formularul de contact.
        </p>

        <h2 class="text-h3 text-paper mt-12">Proprietatea asupra conținutului</h2>
        <p class="mt-4 text-paper-dim">
            Textele, imaginile, logoul, grafica și structura acestui site ne aparțin sau le folosim
            cu drept de utilizare. Le poți citi și distribui link către ele. Nu ai voie să le copiezi,
            reproduci sau refolosești în scop comercial fără acordul nostru scris.
        </p>

        <h2 class="text-h3 text-paper mt-12">Formularul de contact</h2>
        <p class="mt-4 text-paper-dim">
            Formularul de pe site îl folosești ca să ne trimiți o cerere. Când îl completezi,
            te obligi să introduci date reale și să nu îl folosești pentru mesaje abuzive, spam
            sau conținut ilegal. Mesajul îți ajunge la noi prin email; nu îl stocăm într-o bază de date.
            Ce date colectăm și cum le protejăm îți explicăm în
            <a href="{{ route('legal.privacy') }}" class="text-gold underline decoration-line underline-offset-4 transition hover:decoration-gold">Politica de confidențialitate</a>.
        </p>

        <h2 class="text-h3 text-paper mt-12">Limitarea răspunderii</h2>
        <p class="mt-4 text-paper-dim">
            Ne străduim ca informațiile de pe site să fie corecte și actuale, dar au caracter general
            și pot fi modificate fără o notificare prealabilă. Ele nu înlocuiesc o evaluare la fața locului
            și nu reprezintă o ofertă fermă. Prețul, termenul și detaliile unei lucrări ți le confirmăm
            întotdeauna în oferta pe care ți-o dăm direct, nu pe baza informațiilor generice de aici.
        </p>
        <ul class="mt-4 list-disc space-y-2 pl-5 text-paper-dim">
            <li>Nu răspundem pentru decizii luate exclusiv pe baza conținutului de pe site.</li>
            <li>Nu garantăm că site-ul va fi disponibil neîntrerupt sau lipsit de erori.</li>
            <li>Nu răspundem pentru probleme apărute din cauza conexiunii tale la internet sau a echipamentului tău.</li>
        </ul>

        <h2 class="text-h3 text-paper mt-12">Linkuri către alte site-uri</h2>
        <p class="mt-4 text-paper-dim">
            Site-ul poate conține linkuri către pagini externe, de exemplu rețelele noastre sociale.
            Aceste pagini au regulile și politicile lor, pe care nu le controlăm. Când urmezi un asemenea
            link, părăsești site-ul nostru, iar noi nu răspundem pentru conținutul sau practicile lor.
        </p>

        <h2 class="text-h3 text-paper mt-12">Modificarea termenilor</h2>
        <p class="mt-4 text-paper-dim">
            Putem actualiza acești termeni oricând, pe măsură ce site-ul sau serviciile se schimbă.
            Versiunea în vigoare este cea publicată pe această pagină, împreună cu data ultimei actualizări
            de mai sus. Dacă folosești site-ul după o modificare, înseamnă că accepți termenii noi.
        </p>

        <h2 class="text-h3 text-paper mt-12">Legea aplicabilă</h2>
        <p class="mt-4 text-paper-dim">
            Acești termeni sunt guvernați de legislația {{ config('energix.contact.country') }}.
            Orice neînțelegere legată de folosirea site-ului încercăm mai întâi să o rezolvăm direct,
            pe cale amiabilă. Dacă nu reușim, ea se soluționează de instanțele competente din
            {{ config('energix.contact.country') }}.
        </p>

        <h2 class="text-h3 text-paper mt-12">Contact</h2>
        <p class="mt-4 text-paper-dim">
            Pentru orice întrebare legată de acești termeni, ne poți scrie la
            <a href="mailto:{{ config('energix.contact.email') }}" class="text-gold underline decoration-line underline-offset-4 transition hover:decoration-gold">{{ config('energix.contact.email') }}</a>
            sau ne poți suna la
            <a href="tel:{{ config('energix.contact.phone_href') }}" class="text-gold underline decoration-line underline-offset-4 transition hover:decoration-gold">{{ config('energix.contact.phone') }}</a>.
        </p>

    </section>

@endsection
