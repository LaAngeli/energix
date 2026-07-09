@props(['step', 'index', 'last' => false])

{{-- Pasii au ordine reala, deci numerotarea poarta informatie. --}}
<li {{ $attributes->class('relative flex gap-5 pb-10 last:pb-0') }} data-reveal>
    @unless ($last)
        <div class="absolute top-11 bottom-0 left-[1.375rem] w-px bg-line" aria-hidden="true"></div>
    @endunless

    <div class="relative z-10 flex h-11 w-11 shrink-0 items-center justify-center rounded-sm border border-line bg-ink-raised">
        <span class="readout text-sm text-gold">{{ str_pad((string) $index, 2, '0', STR_PAD_LEFT) }}</span>
    </div>

    <div class="pt-1.5">
        <h3 class="text-h3 text-paper">{{ $step['title'] }}</h3>
        <p class="mt-2 text-paper-dim">{{ $step['body'] }}</p>
    </div>
</li>
