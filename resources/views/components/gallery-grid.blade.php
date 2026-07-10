@props(['items', 'categories'])

<div data-gallery {{ $attributes }}>
    <div class="flex flex-wrap items-center gap-2" role="group" aria-label="Filtrează după tipul lucrării">
        @foreach ($categories as $slug => $label)
            {{-- filtrele sunt disjunctoare: LED aprins = circuit selectat --}}
            <button
                type="button"
                data-filter="{{ $slug }}"
                aria-pressed="{{ $slug === 'toate' ? 'true' : 'false' }}"
                class="group inline-flex min-h-11 items-center gap-2.5 rounded-sm border border-line px-4 font-mono text-xs tracking-wider uppercase transition aria-pressed:border-gold aria-pressed:bg-gold/10 aria-pressed:text-gold text-paper-dim hover:border-gold hover:text-gold"
            >
                <span class="led" aria-hidden="true"></span>
                {{ $label }}
            </button>
        @endforeach

        <p class="ml-auto font-mono text-xs tracking-wider text-paper-dim uppercase">
            <span class="readout text-gold" data-gallery-count>{{ count($items) }}</span> lucrări pe circuit
        </p>
    </div>

    <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($items as $item)
            <figure data-category="{{ $item['category'] }}" class="group overflow-hidden rounded-sm border border-line bg-ink-raised" data-reveal>
                <img
                    src="{{ asset($item['image']) }}"
                    alt="{{ $item['title'] }}"
                    width="640"
                    height="480"
                    loading="lazy"
                    decoding="async"
                    class="h-52 w-full object-cover opacity-75 transition duration-500 group-hover:scale-[1.03] group-hover:opacity-100"
                >
                <figcaption class="flex items-center justify-between gap-3 border-t border-line px-4 py-3">
                    <span class="text-sm text-paper">{{ $item['title'] }}</span>
                    <span class="font-mono text-[0.65rem] tracking-wider text-paper-dim uppercase">
                        {{ $categories[$item['category']] }}
                    </span>
                </figcaption>
            </figure>
        @endforeach
    </div>
</div>
