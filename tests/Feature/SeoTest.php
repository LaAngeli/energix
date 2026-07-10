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

it('emite JSON-LD valid, fara noduri neparsabile', function (string $path, int $status): void {
    $html = $this->get($path)->assertStatus($status)->getContent();

    preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $blocks);

    expect($blocks[1])->not->toBeEmpty();

    foreach ($blocks[1] as $json) {
        expect(json_decode($json, true))->toBeArray("JSON-LD invalid pe {$path}");
    }
})->with([
    ['/', 200],
    ['/servicii', 200],
    ['/servicii/apartamente', 200],
    ['/despre', 200],
    ['/ru/uslugi/doma', 200],
    // 404 randeaza tot layout-ul, deci si nodul de business — trebuie sa ramana valid.
    ['/inexistent', 404],
]);

it('nu declara valori in conflict pe acelasi @id', function (string $path): void {
    /*
     | Regresie: `Service`-ul aparea si in `hasOfferCatalog`, si ca nod de sine
     | statator, cu ACELASI `@id` dar cu `areaServed` de tipuri diferite (text vs
     | lista de City). Nodurile cu acelasi `@id` se contopesc in ochii lui Google,
     | iar doua valori pe aceeasi proprietate a aceleiasi entitati e un conflict.
     */
    $html = $this->get($path)->assertOk()->getContent();

    preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $blocks);

    // Aduna toate nodurile complete (mai mult decat o simpla referinta `{@id}`), grupate pe @id.
    $byId = [];
    $walk = function (mixed $node) use (&$walk, &$byId): void {
        if (! is_array($node)) {
            return;
        }

        if (isset($node['@id']) && count($node) > 1) {
            $byId[$node['@id']][] = $node;
        }

        foreach ($node as $value) {
            $walk($value);
        }
    };

    foreach ($blocks[1] as $json) {
        $walk(json_decode($json, true));
    }

    foreach ($byId as $id => $nodes) {
        $keys = array_unique(array_merge(...array_map('array_keys', $nodes)));

        foreach ($keys as $key) {
            if ($key === '@id' || $key === '@context') {
                continue;
            }

            $values = [];

            foreach ($nodes as $node) {
                if (isset($node[$key])) {
                    $values[json_encode($node[$key])] = true;
                }
            }

            expect(count($values))->toBeLessThanOrEqual(1, "Conflict pe `{$key}` la {$id} ({$path})");
        }
    }
})->with(['/', '/servicii/apartamente', '/servicii/case', '/ru/uslugi/promyshlennye']);

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

/*
|--------------------------------------------------------------------------
| Paginile de segment
|--------------------------------------------------------------------------
|
| Regresie: cele trei segmente traiau ca tab-uri pe /servicii, sub un singur
| <title> si un singur H1. Trei intentii comerciale nu incap intr-un URL, iar
| un fragment (`#apartamente`) nu se rankeaza ca pagina.
|
*/

it('da fiecarui segment un URL, un titlu si un H1 proprii', function (string $path, string $slug): void {
    $html = $this->get($path)->assertOk()->getContent();

    $title = trans('site.seo')["services.{$slug}"]['title'];
    $h1 = trans("site.services.{$slug}.title");

    expect($html)->toContain('<title>'.e($title).'</title>')
        ->and($html)->toContain('<link rel="canonical" href="'.url($path).'">')
        // Exact un H1, si acela e titlul segmentului.
        ->and(preg_match_all('/<h1[ >]/', $html))->toBe(1)
        ->and($html)->toContain('>'.e($h1).'</h1>');
})->with([
    ['/servicii/apartamente', 'apartamente'],
    ['/servicii/case', 'case'],
    ['/servicii/industriale', 'industriale'],
    ['/ru/uslugi/kvartiry', 'apartamente'],
    ['/ru/uslugi/doma', 'case'],
    ['/ru/uslugi/promyshlennye', 'industriale'],
]);

it('leaga segmentele intre limbi prin hreflang', function (): void {
    $html = $this->get('/servicii/apartamente')->assertOk()->getContent();

    expect($html)->toContain('hreflang="ru-MD" href="'.url('/ru/uslugi/kvartiry').'"');
});

it('declara Service cu URL propriu si firimituri pe trei niveluri', function (): void {
    $html = $this->get('/servicii/case')->assertOk()->getContent();

    expect($html)->toContain('"@type":"Service"')
        ->and($html)->toContain('"url":"'.url('/servicii/case').'"')
        // Acasa > Servicii > Case
        ->and($html)->toContain('"position":3')
        // FAQ propriu segmentului, nu cel de pe homepage.
        ->and($html)->toContain('"@type":"FAQPage"')
        ->and($html)->toContain(trans('site.services.case.faq.0.q'));
});

it('nu duplica intrebarile de pe homepage pe paginile de segment', function (): void {
    $homeQuestions = array_column(trans('site.faq'), 'q');

    foreach (config('energix.services') as $service) {
        foreach (trans("site.services.{$service['slug']}.faq") as $item) {
            expect($homeQuestions)->not->toContain($item['q']);
        }
    }
});

/*
|--------------------------------------------------------------------------
| Legaturi interne
|--------------------------------------------------------------------------
*/

it('foloseste text de ancora descriptiv catre paginile de segment', function (string $path): void {
    $html = $this->get($path)->assertOk()->getContent();

    foreach (config('energix.services') as $service) {
        $anchor = trans("site.services.{$service['slug']}.anchor");

        expect($html)->toContain(url('/servicii/'.$service['uri']['ro']))
            ->and($html)->toContain(e($anchor));
    }

    /*
     | „Detalii complete” era textul celui mai valoros link intern de pe site
     | si nu spunea nimic despre pagina-tinta — nici lui Google, nici unui
     | cititor de ecran care parcurge lista de linkuri.
     */
    expect($html)->not->toContain('Detalii complete');
})->with(['/', '/servicii', '/galerie', '/despre']);

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

    // 11 pagini × 2 limbi (8 + cele trei segmente de servicii)
    expect(substr_count($xml, '<loc>'))->toBe(22)
        ->and($xml)->toContain('xmlns:xhtml')
        ->and($xml)->toContain(url('/ru/uslugi'))
        ->and($xml)->toContain(url('/servicii'))
        ->and($xml)->toContain(url('/servicii/apartamente'))
        ->and($xml)->toContain(url('/ru/uslugi/promyshlennye'))
        ->and($xml)->toContain('hreflang="x-default"');
});

it('declara lastmod real, nu ora cererii', function (): void {
    $xml = $this->get('/sitemap.xml')->assertOk()->getContent();

    preg_match_all('#<lastmod>([^<]+)</lastmod>#', $xml, $matches);

    expect($matches[1])->toHaveCount(22);

    /*
     | `lastmod` vine din mtime-ul surselor, deci e in trecut. Daca ar fi `now()`,
     | s-ar schimba la fiecare cerere — iar Google, odata ce prinde asta, ignora
     | `lastmod` pe tot site-ul.
     */
    foreach ($matches[1] as $stamp) {
        expect(strtotime($stamp))->toBeLessThanOrEqual(time());
    }

    // Google ignora ambele de ani buni; nu le mai trimitem.
    expect($xml)->not->toContain('<changefreq>')
        ->and($xml)->not->toContain('<priority>');
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
