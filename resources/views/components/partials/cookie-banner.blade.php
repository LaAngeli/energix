{{--
| Banner de consimtamant real, nu decorativ.
|
| Site-ul vechi pornea Google Tag Manager in <head>, inaintea banner-ului, iar
| butonul „Accept” nu controla nimic. Aici GTM se incarca DOAR dupa accept, din JS.
| „Refuz” e un buton de prim rang, nu un link ascuns.
--}}
<div
    data-cookie-banner
    data-gtm-id="{{ config('energix.gtm_id') }}"
    hidden
    tabindex="-1"
    role="dialog"
    aria-labelledby="cookie-title"
    {{--
    | `bottom-24` pana la `lg`, fiindca bara sticky de apel e vizibila pana la `lg`.
    | Cu `sm:bottom-6` banner-ul acoperea butonul de telefon pe tablete (640–1023px),
    | adica exact conversia principala a site-ului.
    --}}
    class="fixed inset-x-3 bottom-24 z-50 rounded-sm border border-line bg-ink-raised p-5 shadow-2xl sm:inset-x-auto sm:right-6 sm:max-w-md lg:bottom-6"
>
    <h2 id="cookie-title" class="font-display text-base text-paper">Cookie-uri</h2>

    <p class="mt-2 text-sm text-paper-dim">
        Folosim cookie-uri de analiză ca să înțelegem cum e folosit site-ul. Nu pornesc
        decât dacă ești de acord.
        <a href="{{ route('legal.cookies') }}" class="text-gold underline underline-offset-2">Detalii</a>.
    </p>

    <div class="mt-4 flex gap-2">
        <button
            type="button"
            data-cookie-accept
            class="flex-1 rounded-sm bg-gold px-4 py-2.5 font-mono text-xs font-medium tracking-wider text-ink uppercase transition hover:brightness-110"
        >
            Accept
        </button>
        <button
            type="button"
            data-cookie-decline
            class="flex-1 rounded-sm border border-line px-4 py-2.5 font-mono text-xs tracking-wider text-paper uppercase transition hover:border-paper-dim"
        >
            Refuz
        </button>
    </div>
</div>
