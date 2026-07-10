@php
    $faq = __('site.faq');
    $mid = (int) ceil(count($faq) / 2);
    // Chei pastrate (0,1,2 / 3,4,5): `$i` ramane indexul global, pentru numar si `id`.
    $columns = [
        array_slice($faq, 0, $mid, true),
        array_slice($faq, $mid, null, true),
    ];
@endphp

{{--
| Întrebări frecvente — piesă de AEO. Fiecare răspuns e scurt, factual și AUTONOM
| (se înțelege scos din context): asta citează motoarele de răspuns. Marcată
| FAQPage în JSON-LD, din aceleași chei `site.faq`.
|
| Acordeon: întrebările stau strânse, răspunsul se deschide la click — aceeași
| mișcare ca la segmentele „Ce facem”, o singură logică de interacțiune pe tot
| site-ul. Răspunsurile rămân în DOM (colapsate din CSS, nu șterse), deci sunt
| indexabile și citabile de motoarele de răspuns.
|
| Foaie luminoasă: auriul dă 1.32:1 ca text aici, deci totul e grafit.
|
| Două coloane INDEPENDENTE pe desktop — nu un grid unde deschiderea unui rând ar
| lăsa un gol lângă perechea lui de pe același rând; o singură coloană pe mobil.
--}}
<div {{ $attributes->class('grid gap-x-12 lg:grid-cols-2') }} data-faq>
    @foreach ($columns as $column)
        <div>
            @foreach ($column as $i => $item)
                <div class="faq-item border-t border-graphite/15 [&:last-child]:border-b" data-faq-item>
                    <h3 class="m-0">
                        <button
                            type="button"
                            data-faq-toggle
                            aria-expanded="false"
                            aria-controls="faq-a-{{ $i }}"
                            class="group grid w-full grid-cols-[auto_1fr_auto] items-start gap-x-4 py-4 text-left sm:gap-x-5 sm:py-5"
                        >
                            <span class="readout mt-0.5 text-sm text-graphite-dim transition-colors group-hover:text-graphite sm:mt-1">
                                {{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}
                            </span>

                            <span class="text-base leading-snug font-medium text-graphite sm:text-lg">{{ $item['q'] }}</span>

                            {{-- „+” care devine „−” la deschidere: afordanță logică, nu decor. --}}
                            <span class="faq-mark relative mt-1.5 block size-3.5 shrink-0" aria-hidden="true">
                                <span class="absolute top-1/2 left-0 h-px w-full -translate-y-1/2 bg-graphite-dim transition-colors group-hover:bg-graphite"></span>
                                <span class="faq-mark-v absolute top-0 left-1/2 h-full w-px -translate-x-1/2 bg-graphite-dim transition group-hover:bg-graphite"></span>
                            </span>
                        </button>
                    </h3>

                    <div id="faq-a-{{ $i }}" class="faq-body">
                        <div class="overflow-hidden">
                            {{-- Răspunsul aliniat sub întrebare: spacer invizibil cât numărul. --}}
                            <div class="grid grid-cols-[auto_1fr] gap-x-4 pb-4 sm:gap-x-5 sm:pb-5">
                                <span class="readout invisible text-sm select-none" aria-hidden="true">00</span>
                                <p class="text-sm text-graphite-dim sm:text-base">{{ $item['a'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
</div>
