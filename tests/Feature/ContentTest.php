<?php

declare(strict_types=1);

/**
 * Integritatea continutului.
 *
 * Structura sta in config/energix.php, textul in lang/{ro,ru}/site.php.
 * Nu exista baza de date si nici admin, deci singura plasa de siguranta
 * impotriva unei cai gresite de imagine sau a unei traduceri lipsa e testul asta.
 */
it('declara exact cele trei segmente de business', function (): void {
    $slugs = array_column(config('energix.services'), 'slug');

    expect($slugs)->toBe(['apartamente', 'case', 'industriale']);
});

it('nu referentiaza imagini inexistente', function (): void {
    $paths = array_merge(
        array_column(config('energix.services'), 'image'),
        array_column(config('energix.gallery'), 'image'),
    );

    $missing = array_values(array_filter(
        $paths,
        fn (string $path): bool => ! file_exists(public_path($path)),
    ));

    expect($missing)->toBe([]);
});

it('foloseste doar categorii de galerie declarate', function (): void {
    $declared = config('energix.gallery_categories');
    $used = array_unique(array_column(config('energix.gallery'), 'category'));

    expect(array_diff($used, $declared))->toBe([]);
});

it('nu afiseaza cifre neverificate', function (): void {
    expect(config('energix.stats_enabled'))->toBeFalse();
});

it('pastreaza datele de contact intacte', function (): void {
    expect(config('energix.contact.phone'))->toBe('+373 68 582 016')
        ->and(config('energix.contact.phone_href'))->toBe('+37368582016')
        ->and(config('energix.contact.email'))->toBe('contact@energix.md');
});

it('are o glifa pentru fiecare retea sociala', function (): void {
    // <x-social-icon> randeaza un <svg> gol daca `icon` nu are un case in @switch.
    $component = file_get_contents(resource_path('views/components/social-icon.blade.php'));

    foreach (config('energix.social') as $network) {
        expect($network)->toHaveKey('icon')
            ->and($component)->toContain("@case('{$network['icon']}')");
    }
});

it('randeaza toate iconitele sociale, cu nume accesibil', function (): void {
    $html = $this->withoutVite()->get('/contacte')->assertOk()->getContent();
    $networks = config('energix.social');

    foreach ($networks as $network) {
        expect($html)->toContain('aria-label="'.$network['name'].'"');
    }

    // De doua ori: coloana de contact + footer. Y-ul din wye e stroke, nu fill.
    expect(substr_count($html, 'fill="currentColor"'))->toBe(count($networks) * 2);
});

/*
|--------------------------------------------------------------------------
| Paritate intre limbi
|--------------------------------------------------------------------------
|
| Un site bilingv se strica tacut: cineva adauga un serviciu sau o intrebare
| in romana si uita rusa. Blade nu crapa — afiseaza cheia bruta, `site.faq.6.q`.
| Testele de mai jos prind exact asta.
|
*/

it('are aceleasi chei de continut in ambele limbi', function (): void {
    $flatten = function (array $array, string $prefix = '') use (&$flatten): array {
        $keys = [];

        foreach ($array as $key => $value) {
            $path = $prefix === '' ? (string) $key : "{$prefix}.{$key}";
            $keys = array_merge($keys, is_array($value) ? $flatten($value, $path) : [$path]);
        }

        return $keys;
    };

    $ro = $flatten(require lang_path('ro/site.php'));
    $ru = $flatten(require lang_path('ru/site.php'));

    expect(array_diff($ro, $ru))->toBe([], 'Chei prezente doar in RO')
        ->and(array_diff($ru, $ro))->toBe([], 'Chei prezente doar in RU');
});

it('traduce fiecare serviciu, etapa si intrebare in ambele limbi', function (string $locale): void {
    app()->setLocale($locale);

    foreach (config('energix.services') as $service) {
        expect(trans("site.services.{$service['slug']}.title"))->not->toStartWith('site.')
            ->and(trans("site.services.{$service['slug']}.features"))->toHaveCount(5);
    }

    expect(trans('site.stages'))->toHaveCount(5)
        ->and(trans('site.faq'))->toHaveCount(6)
        ->and(trans('site.promises'))->toHaveCount(4)
        ->and(trans('site.values'))->toHaveCount(4);

    foreach (config('energix.gallery') as $item) {
        expect(trans("site.gallery.items.{$item['key']}"))->not->toStartWith('site.');
    }

    foreach (config('energix.panel_circuits') as $circuit) {
        expect(trans("site.panel.circuits.{$circuit['key']}"))->not->toStartWith('site.');
    }
})->with(['ro', 'ru']);
