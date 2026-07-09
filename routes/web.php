<?php

declare(strict_types=1);

use App\Http\Controllers\ContactController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Paginile publice
|--------------------------------------------------------------------------
*/

Route::view('/', 'pages.home')->name('home');
Route::view('/servicii', 'pages.services')->name('services');
Route::view('/galerie', 'pages.gallery')->name('gallery');
Route::view('/despre', 'pages.about')->name('about');
Route::view('/contacte', 'pages.contact')->name('contact');

Route::view('/termeni-si-conditii', 'pages.legal.terms')->name('legal.terms');
Route::view('/politica-de-confidentialitate', 'pages.legal.privacy')->name('legal.privacy');
Route::view('/politica-cookie', 'pages.legal.cookies')->name('legal.cookies');

/*
|--------------------------------------------------------------------------
| Formularul de contact
|--------------------------------------------------------------------------
|
| `throttle:5,1` — cinci trimiteri pe minut, per IP. Site-ul vechi nu avea
| nicio limita si putea fi folosit ca relay de spam.
|
*/

Route::post('/contacte', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.store');

/*
|--------------------------------------------------------------------------
| SEO
|--------------------------------------------------------------------------
*/

// Controller invocabil, nu closure: closure-urile rup `php artisan route:cache`.
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

/*
|--------------------------------------------------------------------------
| Redirect-uri 301 de la site-ul static vechi
|--------------------------------------------------------------------------
|
| Fara ele pierdem indexarea existenta. Vezi .claude/context/DEPLOY-HOSTINGER.md.
| Atentie: vechiul URL de galerie continea typo-ul „galery”.
|
*/

$legacy = [
    '/index.html' => '/',
    '/services.html' => '/servicii',
    '/galery.html' => '/galerie',
    '/about.html' => '/despre',
    '/contacts.html' => '/contacte',
    '/terms_conditions.html' => '/termeni-si-conditii',
    '/privacy_policy.html' => '/politica-de-confidentialitate',
    '/cookie_policy.html' => '/politica-cookie',
];

foreach ($legacy as $old => $new) {
    Route::redirect($old, $new, 301);
}
