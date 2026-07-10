@props(['page'])

@php($crumbs = \App\Support\Breadcrumbs::trail($page))

{{--
| Firimiturile: traseul până la pagina curentă, în limbajul fișei de lucrare.
|
| Aceeași sursă ca `BreadcrumbList` din JSON-LD — Google compară marcajul cu ce
| vede pe pagină, iar dacă nu coincid ignoră marcajul.
|
| Ultima firimitură nu e link: e chiar pagina pe care ești. `aria-current="page"`
| o spune, `<ol>` dă ordinea, iar separatorul e decorativ.
--}}
@if ($crumbs !== [])
    <nav {{ $attributes->class('font-mono text-xs tracking-wider uppercase') }} aria-label="{{ __('site.nav.breadcrumb') }}">
        <ol class="flex flex-wrap items-center gap-x-2 gap-y-1">
            @foreach ($crumbs as $crumb)
                <li class="flex items-center gap-x-2">
                    @if ($crumb['url'])
                        <a href="{{ $crumb['url'] }}" class="text-paper-dim transition-colors hover:text-gold">
                            {{ $crumb['name'] }}
                        </a>
                        <span class="text-line" aria-hidden="true">/</span>
                    @else
                        <span class="text-paper" aria-current="page">{{ $crumb['name'] }}</span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
