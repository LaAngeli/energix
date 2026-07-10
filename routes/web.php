<?php

declare(strict_types=1);

use App\Http\Controllers\ContactController;
use App\Http\Controllers\LlmsTxtController;
use App\Http\Controllers\SitemapController;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Site bilingv: RO la radacina, RU sub /ru cu slug-uri traduse
|--------------------------------------------------------------------------
|
| Slug-urile ruse sunt traduse (`/ru/uslugi`, nu `/ru/servicii`) — cuvantul-cheie
| in URL e un semnal slab, dar gratuit, iar pentru un vizitator rusofon un URL
| romanesc arata a traducere neterminata.
|
| Numele rutelor: `services` pentru RO, `ru.services` pentru RU. Vederile nu stiu
| asta — folosesc `URL::localized('services')` (macro in AppServiceProvider).
|
| Nu redirectam dupa `Accept-Language`: Googlebot crawleaza cu un singur set de
| headere si ar vedea mereu aceeasi limba.
|
*/

/** @var array<string, array<string, string>> URI-ul fiecarei pagini, per limba. */
$pages = [
    'home' => ['ro' => '/', 'ru' => '/ru'],
    'services' => ['ro' => '/servicii', 'ru' => '/ru/uslugi'],
    'gallery' => ['ro' => '/galerie', 'ru' => '/ru/raboty'],
    'about' => ['ro' => '/despre', 'ru' => '/ru/o-nas'],
    'contact' => ['ro' => '/contacte', 'ru' => '/ru/kontakty'],
    'legal.terms' => ['ro' => '/termeni-si-conditii', 'ru' => '/ru/usloviya'],
    'legal.privacy' => ['ro' => '/politica-de-confidentialitate', 'ru' => '/ru/konfidencialnost'],
    'legal.cookies' => ['ro' => '/politica-cookie', 'ru' => '/ru/cookie'],
];

/**
 * Numele rutei => [vedere, date]. Cele trei pagini legale impart o singura
 * vedere; continutul lor vine din `lang/{locale}/site.php`.
 *
 * @var array<string, array{0: string, 1: array<string, string>}>
 */
$views = [
    'home' => ['pages.home', []],
    'services' => ['pages.services', []],
    'gallery' => ['pages.gallery', []],
    'about' => ['pages.about', []],
    'contact' => ['pages.contact', []],
    'legal.terms' => ['pages.legal.page', ['doc' => 'terms']],
    'legal.privacy' => ['pages.legal.page', ['doc' => 'privacy']],
    'legal.cookies' => ['pages.legal.page', ['doc' => 'cookies']],
];

foreach (config('energix.locales') as $locale) {
    $prefix = $locale === 'ro' ? '' : $locale.'.';

    Route::middleware(SetLocale::class.':'.$locale)->group(function () use ($pages, $views, $locale, $prefix): void {
        foreach ($views as $name => [$view, $data]) {
            Route::view($pages[$name][$locale], $view, $data)->name($prefix.$name);
        }

        /*
         | `throttle:5,1` — cinci trimiteri pe minut, per IP. Site-ul vechi nu avea
         | nicio limita si putea fi folosit ca relay de spam.
         */
        Route::post($pages['contact'][$locale], [ContactController::class, 'store'])
            ->middleware('throttle:5,1')
            ->name($prefix.'contact.store');
    });
}

// Controllere invocabile, nu closure: closure-urile rup `php artisan route:cache`.
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

/*
| `/llms.txt` (llmstxt.org) — rezumat in Markdown pentru motoarele de raspuns.
| Nu e in vreun grup de limba: e un singur fisier, bilingv, pentru tot site-ul.
*/
Route::get('/llms.txt', LlmsTxtController::class)->name('llms');

/*
|--------------------------------------------------------------------------
| Redirect-uri 301 de la site-ul static vechi
|--------------------------------------------------------------------------
|
| Fara ele pierdem indexarea existenta. Vezi .claude/context/DEPLOY-HOSTINGER.md.
| Atentie: vechiul URL de galerie continea typo-ul „galery”.
|
*/

$legacy = [
    '/index.html' => '/',
    '/services.html' => '/servicii',
    '/galery.html' => '/galerie',
    '/about.html' => '/despre',
    '/contacts.html' => '/contacte',
    '/terms_conditions.html' => '/termeni-si-conditii',
    '/privacy_policy.html' => '/politica-de-confidentialitate',
    '/cookie_policy.html' => '/politica-cookie',
];

foreach ($legacy as $old => $new) {
    Route::redirect($old, $new, 301);
}
