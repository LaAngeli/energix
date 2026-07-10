@php
    $links = [
        ['route' => 'home', 'code' => '01'],
        ['route' => 'services', 'code' => '02'],
        ['route' => 'gallery', 'code' => '03'],
        ['route' => 'about', 'code' => '04'],
        ['route' => 'contact', 'code' => '05'],
    ];
    $phone = config('energix.contact.phone');
    $phoneHref = config('energix.contact.phone_href');
    $ru = app()->getLocale() === 'ru' ? 'ru.' : '';
@endphp

{{--
| Navbar = banda de comanda a instalatiei: fiecare pagina e un circuit cu LED.
| Circuitul activ e aprins; hover-ul „pune sub tensiune” restul.
--}}
<header
    data-navbar
    class="sticky top-0 z-50 border-b border-line/60 bg-ink/80 backdrop-blur transition-colors duration-300 [&.is-scrolled]:border-line [&.is-scrolled]:bg-ink/95"
>
    <nav class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-5 py-3.5 sm:px-8" aria-label="{{ __('site.nav.main') }}">
        {{--
        | Sigla ocupa 44px. Inaltimea barei o dicteaza linkul de circuit (46px), nu
        | sigla, deci cresterea incape in marja existenta: bara ramane la 74px.
        | mark-96 pentru ca un slot de 44px cere 88px pe ecrane retina.
        --}}
        <a href="{{ URL::localized('home') }}" class="flex items-center gap-3" @if (request()->routeIs($ru.'home')) aria-current="page" @endif>
            <img src="{{ asset('images/logo/mark-96.png') }}" alt="" width="44" height="44" class="h-11 w-11" aria-hidden="true">
            <span class="font-display text-2xl leading-none tracking-tight text-paper">Energix</span>
        </a>

        {{-- Desktop: circuite cu LED --}}
        <ul class="hidden items-stretch lg:flex">
            @foreach ($links as $link)
                {{--
                | `$active` = chiar pagina asta. `$inSection` = si paginile ei copil
                | (/servicii/apartamente). Circuitul se aprinde pentru toata sectiunea,
                | dar `aria-current="page"` ramane doar pe pagina curenta — altfel am
                | minti cititorul de ecran.
                --}}
                @php($active = request()->routeIs($ru.$link['route']))
                @php($inSection = $active || request()->routeIs($ru.$link['route'].'.*'))
                <li class="group border-l border-line/60 last:border-r">
                    <a
                        href="{{ URL::localized($link['route']) }}"
                        @class([
                            'flex h-full flex-col justify-center gap-1 px-4 py-1.5 transition-colors',
                            'bg-ink-raised/70' => $inSection,
                            'hover:bg-ink-raised/40' => ! $inSection,
                        ])
                        @if ($active) aria-current="page" @endif
                    >
                        <span class="flex items-center gap-2">
                            <span @class(['led', 'is-on' => $inSection]) aria-hidden="true"></span>
                            <span class="font-mono text-[0.6rem] tracking-[0.16em] text-paper-dim uppercase">{{ $link['code'] }}</span>
                        </span>
                        <span @class(['text-sm leading-none', 'text-gold' => $inSection, 'text-paper' => ! $inSection])>
                            {{ __('site.nav.'.$link['route']) }}
                        </span>
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="flex items-center gap-3">
            <x-partials.lang-switch class="hidden sm:flex" />

            <a
                href="tel:{{ $phoneHref }}"
                class="energize-sweep hidden items-center gap-2 rounded-sm bg-gold px-4 py-2.5 font-mono text-sm font-medium text-ink tabular-nums lg:inline-flex"
            >
                {{ $phone }}
            </a>

            {{-- Mobil: deschide tabloul --}}
            <button
                type="button"
                data-nav-toggle
                aria-expanded="false"
                aria-controls="nav-menu"
                class="inline-flex h-11 w-11 items-center justify-center rounded-sm border border-line text-paper active:translate-y-px lg:hidden"
            >
                <span class="sr-only">{{ __('site.nav.menu') }}</span>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path d="M3 6h18M3 12h18M3 18h18" stroke-linecap="round" />
                </svg>
            </button>
        </div>
    </nav>
</header>

