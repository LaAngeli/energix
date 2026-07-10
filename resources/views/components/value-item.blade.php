@props(['value', 'index'])

{{-- O valoare = un dispozitiv de protectie din schema: nu se scoate niciodata. --}}
<div {{ $attributes->class('protection p-6') }}>
    <div class="flex items-center justify-between gap-3">
        <p class="eyebrow">Protecție {{ str_pad((string) $index, 2, '0', STR_PAD_LEFT) }}</p>
        <span class="led is-on" aria-hidden="true"></span>
    </div>
    <h3 class="mt-4 text-h3 text-paper">{{ $value['title'] }}</h3>
    <p class="mt-3 text-paper-dim">{{ $value['body'] }}</p>
</div>
