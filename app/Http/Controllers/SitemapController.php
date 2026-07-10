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
    private const HREFLANG = ['ro' => 'ro-MD', 'ru' => 'ru-MD'];

    /**
     * Ruta => vederea care o randeaza. Ordinea da si ordinea din sitemap.
     *
     * `changefreq` si `priority` au disparut: Google le ignora declarat de ani buni,
     * iar `priority` sugereaza o ierarhie pe care crawler-ul nu o citeste.
     *
     * @var array<string, string>
     */
    private const PAGES = [
        'home' => 'pages/home.blade.php',
        'services' => 'pages/services.blade.php',
        'services.apartamente' => 'pages/service-segment.blade.php',
        'services.case' => 'pages/service-segment.blade.php',
        'services.industriale' => 'pages/service-segment.blade.php',
        'contact' => 'pages/contact.blade.php',
        'gallery' => 'pages/gallery.blade.php',
        'about' => 'pages/about.blade.php',
        'legal.terms' => 'pages/legal/page.blade.php',
        'legal.privacy' => 'pages/legal/page.blade.php',
        'legal.cookies' => 'pages/legal/page.blade.php',
    ];

    public function __invoke(): Response
    {
        $urls = [];

        foreach (config('energix.locales') as $locale) {
            foreach (self::PAGES as $name => $view) {
                $alternates = [];

                foreach (config('energix.locales') as $alt) {
                    $alternates[self::HREFLANG[$alt]] = URL::inLocale($alt, $name);
                }

                $alternates['x-default'] = URL::inLocale('ro', $name);

                $urls[] = [
                    'loc' => URL::inLocale($locale, $name),
                    'lastmod' => $this->lastModified($view, $locale),
                    'alternates' => $alternates,
                ];
            }
        }

        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Cand s-a schimbat ultima data continutul acestei pagini, in aceasta limba.
     *
     * Sursa e mtime-ul fisierelor din care se compune pagina — vederea ei si
     * fisierul de limba. NU `now()`: o data care se schimba la fiecare cerere e
     * o minciuna, iar Google, odata ce o prinde, ignora `lastmod` pe tot site-ul.
     *
     * `rsync -a` pastreaza mtime-urile la deploy, deci valoarea supravietuieste.
     */
    private function lastModified(string $view, string $locale): string
    {
        $times = array_filter([
            @filemtime(resource_path('views/'.$view)),
            @filemtime(lang_path($locale.'/site.php')),
        ]);

        return date(DATE_ATOM, $times === [] ? time() : max($times));
    }
}
