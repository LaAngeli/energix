<?php

declare(strict_types=1);

/**
 * `withoutVite()` — homepage-ul include `@vite(...)`; fara neutralizare, un manifest de
 * build lipsa ar da 500 si testele SEO ar minti. Nu afecteaza JSON-LD-ul, care e
 * independent de Vite. Vezi PagesTest pentru detalii despre `Vite::fonts()`.
 */
beforeEach(function (): void {
    $this->withoutVite();
});

it('descrie afacerea drept ElectricalContractor in JSON-LD', function (): void {
    $this->get('/')
        ->assertOk()
        ->assertSee('ElectricalContractor', false);
});

it('nu declara @type Electrician in JSON-LD', function (): void {
    // Tipul `Electrician` din schema.org implica o afirmatie de autorizare, exclusa de client.
    // `ElectricalContractor` nu contine sirul `"@type":"Electrician"`, deci asertiunea e strict.
    $this->get('/')
        ->assertOk()
        ->assertDontSee('"@type":"Electrician"', false);
});

it('serveste sitemap.xml ca application/xml cu cele opt pagini', function (): void {
    $response = $this->get('/sitemap.xml');

    $response->assertOk();

    expect($response->headers->get('Content-Type'))->toContain('application/xml');

    $pages = ['home', 'services', 'contact', 'gallery', 'about', 'legal.terms', 'legal.privacy', 'legal.cookies'];

    foreach ($pages as $name) {
        // `<loc>` exact ca sa distingem home (prefix al celorlalte URL-uri) de restul.
        $response->assertSee('<loc>'.route($name).'</loc>', false);
    }
});

it('redirectioneaza fiecare URL vechi .html 301 catre noul URL', function (string $old, string $new): void {
    $response = $this->get($old);

    $response->assertStatus(301);
    $response->assertRedirect($new);
})->with([
    'index' => ['/index.html', '/'],
    'services' => ['/services.html', '/servicii'],
    'galery' => ['/galery.html', '/galerie'],
    'about' => ['/about.html', '/despre'],
    'contacts' => ['/contacts.html', '/contacte'],
    'terms' => ['/terms_conditions.html', '/termeni-si-conditii'],
    'privacy' => ['/privacy_policy.html', '/politica-de-confidentialitate'],
    'cookies' => ['/cookie_policy.html', '/politica-cookie'],
]);
