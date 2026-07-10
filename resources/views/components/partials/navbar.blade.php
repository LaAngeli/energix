@php
    $links = [
        ['route' => 'home', 'label' => 'Acasă'],
        ['route' => 'services', 'label' => 'Servicii'],
        ['route' => 'gallery', 'label' => 'Lucrări'],
        ['route' => 'about', 'label' => 'Despre'],
        ['route' => 'contact', 'label' => 'Contacte'],
    ];
    $phone = config('energix.contact.phone');
    $phoneHref = config('energix.contact.phone_href');
@endphp

<header
    data-navbar
    class="sticky top-0 z-50 border-b border-transparent transition-colors duration-300 [&.is-scrolled]:border-line [&.is-scrolled]:bg-ink/90 [&.is-scrolled]:backdrop-blur"
>
    <nav class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-5 py-4 sm:px-8" aria-label="Navigare principală">
        <a href="{{ route('home') }}" class="flex items-center gap-2.5" @if (request()->routeIs('home')) aria-current="page" @endif>
            {{-- mark-64: 32px afisati, 2x pentru ecrane retina. mark-180 e doar apple-touch-icon. --}}
            <img src="{{ asset('images/logo/mark-64.png') }}" alt="" width="32" height="32" class="h-8 w-8" aria-hidden="true">
            <span class="font-display text-lg tracking-tight text-paper">Energix</span>
        </a>

        {{-- Desktop --}}
        <ul class="hidden items-center gap-7 lg:flex">
            @foreach ($links as $link)
                <li>
                    <a
                        href="{{ route($link['route']) }}"
                        @class([
                            'text-sm transition-colors hover:text-gold',
                            'text-gold' => request()->routeIs($link['route']),
                            'text-paper-dim' => ! request()->routeIs($link['route']),
                        ])
                        @if (request()->routeIs($link['route'])) aria-current="page" @endif
                    >{{ $link['label'] }}</a>
                </li>
            @endforeach
        </ul>

        <a
            href="tel:{{ $phoneHref }}"
            class="hidden items-center gap-2 rounded-sm bg-gold px-4 py-2.5 font-mono text-sm font-medium text-ink tabular-nums transition hover:brightness-110 lg:inline-flex"
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
            <span class="sr-only">Deschide meniul</span>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <path d="M3 6h18M3 12h18M3 18h18" stroke-linecap="round" />
            </svg>
        </button>
    </nav>

    <div id="nav-menu" data-nav-menu hidden class="border-t border-line bg-ink lg:hidden">
        <ul class="mx-auto max-w-6xl px-5 py-4 sm:px-8">
            @foreach ($links as $link)
                <li class="border-b border-line/60 last:border-0">
                    <a
                        href="{{ route($link['route']) }}"
                        @class([
                            'flex items-center justify-between py-4 text-lg',
                            'text-gold' => request()->routeIs($link['route']),
                            'text-paper' => ! request()->routeIs($link['route']),
                        ])
                    >
                        {{ $link['label'] }}
                        @if (request()->routeIs($link['route']))
                            <x-signature.wye :size="14" :live="true" />
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</header>
