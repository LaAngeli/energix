<?php

declare(strict_types=1);

/**
 * Integritatea continutului din config/energix.php.
 *
 * Continutul nu are baza de date si nici admin, deci singura plasa de siguranta
 * impotriva unei cai gresite de imagine sau a unui serviciu sters din greseala
 * este testul asta.
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
    $declared = array_keys(config('energix.gallery_categories'));
    $used = array_unique(array_column(config('energix.gallery'), 'category'));

    expect(array_diff($used, $declared))->toBe([]);
});

it('nu afiseaza cifre neverificate', function (): void {
    // Site-ul vechi se contrazicea: homepage „8 ani”, pagina Despre „peste 10 ani”.
    // Raman ascunse pana la confirmarea clientului.
    expect(config('energix.stats_enabled'))->toBeFalse();
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

    /*
     | Glife desenate, nu pastile de text. Pagina de contact le arata de doua ori:
     | o data in coloana de date, o data in footer. (Y-ul din <x-signature.wye> e
     | stroke, nu fill, deci nu intra la numaratoare.)
     */
    expect(substr_count($html, 'fill="currentColor"'))->toBe(count($networks) * 2);
});

it('pastreaza datele de contact intacte', function (): void {
    expect(config('energix.contact.phone'))->toBe('+373 68 582 016')
        ->and(config('energix.contact.phone_href'))->toBe('+37368582016')
        ->and(config('energix.contact.email'))->toBe('contact@energix.md')
        ->and(config('energix.contact.city'))->toBe('Chișinău');
});
