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

    // Cartonas social pe limba: cel rus e scris in chirilica, nu e o traducere de alt.
    $ogImage = asset($seo['og_images'][$locale] ?? $seo['og_images']['ro']);

    $hreflangMap = ['ro' => 'ro-MD', 'ru' => 'ru-MD'];
    $ogLocaleMap = ['ro' => 'ro_MD', 'ru' => 'ru_MD'];
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
<meta property="og:locale" content="{{ $ogLocaleMap[$locale] }}">
@foreach (array_diff(config('energix.locales'), [$locale]) as $alt)
    <meta property="og:locale:alternate" content="{{ $ogLocaleMap[$alt] }}">
@endforeach
<meta property="og:title" content="{{ $meta['title'] }}">
<meta property="og:description" content="{{ $meta['description'] }}">
@if ($canonical)
    <meta property="og:url" content="{{ $canonical }}">
@endif

{{--
| Latimea, inaltimea si tipul evita „prima partajare fara imagine”: fara ele,
| crawler-ul trebuie sa descarce fisierul inainte de a randa cardul si adesea
| renunta. `secure_url` doar pe HTTPS — pe HTTP ar fi o minciuna.
--}}
<meta property="og:image" content="{{ $ogImage }}">
@if (str_starts_with($ogImage, 'https://'))
    <meta property="og:image:secure_url" content="{{ $ogImage }}">
@endif
<meta property="og:image:type" content="{{ $seo['og_image_type'] }}">
<meta property="og:image:width" content="{{ $seo['og_image_width'] }}">
<meta property="og:image:height" content="{{ $seo['og_image_height'] }}">
<meta property="og:image:alt" content="{{ __('site.og_alt') }}">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $meta['title'] }}">
<meta name="twitter:description" content="{{ $meta['description'] }}">
<meta name="twitter:image" content="{{ $ogImage }}">
<meta name="twitter:image:alt" content="{{ __('site.og_alt') }}">
