<?php

declare(strict_types=1);

/**
 * `withoutVite()` — paginile includ `@vite(...)`; fara neutralizare, un manifest de
 * build lipsa ar da 500 si testele SEO ar minti. Nu afecteaza JSON-LD-ul, care e
 * independent de Vite. Vezi PagesTest pentru detalii despre `Vite::fonts()`.
 */
beforeEach(function (): void {
    $this->withoutVite();
});

/*
|--------------------------------------------------------------------------
| Date structurate
|--------------------------------------------------------------------------
*/

it('declara ElectricalContractor, nu Electrician', function (): void {
    $html = $this->get('/')->assertOk()->getContent();

    // `Electrician` din schema.org implica o afirmatie de autorizare — exclusa.
    expect($html)->toContain('"@type":"ElectricalContractor"')
        ->and($html)->not->toContain('"@type":"Electrician"');
});

it('publica FAQPage pe homepage, in ambele limbi', function (string $path, string $question): void {
    $html = $this->get($path)->assertOk()->getContent();

    expect($html)->toContain('"@type":"FAQPage"')
        ->and($html)->toContain('"@type":"Question"')
        ->and($html)->toContain($question);
})->with([
    ['/', 'Cât costă o instalație electrică completă într-un apartament?'],
    ['/ru', 'Сколько стоит электромонтаж квартиры под ключ?'],
]);

it('nu publica FAQPage pe paginile interioare', function (): void {
    $this->get('/despre')->assertOk()->assertDontSee('"@type":"FAQPage"', escape: false);
});

it('publica BreadcrumbList doar pe paginile interioare', function (): void {
    $this->get('/')->assertOk()->assertDontSee('BreadcrumbList');
    $this->get('/servicii')->assertOk()->assertSee('BreadcrumbList');
});

it('declara zonele deservite in areaServed', function (): void {
    $html = $this->get('/')->assertOk()->getContent();

    foreach (['Chișinău', 'Bălți', 'Orhei'] as $city) {
        expect($html)->toContain('"name":"'.$city.'"');
    }
});

/*
|--------------------------------------------------------------------------
| Bilingv: hreflang si canonical
|--------------------------------------------------------------------------
*/

it('declara hreflang pentru ambele limbi plus x-default', function (string $path): void {
    $html = $this->get($path)->assertOk()->getContent();

    expect($html)->toContain('hreflang="ro-MD"')
        ->and($html)->toContain('hreflang="ru-MD"')
        ->and($html)->toContain('hreflang="x-default"');
})->with(['/', '/servicii', '/ru', '/ru/uslugi']);

it('leaga hreflang catre pagina sora, nu catre homepage', function (): void {
    $html = $this->get('/servicii')->assertOk()->getContent();

    expect($html)->toContain('hreflang="ru-MD" href="'.url('/ru/uslugi').'"');
});

it('are canonical propriu pe fiecare pagina, coerent cu hreflang', function (string $path): void {
    $html = $this->get($path)->assertOk()->getContent();

    expect($html)->toContain('<link rel="canonical" href="'.url($path).'">');
})->with(['/servicii', '/galerie', '/ru/uslugi', '/ru/raboty']);

it('nu pune canonical pe 404', function (): void {
    $this->get('/inexistent')->assertNotFound()->assertDontSee('rel="canonical"', escape: false);
});

/*
|--------------------------------------------------------------------------
| Sitemap si redirect-uri
|--------------------------------------------------------------------------
*/

it('genereaza sitemap cu ambele limbi si alternative', function (): void {
    $response = $this->get('/sitemap.xml')->assertOk();
    $response->assertHeader('Content-Type', 'application/xml');

    $xml = $response->getContent();

    // 8 pagini × 2 limbi
    expect(substr_count($xml, '<loc>'))->toBe(16)
        ->and($xml)->toContain('xmlns:xhtml')
        ->and($xml)->toContain(url('/ru/uslugi'))
        ->and($xml)->toContain(url('/servicii'))
        ->and($xml)->toContain('hreflang="x-default"');
});

