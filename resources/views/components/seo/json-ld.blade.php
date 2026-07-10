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
        // Cele trei segmente, ca oferta structurata.
        'hasOfferCatalog' => [
            '@type' => 'OfferCatalog',
            'name' => trans('site.home.services_title'),
            'itemListElement' => array_map(
                fn (array $service): array => [
                    '@type' => 'Offer',
                    'itemOffered' => [
                        '@type' => 'Service',
                        'name' => trans("site.services.{$service['slug']}.title"),
                        'description' => trans("site.services.{$service['slug']}.intro"),
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

    // Firimituri: doar pe paginile interioare — homepage-ul e radacina.
    if ($page !== 'home' && $page !== '404') {
        $graph[] = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => trans('site.nav.home'), 'item' => URL::localized('home')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => trans("site.seo")[$page]['title']],
            ],
        ];
    }
@endphp

@foreach ($graph as $schema)
    <script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endforeach
