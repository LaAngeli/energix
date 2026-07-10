@props(['service', 'index'])

{{--
| Un segment de business ca rand de tablou: inchis arata doar eticheta;
| deschis isi desfasoara circuitele (features) si o imagine de suport.
--}}
<article class="segment-row" data-segment-row>
    <h3>
        <button
            type="button"
            data-segment-toggle
            aria-expanded="false"
            class="grid w-full grid-cols-[auto_1fr_auto] items-center gap-x-4 py-6 text-left sm:grid-cols-[auto_1fr_auto_auto] sm:gap-x-8"
        >
            <span class="readout text-sm text-gold">{{ str_pad((string) $index, 2, '0', STR_PAD_LEFT) }}</span>
            <span class="font-display text-h3 text-paper">{{ $service['title'] }}</span>
            <span class="hidden font-mono text-xs tracking-wide text-paper-dim uppercase sm:block">{{ $service['tagline'] }}</span>
            <span class="segment-arrow font-mono text-gold" aria-hidden="true">&rarr;</span>
        </button>
    </h3>

    <div class="segment-body">
        <div>
            <div class="grid gap-8 pb-8 lg:grid-cols-[1.2fr_1fr] lg:gap-14">
                <div>
                    <p class="text-paper-dim">{{ $service['intro'] }}</p>
                    <ul class="mt-6 grid gap-2.5 sm:grid-cols-2">
                        @foreach ($service['features'] as $feature)
                            <li class="flex gap-3 text-sm text-paper-dim">
                                <span class="mt-2 h-1 w-1 shrink-0 rounded-full bg-gold" aria-hidden="true"></span>
                                {{ $feature }}
                            </li>
                        @endforeach
                    </ul>
                    <a
                        href="{{ route('services') }}#{{ $service['slug'] }}"
                        class="mt-6 inline-flex items-center gap-2 font-mono text-sm tracking-wider text-gold uppercase transition hover:brightness-110"
                    >
                        Detalii complete
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>

                <div class="overflow-hidden rounded-sm border border-line">
                    <img
                        src="{{ asset($service['image']) }}"
                        alt=""
                        width="800"
                        height="533"
                        loading="lazy"
                        decoding="async"
                        aria-hidden="true"
                        class="h-44 w-full object-cover opacity-70 lg:h-full"
                    >
                </div>
            </div>
        </div>
    </div>
</article>