{{--
| Meniul mobil = TABLOUL care se pune sub tensiune. Overlay pe tot ecranul, in
| afara header-ului: header-ul are `backdrop-filter`, care ancoreaza `fixed` la
| el, nu la viewport — deci overlay-ul ar fi fost taiat la inaltimea barei.
|
| Fara scroll, la orice inaltime: coloana flex in care banda de sus, circuitele
| si CTA-ul au inaltime fixa, iar animatia (`data-nav-anim`) absoarbe restul si
| se retrage spre 0 pe ecrane scunde. Vizibilitatea o comanda clasa `.is-open`
| (vezi app.css) — nu `.flex` + `hidden`, care s-ar anula reciproc.
--}}
<div
    id="nav-menu"
    data-nav-menu
    hidden
    class="fixed inset-x-0 top-0 z-[70] h-dvh flex-col overflow-hidden bg-ink lg:hidden"
>
    <div class="blueprint pointer-events-none absolute inset-0 opacity-20 [mask-image:radial-gradient(75%_55%_at_50%_28%,black,transparent)]" aria-hidden="true"></div>

    {{-- 1. banda de sus: eticheta „sub tensiune” + inchidere, aliniata cu bara --}}
    <div class="nav-strip relative flex shrink-0 items-center justify-between px-5 py-3.5 sm:px-8">
        <p class="flex items-center gap-2.5 font-mono text-[0.65rem] tracking-[0.2em] text-paper-dim uppercase">
            <span class="led is-on" aria-hidden="true"></span>
            {{ __('site.nav.menu_title') }}
        </p>

        <button
            type="button"
            data-nav-close
            aria-controls="nav-menu"
            class="inline-flex h-11 w-11 items-center justify-center rounded-sm border border-line text-paper transition-colors hover:border-gold hover:text-gold active:translate-y-px"
        >
            <span class="sr-only">{{ __('site.nav.close') }}</span>
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <path d="M6 6l12 12M18 6L6 18" stroke-linecap="round" />
            </svg>
        </button>
    </div>

    {{-- 2. circuitele = paginile. Se aprind in cascada la deschidere. --}}
    <nav class="relative shrink-0 px-5 sm:px-8" aria-label="{{ __('site.nav.main') }}">
        <ul>
            @foreach ($links as $link)
                @php($active = request()->routeIs($ru.$link['route']))
                @php($inSection = $active || request()->routeIs($ru.$link['route'].'.*'))
                <li class="nav-circuit border-b border-line/60" style="--i: {{ $loop->index }}">
                    <a
                        href="{{ URL::localized($link['route']) }}"
                        class="flex items-center gap-4 py-4"
                        @if ($active) aria-current="page" @endif
                    >
                        <span @class(['led shrink-0', 'is-on' => $inSection]) aria-hidden="true"></span>
                        <span class="font-mono text-xs tracking-[0.16em] text-paper-dim tabular-nums">{{ $link['code'] }}</span>
                        <span @class(['nav-label font-display text-3xl leading-none', 'text-gold' => $inSection, 'text-paper' => ! $inSection])>
                            {{ __('site.nav.'.$link['route']) }}
                        </span>
                        <span class="ml-auto flex items-center">
                            @if ($inSection)
                                <span class="font-mono text-[0.6rem] tracking-[0.16em] text-gold uppercase">{{ __('site.nav.active') }}</span>
                            @else
                                <svg class="h-4 w-4 text-line" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path d="M9 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            @endif
                        </span>
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>

    {{-- 3. sigla care se construieste — tabloul energizat. Umple spatiul liber. --}}
    <div class="relative flex min-h-0 flex-1 items-center justify-center px-5 py-3" data-nav-anim>
        <x-signature.logo-build />
    </div>

    {{-- 4. josul: telefonul (conversia) in zona degetului, plus limba. --}}
    <div class="nav-foot relative shrink-0 border-t border-line/60 px-5 pt-4 pb-[max(1rem,env(safe-area-inset-bottom))] sm:px-8">
        <a
            href="tel:{{ $phoneHref }}"
            class="nav-cta energize-sweep flex items-center justify-center gap-3 rounded-sm bg-gold px-6 py-4 font-mono text-base font-medium text-ink tabular-nums"
        >
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .3 1.9.6 2.8a2 2 0 0 1-.4 2.1L8 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.5c.9.3 1.8.5 2.8.6a2 2 0 0 1 1.7 2Z" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            {{ $phone }}
        </a>

        <div class="nav-foot-lang mt-4 flex items-center justify-between">
            <span class="eyebrow">{{ __('site.nav.switch') }}</span>
            <x-partials.lang-switch />
        </div>
    </div>
</div>
