<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Fixeaza limba aplicatiei din prefixul rutei.
 *
 * Limba NU se ghiceste din `Accept-Language`: fiecare limba are URL-ul ei
 * (`/servicii` vs `/ru/uslugi`), iar redirectarea automata dupa browser ar
 * sparge indexarea — Googlebot crawleaza cu un singur set de headere.
 */
class SetLocale
{
    public function handle(Request $request, Closure $next, string $locale = 'ro'): Response
    {
        app()->setLocale($locale);

        return $next($request);
    }
}
