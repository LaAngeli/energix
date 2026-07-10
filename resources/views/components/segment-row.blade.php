@props(['service', 'index'])

@php($t = __("site.services.{$service['slug']}"))

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
            <span class="font-display text-h3 text-paper">{{ $t['title'] }}</span>
            <span class="hidden font-mono text-xs tracking-wide text-paper-dim uppercase sm:block">{{ $t['tagline'] }}</span>
            <span class="segment-arrow font-mono text-gold" aria-hidden="true">&rarr;</span>
        </button>
    </h3>

    <div class="segment-body">
        <div>
            <div class="grid gap-8 pb-8 lg:grid-cols-[1.2fr_1fr] lg:gap-14">
                <div>
                    <p class="text-paper-dim">{{ $t['intro'] }}</p>
                    <ul class="mt-6 grid gap-2.5 sm:grid-cols-2">
                        @foreach ($t['features'] as $i => $feature)
                            <li class="flex gap-3 text-sm text-paper-dim" style="--i: {{ $i }}">
                                <span class="mt-2 h-1 w-1 shrink-0 rounded-full bg-gold" aria-hidden="true"></span>
                                {{ $feature }}
                            </li>
                        @endforeach
                    </ul>
                    <a
                        href="{{ URL::localized('services') }}#{{ $service['slug'] }}"
                        class="mt-6 inline-flex items-center gap-2 font-mono text-sm tracking-wider text-gold uppercase transition hover:brightness-110"
                    >
                        {{ __('site.common.details') }}
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>

                <div class="overflow-hidden rounded-sm border border-line">
                    <img
                        src="{{ asset($service['image']) }}"
                        alt="{{ $t['title'] }} — Energix, {{ __('site.common.city') }}"
                        width="800"
                        height="533"
                        loading="lazy"
                        decoding="async"
                        class="h-44 w-full object-cover opacity-70 lg:h-full"
                    >
                </div>
            </div>
        </div>
    </div>
</article>
