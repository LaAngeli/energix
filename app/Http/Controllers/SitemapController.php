<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Response;

/**
 * Sitemap-ul se genereaza din tabela de rute, deci nu ramane niciodata in urma.
 *
 * Controller invocabil, nu closure: un closure in routes/web.php face
 * `php artisan route:cache` sa esueze, iar asta e singura optimizare reala
 * de routing pe shared hosting.
 */
class SitemapController extends Controller
{
    /**
     * Ruta => prioritate. Ordinea conteaza pentru crawlere.
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

    public function __invoke(): Response
    {
        $urls = [];

        foreach (self::PAGES as $name => $priority) {
            $urls[] = ['loc' => route($name), 'priority' => $priority];
        }

        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml');
    }
}
