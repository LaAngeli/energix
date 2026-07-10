<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\URL;

/**
 * Sitemap-ul se genereaza din tabela de rute, deci nu ramane niciodata in urma.
 *
 * Controller invocabil, nu closure: un closure in routes/web.php face
 * `php artisan route:cache` sa esueze, iar asta e singura optimizare reala
 * de routing pe shared hosting.
 *
 * Fiecare URL isi declara alternativele de limba prin `xhtml:link` — asa cere
 * Google pentru siteuri multilingve, in plus fata de `hreflang` din `<head>`.
 */
class SitemapController extends Controller
{
    /**
     * Ruta => prioritate.
     *
     * @var array<string, string>
     */
    private const PAGES = [
        'home' => '1.0',
        'services' => '0.9',
        'contact' => '0.9',
        'gallery' => '0.7',
        'about' => '0.7',
        'legal.terms' => '0.1',
        'legal.privacy' => '0.1',
        'legal.cookies' => '0.1',
    ];

    private const HREFLANG = ['ro' => 'ro-MD', 'ru' => 'ru-MD'];

    public function __invoke(): Response
    {
        $urls = [];

        foreach (config('energix.locales') as $locale) {
            foreach (self::PAGES as $name => $priority) {
                $alternates = [];

                foreach (config('energix.locales') as $alt) {
                    $alternates[self::HREFLANG[$alt]] = URL::inLocale($alt, $name);
                }

                $alternates['x-default'] = URL::inLocale('ro', $name);

                $urls[] = [
                    'loc' => URL::inLocale($locale, $name),
                    'priority' => $priority,
                    'alternates' => $alternates,
                ];
            }
        }

        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml');
    }
}
