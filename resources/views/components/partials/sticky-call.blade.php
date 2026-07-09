{{--
| Telefonul este conversia principala. Pe mobil sta permanent la un tap distanta.
| `pb-safe` prin padding: pe iPhone bara de jos ar acoperi butonul.
--}}
<div class="fixed inset-x-0 bottom-0 z-40 border-t border-line bg-ink/95 backdrop-blur lg:hidden" style="padding-bottom: env(safe-area-inset-bottom)">
    <div class="flex gap-2 p-3">
        <a
            href="tel:{{ config('energix.contact.phone_href') }}"
            class="flex flex-1 items-center justify-center gap-2 rounded-sm bg-gold px-4 py-3.5 font-mono text-sm font-medium text-ink tabular-nums"
        >
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .3 1.9.6 2.8a2 2 0 0 1-.4 2.1L8 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.5c.9.3 1.8.5 2.8.6a2 2 0 0 1 1.7 2Z" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            {{ config('energix.contact.phone') }}
        </a>

        <a
            href="{{ route('contact') }}"
            class="flex items-center justify-center rounded-sm border border-line px-4 py-3.5 font-mono text-xs tracking-wider text-paper uppercase"
        >
            Ofertă
        </a>
    </div>
</div>

{{-- Bara sticky ar acoperi finalul paginii. Ii rezervam inaltimea. --}}
<div class="h-20 lg:hidden" aria-hidden="true"></div>
