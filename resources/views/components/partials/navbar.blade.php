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
        <a href="{{ URL::localized('home') }}" class="flex items-center gap-2.5" @if (request()->routeIs($ru.'home')) aria-current="page" @endif>
            <img src="{{ asset('images/logo/mark-64.png') }}" alt="" width="32" height="32" class="h-8 w-8" aria-hidden="true">
            <span class="font-display text-lg tracking-tight text-paper">Energix</span>
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

            {{-- Mobil --}}
            <button
                type="button"
                data-nav-toggle
                aria-expanded="false"
                aria-controls="nav-menu"
                class="inline-flex h-11 w-11 items-center justify-center rounded-sm border border-line text-paper lg:hidden"
            >
                <span class="sr-only">{{ __('site.nav.menu') }}</span>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path d="M3 6h18M3 12h18M3 18h18" stroke-linecap="round" />
                </svg>
            </button>
        </div>
    </nav>

    {{-- Mobil: panoul de circuite --}}
    <div id="nav-menu" data-nav-menu hidden class="border-t border-line bg-ink lg:hidden">
        <ul class="mx-auto max-w-6xl px-5 py-3 sm:px-8">
            @foreach ($links as $link)
                @php($active = request()->routeIs($ru.$link['route']))
                @php($inSection = $active || request()->routeIs($ru.$link['route'].'.*'))
                <li class="border-b border-line/60">
                    <a href="{{ URL::localized($link['route']) }}" class="flex items-center justify-between gap-4 py-4" @if ($active) aria-current="page" @endif>
                        <span class="flex items-center gap-3.5">
                            <span @class(['led', 'is-on' => $inSection]) aria-hidden="true"></span>
                            <span class="font-mono text-[0.6rem] tracking-[0.16em] text-paper-dim uppercase">{{ $link['code'] }}</span>
                            <span @class(['text-lg leading-none', 'text-gold' => $inSection, 'text-paper' => ! $inSection])>
                                {{ __('site.nav.'.$link['route']) }}
                            </span>
                        </span>
                        <span class="font-mono text-[0.6rem] tracking-[0.16em] uppercase {{ $inSection ? 'text-gold' : 'text-paper-dim' }}">
                            {{ $inSection ? __('site.nav.active') : '—' }}
                        </span>
                    </a>
                </li>
            @endforeach

            <li class="flex items-center justify-between gap-4 py-4">
                <span class="eyebrow">{{ __('site.nav.switch') }}</span>
                <x-partials.lang-switch />
            </li>
        </ul>
    </div>
</header>
