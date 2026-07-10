<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->forceHttpsInProduction();
        $this->registerLocalizedUrlMacros();
    }

    /**
     * In productie, orice URL generat e `https://`.
     *
     * `route()` ia schema din CERERE, nu din `APP_URL`. Astazi LiteSpeed serveste
     * direct si pune `HTTPS=on`, deci totul e bine. Dar `advista.marketing`, de pe
     * ACELASI cont Hostinger, ruleaza in spatele Cloudflare — daca energix.md
     * ajunge vreodata acolo, proxy-ul termina TLS si trimite mai departe `http`.
     * Fara `TrustProxies`, Laravel ar genera atunci `canonical`, `hreflang` si
     * `sitemap.xml` cu `http://`, adica exact opusul redirectarilor 301 din
     * `.htaccess`. Un canonical care contrazice redirectarea e cel mai bun mod
     * de a-i spune lui Google sa ignore ambele.
     *
     * NU folosim `trustProxies(at: '*')`: ar face `X-Forwarded-For` demn de
     * incredere, iar `throttle:5,1` de pe formular se cheieaza pe IP — oricine
     * l-ar putea ocoli rotind antetul. Site-ul e https-only oricum (301 + HSTS),
     * deci fortarea schemei e adevarata neconditionat.
     */
    private function forceHttpsInProduction(): void
    {
        if ($this->app->isProduction()) {
            URL::forceScheme('https');
        }
    }

    /**
     * Rutele exista de doua ori: `home` (ro) si `ru.home`. Vederile nu trebuie
     * sa stie asta — cer ruta pe nume, iar macro-ul o rezolva in limba curenta.
     */
    private function registerLocalizedUrlMacros(): void
    {
        URL::macro('localized', function (string $name, array $parameters = []): string {
            /** @var string $locale */
            $locale = app()->getLocale();

            return route(($locale === 'ro' ? '' : $locale.'.').$name, $parameters);
        });

        // Aceeasi pagina, in alta limba — pentru hreflang si comutatorul de limba.
        URL::macro('inLocale', function (string $locale, string $name, array $parameters = []): string {
            return route(($locale === 'ro' ? '' : $locale.'.').$name, $parameters);
        });
    }
}
