<?php

declare(strict_types=1);

/**
 * Garda de conformitate.
 *
 * Clientul a exclus explicit trei lucruri (.claude/context/BUSINESS.md):
 *   1. orice afirmatie de certificare / autorizare;
 *   2. serviciul „Smart Home” si orice mentiune de automatizare;
 *   3. serviciul „Audit Energetic”.
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

    // 3. audit energetic — atentie: „consum” ramane permis („monitorizare consum”)
    'audit',
    'energetic',
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

it('nu ofera decat trei servicii', function (): void {
    $services = config('energix.services');

    expect($services)->toHaveCount(3);

    $titles = mb_strtolower(implode(' ', array_column($services, 'title')));

    expect($titles)->not->toContain('smart')
        ->and($titles)->not->toContain('audit');
});
