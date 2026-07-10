@props(['service'])

@php($t = __("site.services.{$service['slug']}"))

{{--
| Link către pagina unui segment.
|
| Textul ancorei este `anchor` din `lang/`, nu „Detalii complete →”. E cel mai
| valoros text de ancoră de pe site: îi spune lui Google despre ce e pagina-țintă.
| Un „Detalii complete” nu spune nimic despre nimic.
--}}
<a
    href="{{ URL::localized("services.{$service['slug']}") }}"
    class="group flex items-center justify-between gap-5 rounded-sm border border-line bg-ink-raised/40 p-5 transition hover:border-gold"
>
    <span>
        <span class="block font-mono text-[0.62rem] tracking-[0.16em] text-paper-dim uppercase">
            {{ $t['tagline'] }}
        </span>
        <span class="mt-1.5 block font-display text-base text-paper transition group-hover:text-gold">
            {{ $t['anchor'] }}
        </span>
    </span>

    <span class="font-mono text-gold transition group-hover:translate-x-1" aria-hidden="true">&rarr;</span>
</a>
