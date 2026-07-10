{{--
| Nivela cu bula (hero /despre).
|
| Bula fuge dupa cursor; adusa la centru, instrumentul confirma. Metafora
| meseriei: totul montat drept, la cota. Pe tactil si sub prefers-reduced-motion
| sta fixa, la zero.
--}}
<div
    class="instrument w-full max-w-sm"
    data-level
    data-label-center="{{ __('site.level.center') }}"
    data-label-ok="{{ __('site.level.ok') }}"
>
    <p class="instrument-head">
        <span>{{ __('site.level.title') }}</span>
        <span class="led" data-level-led aria-hidden="true"></span>
    </p>

    <div class="p-4">
        <div class="vial" aria-hidden="true">
            <span class="bubble" data-bubble></span>
        </div>

        <div class="mt-3.5 flex items-baseline justify-between gap-4" aria-live="off">
            <p class="font-mono text-[0.62rem] tracking-[0.16em] text-paper-dim uppercase" data-level-label>
                {{ __('site.level.center') }}
            </p>
            <p class="readout text-xl text-paper tabular-nums"><span data-level-deg>0.0</span>°</p>
        </div>

        <p class="mt-3 border-t border-line pt-3 text-[0.72rem] leading-snug text-paper-dim">
            {{ __('site.level.note') }}
        </p>
    </div>
</div>
