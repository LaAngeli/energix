@php
    $contact = config('energix.contact');

    /*
     | `ElectricalContractor` (subtip de LocalBusiness), NU `Electrician`.
     | Tipul `Electrician` din schema.org implica o afirmatie de autorizare, iar
     | clientul a exclus orice afirmatie de acest fel. Vezi BUSINESS.md.
     |
     | `sameAs` accepta doar URL-uri http(s) — `viber://` nu e valid acolo.
     */
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'ElectricalContractor',
        'name' => config('energix.seo.site_name'),
        'url' => config('energix.seo.canonical'),
        'image' => rtrim(config('energix.seo.canonical'), '/').'/'.ltrim(config('energix.seo.og_image'), '/'),
        'telephone' => $contact['phone'],
        'email' => $contact['email'],
        'address' => [
            '@type' => 'PostalAddress',
            'addressLocality' => $contact['city'],
            'addressCountry' => $contact['country_code'],
        ],
        'areaServed' => $contact['area_served'],
        'openingHours' => ['Mo-Fr 08:00-20:00', 'Sa 09:00-17:00'],
        'sameAs' => array_values(array_filter(
            array_column(config('energix.social'), 'url'),
            fn (string $url): bool => str_starts_with($url, 'https://'),
        )),
    ];
@endphp

<script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
