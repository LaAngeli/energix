<?php

declare(strict_types=1);

/**
 * Garda de conformitate.
 *
 * Clientul a exclus explicit patru lucruri (.claude/context/BUSINESS.md):
 *   1. orice afirmatie de certificare / autorizare;
 *   2. serviciul „Smart Home” si orice mentiune de automatizare;
 *   3. serviciul „Audit Energetic”;
 *   4. orice serviciu de tip service: reparatii, mentenanta, interventii
 *      urgente / non-stop — business-ul face DOAR instalatii complete de la zero.
 *
 * A le lasa intr-un singur loc uitat — un `<meta keywords>`, un link din footer —
 * e o problema de conformitate, nu de copywriting. Testul asta le vaneaza in HTML-ul
 * randat al fiecarei pagini publice, nu doar in sursa.
 */
$forbidden = [
    // 1. certificare / autorizare
    'autoriz',
    'anre',
    'certific',
    'licenț',
    'licent',
    'atestat',

    // 2. smart home / automatizari
    'smart',
    'automatiz',
    'inteligent',

    // 3. audit energetic — atentie: „consum” ramane permis
    'audit',
    'energetic',

    // 4. service / reparatii / urgente
    'reparat',
    'reparaț',
    'mentenan',
    'urgen',
    'non-stop',
    '24/7',
    'depan',
];

$pages = [
    '/',
    '/servicii',
    '/galerie',
    '/despre',
    '/contacte',
    '/termeni-si-conditii',
    '/politica-de-confidentialitate',
    '/politica-cookie',
    '/sitemap.xml',
];

it('nu randeaza niciun cuvant exclus', function (string $page) use ($forbidden): void {
    $html = mb_strtolower($this->get($page)->assertOk()->getContent());

    $found = array_values(array_filter(
        $forbidden,
        fn (string $word): bool => str_contains($html, mb_strtolower($word)),
    ));

    expect($found)->toBe([], "Pagina {$page} contine cuvinte excluse: ".implode(', ', $found));
})->with($pages);

it('nu ofera decat trei segmente de instalatii complete', function (): void {
    $services = config('energix.services');

    expect($services)->toHaveCount(3);

    $titles = mb_strtolower(implode(' ', array_column($services, 'title')));

    expect($titles)->not->toContain('smart')
        ->and($titles)->not->toContain('audit')
        ->and($titles)->not->toContain('reparat')
        ->and($titles)->not->toContain('mentenan');
});
