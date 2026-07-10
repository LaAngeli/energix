<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Energix — structura site-ului (fara text)
|--------------------------------------------------------------------------
|
| Site bilingv RO + RU. TOT textul sta in `lang/ro/site.php` si `lang/ru/site.php`.
| Aici raman doar lucrurile care NU se traduc: date de contact, cai de imagini,
| slug-uri, amperaje, program numeric, identificatori.
|
| CE VINDE BUSINESS-UL: instalatii electrice COMPLETE, de la zero, in constructii —
| toate etapele, pentru apartamente, case si spatii industriale.
|
| EXCLUDERI OBLIGATORII (.claude/context/BUSINESS.md), in AMBELE limbi:
|   - orice afirmatie de certificare / autorizare;
|   - „Smart Home” si orice „automatizare”;
|   - „Audit Energetic”;
|   - reparatii izolate, mentenanta, interventii urgente / non-stop.
| Garda: tests/Feature/ContentExclusionsTest.php (radacini RO si RU).
|
*/

return [

    'locales' => ['ro', 'ru'],
    'fallback_locale' => 'ro',

    'contact' => [
        'phone' => '+373 68 582 016',
        'phone_href' => '+37368582016',
        'email' => 'contact@energix.md',
        'country_code' => 'MD',
    ],

    /*
     | Destinatarul formularului de contact. In productie vine din .env,
     | impreuna cu credentialele SMTP. Niciodata hardcodat un secret aici.
     */
    'mail_to' => env('MAIL_TO_ADDRESS', 'contact@energix.md'),

    /*
     | Programul, in forma numerica. Zilele traduse stau in lang.
     | Index 0 = duminica (ca `Date.getDay()` din JS, folosit de instrumentul
     | „Starea liniei” din hero-ul paginii de contact).
     */
    'schedule' => [
        0 => null,
        1 => [8, 20],
        2 => [8, 20],
        3 => [8, 20],
        4 => [8, 20],
        5 => [8, 20],
        6 => [9, 17],
    ],

    /*
     | Semnalul de incredere care inlocuieste numele fondatorului (eliminat la
     | cererea clientului) si afirmatia de certificare (exclusa).
     */
    'experience_years' => 10,

    'social' => [
        ['name' => 'Facebook', 'icon' => 'facebook', 'url' => 'https://facebook.com/profile.php?id=61567185351755'],
        ['name' => 'Instagram', 'icon' => 'instagram', 'url' => 'https://instagram.com/energix_electrician_moldova_/'],
        ['name' => 'Telegram', 'icon' => 'telegram', 'url' => 'https://t.me/energix_md'],
        ['name' => 'WhatsApp', 'icon' => 'whatsapp', 'url' => 'https://wa.me/37368582016'],
        ['name' => 'Viber', 'icon' => 'viber', 'url' => 'viber://chat?number=37368582016'],
    ],

    /*
     | Cele trei segmente. Textul lor: lang `site.services.{slug}`.
     */
    'services' => [
        ['slug' => 'apartamente', 'image' => 'images/content/img1.webp'],
        ['slug' => 'case', 'image' => 'images/content/img6.webp'],
        ['slug' => 'industriale', 'image' => 'images/content/img4.webp'],
    ],

    /*
     | Circuitele tabloului interactiv din hero. Amperaje reale per tip de circuit —
     | tabloul e demonstratia de competenta, deci datele sunt corecte.
     | Numele: lang `site.panel.circuits.{key}`.
     */
    'panel_circuits' => [
        ['key' => 'lighting', 'amps' => '10 A', 'icon' => 'bulb'],
        ['key' => 'sockets', 'amps' => '16 A', 'icon' => 'socket'],
        ['key' => 'kitchen', 'amps' => '20 A', 'icon' => 'stove'],
        ['key' => 'boiler', 'amps' => '16 A', 'icon' => 'boiler'],
        ['key' => 'ac', 'amps' => '16 A', 'icon' => 'ac'],
        ['key' => 'power', 'amps' => '25 A', 'icon' => 'motor'],
    ],

    /*
     | Consumatorii din calculatorul de circuite (hero /servicii).
     | Etichetele: lang `site.calc.loads.{key}`.
     */
    'calc_loads' => [
        ['key' => 'hob', 'amps' => '20 A'],
        ['key' => 'oven', 'amps' => '16 A'],
        ['key' => 'boiler', 'amps' => '16 A'],
        ['key' => 'ac', 'amps' => '16 A'],
        ['key' => 'washer', 'amps' => '16 A'],
    ],

    /*
     | Galerie de umplutura pana la fotografii reale ale lucrarilor.
     | Titlurile: lang `site.gallery.items.{key}`.
     */
    'gallery' => [
        ['key' => 'flat_full', 'image' => 'images/content/img1.webp', 'category' => 'apartamente'],
        ['key' => 'flat_light', 'image' => 'images/content/img2.webp', 'category' => 'apartamente'],
        ['key' => 'flat_wiring', 'image' => 'images/content/img3.webp', 'category' => 'apartamente'],
        ['key' => 'house_full', 'image' => 'images/content/img6.webp', 'category' => 'case'],
        ['key' => 'house_panel', 'image' => 'images/content/img5_flipped.webp', 'category' => 'case'],
        ['key' => 'house_site', 'image' => 'images/content/img8.webp', 'category' => 'case'],
        ['key' => 'ind_panel', 'image' => 'images/content/img4.webp', 'category' => 'industriale'],
        ['key' => 'ind_trays', 'image' => 'images/content/img7.webp', 'category' => 'industriale'],
        ['key' => 'ind_retail', 'image' => 'images/content/img9.webp', 'category' => 'industriale'],
    ],

    'gallery_categories' => ['toate', 'apartamente', 'case', 'industriale'],

    /*
     | Cifrele de pe site-ul vechi (500/350/50) raman NEVERIFICATE si ascunse.
     | Singura confirmata de client: vechimea.
     */
    'stats_enabled' => false,

    /*
     | Identificator Google Tag Manager. Se initializeaza DOAR dupa acceptul
     | din banner-ul de cookie. Site-ul vechi il incarca inainte de consimtamant.
     */
    'gtm_id' => env('GTM_ID', 'GTM-K3K2BR3B'),

    /*
     | Canonical, hreflang si sitemap se genereaza toate din tabela de rute, deci
     | din `APP_URL`. O singura sursa de adevar pentru domeniu.
     */
    'seo' => [
        'site_name' => 'Energix',
        'og_image' => 'images/logo/logo_transparent.webp',
    ],

    /*
     | Local SEO / GEO: zonele deservite, folosite si in JSON-LD (`areaServed`)
     | si in blocul „Unde lucram” de pe homepage.
     */
    'areas' => [
        'sectors' => ['Botanica', 'Buiucani', 'Centru', 'Ciocana', 'Râșcani'],
        'cities' => ['Chișinău', 'Bălți', 'Orhei', 'Ialoveni', 'Strășeni', 'Anenii Noi', 'Codru', 'Durlești'],
    ],

];
