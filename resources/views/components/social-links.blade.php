@props(['size' => 20])

{{--
| Legaturile catre retele, ca butoane-iconita de 44px (tinta de atins).
| Fiind doar iconite, fiecare are nume accesibil prin `aria-label` + `title`.
|
| Hover: se energizeaza auriu, la fel ca restul aparatajului de pe site.
| `viber://` se deschide doar daca exista clientul instalat — il pastram
| vizibil oriunde, fiindca in Moldova Viber e folosit masiv si pe desktop.
--}}
<ul {{ $attributes->class('flex flex-wrap gap-2') }}>
    @foreach (config('energix.social') as $item)
        <li>
            <a
                href="{{ $item['url'] }}"
                @if (str_starts_with($item['url'], 'https://')) target="_blank" rel="noopener noreferrer" @endif
                title="{{ $item['name'] }}"
                aria-label="{{ $item['name'] }}"
                class="inline-flex h-11 w-11 items-center justify-center rounded-sm border border-line text-paper-dim transition-colors hover:border-gold hover:text-gold active:translate-y-px"
            >
                <x-social-icon :icon="$item['icon']" :size="$size" />
            </a>
        </li>
    @endforeach
</ul>
