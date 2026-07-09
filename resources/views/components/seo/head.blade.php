@props(['page' => 'home'])

@php
    $seo = config('energix.seo');
    $meta = data_get($seo, "pages.{$page}", $seo['pages']['home']);
    $canonical = rtrim($seo['canonical'], '/').request()->getPathInfo();
    $canonical = rtrim($canonical, '/') ?: $seo['canonical'];
    $ogImage = rtrim($seo['canonical'], '/').'/'.ltrim($seo['og_image'], '/');
@endphp

<title>{{ $meta['title'] }}</title>
<meta name="description" content="{{ $meta['description'] }}">
<link rel="canonical" href="{{ $canonical }}">

<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ $seo['site_name'] }}">
<meta property="og:locale" content="{{ $seo['locale'] }}">
<meta property="og:title" content="{{ $meta['title'] }}">
<meta property="og:description" content="{{ $meta['description'] }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:image" content="{{ $ogImage }}">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $meta['title'] }}">
<meta name="twitter:description" content="{{ $meta['description'] }}">
<meta name="twitter:image" content="{{ $ogImage }}">
