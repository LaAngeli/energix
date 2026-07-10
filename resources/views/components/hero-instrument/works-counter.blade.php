{{--
| Contorul de lucrari (hero /galerie).
|
| Un contor electromecanic: alegi circuitul, cifrele se rostogolesc la numarul
| de lucrari, iar galeria de sub hero se filtreaza pe loc — instrumentul chiar
| comanda pagina, nu e decor.
--}}
@php
    $labels = __('site.gallery.categories');
    $counts = ['toate' => count(config('energix.gallery'))];

    foreach (config('energix.gallery') as $item) {
        $counts[$item['category']] = ($counts[$item['category']] ?? 0) + 1;
    }
@endphp

<div class="instrument w-full max-w-sm" data-works-counter data-counts='@json($counts)'>
    <p class="instrument-head">
        <span>{{ __('site.counter.title') }}</span>
        <span class="led is-on" aria-hidden="true"></span>
    </p>

    <div class="p-4">
        <div class="flex items-center justify-center gap-4">
            <div class="odo readout text-2xl text-gold" aria-hidden="true">
                @foreach ([0, 1] as $place)
                    <span class="odo-digit">
                        <span class="odo-strip" data-odo-digit>
                            @for ($d = 0; $d <= 9; $d++)
                                <span>{{ $d }}</span>
                            @endfor
                        </span>
                    </span>
                @endforeach
            </div>
            <p class="max-w-28 font-mono text-[0.62rem] tracking-[0.14em] text-paper-dim uppercase">
                {{ __('site.counter.label') }}
            </p>
        </div>

        <p class="sr-only" role="status" data-works-status></p>

        <div class="mt-4 grid gap-1.5" role="group" aria-label="{{ __('site.counter.group') }}">
            @foreach (config('energix.gallery_categories') as $slug)
                <button
                    type="button"
                    data-works-pick="{{ $slug }}"
                    aria-pressed="{{ $slug === 'toate' ? 'true' : 'false' }}"
                    class="flex items-center justify-between gap-3 rounded-sm border border-line px-3 py-2 text-left transition-colors hover:border-gold/60 aria-pressed:border-gold/70 aria-pressed:bg-gold/10"
                >
                    <span class="flex items-center gap-2.5">
                        <span class="led" aria-hidden="true"></span>
                        <span class="text-sm text-paper">{{ $labels[$slug] }}</span>
                    </span>
                    <span class="readout text-xs text-paper-dim tabular-nums">{{ str_pad((string) ($counts[$slug] ?? 0), 2, '0', STR_PAD_LEFT) }}</span>
                </button>
            @endforeach
        </div>

        <p class="mt-3 text-[0.72rem] leading-snug text-paper-dim">{{ __('site.counter.note') }}</p>
    </div>
</div>
