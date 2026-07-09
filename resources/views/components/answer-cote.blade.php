@props(['question', 'answer', 'index'])

{{--
| O „cotă” de pe un desen tehnic: eticheta, linia de indicație, valoarea.
| Cele trei intrebari pe care si le pune orice client inainte sa sune.
|
| Regula de paleta: pe foaia luminoasa auriul apare DOAR ca hairline (1.3:1 ca text,
| pica WCAG). Raspunsul e in `graphite`. Cyan-ul nu apare deloc aici.
--}}
<div {{ $attributes->class('relative pt-6') }} data-reveal>
    {{-- Linia de indicatie a cotei. Grafit, nu auriu: pe foaie auriul da 1.32:1. --}}
    <div class="absolute inset-x-0 top-0 h-px bg-graphite-dim/25" aria-hidden="true"></div>
    <div class="absolute top-0 left-0 h-6 w-0.5 bg-graphite" aria-hidden="true"></div>

    <p class="font-mono text-xs tracking-widest text-graphite-dim uppercase">
        {{ str_pad((string) $index, 2, '0', STR_PAD_LEFT) }}
    </p>

    <h3 class="mt-3 text-h3 text-graphite">{{ $question }}</h3>
    <p class="mt-2 text-graphite-dim">{{ $answer }}</p>
</div>
