@props(['page' => 'home'])

@php
    $contact = config('energix.contact');
    $seo = config('energix.seo');
    $locale = app()->getLocale();

    // O singura sursa pentru URL-uri: APP_URL, prin helperii Laravel.
    $base = rtrim(url('/'), '/');

    /*
     | `ElectricalContractor` (subtip de LocalBusiness), NU `Electrician`.
     | Tipul `Electrician` din schema.org implica o afirmatie de autorizare, iar
     | clientul a exclus orice afirmatie de acest fel. Vezi BUSINESS.md.
     |
     | `sameAs` accepta doar URL-uri http(s) — `viber://` nu e valid acolo.
     */
    $business = [
        '@context' => 'https://schema.org',
        '@type' => 'ElectricalContractor',
        '@id' => $base.'/#business',
        'name' => $seo['site_name'],
        'url' => $base,
        'image' => asset($seo['og_images'][$locale] ?? $seo['og_images']['ro']),
        'logo' => asset('images/logo/mark-512.png'),
        'telephone' => $contact['phone'],
        'email' => $contact['email'],
        'description' => trans('site.home.summary'),
        'foundingDate' => (string) (now()->year - config('energix.experience_years')),
        'address' => [
            '@type' => 'PostalAddress',
            'addressLocality' => trans('site.common.city'),
            'addressCountry' => $contact['country_code'],
        ],
        // GEO / local: fiecare localitate deservita, declarata explicit.
        'areaServed' => array_map(
            fn (string $city): array => ['@type' => 'City', 'name' => $city],
            config('energix.areas.cities'),
        ),
        'openingHoursSpecification' => [
            [
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
                'opens' => '08:00',
                'closes' => '20:00',
            ],
            [
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => ['Saturday'],
                'opens' => '09:00',
                'closes' => '17:00',
            ],
        ],
        'knowsLanguage' => ['ro', 'ru'],
        'sameAs' => array_values(array_filter(
            array_column(config('energix.social'), 'url'),
            fn (string $url): bool => str_starts_with($url, 'https://'),
        )),
        // Cele trei segmente, ca oferta structurata. Fiecare arata spre pagina lui.
        'hasOfferCatalog' => [
            '@type' => 'OfferCatalog',
            'name' => trans('site.home.services_title'),
            'itemListElement' => array_map(
                fn (array $service): array => [
                    '@type' => 'Offer',
                    'itemOffered' => [
                        '@type' => 'Service',
                        '@id' => URL::localized("services.{$service['slug']}").'#service',
                        'name' => trans("site.services.{$service['slug']}.title"),
                        'description' => trans("site.services.{$service['slug']}.intro"),
                        'url' => URL::localized("services.{$service['slug']}"),
                        'areaServed' => trans('site.common.area_served'),
                    ],
                ],
                config('energix.services'),
            ),
        ],
    ];

    $graph = [$business];

    /*
     | AEO — intrebarile si raspunsurile, marcate ca FAQPage. Motoarele de raspuns
     | (Google AI Overviews, ChatGPT, Perplexity) citeaza raspunsuri scurte si
     | autonome. De aceea fiecare raspuns e complet fara context.
     */
    if ($page === 'home') {
        $graph[] = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'inLanguage' => $locale,
            'mainEntity' => array_map(
                fn (array $item): array => [
                    '@type' => 'Question',
                    'name' => $item['q'],
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $item['a']],
                ],
                trans('site.faq'),
            ),
        ];
    }

    /*
     | Paginile de segment (`services.apartamente` …) declara serviciul cu URL-ul
     | LUI, legat de firma prin `@id`. Fara `url`, cele trei Service-uri din
     | `hasOfferCatalog` erau entitati fara adresa: Google nu avea unde sa le trimita.
     */
    if (str_starts_with($page, 'services.')) {
        $slug = substr($page, strlen('services.'));

        $graph[] = [
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            '@id' => URL::localized($page).'#service',
            'name' => trans("site.services.{$slug}.title"),
            'description' => trans("site.services.{$slug}.intro"),
            'url' => URL::localized($page),
            'serviceType' => trans("site.services.{$slug}.title"),
            'areaServed' => array_map(
                fn (string $city): array => ['@type' => 'City', 'name' => $city],
                config('energix.areas.cities'),
            ),
            'provider' => ['@id' => $base.'/#business'],
            'inLanguage' => $locale,
        ];

        /*
         | Intrebarile de pe pagina de segment, marcate FAQPage. Sunt DIFERITE de
         | cele de pe homepage: acelasi Q&A marcat pe doua URL-uri e continut
         | duplicat in ochii lui Google, iar el alege singur pe care sa-l ignore.
         */
        $graph[] = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'inLanguage' => $locale,
            'mainEntity' => array_map(
                fn (array $item): array => [
                    '@type' => 'Question',
                    'name' => $item['q'],
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $item['a']],
                ],
                trans("site.services.{$slug}.faq"),
            ),
        ];
    }

    /*
     | Firimituri: aceeasi sursa ca `<x-breadcrumbs>`, deci marcajul si pagina spun
     | acelasi lucru. Google compara cele doua si ignora marcajul daca difera.
     | Homepage-ul e radacina, iar 404 nu e nicaieri in ierarhie — `trail()` intoarce
     | un array gol pentru amandoua.
     |
     | Ultimul element nu primeste `item`: e pagina curenta, exact cum recomanda Google.
     */
    $trail = App\Support\Breadcrumbs::trail($page);

    if ($trail !== []) {
        $graph[] = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => array_map(
                fn (array $crumb, int $i): array => array_filter([
                    '@type' => 'ListItem',
                    'position' => $i + 1,
                    'name' => $crumb['name'],
                    'item' => $crumb['url'],
                ], fn (mixed $value): bool => $value !== null),
                $trail,
                array_keys($trail),
            ),
        ];
    }
@endphp

@foreach ($graph as $schema)
    <script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endforeach
