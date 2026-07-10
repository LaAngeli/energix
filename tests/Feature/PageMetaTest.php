<?php

declare(strict_types=1);

beforeEach(fn () => $this->withoutVite());

/**
 * Regresie: `data_get($seo, "pages.legal.terms")` sparge cheia la punct si cauta
 * $seo['pages']['legal']['terms'], care nu exista. Cele trei pagini legale serveau
 * titlul si descrierea homepage-ului. Acum se face acces direct pe cheia literala.
 */
it('serveste titlul propriu, nu pe cel al homepage-ului', function (string $path, string $expected): void {
    $this->get($path)
        ->assertOk()
        ->assertSee("<title>{$expected}</title>", escape: false);
})->with([
    ['/', 'Energix — instalații electrice în Chișinău și toată Moldova'],
    ['/servicii', 'Servicii — instalații, reparații și mentenanță electrică | Energix'],
    ['/termeni-si-conditii', 'Termeni și condiții | Energix'],
    ['/politica-de-confidentialitate', 'Politica de confidențialitate | Energix'],
    ['/politica-cookie', 'Politica de cookie | Energix'],
]);

it('are descrieri unice pe fiecare pagina', function (): void {
    $paths = [
        '/', '/servicii', '/galerie', '/despre', '/contacte',
        '/termeni-si-conditii', '/politica-de-confidentialitate', '/politica-cookie',
    ];

    $descriptions = [];

    foreach ($paths as $path) {
        preg_match(
            '/<meta name="description" content="([^"]+)"/',
            $this->get($path)->assertOk()->getContent(),
            $matches,
        );

        $descriptions[$path] = $matches[1] ?? '';
    }

    expect($descriptions)->not->toContain('')
        ->and(array_unique($descriptions))->toHaveCount(count($paths));
});

it('nu foloseste meta de homepage pe pagina 404', function (): void {
    $html = $this->get('/aceasta-pagina-nu-exista')->assertNotFound()->getContent();

    expect($html)->toContain('Pagina nu a fost găsită | Energix')
        ->and($html)->not->toContain('Energix — instalații electrice în Chișinău');
});
