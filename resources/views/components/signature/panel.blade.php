{{--
| Semnatura site-ului: un tablou de distributie INTERACTIV.
|
| Nu e o ilustratie — e obiectul pe care firma il monteaza, functional:
| separatorul general pune tabloul sub tensiune, diferentialul de 30 mA are
| buton TEST care chiar declanseaza si reanclanseaza, fiecare disjunctor se
| comuta si isi aprinde consumatorul, iar voltmetrul numara pana la 230 V.
|
| Accesibilitate: `role="switch"`, anunturi `role="status"`, iar
| prefers-reduced-motion primeste starea finala instant.
--}}
<div
    {{ $attributes->class('panel nameplate relative rounded-sm border border-line bg-ink-raised/70 p-5 backdrop-blur-[2px] sm:p-6') }}
    data-panel
    data-live="false"
>
    <p class="flex items-center justify-between gap-3 border-b border-line pb-3 font-mono text-[0.6rem] tracking-[0.18em] text-paper-dim uppercase">
        <span>{{ __('site.panel.title') }}</span>
        <span>230 V ~ 50 Hz</span>
    </p>

    <div class="mt-4 flex flex-wrap items-center justify-between gap-x-4 gap-y-3">
        <div class="flex items-center gap-3">
            <x-signature.wye :size="20" data-panel-source class="shrink-0" />
            <div>
                <p class="font-mono text-[0.6rem] tracking-[0.18em] text-paper-dim uppercase">{{ __('site.panel.grid') }}</p>
                <p class="readout text-xl leading-none text-paper">
                    <span data-voltmeter>0</span><span class="ml-1 text-sm text-paper-dim">V</span>
                </p>
            </div>
        </div>

        <button
            type="button"
            data-master
            role="switch"
            aria-checked="false"
            class="group flex items-center gap-3 rounded-sm border border-line bg-ink px-3.5 py-2.5 transition-colors hover:border-gold/60"
        >
            <span class="text-left">
                <span class="block font-mono text-[0.6rem] tracking-[0.18em] text-paper-dim uppercase">{{ __('site.panel.breaker') }}</span>
                <span class="block font-mono text-[0.6rem] tracking-[0.18em] text-gold uppercase" data-master-state>{{ __('site.panel.off') }}</span>
            </span>
            <span class="lever-track" aria-hidden="true"><span class="lever-knob"></span></span>
        </button>

        <div class="flex items-center gap-3">
            <span class="rcd-led" data-rcd-led aria-hidden="true"></span>
            <div>
                <p class="font-mono text-[0.6rem] tracking-[0.18em] text-cyan uppercase">{{ __('site.panel.rcd') }}</p>
                <button
                    type="button"
                    data-rcd-test
                    class="mt-1 rounded-[2px] border border-line px-2 py-0.5 font-mono text-[0.6rem] tracking-[0.18em] text-paper-dim uppercase transition-colors hover:border-cyan hover:text-cyan"
                >{{ __('site.panel.test') }}</button>
            </div>
        </div>
    </div>

    <div class="panel-bus mt-5" aria-hidden="true"></div>

    <ol class="grid grid-cols-3 gap-x-2 lg:grid-cols-6" role="group" aria-label="{{ __('site.panel.group') }}">
        @foreach (config('energix.panel_circuits') as $circuit)
            @php($name = __("site.panel.circuits.{$circuit['key']}"))
            <li class="circuit flex flex-col items-center" data-circuit>
                <span class="circuit-drop" aria-hidden="true"></span>

                <button
                    type="button"
                    data-breaker
                    role="switch"
                    aria-checked="true"
                    aria-label="{{ $name }}, {{ $circuit['amps'] }}"
                    class="breaker group"
                >
                    <span class="breaker-window" aria-hidden="true"><span class="breaker-lever"></span></span>
                    <span class="font-mono text-[0.6rem] text-paper-dim tabular-nums">{{ $circuit['amps'] }}</span>
                </button>

                <span class="circuit-wire" aria-hidden="true"></span>

                <span class="circuit-load">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" aria-hidden="true">
                        @switch($circuit['icon'])
                            @case('bulb')
                                <circle cx="12" cy="10" r="5.5" />
                                <path d="M12 15.5V12M12 12l-3-3.5M12 12l3-3.5M10 19h4" />
                                @break
                            @case('socket')
                                <circle cx="12" cy="12" r="8" />
                                <path d="M9.5 10.5v3M14.5 10.5v3" />
                                @break
                            @case('stove')
                                <rect x="4" y="5" width="16" height="14" rx="1.5" />
                                <circle cx="9" cy="10" r="1.6" /><circle cx="15" cy="10" r="1.6" />
                                <path d="M7.5 15.5h9" />
                                @break
                            @case('boiler')
                                <rect x="7" y="3.5" width="10" height="17" rx="4" />
                                <path d="M10 13c.7-1 1.3-1 2 0s1.3 1 2 0" />
                                @break
                            @case('ac')
                                <rect x="3.5" y="7" width="17" height="8" rx="1.5" />
                                <path d="M7 18c0 1.2-.8 1.8-1.5 2M12 18c0 1.2-.8 1.8-1.5 2M17 18c0 1.2-.8 1.8-1.5 2" />
                                @break
                            @default
                                <circle cx="11" cy="12" r="6" />
                                <path d="M17 12h3.5M11 9.5v5M8.8 10.5l4.4 3M8.8 13.5l4.4-3" />
                        @endswitch
                    </svg>
                    <span class="font-mono text-[0.58rem] tracking-[0.12em] uppercase">{{ $name }}</span>
                </span>
            </li>
        @endforeach
    </ol>

    <p class="mt-4 border-t border-line pt-3 text-center font-mono text-[0.6rem] tracking-[0.14em] text-paper-dim uppercase">
        {{ __('site.panel.hint') }}
    </p>

    <p class="sr-only" role="status" data-panel-status></p>
</div>
