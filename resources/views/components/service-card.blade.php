@props(['service', 'index'])

{{--
| Cardul se „clipseaza” pe sina orizontala a sectiunii, ca un modul pe sina DIN.
| Imaginea e un thumbnail restrans, nu un argument: pozele sunt de umplutura.
--}}
<article
    {{ $attributes->class('group relative flex flex-col rounded-sm border border-line bg-ink-raised p-6 transition-colors duration-300 hover:border-gold/60') }}
    data-reveal
>
    {{-- clema de prindere pe sina --}}
    <div class="absolute -top-px left-6 h-px w-10 bg-gold" aria-hidden="true"></div>

    <div class="flex items-center justify-between gap-3">
        <p class="eyebrow">{{ str_pad((string) $index, 2, '0', STR_PAD_LEFT) }}</p>
        <x-signature.wye :size="16" class="transition-colors group-hover:text-gold" />
    </div>

    <h3 class="mt-5 text-h3 text-paper">{{ $service['title'] }}</h3>
    <p class="mt-1.5 font-mono text-xs tracking-wide text-gold">{{ $service['tagline'] }}</p>

    <p class="mt-4 text-paper-dim">{{ $service['intro'] }}</p>

    <ul class="mt-6 space-y-2.5 border-t border-line pt-6">
        @foreach ($service['features'] as $feature)
            <li class="flex gap-3 text-sm text-paper-dim">
                <span class="mt-2 h-1 w-1 shrink-0 rounded-full bg-gold" aria-hidden="true"></span>
                {{ $feature }}
            </li>
        @endforeach
    </ul>

    <div class="mt-6 overflow-hidden rounded-sm border border-line">
        <img
            src="{{ asset($service['image']) }}"
            alt=""
            width="640"
            height="360"
            loading="lazy"
            decoding="async"
            aria-hidden="true"
            class="h-28 w-full object-cover opacity-60 transition duration-500 group-hover:opacity-90"
        >
    </div>
</article>
