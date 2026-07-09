@props(['value', 'index'])

<div {{ $attributes->class('rounded-sm border border-line bg-ink-raised p-6') }} data-reveal>
    <p class="eyebrow">{{ str_pad((string) $index, 2, '0', STR_PAD_LEFT) }}</p>
    <h3 class="mt-4 text-h3 text-paper">{{ $value['title'] }}</h3>
    <p class="mt-3 text-paper-dim">{{ $value['body'] }}</p>
</div>
