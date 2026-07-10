<?php

declare(strict_types=1);

/**
 * Garda de conformitate, in AMBELE limbi.
 *
 * Clientul a exclus explicit patru lucruri (.claude/context/BUSINESS.md):
 *   1. orice afirmatie de certificare / autorizare;
 *   2. serviciul „Smart Home” si orice mentiune de automatizare;
 *   3. serviciul „Audit Energetic”;
 *   4. orice serviciu de tip service: reparatii, mentenanta, interventii
 *      urgente / non-stop — business-ul face DOAR instalatii complete de la zero.
 *
 * O traducere e exact locul unde reintra un cuvant interzis („ремонт” e cuvantul
 * normal pentru renovare in rusa). De aceea garda vaneaza radacini in ambele limbi,
 * pe HTML-ul RANDAT al fiecarei pagini publice.
 */
$forbidden = [
    // ---- română ----
    'autoriz', 'anre', 'certific', 'licenț', 'licent', 'atestat',
    'smart', 'automatiz', 'inteligent',
    'audit', 'energetic',
    'reparat', 'reparaț', 'mentenan', 'urgen', 'non-stop', '24/7', 'depan',

    // ---- русский ----
    'сертифиц', 'лицензи', 'авторизован', 'аттестат',
    'умный дом', 'автоматизац',
    'аудит',
    'ремонт', 'обслуживан', 'аварийн', 'круглосуточн', 'неисправност',
];

$pages = [
    // română
    '/',
    '/servicii',
    '/galerie',
    '/despre',
    '/contacte',
    '/termeni-si-conditii',
    '/politica-de-confidentialitate',
    '/politica-cookie',
    '/sitemap.xml',

    // русский
    '/ru',
    '/ru/uslugi',
    '/ru/raboty',
    '/ru/o-nas',
    '/ru/kontakty',
    '/ru/usloviya',
    '/ru/konfidencialnost',
    '/ru/cookie',
];

it('nu randeaza niciun cuvant exclus', function (string $page) use ($forbidden): void {
    $html = mb_strtolower($this->withoutVite()->get($page)->assertOk()->getContent());

    $found = array_values(array_filter(
        $forbidden,
        fn (string $word): bool => str_contains($html, mb_strtolower($word)),
    ));

    expect($found)->toBe([], "Pagina {$page} contine cuvinte excluse: ".implode(', ', $found));
})->with($pages);

it('nu ofera decat trei segmente de instalatii complete', function (): void {
    $services = config('energix.services');

    expect($services)->toHaveCount(3);

    foreach (config('energix.locales') as $locale) {
        app()->setLocale($locale);

        $titles = mb_strtolower(implode(' ', array_map(
            fn (array $service): string => trans("site.services.{$service['slug']}.title"),
            $services,
        )));

        foreach (['smart', 'audit', 'reparat', 'mentenan', 'ремонт', 'обслуживан'] as $word) {
            expect($titles)->not->toContain($word);
        }
    }
});
