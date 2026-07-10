<?php

declare(strict_types=1);

beforeEach(fn () => $this->withoutVite());

/**
 * Regresie: `data_get($seo, "pages.legal.terms")` sparge cheia la punct si cauta
 * o structura imbricata inexistenta. Cele trei pagini legale serveau titlul si
 * descrierea homepage-ului. Acum se face acces direct pe cheia literala.
 */
it('serveste titlul propriu pe fiecare pagina', function (string $path, string $expected): void {
    $this->get($path)
        ->assertOk()
        ->assertSee("<title>{$expected}</title>", escape: false);
})->with([
    // română
    ['/', 'Instalații electrice Chișinău — montaj complet | Energix'],
    ['/servicii', 'Servicii instalații electrice în Chișinău | Energix'],
    ['/galerie', 'Lucrări de instalații electrice | Energix'],
    ['/despre', 'Despre Energix — 10 ani de instalații electrice'],
    ['/contacte', 'Contact — cere ofertă instalații electrice | Energix'],
    ['/termeni-si-conditii', 'Termeni și condiții | Energix'],

    // русский
    ['/ru', 'Электромонтажные работы в Кишинёве | Energix'],
    ['/ru/uslugi', 'Услуги: электромонтаж под ключ в Кишинёве | Energix'],
    ['/ru/raboty', 'Работы по электромонтажу | Energix'],
    ['/ru/o-nas', 'О нас — 10 лет электромонтажа в Кишинёве | Energix'],
    ['/ru/kontakty', 'Контакты — заказать электромонтаж | Energix'],
    ['/ru/usloviya', 'Условия использования | Energix'],
]);

it('are titluri sub 60 de caractere si descrieri sub 158', function (string $locale): void {
    app()->setLocale($locale);

    foreach (trans('site.seo') as $page => $meta) {
        expect(mb_strlen($meta['title']))->toBeLessThanOrEqual(60, "Titlu prea lung: {$locale}/{$page}")
            ->and(mb_strlen($meta['description']))->toBeLessThanOrEqual(158, "Descriere prea lunga: {$locale}/{$page}");
    }
})->with(['ro', 'ru']);

it('are descrieri unice pe fiecare pagina, in fiecare limba', function (): void {
    $paths = [
        '/', '/servicii', '/galerie', '/despre', '/contacte',
        '/ru', '/ru/uslugi', '/ru/raboty', '/ru/o-nas', '/ru/kontakty',
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

it('declara limba corecta in <html>', function (string $path, string $lang): void {
    $this->get($path)->assertOk()->assertSee('<html lang="'.$lang.'">', escape: false);
})->with([
    ['/', 'ro'],
    ['/servicii', 'ro'],
    ['/ru', 'ru'],
    ['/ru/uslugi', 'ru'],
]);

it('nu foloseste meta de homepage pe pagina 404', function (): void {
    $html = $this->get('/aceasta-pagina-nu-exista')->assertNotFound()->getContent();

    expect($html)->toContain('Pagina nu a fost găsită | Energix')
        ->and($html)->not->toContain('Instalații electrice Chișinău — montaj complet');
});
