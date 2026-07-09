<?php

declare(strict_types=1);

use App\Http\Requests\ContactRequest;
use App\Mail\ContactMessage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

/**
 * Construieste un payload valid de formular.
 *
 * `rendered_at` e un timestamp criptat, imbatranit cu cateva secunde ca sa treaca de
 * garda anti-bot (minim 3s de la randare). Honeypot-ul `website` lipseste intentionat.
 *
 * @param  array<string, mixed>  $overrides
 * @return array<string, mixed>
 */
function energixContactPayload(array $overrides = []): array
{
    return array_merge([
        'name' => 'Popescu',
        'prenume' => 'Ion',
        'phone' => '+373 68 111 222',
        'email' => 'ion@example.md',
        'message' => 'Am nevoie de o priză nouă în bucătărie. Cât ar costa?',
        ContactRequest::TIMESTAMP => encrypt(time() - 5),
    ], $overrides);
}

beforeEach(function (): void {
    // `throttle:5,1` tine contorul in cache. Cu CACHE_STORE=array (phpunit.xml) fiecare
    // test primeste o aplicatie noua cu cache gol, deci contorul nu se scurge intre teste.
    // Golim explicit ca testele sa ramana corecte chiar daca driverul de cache se schimba.
    Cache::flush();
});

it('trimite emailul si confirma pentru un formular valid', function (): void {
    Mail::fake();

    $response = $this->post(route('contact.store'), energixContactPayload());

    Mail::assertSent(
        ContactMessage::class,
        fn (ContactMessage $mail): bool => $mail->hasTo(config('energix.mail_to')),
    );

    $response->assertRedirect();
    $response->assertSessionHas('contact.success');
});

it('respinge cererea cand honeypot-ul e completat', function (): void {
    Mail::fake();

    $response = $this->post(route('contact.store'), energixContactPayload([
        ContactRequest::HONEYPOT => 'http://spam.example',
    ]));

    $response->assertSessionHasErrors(ContactRequest::HONEYPOT);
    Mail::assertNothingSent();
});

it('respinge un formular trimis prea repede', function (): void {
    Mail::fake();

    // encrypt(time()) => 0 secunde scurse, sub pragul de 3s.
    $response = $this->post(route('contact.store'), energixContactPayload([
        ContactRequest::TIMESTAMP => encrypt(time()),
    ]));

    $response->assertSessionHasErrors('message');
    Mail::assertNothingSent();
});

it('respinge un timestamp care nu se poate decripta', function (): void {
    Mail::fake();

    $response = $this->post(route('contact.store'), energixContactPayload([
        ContactRequest::TIMESTAMP => 'garbage',
    ]));

    $response->assertSessionHasErrors('message');
    Mail::assertNothingSent();
});

it('cere campurile obligatorii', function (): void {
    Mail::fake();

    // phone, email si message lipsesc; restul e valid ca sa izolam erorile pe ele.
    $response = $this->post(route('contact.store'), [
        'name' => 'Popescu',
        'prenume' => 'Ion',
        ContactRequest::TIMESTAMP => encrypt(time() - 5),
    ]);

    $response->assertSessionHasErrors(['phone', 'email', 'message']);
    Mail::assertNothingSent();
});

it('nu da 500 cand SMTP-ul pica, ci anunta userul', function (): void {
    // Mail::fake() nu poate simula o eroare de transport, iar MAIL_MAILER=array nu arunca.
    // Fortam `Mail::to(...)->send(...)` sa arunce, exact ca un SMTP picat, ca sa verificam
    // ca eroarea e prinsa in controller si intoarce back(), nu un 500.
    Mail::shouldReceive('to->send')->andThrow(new RuntimeException('SMTP down'));

    $response = $this->post(route('contact.store'), energixContactPayload());

    $response->assertRedirect();
    $response->assertSessionHas('contact.error');
    expect($response->getStatusCode())->toBe(302);
});

it('blocheaza a sasea trimitere intr-un minut (throttle:5,1)', function (): void {
    Mail::fake();

    // Primele cinci trec de throttle (indiferent de rezultatul validarii).
    for ($i = 0; $i < 5; $i++) {
        $this->post(route('contact.store'), energixContactPayload());
    }

    // A sasea e blocata de middleware inainte sa ajunga la controller.
    $this->post(route('contact.store'), energixContactPayload())
        ->assertStatus(429);
});
