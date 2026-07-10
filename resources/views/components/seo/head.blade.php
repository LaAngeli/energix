@props(['page' => 'home'])

@php
    $seo = config('energix.seo');
    $locale = app()->getLocale();

    /*
     | Acces direct pe cheie, NU data_get(): cheile 'legal.terms' / 'legal.privacy' /
     | 'legal.cookies' contin un punct, iar data_get l-ar interpreta ca separator de
     | nivel. Rezultatul era ca cele trei pagini legale serveau meta homepage-ului.
     */
    $meta = trans('site.seo')[$page] ?? trans('site.seo')['home'];

    // Paginile de eroare nu au ruta, deci nici canonical, nici alternative de limba.
    $routeName = $page === '404' ? null : $page;

    /*
     | Canonical si hreflang se genereaza din ACEEASI sursa — tabela de rute.
     | Anterior canonical venea din config si hreflang din `route()`, deci in dev
     | aratau spre domenii diferite. Un canonical care nu se potriveste cu hreflang
     | e cel mai bun mod de a-i spune lui Google sa ignore ambele.
     */
    $canonical = $routeName ? URL::localized($routeName) : null;
    $ogImage = asset($seo['og_image']);

    $hreflangMap = ['ro' => 'ro-MD', 'ru' => 'ru-MD'];
@endphp

<title>{{ $meta['title'] }}</title>
<meta name="description" content="{{ $meta['description'] }}">

@if ($routeName)
    <link rel="canonical" href="{{ $canonical }}">
@else
    {{-- 404: fara canonical (ar consolida un URL inexistent) si scoasa din index. --}}
    <meta name="robots" content="noindex, follow">
@endif

@if ($routeName)
    {{--
    | hreflang: fiecare pagina se declara pe sine si pe sora ei din cealalta limba.
    | `x-default` merge pe romana — limba de stat si versiunea de la radacina.
    --}}
    @foreach (config('energix.locales') as $alt)
        <link rel="alternate" hreflang="{{ $hreflangMap[$alt] }}" href="{{ URL::inLocale($alt, $routeName) }}">
    @endforeach
    <link rel="alternate" hreflang="x-default" href="{{ URL::inLocale('ro', $routeName) }}">
@endif

<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ $seo['site_name'] }}">
<meta property="og:locale" content="{{ $locale === 'ru' ? 'ru_MD' : 'ro_MD' }}">
<meta property="og:title" content="{{ $meta['title'] }}">
<meta property="og:description" content="{{ $meta['description'] }}">
@if ($canonical)
    <meta property="og:url" content="{{ $canonical }}">
@endif
<meta property="og:image" content="{{ $ogImage }}">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $meta['title'] }}">
<meta name="twitter:description" content="{{ $meta['description'] }}">
<meta name="twitter:image" content="{{ $ogImage }}">
