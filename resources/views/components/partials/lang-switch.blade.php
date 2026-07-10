@php
    /*
     | Comutatorul de limba duce la ACEEASI pagina in cealalta limba, nu la
     | homepage — altfel pierzi vizitatorul si semnalul de hreflang devine
     | inconsistent cu experienta reala.
     |
     | Numele rutei curente e prefixat cu `ru.` pe versiunea rusa; il curatam
     | ca sa aflam pagina. Pe 404 nu exista ruta, deci cadem pe `home`.
     */
    $current = Route::currentRouteName();
    $base = $current ? preg_replace('/^ru\./', '', $current) : 'home';
@endphp

<div {{ $attributes->class('flex items-center gap-1') }} role="group" aria-label="{{ __('site.nav.switch') }}">
    @foreach (config('energix.locales') as $locale)
        @php($active = app()->getLocale() === $locale)
        <a
            href="{{ URL::inLocale($locale, $base) }}"
            hreflang="{{ $locale }}"
            @if ($active) aria-current="true" @endif
            @class([
                'rounded-sm px-2 py-1 font-mono text-[0.65rem] tracking-[0.14em] uppercase transition-colors',
                'bg-gold/15 text-gold' => $active,
                'text-paper-dim hover:text-gold' => ! $active,
            ])
        >{{ $locale }}</a>
    @endforeach
</div>
