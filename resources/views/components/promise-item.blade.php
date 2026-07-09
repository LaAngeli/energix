@props(['promise'])

{{-- Fapte verificabile, nu insigne. Aici se umple golul lasat de „electrician autorizat”. --}}
<div {{ $attributes->class('flex gap-4 border-t border-line py-6') }} data-reveal>
    <x-signature.wye :size="18" class="mt-1 shrink-0" />

    <div>
        <h3 class="font-display text-base text-paper">{{ $promise['label'] }}</h3>
        <p class="mt-1.5 text-sm text-paper-dim">{{ $promise['body'] }}</p>
    </div>
</div>
