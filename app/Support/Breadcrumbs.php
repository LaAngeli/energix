<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

/**
 * Drumul catre pagina curenta, dedus din NUMELE RUTEI.
 *
 * O singura sursa pentru firimiturile vizibile (`<x-breadcrumbs>`) si pentru
 * `BreadcrumbList` din JSON-LD. Google cere ca cele doua sa spuna acelasi lucru;
 * inainte, marcajul zicea „Instalatii electrice pentru case”, iar pagina zicea
 * „Case”, si numai paginile de segment aveau firimituri vizibile.
 *
 * Ierarhia nu se declara nicaieri: numele rutei o contine deja.
 *   `services.apartamente` -> home / services / services.apartamente
 *   `legal.terms`          -> home / legal.terms       (`legal` nu e o pagina)
 *   `gallery`              -> home / gallery
 *
 * Nivelurile intermediare fara ruta proprie se sar. Asa, o eventuala pagina
 * `legal` ar aparea automat, fara sa umble nimeni aici.
 */
class Breadcrumbs
{
    /**
     * Ultimul element e pagina curenta si NU are URL.
     *
     * @return list<array{name: string, url: string|null}>
     */
    public static function trail(string $page): array
    {
        // Homepage-ul e radacina; pagina de eroare nu e nicaieri in ierarhie.
        if ($page === 'home' || $page === '404') {
            return [];
        }

        $crumbs = [[
            'name' => trans('site.nav.home'),
            'url' => URL::localized('home'),
        ]];

        $segments = explode('.', $page);
        $path = '';

        foreach ($segments as $index => $segment) {
            $path = $path === '' ? $segment : $path.'.'.$segment;
            $isCurrent = $index === array_key_last($segments);

            if (! $isCurrent && ! self::routeExists($path)) {
                continue;
            }

            $crumbs[] = [
                'name' => self::label($path),
                'url' => $isCurrent ? null : URL::localized($path),
            ];
        }

        return $crumbs;
    }

    /**
     * Eticheta scurta, aceeasi pe care o vede si utilizatorul.
     *
     * NU `<title>`-ul paginii: acela contine „| Energix”, iar un nume de brand
     * intr-o firimitura e zgomot si in SERP, si intr-un cititor de ecran.
     */
    private static function label(string $page): string
    {
        if (str_starts_with($page, 'services.')) {
            return trans('site.services.'.substr($page, strlen('services.')).'.nav');
        }

        if (str_starts_with($page, 'legal.')) {
            return trans('site.common.legal_'.substr($page, strlen('legal.')));
        }

        return trans("site.nav.{$page}");
    }

    /**
     * Rutele RU sunt prefixate (`ru.services`), cele RO nu. Aceeasi conventie ca
     * macro-ul `URL::localized()` din AppServiceProvider.
     */
    private static function routeExists(string $page): bool
    {
        /** @var string $locale */
        $locale = app()->getLocale();

        return Route::has(($locale === 'ro' ? '' : $locale.'.').$page);
    }
}
