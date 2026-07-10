{{--
| Comutatorul de proces (secțiunea „Cum lucrăm", /servicii).
|
| Un comutator cu came — aparatul rotativ de pe tablourile industriale — cu câte
| o poziție gravată pentru fiecare pas al procesului. La intrarea în cadru, knob-ul
| parcurge singur pozițiile, o dată; pozițiile trecute rămân aprinse. Semantica e
| exactă: pe un comutator cu came nu poți sări peste poziții.
|
| Legat în ambele sensuri de lista de pași: hover pe un pas (pointer fin) rotește
| comutatorul la poziția lui, iar poziția selectată încălzește pasul din listă.
|
| Decorativ (`aria-hidden`, fără elemente focusabile): cei patru pași din stânga
| sunt conținutul real. Aparatul nu adaugă informație — o pune sub tensiune.
| De aceea starea fără JS e cea FINALĂ (toate pozițiile aprinse, knob-ul pe
| ultima): `initProcessSwitch` o resetează la prima poziție înainte de a anima.
|
| Textul vine integral din `site.process` — zero chei noi de limbă.
--}}
@php
    $steps = __('site.process');
    $count = count($steps);

    /*
     | Pozițiile stau pe un arc de 135°, centrat pe verticală — geometria tipică a
     | unui comutator cu came. Unghiurile se calculează din numărul de pași, deci
     | un al cincilea pas s-ar așeza singur.
     */
    $span = 135;
    $angleOf = fn (int $i): float => $count > 1 ? -$span / 2 + $span * $i / ($count - 1) : 0;
@endphp

<div class="nameplate cam-plate mx-auto w-full max-w-[21rem] p-6 sm:p-7" data-cam-switch aria-hidden="true">
    <div class="cam-dial">
        @foreach ($steps as $i => $step)
            <div
                class="cam-pos is-passed {{ $i === $count - 1 ? 'is-current' : '' }}"
                data-cam-pos
                data-angle="{{ $angleOf($i) }}"
                data-readout="{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }} · {{ $step['title'] }}"
                style="--a: {{ $angleOf($i) }}deg"
            >
                <div class="cam-hit">
                    <span class="cam-label">{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</span>
                    <span class="cam-tick"></span>
                    <span class="led is-on"></span>
                </div>
            </div>
        @endforeach

        {{-- Knob-ul: grip auriu + butuc central. Rotația vine din `--cam-angle`. --}}
        <div class="cam-knob" data-cam-knob style="--cam-angle: {{ $angleOf($count - 1) }}deg"></div>
    </div>

    {{-- Fereastra de citire: poziția curentă, ca pe o etichetă de aparat. --}}
    <p class="cam-readout" data-cam-readout>{{ str_pad((string) $count, 2, '0', STR_PAD_LEFT) }} · {{ $steps[$count - 1]['title'] }}</p>
</div>
