<?php

declare(strict_types=1);

beforeEach(fn () => $this->withoutVite());

it('trimite header-ele de securitate pe fiecare raspuns', function (): void {
    $response = $this->get('/');

    $response->assertOk()
        ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
        ->assertHeader('X-Content-Type-Options', 'nosniff')
        ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
});

it('nu trimite HSTS in afara productiei', function (): void {
    // Trimis pe local ar bloca accesul http:// la alte proiecte de pe aceeasi masina.
    $this->get('/')->assertOk()->assertHeaderMissing('Strict-Transport-Security');
});
