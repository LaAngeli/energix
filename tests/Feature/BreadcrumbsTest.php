<?php

declare(strict_types=1);

use App\Support\Breadcrumbs;

beforeEach(fn () => $this->withoutVite());

/**
 * Numele firimiturilor vizibile, in ordinea din pagina.
 *
 * @return list<string>
 */
function visibleCrumbs(string $html): array
{
    if (! preg_match('#<nav[^>]*aria-label="[^"]*"[^>]*>\s*<ol.*?</ol>\s*</nav>#s', $html, $nav)) {
        return [];
    }

    preg_match_all('#<(?:a|span)[^>]*>([^<]+)</(?:a|span)>#', $nav[0], $matches);

    return array_values(array_filter(
        array_map(fn (string $label): string => html_entity_decode(trim($label)), $matches[1]),
        fn (string $label): bool => $label !== '' && $label !== '/',
    ));
}

/**
 * Elementele din `BreadcrumbList`, sau `null` daca marcajul lipseste.
 *
 * @return list<array{name: string, item: string|null}>|null
 */
function markupCrumbs(string $html): ?array
{
    preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $scripts);

    foreach ($scripts[1] as $json) {
        $data = json_decode($json, true);

        if (($data['@type'] ?? null) !== 'BreadcrumbList') {
            continue;
        }

        return array_map(
            fn (array $item): array => ['name' => $item['name'], 'item' => $item['item'] ?? null],
            $data['itemListElement'],
        );
    }

    return null;
}

/*
|--------------------------------------------------------------------------
| Marcajul si pagina trebuie sa spuna acelasi lucru
|--------------------------------------------------------------------------
|
| Regresie: `BreadcrumbList` folosea `<title>`-ul paginii („Lucrări de instalații
| electrice | Energix”), iar firimiturile vizibile existau doar pe paginile de
| segment, unde scriau altceva („Case”). Google compara cele doua si ignora
| marcajul cand difera.
|
*/

it('afiseaza aceleasi firimituri in pagina si in JSON-LD', function (string $path): void {
    $html = $this->get($path)->assertOk()->getContent();

    $visible = visibleCrumbs($html);
    $markup = markupCrumbs($html);

    expect($markup)->not->toBeNull()
        ->and($visible)->not->toBeEmpty()
        ->and($visible)->toBe(array_column($markup, 'name'));
})->with([
    '/servicii', '/servicii/apartamente', '/servicii/case', '/servicii/industriale',
    '/galerie', '/despre', '/contacte', '/termeni-si-conditii',
    '/ru/uslugi', '/ru/uslugi/kvartiry', '/ru/raboty', '/ru/cookie',
]);

it('nu pune numele de brand in firimituri', function (string $path): void {
    $names = array_column(markupCrumbs($this->get($path)->assertOk()->getContent()), 'name');

    // `<title>` are sufixul „| Energix”; o firimitura, nu.
    foreach ($names as $name) {
        expect($name)->not->toContain('| Energix');
    }
})->with(['/servicii', '/galerie', '/despre', '/termeni-si-conditii']);

/*
|--------------------------------------------------------------------------
| Ierarhia
|--------------------------------------------------------------------------
*/

it('nu are firimituri pe homepage', function (string $path): void {
    $html = $this->get($path)->assertOk()->getContent();

    expect(markupCrumbs($html))->toBeNull()
        ->and(visibleCrumbs($html))->toBeEmpty();
})->with(['/', '/ru']);

it('nu are firimituri pe 404', function (): void {
    $html = $this->get('/pagina-inexistenta')->assertNotFound()->getContent();

    expect(markupCrumbs($html))->toBeNull();
});

it('aseaza segmentele sub pagina de servicii', function (): void {
    $trail = Breadcrumbs::trail('services.case');

    expect($trail)->toHaveCount(3)
        ->and($trail[0]['url'])->toBe(url('/'))
        ->and($trail[1]['url'])->toBe(url('/servicii'))
        // Ultima firimitura e pagina curenta: nu e link.
        ->and($trail[2]['url'])->toBeNull()
        ->and($trail[2]['name'])->toBe(trans('site.services.case.nav'));
});

it('sare nivelurile intermediare care nu au pagina', function (): void {
    // `legal` e doar un prefix de nume de ruta, nu un URL. Nu apare in traseu.
    $trail = Breadcrumbs::trail('legal.terms');

    expect($trail)->toHaveCount(2)
        ->and($trail[1]['name'])->toBe(trans('site.common.legal_terms'));
});

it('ramane in limba paginii', function (): void {
    app()->setLocale('ru');

    $trail = Breadcrumbs::trail('services.industriale');

    expect($trail[0]['url'])->toBe(url('/ru'))
        ->and($trail[1]['url'])->toBe(url('/ru/uslugi'))
        ->and($trail[2]['name'])->toBe(trans('site.services.industriale.nav', [], 'ru'));
});

/*
|--------------------------------------------------------------------------
| Accesibilitate
|--------------------------------------------------------------------------
*/

it('marcheaza pagina curenta si nu o face link', function (): void {
    $html = $this->get('/servicii/case')->assertOk()->getContent();

    $current = trans('site.services.case.nav');

    expect($html)->toContain('<span class="text-paper" aria-current="page">')
        // Ultimul element din marcaj nu are `item` — asa cere Google.
        ->and(markupCrumbs($html)[2]['item'])->toBeNull()
        ->and($html)->toContain($current);
});

it('eticheteaza navigarea de firimituri separat de cea principala', function (): void {
    $html = $this->get('/galerie')->assertOk()->getContent();

    expect($html)->toContain('aria-label="'.e(trans('site.nav.breadcrumb')).'"')
        // Doua `<nav>` cu aceeasi eticheta ar fi indistinctibile pentru un cititor de ecran.
        ->and(trans('site.nav.breadcrumb'))->not->toBe(trans('site.nav.main'));
});
