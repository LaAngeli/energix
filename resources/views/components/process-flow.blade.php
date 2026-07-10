{{--
| „De la apel la curent" — ilustrația interactivă a secțiunii „Cum lucrăm".
|
| Patru stații (apel → evaluare → ofertă → sub tensiune) legate printr-un traseu
| care se umple cu curent auriu, în ordinea celor patru pași. Se energizează o
| dată la intrarea în cadru; pe desktop, hover pe o stație derulează curentul
| până la ea. Sub `prefers-reduced-motion`: starea finală, instant.
|
| Decorativă (`aria-hidden`): cei patru pași din stânga sunt conținutul real, în
| text. Ilustrația nu adaugă informație, doar o pune sub tensiune.
|
| Pur SVG cu `viewBox`, deci scalează perfect la orice lățime a coloanei — o
| singură sursă de coordonate, fără JavaScript pentru layout.
|
| Nodurile pornesc `is-lit` (starea fără JS = diagramă aprinsă, decorativ corectă);
| `initProcessFlow` le stinge la init și apoi le aprinde în secvență.
--}}
<div class="process-flow mx-auto w-full max-w-[26rem]" data-process-flow style="--pf-fill: 1" aria-hidden="true">
    <svg viewBox="0 0 400 400" fill="none" focusable="false" xmlns="http://www.w3.org/2000/svg">
        {{-- traseul: prin centrele celor patru stații, în sensul acelor de ceasornic --}}
        <path class="pf-wire" d="M70 70 L330 70 L330 330 L70 330" />
        <path
            class="pf-flow"
            data-flow-path
            d="M70 70 L330 70 L330 330 L70 330"
            pathLength="100"
        />

        {{-- aura becului: apare când ultima stație e sub tensiune --}}
        <circle class="pf-aura" cx="70" cy="330" r="52" />

        {{-- 01 — apel (sursa) --}}
        <g class="pf-node is-lit" data-flow-node data-at="0">
            <rect class="pf-tile" x="40" y="40" width="60" height="60" rx="7" />
            <circle class="pf-led" cx="91" cy="49" r="3.5" />
            <g class="pf-glyph" transform="translate(55 55) scale(1.25)">
                <path d="M13.83 16.57a1 1 0 0 0 1.21-.3l.36-.47A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.47.35a1 1 0 0 0-.29 1.23 14 14 0 0 0 6.39 6.39z" />
            </g>
        </g>

        {{-- 02 — evaluare la fața locului --}}
        <g class="pf-node is-lit" data-flow-node data-at="0.3333">
            <rect class="pf-tile" x="300" y="40" width="60" height="60" rx="7" />
            <circle class="pf-led" cx="351" cy="49" r="3.5" />
            <g class="pf-glyph" transform="translate(315 55) scale(1.25)">
                <circle cx="11" cy="11" r="7" />
                <path d="M21 21l-4.3-4.3" />
            </g>
        </g>

        {{-- 03 — oferta, în scris --}}
        <g class="pf-node is-lit" data-flow-node data-at="0.6667">
            <rect class="pf-tile" x="300" y="300" width="60" height="60" rx="7" />
            <circle class="pf-led" cx="351" cy="309" r="3.5" />
            <g class="pf-glyph" transform="translate(315 315) scale(1.25)">
                <path d="M15 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                <path d="M15 3v5h5" />
                <path d="M9 13h6" />
                <path d="M9 17h5" />
            </g>
        </g>

        {{-- 04 — execuție + punere sub tensiune --}}
        <g class="pf-node is-lit" data-flow-node data-at="1">
            <rect class="pf-tile" x="40" y="300" width="60" height="60" rx="7" />
            <circle class="pf-led" cx="91" cy="309" r="3.5" />
            <g class="pf-glyph" transform="translate(55 315) scale(1.25)">
                <path d="M9 18h6" />
                <path d="M10 21h4" />
                <path d="M15 14c.2-1 .7-1.7 1.5-2.5A5.5 5.5 0 0 0 12 3a5.5 5.5 0 0 0-4.5 8.5C8.3 12.3 8.8 13 9 14" />
                {{-- „Y" — nodul wye, semnătura brandului, în interiorul becului --}}
                <path d="M12 14v-2.6M12 11.4l-1.7-1.7M12 11.4l1.7-1.7" />
            </g>
        </g>

        {{-- bila de curent: călărește vârful umplerii, poziționată din JS --}}
        <circle class="pf-head" data-flow-head cx="0" cy="0" r="5" transform="translate(70 70)" />
    </svg>
</div>