it('redirectioneaza 301 vechile URL-uri .html', function (string $old, string $new): void {
    $this->get($old)->assertStatus(301)->assertRedirect($new);
})->with([
    ['/index.html', '/'],
    ['/services.html', '/servicii'],
    ['/galery.html', '/galerie'],
    ['/about.html', '/despre'],
    ['/contacts.html', '/contacte'],
    ['/terms_conditions.html', '/termeni-si-conditii'],
    ['/privacy_policy.html', '/politica-de-confidentialitate'],
    ['/cookie_policy.html', '/politica-cookie'],
]);

/*
|--------------------------------------------------------------------------
| Cartonasul social (Open Graph)
|--------------------------------------------------------------------------
|
| Regresie: `og:image` a fost un WebP transparent de 669x543. Facebook si LinkedIn
| nu randeaza fiabil WebP, iar raportul cerut e 1.91:1. Testul apara si formatul,
| si dimensiunile declarate.
|
*/

it('serveste un cartonas social opac de 1200x630, pe fiecare limba', function (string $locale): void {
    $path = public_path(config("energix.seo.og_images.{$locale}"));

    expect($path)->toBeFile();

    [$width, $height, $type] = getimagesize($path);

    expect($width)->toBe(1200)
        ->and($height)->toBe(630)
        ->and(image_type_to_mime_type($type))->toBe('image/png');
})->with(['ro', 'ru']);

it('declara imaginea Open Graph cu dimensiuni, tip si alt', function (string $path, string $image, string $ogLocale, string $altLocale): void {
    $html = $this->get($path)->assertOk()->getContent();

    expect($html)->toContain('<meta property="og:image" content="'.url($image).'">')
        ->and($html)->toContain('<meta property="og:image:width" content="1200">')
        ->and($html)->toContain('<meta property="og:image:height" content="630">')
        ->and($html)->toContain('<meta property="og:image:type" content="image/png">')
        ->and($html)->toContain('<meta property="og:locale" content="'.$ogLocale.'">')
        ->and($html)->toContain('<meta property="og:locale:alternate" content="'.$altLocale.'">')
        // `alt` e obligatoriu si pe Twitter, altfel cardul e mut pentru cititoarele de ecran.
        ->and($html)->toContain('twitter:image:alt')
        ->and($html)->toContain('og:image:alt');
})->with([
    ['/', '/images/og/energix-ro.png', 'ro_MD', 'ru_MD'],
    ['/ru', '/images/og/energix-ru.png', 'ru_MD', 'ro_MD'],
]);

/*
|--------------------------------------------------------------------------
| llms.txt
|--------------------------------------------------------------------------
*/

it('publica /llms.txt ca text simplu, bilingv', function (): void {
    $response = $this->get('/llms.txt')->assertOk();

    expect($response->headers->get('Content-Type'))->toBe('text/plain; charset=utf-8');

    $body = $response->getContent();

    expect($body)->toStartWith('# Energix')
        // Rezumatul GEO, in ambele limbi.
        ->and($body)->toContain(trans('site.home.summary', [], 'ro'))
        ->and($body)->toContain(trans('site.home.summary', [], 'ru'))
        // Ambele arbori de URL-uri.
        ->and($body)->toContain(url('/servicii'))
        ->and($body)->toContain(url('/ru/uslugi'))
        // Partea care califica lead-urile: ce NU face firma.
        ->and($body)->toContain('Nu execută lucrări punctuale pe instalații existente.')
        ->and($body)->toContain('Не выполняет точечные работы на существующей проводке.')
        // Cele cinci etape, ca un motor de raspuns sa poata cita procesul complet.
        ->and($body)->toContain('5. Verificare și predare');
});

/*
|--------------------------------------------------------------------------
| Conversia
|--------------------------------------------------------------------------
*/

it('are telefonul la un tap distanta pe fiecare pagina', function (string $path): void {
    $this->get($path)->assertOk()->assertSee('tel:+37368582016', escape: false);
})->with(['/', '/servicii', '/galerie', '/despre', '/contacte', '/ru', '/ru/uslugi', '/ru/kontakty']);
