{{--
| Etapele instalatiei, ca statii pe un cablu.
|
| Cablul se „umple” cu auriu pana la etapa selectata — curentul ajunge acolo
| unde esti in proiect. Tab-uri reale (role=tablist), navigabile cu sagetile.
--}}
@php($stages = __('site.stages'))

<div {{ $attributes }} data-stages>
    <div class="relative">
        {{-- cablul + umplerea lui --}}
        <div class="absolute top-[22px] right-0 left-0 h-px bg-line" aria-hidden="true"></div>
        <div class="stage-fill absolute top-[22px] left-0 h-px" data-stage-fill aria-hidden="true"></div>

        <div role="tablist" aria-label="{{ __('site.home.stages_title') }}" class="relative grid grid-cols-5 gap-1 sm:gap-2">
            @foreach ($stages as $i => $stage)
                <button
                    type="button"
                    role="tab"
                    id="stage-tab-{{ $i }}"
                    aria-controls="stage-panel-{{ $i }}"
                    aria-selected="{{ $i === 0 ? 'true' : 'false' }}"
                    aria-label="{{ $stage['title'] }}"
                    tabindex="{{ $i === 0 ? '0' : '-1' }}"
                    class="stage-tab group flex flex-col items-center gap-2.5 pb-1"
                >
                    <span class="stage-node readout text-sm" aria-hidden="true">{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</span>
                    <span class="stage-label hidden font-mono text-[0.62rem] tracking-[0.1em] uppercase sm:block">
                        {{ $stage['title'] }}
                    </span>
                </button>
            @endforeach
        </div>
    </div>

    <div class="mt-7 min-h-32 rounded-sm border border-line bg-ink-raised p-6 sm:min-h-28">
        @foreach ($stages as $i => $stage)
            <div
                role="tabpanel"
                id="stage-panel-{{ $i }}"
                aria-labelledby="stage-tab-{{ $i }}"
                @if ($i !== 0) hidden @endif
            >
                <h3 class="flex items-baseline gap-3 text-h3 text-paper">
                    <span class="readout text-sm text-gold">{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</span>
                    {{ $stage['title'] }}
                </h3>
                <p class="mt-3 max-w-2xl text-paper-dim">{{ $stage['body'] }}</p>
            </div>
        @endforeach
    </div>
</div>
