<!DOCTYPE html>
<html lang="ro" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0a0e17">
    <meta name="color-scheme" content="dark">

    <title>@yield('title', config('app.name'))</title>

    {{-- Preload-uri + @font-face + variabilele --font-*. Trebuie inainte de app.css. --}}
    {{ Vite::fonts() }}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-ink text-paper antialiased">
    @yield('content')
</body>
</html>
