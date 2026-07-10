{{--
| Sigla „Flux” (hero /despre) — portarea vectorială a animației livrate de client
| („Energix Loop B — Flux”).
|
| PORTATĂ, NU ÎNCORPORATĂ. Clientul a trimis un mp4 de 1.43 MB (1000×840, H.264,
| fundal #0a0a0a ars în cadru). În aceeași arhivă era însă SURSA: geometria vectorială
| a siglei și formulele scenei. Le-am portat aici, unde:
|
|   - se descarcă ~7 KB în loc de 1.43 MB, și zero decodare video (costul real pe mobil);
|   - fundalul e transparent, deci nu apare un dreptunghi negru pe bleumarinul nostru;
|   - e vectorială: clară la orice densitate de ecran;
|   - culorile vin din tokenii de brand (`--color-gold`), nu din auriul #F1C232 al machetei;
|   - `prefers-reduced-motion` primește starea finală, fără nicio buclă rAF.
|
| ABATERE DELIBERATĂ: sursa e o BUCLĂ infinită („Loop B”). Aici rulează o dată,
| complet (6s), când intră în cadru, apoi îngheață. Cerință explicită a clientului,
| și oricum `DESIGN.md` interzice mișcarea ambientală lângă un `<h1>`.
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

    $bulbPath = "M {$cx} {$neckY1} L {$cx} {$neckY0}"; // placeholder, rescris mai jos
    $bulbPath = 'M '.($cx - $gapx)." {$neckY1} L ".($cx - $gapx)." {$neckY0} A {$r} {$r} 0 1 1 ".($cx + $gapx)." {$neckY0} L ".($cx + $gapx)." {$neckY1}";

    $jx = 322;
    $jy = 182;

    // Razele: unghiuri în grade, fără rază în dreptul gâtului becului.
    $rays = [270, 315, 0, 45, 135, 180, 225];

    // Barele soclului, de sus în jos.
    $bars = [['y' => 305, 'hw' => 40], ['y' => 321, 'hw' => 36], ['y' => 337, 'hw' => 31]];

    $word = ['e', 'n', 'e', 'r', 'g', 'ı', 'x'];
@endphp

<div class="logo-flux" data-logo-flux aria-hidden="true">
    <svg viewBox="0 0 1000 840" fill="none" focusable="false">
        <defs>
            <radialGradient id="fluxCore">
                <stop offset="0%" stop-color="rgba(255,232,150,0.95)" />
                <stop offset="55%" stop-color="rgba(255,214,90,0.45)" />
                <stop offset="100%" stop-color="rgba(255,214,90,0)" />
            </radialGradient>
            <radialGradient id="fluxHalo">
                <stop offset="0%" stop-color="rgba(255,220,110,0.5)" />
                <stop offset="100%" stop-color="rgba(255,220,110,0)" />
            </radialGradient>
        </defs>

        <g transform="translate(91 73) scale(1.24)">
            {{-- bloom interior: halo larg + miez --}}
            <circle data-flux-halo cx="{{ $jx }}" cy="{{ $jy }}" r="155" fill="url(#fluxHalo)" opacity="0" />
            <circle data-flux-core cx="{{ $jx }}" cy="{{ $jy }}" r="86" fill="url(#fluxCore)" opacity="0" />

            {{-- conturul becului: pathLength=100 face din poziția bilelor un procent --}}
            <path
                data-flux-bulb
                d="{{ $bulbPath }}"
                pathLength="100"
                fill="none"
                stroke="var(--color-gold)"
                stroke-width="14"
                stroke-linecap="round"
            />

            {{-- filamentul: „Y”-ul din bec, adică nodul conexiunii în stea --}}
            <g data-flux-filament stroke="var(--color-gold)" stroke-width="13" stroke-linecap="round" fill="none">
                <path d="M {{ $jx }} 247 L {{ $jx }} {{ $jy }}" />
                <path d="M {{ $jx }} {{ $jy }} L 289 128" />
                <path d="M {{ $jx }} {{ $jy }} L 355 128" />
            </g>

            {{-- razele: mătura de lumină se rotește o dată la 3 secunde --}}
            <g data-flux-rays>
                @foreach ($rays as $angle)
                    <line data-flux-ray data-angle="{{ $angle }}" stroke="var(--color-gold)" stroke-width="13" stroke-linecap="round" />
                @endforeach
            </g>

            {{-- soclul: rama gri sub miezul alb care licărește --}}
            @foreach ($bars as $bar)
                <g>
                    <line x1="{{ $cx - $bar['hw'] }}" y1="{{ $bar['y'] }}" x2="{{ $cx + $bar['hw'] }}" y2="{{ $bar['y'] }}" stroke="#afb8c0" stroke-width="15" stroke-linecap="round" />
                    <line data-flux-bar x1="{{ $cx - $bar['hw'] }}" y1="{{ $bar['y'] }}" x2="{{ $cx + $bar['hw'] }}" y2="{{ $bar['y'] }}" stroke="#ffffff" stroke-width="11" stroke-linecap="round" />
                </g>
            @endforeach

            {{-- scânteile libere și bilele de curent se creează din JS, aici --}}
            <g data-flux-sparks></g>
            <g data-flux-beads></g>

            {{-- wordmark: literele sunt <text> separate, ca să licărească pe rând --}}
            <g data-flux-word font-family="var(--font-quicksand), sans-serif" font-size="160" font-weight="600" text-anchor="middle">
                @foreach ($word as $i => $letter)
                    <text
                        data-flux-letter
                        data-index="{{ $i }}"
                        x="0"
                        y="530"
                        fill="#ffffff"
                        stroke="#afb8c0"
                        stroke-width="4"
                        paint-order="stroke"
                        stroke-linejoin="round"
                    >{{ $letter }}</text>
                @endforeach

                {{-- punctul auriu de pe „ı”, poziționat din JS după măsurarea literelor --}}
                <circle data-flux-dot r="15" fill="var(--color-gold)" opacity="0" />
            </g>
        </g>
    </svg>
</div>
