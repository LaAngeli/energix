<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Header-ele de securitate pe care le avea si site-ul vechi in `.htaccess`.
 *
 * In Laravel stau aici, nu in `.htaccess`: raman valabile indiferent de serverul web
 * si sunt vizibile in cod, nu ingropate intr-un fisier de configurare a hostului.
 * Vezi .claude/context/DEPLOY-HOSTINGER.md.
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        /*
         | Clickjacking: un site strain nu poate incadra formularul intr-un iframe.
         | SAMEORIGIN, nu DENY: incadrarea de pe acelasi domeniu ramane permisa (utila,
         | si e politica pe care o avea si site-ul vechi), iar protectia e aceeasi —
         | atacul vine intotdeauna dintr-o alta origine.
         */
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Fara MIME sniffing: browserul respecta Content-Type-ul declarat.
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Nu scurgem calea completa catre site-urile externe pe care le linkam.
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Nu cerem nimic din astea. Le refuzam explicit.
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), interest-cohort=()');

        /*
         | HSTS doar in productie si doar pe HTTPS. Trimis pe local ar bloca
         | accesul http:// la alte proiecte de pe aceeasi masina.
         */
        if ($request->secure() && app()->isProduction()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }
}
