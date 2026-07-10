<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Energix — sursa unica de adevar pentru continut si date de contact
|--------------------------------------------------------------------------
|
| Site de prezentare, fara baza de date. Continutul editabil sta aici, nu in
| Blade. Datele de contact sunt documentate in .claude/context/CONTACT.md.
|
| EXCLUDERI OBLIGATORII (.claude/context/BUSINESS.md): nicaieri in acest fisier
| sau in vreo vedere nu apar afirmatii de certificare/autorizare, serviciul
| „Smart Home” / orice „automatizare”, sau „Audit Energetic”.
|
*/

return [

    'contact' => [
        'phone' => '+373 68 582 016',
        'phone_href' => '+37368582016',
        'email' => 'contact@energix.md',
        'city' => 'Chișinău',
        'country' => 'Republica Moldova',
        'country_code' => 'MD',
        'area_served' => 'Republica Moldova',
    ],

    /*
     | Destinatarul formularului de contact. In productie vine din .env,
     | impreuna cu credentialele SMTP. Niciodata hardcodat un secret aici.
     */
    'mail_to' => env('MAIL_TO_ADDRESS', 'contact@energix.md'),

    'hours' => [
        ['days' => 'Luni – Vineri', 'time' => '08:00 – 20:00'],
        ['days' => 'Sâmbătă', 'time' => '09:00 – 17:00'],
        ['days' => 'Duminică', 'time' => 'Închis'],
    ],

    'emergency' => 'Intervenții urgente: 24/7',

    /*
     | Site-ul vechi se contrazicea: homepage promitea „răspuns în 1 oră”, formularul
     | „maxim 24 de ore”. O singura valoare, folosita peste tot.
     */
    'response_time' => 'În aceeași zi la urgențe. Altfel, te sunăm în cel mult 24 de ore.',

    /*
     | Raspundere cu nume. Nu e o afirmatie de certificare — e o persoana care isi pune
     | numele pe lucrare, ceea ce conteaza mai mult la B2C decat o insigna.
     | De confirmat cu clientul: „Sandulescu” sau „Săndulescu”?
     */
    'founder' => [
        'name' => 'Dima Sandulescu',
        'role' => 'Fondator și electrician principal',
    ],

    /*
     | Cele trei intrebari pe care si le pune orice client inainte sa sune.
     | Raspunsuri calitative, fara cifre inventate.
     */
    'answers' => [
        ['q' => 'Cât costă?', 'a' => 'Evaluare gratuită, apoi preț fix în ofertă.'],
        ['q' => 'Cât durează?', 'a' => 'Termen clar, în scris, înainte să începem.'],
        ['q' => 'Veniți azi?', 'a' => 'Da — la urgențe, non-stop.'],
    ],

    'social' => [
        ['name' => 'Facebook', 'url' => 'https://facebook.com/profile.php?id=61567185351755'],
        ['name' => 'Instagram', 'url' => 'https://instagram.com/energix_electrician_moldova_/'],
        ['name' => 'Telegram', 'url' => 'https://t.me/energix_md'],
        ['name' => 'WhatsApp', 'url' => 'https://wa.me/37368582016'],
        ['name' => 'Viber', 'url' => 'viber://chat?number=37368582016'],
    ],

    /*
     | Trei servicii. Erau cinci pe site-ul vechi; „Smart Home” si
     | „Audit Energetic” au fost excluse de client.
     */
    'services' => [
        [
            'slug' => 'rezidentiale',
            'title' => 'Instalații rezidențiale',
            'tagline' => 'Case și apartamente, de la zero sau la renovare.',
            'intro' => 'Punem la punct instalația electrică a locuinței: tablou, circuite, prize, iluminat. Lucrăm curat și lăsăm în urmă o schemă pe care o înțelege orice electrician care vine după noi.',
            'features' => [
                'Montaj și configurare tablou electric',
                'Prize, întrerupătoare și corpuri de iluminat',
                'Cablare completă, conform normelor',
                'Verificări și măsurători la final',
            ],
            'image' => 'images/content/img1.webp',
        ],
        [
            'slug' => 'industriale',
            'title' => 'Instalații industriale',
            'tagline' => 'Spații comerciale, hale, depozite.',
            'intro' => 'Proiectăm și executăm instalații pentru spații comerciale și industriale, de la tabloul de distribuție până la alimentarea utilajelor.',
            'features' => [
                'Tablouri de distribuție',
                'Cablare și alimentare utilaje',
                'Monitorizare consum',
                'Punere în funcțiune și documentație',
            ],
            'image' => 'images/content/img4.webp',
        ],
        [
            'slug' => 'reparatii',
            'title' => 'Reparații și mentenanță',
            'tagline' => 'Când s-a stricat. Inclusiv noaptea.',
            'intro' => 'Găsim defectul, îți spunem ce l-a cauzat și îl reparăm. Pentru instalațiile pe care le întreținem periodic, ajungem înainte să se strice.',
            'features' => [
                'Intervenții urgente, 24/7',
                'Diagnosticare cu aparat, nu din ochi',
                'Reparații cu materiale de calitate',
                'Mentenanță preventivă programată',
            ],
            'image' => 'images/content/img5_flipped.webp',
        ],
    ],

    /*
     | Pasii au ordine reala, deci numerotarea lor poarta informatie.
     */
    'process' => [
        ['title' => 'Ne suni', 'body' => 'Ne spui ce ai nevoie. Dacă e urgent, venim azi.'],
        ['title' => 'Venim și ne uităm', 'body' => 'Evaluăm la fața locului. Deplasarea pentru evaluare e gratuită.'],
        ['title' => 'Primești oferta', 'body' => 'Preț fix, în scris, cu termen. Nu apar costuri la final.'],
        ['title' => 'Executăm', 'body' => 'Lucrăm curat, în termenul stabilit, și lăsăm în urmă documentația.'],
    ],

    /*
     | Fapte verificabile, nu insigne. Inlocuiesc semnalul de incredere pierdut
     | odata cu eliminarea afirmatiei „electrician autorizat”.
     */
    'promises' => [
        ['label' => 'Garanție pentru lucrări', 'body' => 'Dacă cedează ceva din ce am montat, ne întoarcem pe cheltuiala noastră.'],
        ['label' => 'Preț fix în ofertă', 'body' => 'Prețul din ofertă este prețul final. Fără costuri descoperite pe parcurs.'],
        ['label' => 'Intervenții urgente 24/7', 'body' => 'Pentru pene de curent și defecte periculoase, răspundem non-stop.'],
        ['label' => 'Evaluare gratuită', 'body' => 'Venim, ne uităm, îți spunem ce e de făcut. Fără obligații.'],
    ],

    'values' => [
        ['title' => 'Siguranță', 'body' => 'Nu improvizăm. O instalație electrică greșită nu se vede până când e prea târziu.'],
        ['title' => 'Transparență', 'body' => 'Îți arătăm ce am găsit, îți explicăm de ce trebuie schimbat și cât costă.'],
        ['title' => 'Curățenie', 'body' => 'Plecăm din casa ta lăsând-o cum am găsit-o. Fără moloz, fără praf.'],
        ['title' => 'Punctualitate', 'body' => 'Dacă am zis ora zece, la ora zece suntem acolo.'],
    ],

    /*
     | Galerie de umplutura pana la fotografii reale ale lucrarilor.
     | Vezi intrebarea deschisa din .claude/context/BUSINESS.md.
     */
    'gallery' => [
        ['image' => 'images/content/img1.webp', 'title' => 'Instalație completă apartament', 'category' => 'rezidentiale'],
        ['image' => 'images/content/img3.webp', 'title' => 'Cablare casă nouă', 'category' => 'rezidentiale'],
        ['image' => 'images/content/img6.webp', 'title' => 'Instalație vilă', 'category' => 'rezidentiale'],
        ['image' => 'images/content/img4.webp', 'title' => 'Tablou de distribuție', 'category' => 'industriale'],
        ['image' => 'images/content/img7.webp', 'title' => 'Cablare hală de producție', 'category' => 'industriale'],
        ['image' => 'images/content/img9.webp', 'title' => 'Instalație spațiu comercial', 'category' => 'industriale'],
        ['image' => 'images/content/img5_flipped.webp', 'title' => 'Modernizare tablou electric', 'category' => 'reparatii'],
        ['image' => 'images/content/img8.webp', 'title' => 'Remediere defect de circuit', 'category' => 'reparatii'],
        ['image' => 'images/content/img2.webp', 'title' => 'Înlocuire corpuri de iluminat', 'category' => 'reparatii'],
    ],

    'gallery_categories' => [
        'toate' => 'Toate',
        'rezidentiale' => 'Rezidențiale',
        'industriale' => 'Industriale',
        'reparatii' => 'Reparații',
    ],

    /*
     | Cifrele de pe site-ul vechi sunt NEVERIFICATE si se contrazic intre pagini
     | (8 vs 10+ ani). Nu se afiseaza pana la confirmarea clientului.
     | Vezi .claude/context/BUSINESS.md, intrebarea deschisa 2.
     */
    'stats_enabled' => false,
    'stats' => [],

    /*
     | Identificator Google Tag Manager. Se initializeaza DOAR dupa acceptul
     | din banner-ul de cookie. Site-ul vechi il incarca inainte de consimtamant.
     */
    'gtm_id' => env('GTM_ID', 'GTM-K3K2BR3B'),

    /*
     | SEO centralizat: titlurile si descrierile nu se hardcodeaza in Blade.
     */
    'seo' => [
        'site_name' => 'Energix',
        'canonical' => 'https://energix.md',
        'og_image' => 'images/logo/logo_transparent.webp',
        'locale' => 'ro_RO',

        'pages' => [
            'home' => [
                'title' => 'Energix — instalații electrice în Chișinău și toată Moldova',
                'description' => 'Instalații electrice, reparații și mentenanță pentru case, apartamente și spații comerciale. Garanție pentru lucrări, intervenții urgente 24/7, preț fix în ofertă.',
            ],
            'services' => [
                'title' => 'Servicii — instalații, reparații și mentenanță electrică | Energix',
                'description' => 'Instalații electrice rezidențiale și industriale, reparații și mentenanță. Electrician în Chișinău și în toată Republica Moldova.',
            ],
            'gallery' => [
                'title' => 'Tipuri de lucrări | Energix',
                'description' => 'Tipurile de lucrări electrice pe care le executăm: instalații rezidențiale, industriale, reparații și mentenanță.',
            ],
            'about' => [
                'title' => 'Despre noi | Energix',
                'description' => 'Cine suntem, cum lucrăm și după ce principii. Servicii electrice în Chișinău și toată Moldova.',
            ],
            'contact' => [
                'title' => 'Contact — cere o ofertă | Energix',
                'description' => 'Sună la +373 68 582 016 sau completează formularul. Evaluare gratuită, preț fix în ofertă, intervenții urgente 24/7.',
            ],
            'legal.terms' => [
                'title' => 'Termeni și condiții | Energix',
                'description' => 'Termenii și condițiile de utilizare a site-ului energix.md.',
            ],
            'legal.privacy' => [
                'title' => 'Politica de confidențialitate | Energix',
                'description' => 'Cum colectăm, folosim și protejăm datele tale personale.',
            ],
            'legal.cookies' => [
                'title' => 'Politica de cookie | Energix',
                'description' => 'Ce cookie-uri folosim pe energix.md și cum îți poți exprima consimțământul.',
            ],
            '404' => [
                'title' => 'Pagina nu a fost găsită | Energix',
                'description' => 'Pagina căutată nu există sau s-a mutat.',
            ],
        ],
    ],

];
