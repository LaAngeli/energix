@props(['size' => 16, 'live' => false, 'onSheet' => false])

{{--
| Nodul „Y” — conexiunea in stea (wye) dintr-un sistem trifazat, chiar semnul din
| interiorul becului din logo. Glyph-ul recurent al site-ului: stins pana cand
| sectiunea intra in cadru, apoi auriu.
|
| Pe suprafata luminoasa auriul da 1.32:1 — invizibil. Regula: auriul traieste
| doar pe bleumarin. Pe foaie, glyph-ul e grafit.
--}}
<svg
    {{ $attributes->class([
        'wye-glyph',
        'is-live' => $live && ! $onSheet,
        'text-graphite-dim!' => $onSheet,
    ]) }}
    width="{{ $size }}"
    height="{{ $size }}"
    viewBox="0 0 24 24"
    fill="none"
    stroke="currentColor"
    stroke-width="2"
    stroke-linecap="round"
    aria-hidden="true"
>
    <path d="M12 22V12M12 12L4 4M12 12L20 4" />
</svg>
