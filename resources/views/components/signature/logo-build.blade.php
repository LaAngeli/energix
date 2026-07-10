{{--
| Sigla „Construcție” (hero /despre) — portarea vectorială a animației livrate de
| client („Energix Loop C — Construcție”).
|
| Logo-ul se DESENEAZĂ: conturul becului se trasează cu o bilă de curent în vârf,
| filamentul „Y” crește din soclu, barele soclului sar la loc, razele ies în
| evantai, un flash aprinde totul, literele urcă în cadru — iar la final o scânteie
| se naște în bec și zboară pe o traiectorie curbă până devine punctul de pe „ı”.
|
| PORTATĂ, NU ÎNCORPORATĂ. Clientul a trimis un mp4; în aceeași arhivă era SURSA
| (`energix-logo.jsx`, scena `InnerC`). Am portat sursa, unde:
|
|   - se descarcă ~8 KB în loc de 1.4 MB, și zero decodare video (costul real pe mobil);
|   - fundalul e transparent, deci nu apare un dreptunghi negru pe bleumarinul nostru;
|   - e vectorială: clară la orice densitate de ecran;
|   - culorile vin din tokenii de brand (`--color-gold`), nu din auriul #F1C232 al machetei;
|   - `prefers-reduced-motion` primește starea finală, fără nicio buclă rAF.
|
| ABATERE DELIBERATĂ — unde se OPREȘTE. Sursa e o buclă de 7s: ultima secundă
| (t = 5.95…6.95) DEZASAMBLEAZĂ logo-ul — literele coboară, razele se retrag,
| conturul se șterge — exact ca să poată reporni din nimic. Clientul cere ca
| animația să ruleze o dată și să înghețe; dacă am rula tot ciclul, hero-ul ar
| rămâne GOL. Deci rulăm construcția integral (0 → 5.5s, până se stinge unda
| punctului) și înghețăm pe sigla aprinsă. Nimic din construcție nu se pierde:
| se taie doar demontarea, care există numai ca să servească bucla.
|
| Toate coordonatele rămân în spațiul machetei (viewBox 1000×840), exact ca în sursă:
| marca e desenată în spațiul 660×352, translatată cu (91,73) și scalată cu 1.24.
|
| Decorativă: `aria-hidden`. „Energix” e deja în navbar, în footer și în `<h1>`.
--}}
@php
    // Geometria mărcii, preluată identic din sursă (energix-logo.jsx).
    $cx = 322;
    $cy = 178;
    $r = 90;
    $gapx = 31;
    $neckY0 = 262.5;
    $neckY1 = 290;

    $bulbPath = 'M '.($cx - $gapx)." {$neckY1} L ".($cx - $gapx)." {$neckY0} A {$r} {$r} 0 1 1 ".($cx + $gapx)." {$neckY0} L ".($cx + $gapx)." {$neckY1}";

    $jx = 322;
    $jy = 182;

    // Razele: unghiuri în grade, fără rază în dreptul gâtului becului.
    $rays = [270, 315, 0, 45, 135, 180, 225];

    // Barele soclului, de sus în jos. `hw` = semi-lățimea la scară 1.
    $bars = [['y' => 305, 'hw' => 40], ['y' => 321, 'hw' => 36], ['y' => 337, 'hw' => 31]];

    $word = ['e', 'n', 'e', 'r', 'g', 'ı', 'x'];
@endphp

