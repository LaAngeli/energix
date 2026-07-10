{{--
| Calculatorul de circuite (hero /servicii).
|
| Comuti consumatorii mari ai locuintei; instrumentul numara circuitele dedicate
| si modulele de tablou. Cifrele sunt cele standard pe care le folosim si in
| proiecte (o plita are circuitul ei de 20 A — nu sta pe priza).
|
| Estimare orientativa, spusa explicit. Proiectul exact se face la evaluare.
--}}
@php
    $base = 3; // iluminat + doua circuite de prize — minimul oricarei locuinte
    $options = [
        ['label' => 'Plită electrică', 'amps' => '20 A'],
        ['label' => 'Cuptor', 'amps' => '16 A'],
        ['label' => 'Boiler', 'amps' => '16 A'],
        ['label' => 'Climatizare', 'amps' => '16 A'],
        ['label' => 'Mașină de spălat', 'amps' => '16 A'],
    ];
@endphp

<div class="instrument w-full max-w-sm" data-circuits-calc data-base="{{ $base }}">
    <p class="instrument-head">
        <span>Calculator de circuite</span>
        <span class="led is-on" aria-hidden="true"></span>
    </p>

    <div class="p-4">
        <p class="flex items-center justify-between gap-3 border-b border-line pb-3 font-mono text-[0.62rem] tracking-[0.14em] text-paper-dim uppercase">
            Iluminat + prize
            <span class="text-paper">{{ $base }} circuite</span>
        </p>

        <div class="mt-3 space-y-1.5" role="group" aria-label="Ce consumatori mari are locuința">
            @foreach ($options as $option)
                <button
                    type="button"
                    role="switch"
                    aria-checked="false"
                    data-calc-toggle
                    class="flex w-full items-center justify-between gap-3 rounded-sm border border-line px-3 py-2 text-left transition-colors hover:border-gold/60 aria-checked:border-gold/70 aria-checked:bg-gold/10"
                >
                    <span class="flex items-center gap-2.5">
                        <span class="led" aria-hidden="true"></span>
                        <span class="text-sm text-paper">{{ $option['label'] }}</span>
                    </span>
                    <span class="font-mono text-[0.62rem] text-paper-dim tabular-nums">{{ $option['amps'] }}</span>
                </button>
            @endforeach
        </div>

        <div class="mt-4 flex items-end justify-between gap-4 border-t border-line pt-3.5" aria-live="polite">
            <div>
                <p class="font-mono text-[0.6rem] tracking-[0.16em] text-paper-dim uppercase">Circuite dedicate</p>
                <p class="readout mt-1 text-3xl leading-none text-gold" data-calc-circuits>{{ $base }}</p>
            </div>
            <div class="text-right">
                <p class="font-mono text-[0.6rem] tracking-[0.16em] text-paper-dim uppercase">Tablou minim</p>
                <p class="readout mt-1 text-3xl leading-none text-paper"><span data-calc-modules>{{ $base + 4 }}</span> <span class="text-sm text-paper-dim">module</span></p>
            </div>
        </div>

        <p class="mt-3 text-[0.72rem] leading-snug text-paper-dim">
            Estimare orientativă — separatorul, diferențialul și rezervele sunt incluse.
            Proiectul exact îl facem la evaluare, gratuit.
        </p>
    </div>
</div>
