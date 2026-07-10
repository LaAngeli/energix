{{--
| Sigla care se încarcă (hero /despre) — portată din animația livrată de client.
|
| Trei abateri deliberate de la sursă:
|
|   1. NU rulează în buclă. Se energizează o dată, când intră în cadru, apoi se
|      oprește în starea aprinsă. Cursorul o reia. O buclă perpetuă lângă un titlu
|      obligă ochiul să lupte cu ea, iar `DESIGN.md` interzice mișcarea ambientală.
|   2. Culorile vin din tokenii de brand (`--color-gold` = #f2d147), nu din
|      auriul #f5c23e al machetei.
|   3. Decorativă: `aria-hidden` și `alt=""`. Numele firmei e deja în navbar și în
|      `<h1>`; un al treilea „Energix” pentru cititoarele de ecran e zgomot.
|
| Toate coordonatele rămân în spațiul original 940×800 al machetei. `--u` traduce
| un pixel de machetă în lățimea reală a containerului, deci geometria e exactă la
| orice dimensiune, fără niciun calcul în JavaScript.
--}}
@php
    $logo = asset('images/logo/logo_transparent.webp');

    /*
     | Particulele care converg spre bec. Un inel ușor neregulat: raze diferite pe
     | trei trepte, ca fluxul să nu pară un ceas. Coordonate în pixeli de machetă,
     | relative la centrul becului (470, 308).
     */
    $sparks = [];

    foreach (range(0, 16) as $i) {
        $angle = deg2rad($i * (360 / 17) - 90);
        $radius = 300 + ($i % 3) * 35;

        $sparks[] = [
            'x' => (int) round(cos($angle) * $radius),
            'y' => (int) round(sin($angle) * $radius),
            'size' => 3 + ($i % 3),
            'lead' => -0.03 * $i,
        ];
    }
@endphp

<div class="logo-charge" data-logo-charge aria-hidden="true">
    <span class="logo-charge-aura"></span>

    @foreach ($sparks as $spark)
        <span
            class="logo-charge-spark"
            style="
                --sx: calc({{ $spark['x'] }} * var(--u));
                --sy: calc({{ $spark['y'] }} * var(--u));
                --size: calc({{ $spark['size'] }} * var(--u));
                --lead: calc(var(--nrg-cycle) * {{ $spark['lead'] }});
            "
        ></span>
    @endforeach

    <img class="logo-charge-img" src="{{ $logo }}" alt="" width="669" height="543" loading="lazy" decoding="async">

    {{-- Copie decupată pe bec: se aprinde puternic în momentul închiderii inelului. --}}
    <img class="logo-charge-surge" src="{{ $logo }}" alt="" width="669" height="543" loading="lazy" decoding="async">

    {{-- Copie decupată pe wordmark + bara de lumină: sweep-ul care trece prin literă. --}}
    <img class="logo-charge-sweep" src="{{ $logo }}" alt="" width="669" height="543" loading="lazy" decoding="async">
    <span class="logo-charge-bar"></span>

    {{-- Inelul de încărcare. `pathLength=100` face din stroke-dashoffset un procent. --}}
    <svg class="logo-charge-ring" viewBox="0 0 940 800" fill="none" focusable="false">
        <circle class="logo-charge-track" cx="470" cy="308" r="190" />
        <circle
            class="logo-charge-arc"
            cx="470"
            cy="308"
            r="190"
            pathLength="100"
            transform="rotate(90 470 308)"
        />
        {{-- Punctul-cap orbitează cu umplerea. Rotația e pusă din CSS, nu din atribut. --}}
        <g class="logo-charge-head">
            <circle cx="470" cy="498" r="6.5" />
        </g>
    </svg>

    {{-- Bătaia de final, în vârful de jos al inelului. --}}
    <span class="logo-charge-flash"></span>
    <span class="logo-charge-ripple"></span>
</div>
