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
| CE VINDE BUSINESS-UL (decis de client 2026-07-10): instalatii electrice
| COMPLETE, de la zero, in constructii — toate etapele, pentru apartamente,
| case si spatii industriale.
|
| EXCLUDERI OBLIGATORII (.claude/context/BUSINESS.md):
|   - orice afirmatie de certificare / autorizare;
|   - „Smart Home” si orice „automatizare”;
|   - „Audit Energetic”;
|   - reparatii izolate, mentenanta, interventii urgente / non-stop / 24-din-7,
|     depanari — business-ul NU face service, doar instalatii de la zero.
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

    /*
     | O singura promisiune de raspuns, folosita peste tot. Fara referiri la
     | interventii rapide: nu facem service, facem proiecte.
     */
    'response_time' => 'Te sunăm înapoi în aceeași zi lucrătoare.',

    /*
     | Semnalul de incredere care inlocuieste numele fondatorului (eliminat la
     | cererea clientului) si afirmatia de certificare (exclusa). 10 ani —
     | singura cifra confirmata de client.
     */
    'experience' => [
        'years' => 10,
        'label' => 'ani de instalații electrice',
    ],

    /*
     | Cele trei intrebari pe care si le pune orice client inainte sa sune.
     | Raspunsuri calitative, fara cifre inventate.
     */
    'answers' => [
        ['q' => 'Cât costă?', 'a' => 'Evaluare gratuită, apoi preț fix în ofertă.'],
        ['q' => 'Cât durează?', 'a' => 'Termen clar, în scris, înainte să începem.'],
        ['q' => 'Când puteți începe?', 'a' => 'De regulă în aceeași săptămână. Data exactă o stabilim la telefon.'],
    ],

    'social' => [
        ['name' => 'Facebook', 'url' => 'https://facebook.com/profile.php?id=61567185351755'],
        ['name' => 'Instagram', 'url' => 'https://instagram.com/energix_electrician_moldova_/'],
        ['name' => 'Telegram', 'url' => 'https://t.me/energix_md'],
        ['name' => 'WhatsApp', 'url' => 'https://wa.me/37368582016'],
        ['name' => 'Viber', 'url' => 'viber://chat?number=37368582016'],
    ],

    /*
     | Un singur serviciu — instalatia electrica completa — pentru trei tipuri
     | de spatii. Fiecare intrare descrie acelasi ciclu complet, adaptat.
     */
    'services' => [
        [
            'slug' => 'apartamente',
            'title' => 'Instalații electrice pentru apartamente',
            'tagline' => 'Bloc nou sau renovare completă.',
            'intro' => 'Executăm instalația apartamentului cap-coadă: schema pe circuite, traseele, tabloul, prizele și iluminatul. Un singur responsabil pentru toate etapele.',
            'features' => [
                'Schema instalației și împărțirea pe circuite',
                'Trasee, doze și cablare completă',
                'Tabloul electric, echipat și etichetat',
                'Prize, întrerupătoare, corpuri de iluminat',
                'Verificări, măsurători, punere sub tensiune',
            ],
            'image' => 'images/content/img1.webp',
        ],
        [
            'slug' => 'case',
            'title' => 'Instalații electrice pentru case',
            'tagline' => 'De la fundație până la predare.',
            'intro' => 'O casă are branșament, exterior, etaje și consumatori mari. Proiectăm și montăm instalația completă, cu împământare și măsurători la predare.',
            'features' => [
                'Proiectul instalației, pe etaje și exterior',
                'Racord de la branșament la tabloul general',
                'Cablare interioară și exterioară',
                'Consumatori mari: plită, boiler, climatizare',
                'Împământare, măsurători, predare cu schemă',
            ],
            'image' => 'images/content/img6.webp',
        ],
        [
            'slug' => 'industriale',
            'title' => 'Instalații pentru spații industriale',
            'tagline' => 'Hale, depozite, spații comerciale.',
            'intro' => 'Distribuția completă a spațiului de lucru: tablouri, trasee de cabluri, alimentarea utilajelor și iluminatul, predate cu documentație.',
            'features' => [
                'Tablouri de distribuție și de forță',
                'Jgheaburi și poduri de cabluri',
                'Alimentarea utilajelor și echipamentelor',
                'Iluminat industrial și de spații comerciale',
                'Măsurători și documentație la predare',
            ],
            'image' => 'images/content/img4.webp',
        ],
    ],

    /*
     | Etapele tehnice ale unei instalatii — continutul sectiunii interactive
     | de pe homepage. Ordinea e cea reala de pe santier.
     */
    'stages' => [
        [
            'title' => 'Proiectare',
            'body' => 'Desenăm schema instalației: circuite, secțiuni de cablu, protecții. Știi de la început ce intră în perete și de ce.',
        ],
        [
            'title' => 'Trasee și cablare',
            'body' => 'Șanțuri, tuburi, doze și cablul tras pe fiecare circuit. E etapa care nu se mai vede — de aceea o facem cel mai atent.',
        ],
        [
            'title' => 'Tabloul electric',
            'body' => 'Echipăm tabloul: separator general, diferențial, câte un disjunctor pe circuit. Totul etichetat, ca schema să fie citită de oricine.',
        ],
        [
            'title' => 'Montajul final',
            'body' => 'Prize, întrerupătoare și corpuri de iluminat, montate la cotele stabilite împreună cu tine.',
        ],
        [
            'title' => 'Verificare și predare',
            'body' => 'Măsurăm izolația și împământarea, punem instalația sub tensiune și predăm lucrarea împreună cu schema ei.',
        ],
    ],

    /*
     | Circuitele tabloului interactiv din hero. Amperaje realiste per tip de
     | circuit — tabloul e demonstratia de competenta, deci datele sunt corecte.
     */
    'panel_circuits' => [
        ['name' => 'Iluminat', 'amps' => '10 A', 'icon' => 'bulb'],
        ['name' => 'Prize', 'amps' => '16 A', 'icon' => 'socket'],
        ['name' => 'Bucătărie', 'amps' => '20 A', 'icon' => 'stove'],
        ['name' => 'Boiler', 'amps' => '16 A', 'icon' => 'boiler'],
        ['name' => 'Climă', 'amps' => '16 A', 'icon' => 'ac'],
        ['name' => 'Forță', 'amps' => '25 A', 'icon' => 'motor'],
    ],

    /*
     | Drumul clientului, de la telefon la predare.
     */
    'process' => [
        ['title' => 'Ne suni', 'body' => 'Ne spui despre proiect: apartament, casă sau spațiu industrial.'],
        ['title' => 'Venim și ne uităm', 'body' => 'La fața locului sau direct pe planurile tale. Evaluarea e gratuită.'],
        ['title' => 'Primești oferta', 'body' => 'Preț fix, în scris, cu termen și etape. Nu apar costuri la final.'],
        ['title' => 'Executăm și predăm', 'body' => 'Toate etapele, în ordinea corectă. La final: măsurători și schema tabloului.'],
    ],

    /*
     | Fapte verificabile, nu insigne. Inlocuiesc semnalul de incredere pierdut
     | odata cu eliminarea afirmatiei „electrician autorizat”.
     */
    'promises' => [
        ['label' => 'Garanție pentru lucrări', 'body' => 'Dacă cedează ceva din ce am montat, ne întoarcem pe cheltuiala noastră.'],
        ['label' => 'Preț fix în ofertă', 'body' => 'Prețul din ofertă este prețul final. Fără costuri descoperite pe parcurs.'],
        ['label' => 'Evaluare gratuită', 'body' => 'Venim la fața locului sau ne uităm pe planuri. Fără obligații.'],
        ['label' => 'Predare cu măsurători', 'body' => 'Primești instalația verificată și schema tabloului. Știi exact ce ai în perete.'],
    ],

    'values' => [
        ['title' => 'Siguranță', 'body' => 'Nu improvizăm. O instalație electrică greșită nu se vede până când e prea târziu.'],
        ['title' => 'Transparență', 'body' => 'Îți arătăm schema, îți explicăm fiecare alegere și îți spunem cât costă. Înainte să începem.'],
        ['title' => 'Curățenie', 'body' => 'Plecăm de pe șantier lăsându-l cum l-am găsit. Fără moloz, fără praf.'],
        ['title' => 'Punctualitate', 'body' => 'Dacă am zis ora zece, la ora zece suntem acolo.'],
    ],

    /*
     | Galerie de umplutura pana la fotografii reale ale lucrarilor.
     | Categoriile urmeaza cele trei segmente de business.
     */
    'gallery' => [
        ['image' => 'images/content/img1.webp', 'title' => 'Instalație completă în apartament', 'category' => 'apartamente'],
        ['image' => 'images/content/img2.webp', 'title' => 'Iluminat montat în apartament', 'category' => 'apartamente'],
        ['image' => 'images/content/img3.webp', 'title' => 'Cablare în apartament nou', 'category' => 'apartamente'],
        ['image' => 'images/content/img6.webp', 'title' => 'Instalație completă de casă', 'category' => 'case'],
        ['image' => 'images/content/img5_flipped.webp', 'title' => 'Tabloul electric al unei case', 'category' => 'case'],
        ['image' => 'images/content/img8.webp', 'title' => 'Cablare pe șantier de casă', 'category' => 'case'],
        ['image' => 'images/content/img4.webp', 'title' => 'Tablou de distribuție', 'category' => 'industriale'],
        ['image' => 'images/content/img7.webp', 'title' => 'Trasee de cabluri în hală', 'category' => 'industriale'],
        ['image' => 'images/content/img9.webp', 'title' => 'Instalație în spațiu comercial', 'category' => 'industriale'],
    ],

    'gallery_categories' => [
        'toate' => 'Toate',
        'apartamente' => 'Apartamente',
        'case' => 'Case',
        'industriale' => 'Industriale',
    ],

    /*
     | Cifrele de pe site-ul vechi (500/350/50) raman NEVERIFICATE si ascunse.
     | Singura confirmata de client: vechimea — vezi `experience`.
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
                'description' => 'Instalații electrice complete pentru apartamente, case și spații industriale — de la proiect la punere sub tensiune. Garanție și preț fix în ofertă. Chișinău și toată Moldova.',
            ],
            'services' => [
                'title' => 'Servicii — instalații electrice complete | Energix',
                'description' => 'Instalația electrică de la zero: proiectare, cablare, tablou, montaj final și verificări. Apartamente, case și spații industriale.',
            ],
            'gallery' => [
                'title' => 'Tipuri de lucrări | Energix',
                'description' => 'Tipurile de lucrări pe care le executăm: instalații electrice complete pentru apartamente, case și spații industriale.',
            ],
            'about' => [
                'title' => 'Despre noi | Energix',
                'description' => 'Cine suntem și cum lucrăm. Instalații electrice complete, de 10 ani, în Chișinău și toată Moldova.',
            ],
            'contact' => [
                'title' => 'Contact — cere o ofertă | Energix',
                'description' => 'Sună la +373 68 582 016 sau completează formularul. Evaluare gratuită și preț fix în ofertă.',
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
