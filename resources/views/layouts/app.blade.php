@php
    // Fiecare pagina isi declara cheia SEO cu @php($page = '...') inaintea lui @extends.
    $page ??= 'home';
@endphp
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#091a31">
    <meta name="color-scheme" content="dark">

    <x-seo.head :page="$page" />

    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="32x32">
    <link rel="icon" href="{{ asset('images/logo/mark-512.png') }}" type="image/png" sizes="512x512">
    <link rel="apple-touch-icon" href="{{ asset('images/logo/mark-180.png') }}">

    {{-- Preload-uri, @font-face si variabilele --font-*. Inainte de app.css. --}}
    {{ Vite::fonts() }}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <x-seo.json-ld />
</head>
<body class="bg-ink text-paper antialiased">
    {{-- Sonda: lumina care urmareste cursorul pe suprafetele navy. --}}
    <div class="probe" data-probe aria-hidden="true"></div>

    {{-- Conductorul: firul care se umple cu aur pe masura ce cobori. --}}
    <div class="spine" data-spine aria-hidden="true">
        <div class="spine-fill"></div>
        <div class="spine-head"></div>
    </div>

    <a href="#continut" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-[60] focus:rounded-sm focus:bg-gold focus:px-4 focus:py-2 focus:font-mono focus:text-sm focus:text-ink">
        Sari la conținut
    </a>

    <x-partials.navbar />

    <main id="continut" class="relative">
        @yield('content')
    </main>

    <x-partials.footer />
    <x-partials.sticky-call />
    <x-partials.cookie-banner />
</body>
</html>
