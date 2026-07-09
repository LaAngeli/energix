<?php

declare(strict_types=1);

/**
 * Smoke test pentru cele opt pagini publice HTML.
 *
 * DECIZIE Vite (capcana a): folosim `withoutVite()` in beforeEach. Directiva
 * `@vite(...)` din layout arunca `ViteException` (=> 500) daca manifestul de build
 * lipseste; fara neutralizare, un smoke test rulat inainte de `npm run build` ar
 * raporta fals 500-uri. `withoutVite()` inlocuieste apelul `@vite` cu string gol.
 *
 * DECIZIE `Vite::fonts()` (capcana b): `withoutVite()` NU acopera `fonts()` — acela e
 * o metoda reala mostenita din clasa `Vite`, deci nu trece prin stub-ul `__call` al
 * instantei injectate; se executa implementarea reala. E in regula totusi: cand
 * `public/build/fonts-manifest.json` lipseste, `ViteFonts::readManifest()` returneaza
 * `null`, iar `fonts()` intoarce string gol — nu arunca niciodata. Deci paginile se
 * randeaza corect cu sau fara build.
 */
beforeEach(function (): void {
    $this->withoutVite();
});

it('raspunde 200, e HTML si expune telefonul la un tap', function (string $path): void {
    $response = $this->get($path);

    $response->assertOk();

    expect($response->headers->get('Content-Type'))->toContain('text/html');

    // Telefonul e conversia principala: link-ul `tel:` trebuie sa fie pe fiecare pagina.
    $response->assertSee('tel:'.config('energix.contact.phone_href'), false);
})->with([
    'home' => ['/'],
    'servicii' => ['/servicii'],
    'galerie' => ['/galerie'],
    'despre' => ['/despre'],
    'contacte' => ['/contacte'],
    'termeni' => ['/termeni-si-conditii'],
    'confidentialitate' => ['/politica-de-confidentialitate'],
    'cookie' => ['/politica-cookie'],
]);
