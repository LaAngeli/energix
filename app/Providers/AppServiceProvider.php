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
        $this->registerLocalizedUrlMacros();
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