<div class="logo-build" data-logo-build aria-hidden="true">
    <svg viewBox="0 0 1000 840" fill="none" focusable="false">
        <defs>
            <radialGradient id="buildCore">
                <stop offset="0%" stop-color="rgba(255,232,150,0.95)" />
                <stop offset="55%" stop-color="rgba(255,214,90,0.45)" />
                <stop offset="100%" stop-color="rgba(255,214,90,0)" />
            </radialGradient>
            <radialGradient id="buildHalo">
                <stop offset="0%" stop-color="rgba(255,220,110,0.5)" />
                <stop offset="100%" stop-color="rgba(255,220,110,0)" />
            </radialGradient>
            <radialGradient id="buildFlash">
                <stop offset="0%" stop-color="rgba(255,249,225,0.95)" />
                <stop offset="55%" stop-color="rgba(255,236,160,0.4)" />
                <stop offset="100%" stop-color="rgba(255,236,160,0)" />
            </radialGradient>
        </defs>

        <g transform="translate(91 73) scale(1.24)">
            {{-- bloom interior: halo larg + miez --}}
            <circle data-build-halo cx="{{ $jx }}" cy="{{ $jy }}" r="155" fill="url(#buildHalo)" opacity="0" />
            <circle data-build-core cx="{{ $jx }}" cy="{{ $jy }}" r="86" fill="url(#buildCore)" opacity="0" />

            {{-- conturul becului: `pathLength=100` face din dasharray un procent de traseu --}}
            <path
                data-build-bulb
                d="{{ $bulbPath }}"
                pathLength="100"
                fill="none"
                stroke="var(--color-gold)"
                stroke-width="14"
                stroke-linecap="round"
                stroke-dasharray="0 200"
            />

            {{-- filamentul: „Y”-ul din bec, adică nodul conexiunii în stea --}}
            <g data-build-filament stroke="var(--color-gold)" stroke-width="13" stroke-linecap="round" fill="none">
                <path data-build-stem d="M {{ $jx }} 247 L {{ $jx }} {{ $jy }}" pathLength="100" stroke-dasharray="0 200" />
                <path data-build-arm d="M {{ $jx }} {{ $jy }} L 289 128" pathLength="100" stroke-dasharray="0 200" />
                <path data-build-arm d="M {{ $jx }} {{ $jy }} L 355 128" pathLength="100" stroke-dasharray="0 200" />
            </g>

            {{-- razele: ies în evantai, în sens orar --}}
            <g data-build-rays>
                @foreach ($rays as $angle)
                    <line data-build-ray data-angle="{{ $angle }}" stroke="var(--color-gold)" stroke-width="13" stroke-linecap="round" opacity="0" />
                @endforeach
            </g>

            {{-- soclul: rama gri sub miezul alb care licărește; ambele se întind din centru --}}
            @foreach ($bars as $bar)
                <g data-build-bar data-hw="{{ $bar['hw'] }}">
                    <line data-build-bar-rim y1="{{ $bar['y'] }}" y2="{{ $bar['y'] }}" stroke="#afb8c0" stroke-width="15" stroke-linecap="round" />
                    <line data-build-bar-core y1="{{ $bar['y'] }}" y2="{{ $bar['y'] }}" stroke="#ffffff" stroke-width="11" stroke-linecap="round" />
                </g>
            @endforeach

            {{-- scânteia care se naște în bec, și bila de curent din vârful trasajului --}}
            <g data-build-sparks></g>
            <g data-build-beads></g>

            {{-- flash-ul de aprindere, peste marcă dar sub wordmark, ca în sursă --}}
            <circle data-build-flash cx="{{ $jx }}" cy="{{ $jy }}" r="250" fill="url(#buildFlash)" opacity="0" />

            {{-- wordmark: literele sunt <text> separate, ca să urce pe rând --}}
            <g data-build-word font-size="160" font-weight="600" text-anchor="middle">
                @foreach ($word as $i => $letter)
                    <text
                        data-build-letter
                        data-index="{{ $i }}"
                        x="0"
                        y="530"
                        fill="#ffffff"
                        stroke="#afb8c0"
                        stroke-width="4"
                        paint-order="stroke"
                        stroke-linejoin="round"
                        opacity="0"
                    >{{ $letter }}</text>
                @endforeach

                {{-- unda de aterizare a punctului: raza creşte, grosimea nu (e o undă, nu un inel gros) --}}
                <circle data-build-ring r="15" fill="none" stroke="rgba(255,216,90,0.9)" stroke-width="3" opacity="0" />

                {{-- punctul auriu de pe „ı”, poziționat din JS după măsurarea literelor --}}
                <circle data-build-dot r="15" fill="var(--color-gold)" opacity="0" />
            </g>

            {{-- scânteia în zbor: se naște în bec și devine punctul de pe „ı” --}}
            <circle data-build-fly r="15" fill="#ffe99a" opacity="0" />
        </g>
    </svg>
</div>
