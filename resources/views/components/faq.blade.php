{{--
| Secțiunea de întrebări frecvente — piesa de AEO.
|
| Fiecare răspuns e scurt, factual și AUTONOM (se înțelege scos din context),
| fiindcă asta citează motoarele de răspuns. Marcată ca FAQPage în JSON-LD.
|
| Stă pe banda luminoasă: regula de paletă spune că auriul nu are voie ca text
| aici (1.32:1), deci indicatorii sunt grafit.
--}}
<div {{ $attributes->class('grid gap-x-10 gap-y-8 lg:grid-cols-2') }}>
    @foreach (__('site.faq') as $i => $item)
        <div class="relative pt-6" data-reveal data-cote style="--reveal-delay: {{ ($i % 2) * 90 }}ms">
            <div class="absolute inset-x-0 top-0 h-px bg-graphite-dim/25" aria-hidden="true"></div>
            <div class="cote-tick absolute top-0 left-0 h-6 w-0.5 bg-graphite" aria-hidden="true"></div>

            <p class="font-mono text-xs tracking-widest text-graphite-dim uppercase">
                {{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}
            </p>

            <h3 class="mt-3 text-h3 text-graphite">{{ $item['q'] }}</h3>
            <p class="mt-2 text-graphite-dim">{{ $item['a'] }}</p>
        </div>
    @endforeach
</div>
