{{--
| Legături contextuale către cele trei pagini de segment.
|
| Înainte, `/galerie` și `/despre` aveau exact UN link în corpul paginii — „Cere o
| ofertă”. Tot restul erau navbar și footer, care nu transmit relevanță tematică:
| apar identic pe fiecare pagină, deci nu spun nimic despre relația dintre ele.
--}}
<section class="border-t border-line" aria-labelledby="related-segments-title">
    <div class="mx-auto max-w-6xl px-5 py-14 sm:px-8 sm:py-16">
        <h2 id="related-segments-title" class="text-h3 text-paper">{{ __('site.common.related_title') }}</h2>

        <div class="mt-8 grid gap-5 lg:grid-cols-3">
            @foreach (config('energix.services') as $service)
                <x-segment-link :service="$service" />
            @endforeach
        </div>
    </div>
</section>
