<?php

declare(strict_types=1);

/**
 * Garda pe canonicalizarea domeniului.
 *
 * `public/.htaccess` vine din scheletul Laravel si e regenerat la upgrade-uri.
 * Regulile de mai jos ar disparea tacut, iar `https://www.energix.md/` ar raspunde
 * 200 — doua site-uri identice care isi impart semnalele de ranking.
 *
 * Nu putem executa `.htaccess` din Pest (local ruleaza nginx, prin Herd). Regulile
 * au fost verificate pe un Apache 2.4 real, cu matrice de cazuri; testul apara doar
 * prezenta si ordinea lor.
 */
function htaccess(): string
{
    return file_get_contents(public_path('.htaccess'));
}

it('redirectioneaza www catre domeniul canonic', function (): void {
    expect(htaccess())
        ->toContain('RewriteCond %{HTTP_HOST} ^www\.(.+)$ [NC]')
        ->toContain('RewriteRule ^ https://%1%{REQUEST_URI} [R=301,L]');
});

it('forteaza https, fara sa intre in bucla in spatele unui proxy', function (): void {
    /*
     | Ambele conditii trebuie sa fie adevarate ca sa redirectionam. Daca oricare
     | dintre ele spune „https”, cererea e deja securizata — altfel, in spatele unui
     | proxy care termina TLS, `%{HTTPS}` ar fi mereu `off` si am redirectiona la
     | infinit catre noi insine.
     */
    expect(htaccess())
        ->toContain('RewriteCond %{HTTPS} !=on')
        ->toContain('RewriteCond %{HTTP:X-Forwarded-Proto} !=https')
        ->toContain('RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [R=301,L]');
});

it('nu redirectioneaza provocarile ACME', function (): void {
    // Un 301 pe /.well-known/acme-challenge/ ar rupe reinnoirea certificatului.
    expect(htaccess())->toContain('RewriteCond %{REQUEST_URI} ^/\.well-known/ [NC]');
});

it('canonicalizeaza gazda inainte de a trimite cererea la front controller', function (): void {
    $content = htaccess();

    $hostRule = strpos($content, 'https://%1%{REQUEST_URI}');
    $frontController = strpos($content, 'RewriteRule ^ index.php [L]');

    expect($hostRule)->not->toBeFalse()
        ->and($frontController)->not->toBeFalse()
        // Altfel Laravel ar raspunde 200 pe www, iar redirectul n-ar mai ajunge niciodata.
        ->and($hostRule)->toBeLessThan($frontController);
});

it('nu scrie domeniul in clar in nicio directiva', function (): void {
    // `%1` si `%{HTTP_HOST}` preiau gazda din cerere: regulile supravietuiesc unei mutari.
    $directives = array_filter(
        array_map('trim', explode("\n", htaccess())),
        // Comentariile pot numi domeniul; directivele, nu.
        fn (string $line): bool => $line !== '' && ! str_starts_with($line, '#'),
    );

    foreach ($directives as $line) {
        expect($line)->not->toContain('energix.md');
    }
});

it('genereaza URL-uri https in productie', function (): void {
    /*
     | `route()` ia schema din cerere, nu din `APP_URL`. In spatele unui proxy care
     | termina TLS (advista.marketing, de pe acelasi cont, e in spatele Cloudflare),
     | canonical-ul ar iesi `http://` — exact opusul redirectarii 301 de mai sus.
     */
    expect(file_get_contents(app_path('Providers/AppServiceProvider.php')))
        ->toContain("URL::forceScheme('https')")
        // `trustProxies(at: '*')` ar face `X-Forwarded-For` demn de incredere,
        // iar `throttle:5,1` de pe formular se cheieaza pe IP.
        ->and(file_get_contents(base_path('bootstrap/app.php')))->not->toContain('trustProxies');
});
