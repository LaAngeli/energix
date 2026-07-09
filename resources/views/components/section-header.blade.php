@props(['eyebrow', 'title', 'intro' => null, 'index' => null, 'onSheet' => false])

{{--
| Eyebrow-ul e o eticheta-callout de pe un desen tehnic: index + nume.
| Indexul e informativ (ordinea sectiunilor), nu decorativ.
--}}
<div {{ $attributes->class('max-w-2xl') }} data-reveal>
    <p @class(['eyebrow flex items-center gap-2.5', 'text-graphite-dim!' => $onSheet])>
        <x-signature.wye :size="13" :on-sheet="$onSheet" />
        @if ($index)
            <span>{{ str_pad((string) $index, 2, '0', STR_PAD_LEFT) }}</span>
            <span aria-hidden="true">·</span>
        @endif
        <span>{{ $eyebrow }}</span>
    </p>

    <h2 @class(['mt-4 text-h2', $onSheet ? 'text-graphite' : 'text-paper'])>{{ $title }}</h2>

    @if ($intro)
        <p @class(['mt-4 text-lead', $onSheet ? 'text-graphite-dim' : 'text-paper-dim'])>{{ $intro }}</p>
    @endif
</div>
