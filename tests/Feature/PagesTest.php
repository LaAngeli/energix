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
    // română
    'home' => ['/'],
    'servicii' => ['/servicii'],
    'galerie' => ['/galerie'],
    'despre' => ['/despre'],
    'contacte' => ['/contacte'],
    'termeni' => ['/termeni-si-conditii'],
    'confidentialitate' => ['/politica-de-confidentialitate'],
    'cookie' => ['/politica-cookie'],

    // русский
    'ru home' => ['/ru'],
    'ru uslugi' => ['/ru/uslugi'],
    'ru raboty' => ['/ru/raboty'],
    'ru o-nas' => ['/ru/o-nas'],
    'ru kontakty' => ['/ru/kontakty'],
    'ru usloviya' => ['/ru/usloviya'],
    'ru konfidencialnost' => ['/ru/konfidencialnost'],
    'ru cookie' => ['/ru/cookie'],
]);

/**
 * Nicio pagina nu trebuie sa afiseze o cheie de traducere neinlocuita
 * (`site.faq.6.q`) — asa se strica tacut un site bilingv.
 */
it('nu randeaza chei de traducere brute', function (string $path): void {
    $html = $this->get($path)->assertOk()->getContent();

    expect($html)->not->toMatch('/>\s*site\.[a-z_.]+\s*</');
})->with(['/', '/servicii', '/galerie', '/despre', '/contacte', '/ru', '/ru/uslugi', '/ru/raboty', '/ru/o-nas', '/ru/kontakty']);

it('comuta limba catre pagina sora, nu catre homepage', function (): void {
    $this->get('/servicii')->assertOk()->assertSee(url('/ru/uslugi'), escape: false);
    $this->get('/ru/uslugi')->assertOk()->assertSee(url('/servicii'), escape: false);
});
