<?php

declare(strict_types=1);

use Illuminate\Support\Carbon;

beforeEach(fn () => $this->withoutVite());

/*
|--------------------------------------------------------------------------
| Anul din copyright
|--------------------------------------------------------------------------
*/

it('afiseaza anul curent, nu unul hardcodat', function (): void {
    // Blade scrie entitatea `&copy;`, nu caracterul `©` — cautam ce ajunge in HTML.
    $this->get('/')
        ->assertOk()
        ->assertSee('&copy; '.date('Y').' Energix', escape: false);

    // Anul nu e scris de mana nicaieri in sursa footer-ului.
    expect(file_get_contents(resource_path('views/components/partials/footer.blade.php')))
        ->not->toContain(date('Y').' Energix');
});

it('trece la anul nou la miezul noptii, ora Chisinaului', function (): void {
    /*
     | Fusul aplicatiei e Europe/Chisinau (UTC+2 iarna). Pe UTC, momentul asta ar
     | fi inca 31 decembrie 22:00, deci footer-ul ar fi aratat anul vechi timp de
     | doua ore dupa Revelion.
     */
    Carbon::setTestNow(Carbon::parse('2027-01-01 00:05:00', 'Europe/Chisinau'));

    expect(config('app.timezone'))->toBe('Europe/Chisinau')
        ->and(Carbon::now()->year)->toBe(2027)
        ->and(Carbon::now()->utc()->year)->toBe(2026); // dovada decalajului

    Carbon::setTestNow();
});

/*
|--------------------------------------------------------------------------
| Creditul agentiei
|--------------------------------------------------------------------------
*/

it('scrie „Created by AdVista" identic in ambele limbi', function (string $path): void {
    $html = $this->get($path)->assertOk()->getContent();

    // Text fix: e semnatura agentiei, nu continut traductibil.
    expect($html)->toContain('Created by')
        ->and($html)->toContain('>AdVista</a>')
        ->and($html)->toContain('href="https://advista.marketing"');

    // Nicio urma din traducerile vechi.
    expect($html)->not->toContain('Creat de AdVista')
        ->and($html)->not->toContain('Разработано');
})->with(['/', '/ru']);

/*
|--------------------------------------------------------------------------
| Sigla
|--------------------------------------------------------------------------
*/

it('serveste marca la rezolutia ceruta de slotul de 44px', function (): void {
    // 44 px afisati => 88 px pe retina. mark-64 ar fi iesit moale.
    $this->get('/')
        ->assertOk()
        ->assertSee('images/logo/mark-96.png', escape: false)
        ->assertDontSee('images/logo/mark-64.png', escape: false);

    expect(file_exists(public_path('images/logo/mark-96.png')))->toBeTrue();
});
