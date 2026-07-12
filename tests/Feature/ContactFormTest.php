<?php

declare(strict_types=1);

use App\Http\Requests\ContactRequest;
use App\Mail\ContactMessage;
use App\Mail\ContactThankYou;
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

it('trimite notificarea catre firma si confirma pentru un formular valid', function (): void {
    Mail::fake();

    $response = $this->post(route('contact.store'), energixContactPayload());

    Mail::assertSent(
        ContactMessage::class,
        fn (ContactMessage $mail): bool => $mail->hasTo(config('energix.mail_to')),
    );

    $response->assertRedirect();
    $response->assertSessionHas('contact.success');
});

it('trimite si confirmarea inapoi la client', function (): void {
    Mail::fake();

    $this->post(route('contact.store'), energixContactPayload(['email' => 'ion@example.md']));

    // Doua emailuri: notificarea catre firma + confirmarea catre client.
    Mail::assertSent(ContactThankYou::class, fn (ContactThankYou $mail): bool => $mail->hasTo('ion@example.md'));
    Mail::assertSent(ContactMessage::class);
});

it('confirmarea care pica NU pierde lead-ul si NU sperie userul', function (): void {
    /*
     | Notificarea (prima) pleaca; confirmarea (a doua) arunca. Userul trebuie sa
     | vada tot succes — mesajul LUI a ajuns la firma. `to()` intoarce mockul (self),
     | iar `send()` arunca doar pentru confirmare.
     */
    Mail::shouldReceive('to')->andReturnSelf();
    Mail::shouldReceive('send')->andReturnUsing(function ($mailable): void {
        if ($mailable instanceof ContactThankYou) {
            throw new RuntimeException('mailbox full');
        }
    });

    $response = $this->post(route('contact.store'), energixContactPayload());

    $response->assertRedirect();
    $response->assertSessionHas('contact.success');
});

it('foloseste subiectul de business, nu „Cerere noua de pe energix.md”', function (): void {
    // Subiectul vine din `__()`, care citeste locala aplicatiei (implicit `ro`).
    $mail = new ContactMessage(energixContactPayload());

    $mail->assertHasSubject('Cerere de ofertă — Ion Popescu');
    expect($mail->render())->not->toContain('Cerere nouă de pe energix.md');
});

it('randeaza emailurile in limba de trimitere', function (): void {
    app()->setLocale('ru');

    // Notificarea RU: subiect si continut in rusa.
    (new ContactMessage(energixContactPayload()))->assertHasSubject('Заявка на смету — Ion Popescu');

    // Confirmarea RU la fel.
    $thanks = new ContactThankYou(energixContactPayload());
    $thanks->assertHasSubject(trans('site.email.thanks.subject', [], 'ru'));
    expect($thanks->render())->toContain(trans('site.email.thanks.team', [], 'ru'));

    app()->setLocale('ro');
});

it('pune semnatura Energix in ambele emailuri', function (): void {
    foreach ([new ContactMessage(energixContactPayload()), new ContactThankYou(energixContactPayload())] as $mail) {
        $html = $mail->render();

        expect($html)->toContain(config('energix.contact.phone'))
            ->and($html)->toContain('energix.md')
            ->and($html)->toContain(trans('site.email.sig.tagline', [], 'ro'));
    }
});

it('nu interpreteaza continutul mesajului ca HTML in email', function (): void {
    // Un `<script>` din mesaj trebuie sa ramana text escapat, nu cod.
    $mail = new ContactMessage(energixContactPayload([
        'message' => 'Salut <script>alert(1)</script>',
    ]));

    expect($mail->render())
        ->toContain('&lt;script&gt;')
        ->not->toContain('<script>alert(1)</script>');
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

/*
|--------------------------------------------------------------------------
| Continut: nume, telefon, mesaj
|--------------------------------------------------------------------------
|
| Regresie: numele era doar `required|string|min:2` — „Ion123” sau „12345”
| treceau, iar telefonul accepta „----------” (charset fara nicio cifra).
|
*/

it('respinge nume care nu sunt nume', function (string $field, string $value): void {
    Mail::fake();

    $response = $this->post(route('contact.store'), energixContactPayload([$field => $value]));

    $response->assertSessionHasErrors($field);
    Mail::assertNothingSent();
})->with([
    'cifre in nume' => ['name', 'Ion123'],
    'doar cifre' => ['name', '12345'],
    'simboluri' => ['name', 'Ion@Popescu'],
    'cratime dublate' => ['name', 'Ana--Maria'],
    'cratima la margine' => ['name', '-Ion'],
    'cifre in prenume' => ['prenume', 'Maria2'],
]);

it('accepta nume reale, cu diacritice si chirilice', function (string $name, string $prenume): void {
    Mail::fake();

    $this->post(route('contact.store'), energixContactPayload([
        'name' => $name,
        'prenume' => $prenume,
    ]))->assertSessionHasNoErrors();

    Mail::assertSent(ContactMessage::class);
})->with([
    'diacritice romanesti' => ['Țurcanu', 'Ștefan'],
    'nume compus' => ['Popescu-Tăriceanu', 'Ana-Maria'],
    'chirilic' => ['Пётр', 'Александрович'],
    'apostrof' => ["O'Brien", 'Sean'],
]);

it('respinge telefoane fara cifre reale', function (string $phone): void {
    Mail::fake();

    $response = $this->post(route('contact.store'), energixContactPayload(['phone' => $phone]));

    $response->assertSessionHasErrors('phone');
    Mail::assertNothingSent();
})->with([
    'doar separatori' => ['----------'],
    'paranteze goale' => ['+() - ()'],
    'prea putine cifre' => ['069 12'],
    'litere' => ['telefon 069'],
]);

it('respinge mesajele cu linkuri — semnatura spamului', function (string $message): void {
    Mail::fake();

    $response = $this->post(route('contact.store'), energixContactPayload(['message' => $message]));

    $response->assertSessionHasErrors('message');
    Mail::assertNothingSent();
})->with([
    'http' => ['Va rog vizitati http://spam.example pentru oferte tari'],
    'https' => ['Buna ziua, castigati bani aici: https://spam.example/win acum'],
    'www' => ['Detalii pe www.spam.example despre produsele noastre minune'],
    'bbcode' => ['Cumpara [url=spam.example]aici[/url] tot ce vrei ieftin'],
]);

/*
|--------------------------------------------------------------------------
| Erori in limba paginii
|--------------------------------------------------------------------------
*/

it('afiseaza erorile de validare in limba paginii', function (): void {
    Mail::fake();

    // Pe ruta RO, eroarea vine din lang/ro.
    $this->post(route('contact.store'), energixContactPayload(['name' => 'Ion123']))
        ->assertSessionHasErrors(['name' => trans('site.form.errors.name_format', [], 'ro')]);

    // Pe ruta RU, ACEEASI greseala primeste mesajul rusesc.
    $this->post(route('ru.contact.store'), energixContactPayload(['name' => 'Ion123']))
        ->assertSessionHasErrors(['name' => trans('site.form.errors.name_format', [], 'ru')]);
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
