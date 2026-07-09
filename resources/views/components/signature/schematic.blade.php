{{--
| Semnatura paginii: schema monofilara a unui tablou electric real.
|
| Nu e ornament. E exact obiectul pe care firma il monteaza, notat corect:
| sursa, siguranta generala, diferential 30 mA, patru circuite.
|
| Nodul „Y” e conexiunea in stea (wye) a sursei — chiar semnul din interiorul
| becului din logo. Se aprinde primul, apoi curentul curge prin traseu si
| diferentialul semnaleaza ca instalatia e sub tensiune.
|
| ATENTIE: `transform` din CSS suprascrie atributul `transform` din SVG. De aceea
| `translate` sta pe <g>-ul exterior, iar animatia de `scale` pe cel interior.
|
| Doua layout-uri, nu unul lat cu scroll orizontal:
|   - sub `md`: riser vertical, incape pe 360px;
|   - de la `md`: bus orizontal cu toate cele patru circuite.
--}}
<figure {{ $attributes }}>
    <figcaption class="sr-only">
        Schemă monofilară: sursă de 230 V, siguranță generală, diferențial de 30 mA
        și patru circuite — iluminat, prize, bucătărie, boiler.
    </figcaption>

    {{-- ----------------------------------------------- mobil: riser vertical --}}
    <svg viewBox="0 0 260 300" class="w-full md:hidden" role="presentation" focusable="false">
        <g stroke="currentColor" stroke-width="1.5" fill="none" class="text-line">
            <path
                class="schema-path"
                style="--schema-len: 900"
                d="M36 44 V60 M36 96 V126 M36 162 V200 M36 200 H224 M36 200 V230 M100 200 V230 M164 200 V230 M224 200 V230"
            />
        </g>

        <rect x="16" y="60" width="40" height="36" fill="none" stroke="currentColor" stroke-width="1.5" class="text-line" />
        <rect x="16" y="126" width="40" height="36" fill="none" stroke="currentColor" stroke-width="1.5" class="text-cyan" />
        <circle cx="66" cy="144" r="3.5" class="schema-led fill-cyan" />

        <g class="text-line">
            <rect x="23" y="230" width="26" height="32" fill="none" stroke="currentColor" stroke-width="1.5" />
            <rect x="87" y="230" width="26" height="32" fill="none" stroke="currentColor" stroke-width="1.5" />
            <rect x="151" y="230" width="26" height="32" fill="none" stroke="currentColor" stroke-width="1.5" />
            <rect x="211" y="230" width="26" height="32" fill="none" stroke="currentColor" stroke-width="1.5" />
        </g>

        {{-- sursa: nodul Y din bec --}}
        <g transform="translate(24 4)">
            <g class="schema-node text-gold">
                <path d="M12 40V22M12 22L2 10M12 22L22 10" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" />
            </g>
        </g>

        <g font-family="var(--font-mono)" font-size="9" letter-spacing="0.6" class="fill-paper-dim">
            <text x="56" y="28">230 V</text>
            <text x="66" y="82">SIGURANȚĂ</text>
            <text x="66" y="140" class="fill-cyan">DIFERENȚIAL</text>
            <text x="66" y="152" class="fill-cyan">30 mA</text>
            <text x="18" y="280">ILUM.</text>
            <text x="82" y="280">PRIZE</text>
            <text x="142" y="280">BUCĂT.</text>
            <text x="204" y="280">BOILER</text>
        </g>
    </svg>

    {{-- ------------------------------------------------ desktop: bus orizontal --}}
    <svg viewBox="0 0 660 240" class="hidden w-full md:block" role="presentation" focusable="false">
        <g stroke="currentColor" stroke-width="1.5" fill="none" class="text-line">
            <path
                class="schema-path"
                style="--schema-len: 1600"
                d="M26 62 V70 M26 70 H120 M180 70 H240 M300 70 H620 M340 70 V120 M420 70 V120 M500 70 V120 M580 70 V120 M340 160 V190 M420 160 V190 M500 160 V190 M580 160 V190"
            />
        </g>

        <rect x="120" y="56" width="60" height="28" fill="none" stroke="currentColor" stroke-width="1.5" class="text-line" />
        <rect x="240" y="56" width="60" height="28" fill="none" stroke="currentColor" stroke-width="1.5" class="text-cyan" />
        <circle cx="270" cy="44" r="4" class="schema-led fill-cyan" />

        <g class="text-line">
            <rect x="322" y="120" width="36" height="40" fill="none" stroke="currentColor" stroke-width="1.5" />
            <rect x="402" y="120" width="36" height="40" fill="none" stroke="currentColor" stroke-width="1.5" />
            <rect x="482" y="120" width="36" height="40" fill="none" stroke="currentColor" stroke-width="1.5" />
            <rect x="562" y="120" width="36" height="40" fill="none" stroke="currentColor" stroke-width="1.5" />
        </g>

        <g transform="translate(10 8)">
            <g class="schema-node text-gold">
                <path d="M16 54V30M16 30L4 14M16 30L28 14" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" />
            </g>
        </g>

        <g font-family="var(--font-mono)" font-size="10.5" letter-spacing="1" class="fill-paper-dim">
            <text x="50" y="40">230 V</text>
            <text x="122" y="102">SIGURANȚĂ</text>
            <text x="242" y="102" class="fill-cyan">DIFERENȚIAL 30 mA</text>
            <text x="316" y="210">ILUMINAT</text>
            <text x="402" y="210">PRIZE</text>
            <text x="476" y="210">BUCĂTĂRIE</text>
            <text x="560" y="210">BOILER</text>
        </g>
    </svg>
</figure>
