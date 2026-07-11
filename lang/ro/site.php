<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Energix — tot textul, în română
|--------------------------------------------------------------------------
|
| Structura (slug-uri, imagini, amperaje) stă în config/energix.php.
|
| Cuvinte-cheie țintă: „instalații electrice Chișinău”, „instalații electrice
| complete / de la zero”, „electrician Chișinău”, „cablare electrică”,
| „tablou electric”, „instalație electrică apartament / casă”,
| „instalații electrice industriale”.
|
| INTERZIS oriunde: autoriz, ANRE, certific, licenț, atestat, smart, automatiz,
| inteligent, audit, energetic, reparat, mentenan, urgen, non-stop, 24/7, depan.
| Garda: tests/Feature/ContentExclusionsTest.php
|
*/

return [

    'locale_name' => 'Română',
    'locale_short' => 'RO',

    'nav' => [
        'home' => 'Acasă',
        'services' => 'Servicii',
        'gallery' => 'Lucrări',
        'about' => 'Despre',
        'contact' => 'Contacte',
        'menu' => 'Deschide meniul',
        'main' => 'Navigare principală',
        // `aria-label` pentru firimituri. Etichetează navigarea, nu o descrie.
        'breadcrumb' => 'Unde te afli',
        'active' => 'Activ',
        'skip' => 'Sari la conținut',
        'switch' => 'Schimbă limba',
        'menu_title' => 'Meniu',
        'close' => 'Închide meniul',
    ],

    'common' => [
        'call_now' => 'Sună acum',
        'get_offer' => 'Cere o ofertă',
        'see_details' => 'Vezi detaliile',
        'related_title' => 'Instalații electrice, pe tipuri de spațiu',
        'phone' => 'Telefon',
        'email' => 'Email',
        'schedule' => 'Program',
        'closed' => 'Închis',
        'emergency_free' => 'Evaluare gratuită',
        'find_us' => 'Ne găsești și pe',
        'legal' => 'Pagini legale',
        'rights' => 'Toate drepturile rezervate.',
        'legal_terms' => 'Termeni și condiții',
        'legal_privacy' => 'Confidențialitate',
        'legal_cookies' => 'Cookie',
        'city' => 'Chișinău',
        'country' => 'Republica Moldova',
        'area_served' => 'Chișinău și toată Republica Moldova',
        'response_time' => 'Te sunăm înapoi în aceeași zi lucrătoare.',
        'experience_label' => 'ani de instalații electrice',
        'under_power' => 'Sub tensiune',
        'job_sheet' => 'Fișă de lucrare',
        'object' => 'Obiect',
        'page' => 'Pagina',
    ],

    'hours' => [
        ['days' => 'Luni – Vineri', 'time' => '08:00 – 20:00'],
        ['days' => 'Sâmbătă', 'time' => '09:00 – 17:00'],
        ['days' => 'Duminică', 'time' => 'Închis'],
    ],

    'days' => ['duminică', 'luni', 'marți', 'miercuri', 'joi', 'vineri', 'sâmbătă'],

    /*
     | Cele trei segmente. Fiecare descrie ACELAȘI ciclu complet, adaptat.
     |
     | Fiecare are pagina lui (`/servicii/{uri}`), deci are nevoie de text propriu:
     | `anchor` (textul link-ului către ea — cel mai valoros text de ancoră de pe
     | site), `body` (corpul paginii) și `faq` (întrebări specifice segmentului,
     | diferite de cele de pe homepage, ca să nu duplicăm marcajul FAQPage).
     */
    'services' => [
        'apartamente' => [
            'nav' => 'Apartamente',
            'title' => 'Instalații electrice pentru apartamente',
            'anchor' => 'Instalații electrice pentru apartamente în Chișinău',
            'tagline' => 'Bloc nou sau renovare completă.',
            'intro' => 'Executăm instalația electrică a apartamentului cap-coadă: schema pe circuite, traseele, tabloul, prizele și iluminatul. Un singur responsabil pentru toate etapele, în Chișinău și în toată Moldova.',
            'features' => [
                'Schema instalației și împărțirea pe circuite',
                'Trasee, doze și cablare electrică completă',
                'Tabloul electric, echipat și etichetat',
                'Prize, întrerupătoare, corpuri de iluminat',
                'Verificări, măsurători, punere sub tensiune',
            ],
            'body' => [
                'Într-un apartament, instalația electrică se face o singură dată — înainte de tencuială. De aceea începem cu schema: câte circuite, ce secțiune de cablu pe fiecare, unde stau dozele și la ce cotă vin prizele. Nimic nu intră în perete până nu știi ce intră și de ce.',
                'Fiecare consumator mare — plita, boilerul, mașina de spălat, climatizarea — primește circuitul lui, protejat separat în tabloul electric. Așa, un scurtcircuit la mașina de spălat nu stinge lumina din tot apartamentul, iar cablul nu se încălzește niciodată peste ce poate duce.',
                'Lucrăm în blocuri noi, primite la recepție, și în apartamente aflate în renovare completă, unde instalația veche se scoate integral. Montăm totul de la zero: numai așa putem răspunde pentru fiecare metru de cablu din perete.',
            ],
            'faq' => [
                [
                    'q' => 'De câte circuite are nevoie un apartament de trei camere?',
                    'a' => 'Minimum trei — unul de iluminat și două de prize — plus câte un circuit dedicat pentru plită, boiler, mașină de spălat și climatizare. În practică, un apartament de trei camere ajunge la șapte–nouă circuite.',
                ],
                [
                    'q' => 'Ce se întâmplă cu instalația veche din apartament?',
                    'a' => 'O scoatem integral. Montăm cablu nou, pe trasee noi, cu tablou nou. Nu legăm conductori de cupru noi la conductori vechi de aluminiu — acolo apar, peste zece ani, cele mai multe probleme.',
                ],
            ],
        ],
        'case' => [
            'nav' => 'Case',
            'title' => 'Instalații electrice pentru case',
            'anchor' => 'Instalații electrice pentru case și vile',
            'tagline' => 'De la fundație până la predare.',
            'intro' => 'O casă are branșament, exterior, etaje și consumatori mari. Proiectăm și montăm instalația electrică completă, cu împământare și măsurători la predare.',
            'features' => [
                'Proiectul instalației, pe etaje și exterior',
                'Racord de la branșament la tabloul general',
                'Cablare electrică interioară și exterioară',
                'Consumatori mari: plită, boiler, climatizare',
                'Împământare, măsurători, predare cu schemă',
            ],
            'body' => [
                'O casă nu e un apartament mai mare. Are un branșament propriu, un tablou general și, de obicei, tablouri secundare pe etaje. Are consumatori pe care un apartament nu-i are: pompa din fântână, poarta, centrala, priza din garaj, iluminatul curții.',
                'Proiectăm instalația pe etaje și separat pentru exterior, cu cablu potrivit pentru montaj îngropat acolo unde iese din casă. Împământarea se execută și se măsoară, nu se presupune: o priză de pământ bună este singura protecție care funcționează atunci când toate celelalte au cedat.',
                'Intrăm pe șantier când structura e ridicată și pereții sunt încă goi, sau într-o casă aflată în renovare completă. La predare primești schema tabloului, valorile măsurate ale izolației și ale prizei de pământ, plus lista circuitelor.',
            ],
            'faq' => [
                [
                    'q' => 'Cine face racordul de la branșament la tabloul general?',
                    'a' => 'Noi executăm traseul de la firida de branșament până la tabloul general al casei, cu secțiunea calculată pentru puterea contractată. Contorul și branșamentul propriu-zis rămân în sarcina furnizorului.',
                ],
                [
                    'q' => 'Instalația exterioară intră în aceeași lucrare?',
                    'a' => 'Da. Iluminatul curții, priza din garaj, alimentarea porții și a pompei fac parte din același proiect, cu cablu pentru montaj îngropat și cu protecții separate în tablou.',
                ],
            ],
        ],
        'industriale' => [
            'nav' => 'Industriale',
            'title' => 'Instalații electrice industriale',
            'anchor' => 'Instalații electrice industriale și comerciale',
            'tagline' => 'Hale, depozite, spații comerciale.',
            'intro' => 'Distribuția electrică completă a spațiului de lucru: tablouri, trasee de cabluri, alimentarea utilajelor și iluminatul industrial, predate cu documentație.',
            'features' => [
                'Tablouri de distribuție și de forță',
                'Jgheaburi și poduri de cabluri',
                'Alimentarea utilajelor și echipamentelor',
                'Iluminat industrial și de spații comerciale',
                'Măsurători și documentație la predare',
            ],
            'body' => [
                'Într-o hală, cablul nu intră în perete: merge pe jgheaburi și poduri de cabluri, la vedere, unde poate fi urmărit și completat. Traseele se gândesc de la început în funcție de unde vor sta utilajele, nu invers.',
                'Dimensionăm tabloul general și tablourile de forță după puterea instalată și după curentul de pornire al fiecărui utilaj. Circuitele trifazate primesc protecții calculate separat, iar iluminatul halei se proiectează pe nivelul de iluminare cerut de tipul de activitate.',
                'Executăm instalații electrice noi în hale, depozite, ateliere și spații comerciale în amenajare. La predare primești schema monofilară a tablourilor, buletinele de măsurători și lista circuitelor, etichetate ca oricine să le poată citi.',
            ],
            'faq' => [
                [
                    'q' => 'Puteți alimenta utilaje trifazate?',
                    'a' => 'Da. Dimensionăm tabloul de forță și cablurile după puterea și curentul de pornire al fiecărui utilaj, cu protecție proprie pe fiecare. La predare primești schema tabloului și valorile măsurate.',
                ],
                [
                    'q' => 'Cum se planifică lucrarea pe un șantier industrial?',
                    'a' => 'Stabilim etapele împreună cu tine și cu ceilalți executanți, ca montajul traseelor să nu blocheze restul șantierului. Lucrăm pe spații în amenajare, nu pe instalații deja aflate în exploatare.',
                ],
            ],
        ],
    ],

    /*
     | Etichetele comune ale paginilor de segment.
     */
    'segment_page' => [
        'includes' => 'Ce include lucrarea',
        'faq_title' => 'Întrebări despre acest tip de lucrare',
        'others_title' => 'Celelalte tipuri de instalații',
        'stages_note' => 'Indiferent de spațiu, executăm aceleași cinci etape.',
        'stages_link' => 'Vezi cele cinci etape ale unei instalații electrice',
        'gallery_link' => 'Vezi lucrări de instalații electrice executate',
    ],

    'stages' => [
        ['title' => 'Proiectare', 'body' => 'Desenăm schema instalației electrice: circuite, secțiuni de cablu, protecții. Știi de la început ce intră în perete și de ce.'],
        ['title' => 'Trasee și cablare', 'body' => 'Șanțuri, tuburi, doze și cablul tras pe fiecare circuit. E etapa care nu se mai vede — de aceea o facem cel mai atent.'],
        ['title' => 'Tabloul electric', 'body' => 'Echipăm tabloul: separator general, diferențial, câte un disjunctor pe circuit. Totul etichetat, ca schema să fie citită de oricine.'],
        ['title' => 'Montajul final', 'body' => 'Prize, întrerupătoare și corpuri de iluminat, montate la cotele stabilite împreună cu tine.'],
        ['title' => 'Verificare și predare', 'body' => 'Măsurăm izolația și împământarea, punem instalația sub tensiune și predăm lucrarea împreună cu schema ei.'],
    ],

    'process' => [
        ['title' => 'Ne suni', 'body' => 'Ne spui despre proiect: apartament, casă sau spațiu industrial.'],
        ['title' => 'Venim și ne uităm', 'body' => 'La fața locului sau direct pe planurile tale. Evaluarea e gratuită.'],
        ['title' => 'Primești oferta', 'body' => 'Preț fix, în scris, cu termen și etape. Nu apar costuri la final.'],
        ['title' => 'Executăm și predăm', 'body' => 'Toate etapele, în ordinea corectă. La final: măsurători și schema tabloului.'],
    ],

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
     | AEO — întrebările pe care oamenii le pun motoarelor de răspuns.
     | Marcate cu FAQPage în JSON-LD. Răspunsuri scurte, citabile, autonome.
     */
    'faq' => [
        [
            'q' => 'Cât costă o instalație electrică completă într-un apartament?',
            'a' => 'Prețul depinde de numărul de circuite și de suprafață. Venim, măsurăm, apoi primești un preț fix, în scris. Evaluarea este gratuită.',
        ],
        [
            'q' => 'Cât durează instalația electrică a unui apartament?',
            'a' => 'Pentru un apartament de două-trei camere, între 5 și 10 zile lucrătoare, în funcție de numărul de circuite și de stadiul șantierului.',
        ],
        [
            'q' => 'Ce include o instalație electrică completă, la cheie?',
            'a' => 'Toate cele cinci etape: proiectarea schemei, traseele și cablarea, echiparea tabloului electric, montajul final al prizelor și corpurilor de iluminat, verificările și punerea sub tensiune.',
        ],
        [
            'q' => 'De câte circuite are nevoie un apartament?',
            'a' => 'Minimum trei: unul de iluminat și două de prize. Fiecare consumator mare — plită, boiler, climatizare, mașină de spălat — primește circuitul lui dedicat.',
        ],
        [
            'q' => 'Lucrați și în afara Chișinăului?',
            'a' => 'Da. Deservim toată Republica Moldova. Pentru localitățile din afara Chișinăului stabilim ziua evaluării la telefon.',
        ],
        [
            'q' => 'Executați doar instalații electrice noi?',
            'a' => 'Da. Montăm instalația electrică completă, de la zero, în construcții noi și renovări complete. Nu executăm lucrări punctuale pe instalații existente.',
        ],
    ],

    'gallery' => [
        'items' => [
            'flat_wiring' => 'Doze și trasee de cablu în apartament',
            'flat_panel' => 'Tabloul electric al unui apartament',
            'flat_outlet' => 'Montajul unei prize în apartament',
            'flat_light' => 'Corpuri de iluminat montate în apartament',
            'house_site' => 'Cablare electrică pe șantier de casă',
            'house_panel' => 'Echiparea tabloului unei case',
            'house_protect' => 'Tablou de casă cu releu de tensiune',
            'house_full' => 'Instalație electrică de casă, în execuție',
            'ind_panel' => 'Tablouri de distribuție industriale',
            'ind_trays' => 'Trasee de cabluri în hală',
            'ind_power' => 'Echiparea unui tablou de forță',
            'ind_control' => 'Dulap de comandă și forță industrial',
            'ind_retail' => 'Instalație electrică în spațiu comercial',
        ],
        'categories' => [
            'toate' => 'Toate',
            'apartamente' => 'Apartamente',
            'case' => 'Case',
            'industriale' => 'Industriale',
        ],
        'filter_label' => 'Filtrează după tipul lucrării',
        'count_suffix' => 'lucrări pe circuit',
        'disclaimer' => 'Imaginile de mai jos sunt ilustrative și arată tipul lucrării, nu proiecte executate de noi. Pregătim fotografii de pe șantierele proprii.',
    ],

    'panel' => [
        'title' => 'Tablou distribuție · TD-01',
        'grid' => 'Rețea',
        'breaker' => 'Separator',
        'on' => 'Pornit',
        'off' => 'Oprit',
        'rcd' => 'Diferențial 30 mA',
        'test' => 'Test',
        'hint' => 'Comută disjunctoarele — tabloul e funcțional',
        'group' => 'Circuitele tabloului — comută disjunctoarele',
        'circuits' => [
            'lighting' => 'Iluminat',
            'sockets' => 'Prize',
            'kitchen' => 'Bucătărie',
            'boiler' => 'Boiler',
            'ac' => 'Climă',
            'power' => 'Forță',
        ],
    ],

    'calc' => [
        'title' => 'Calculator de circuite',
        'base' => 'Iluminat + prize',
        'base_value' => '3 circuite',
        'group' => 'Ce consumatori mari are locuința',
        'circuits' => 'Circuite dedicate',
        'panel' => 'Tablou minim',
        'modules' => 'module',
        'note' => 'Estimare orientativă — separatorul, diferențialul și rezervele sunt incluse. Proiectul exact îl facem la evaluare, gratuit.',
        'loads' => [
            'hob' => 'Plită electrică',
            'oven' => 'Cuptor',
            'boiler' => 'Boiler',
            'ac' => 'Climatizare',
            'washer' => 'Mașină de spălat',
        ],
    ],

    'counter' => [
        'title' => 'Contor de lucrări',
        'label' => 'lucrări pe circuitul ales',
        'group' => 'Alege circuitul de lucrări',
        'note' => 'Alegerea de aici filtrează galeria de mai jos.',
    ],

    'line' => [
        'title' => 'Starea liniei',
        'open' => 'Linie liberă — sună acum',
        'open_detail' => 'Suntem deschiși până la :hour.',
        'closed' => 'Momentan închis',
        'closed_detail' => 'Revenim :when la :hour. Scrie-ne — te sunăm noi.',
        'closed_plain' => 'Scrie-ne — te sunăm noi.',
        'today' => 'azi',
        'tomorrow' => 'mâine',
        'test' => 'Testează linia',
        'testing' => 'Verific linia…',
    ],

    'home' => [
        'eyebrow' => 'Instalații electrice · Chișinău · toată Republica Moldova',
        'h1' => 'Curentul ajunge unde trebuie.',
        'h1_sub' => 'Instalații electrice complete în Chișinău și toată Moldova.',
        'lead' => 'Montăm instalația electrică de la zero, cap-coadă: proiect, cablare, tablou electric, montaj final și punere sub tensiune. Apartamente, case și spații industriale.',
        'sheet_source' => 'Sursa',
        'services_eyebrow' => 'Ce facem',
        'services_title' => 'Instalația electrică completă, de la zero.',
        'services_intro' => 'Facem un singur lucru: instalații electrice pentru construcții. Toate etapele, pentru trei tipuri de spații. Deschide un circuit.',
        'sheet_circuits' => 'Circuite',
        'stages_eyebrow' => 'Cum se construiește o instalație electrică',
        'stages_title' => 'Cinci etape. În ordinea asta.',
        'stages_intro' => 'Fiecare ofertă acoperă toate cele cinci. Apasă pe o etapă ca să vezi ce se întâmplă în ea.',
        'sheet_exec' => 'Execuție',
        'answers_eyebrow' => 'Fișa de măsurători · înainte să suni',
        'answers_title' => 'Întrebări frecvente despre instalațiile electrice.',
        'trust_eyebrow' => 'De ce noi',
        'trust_title' => 'Fapte, nu insigne.',
        'trust_intro' => 'Nu îți fluturăm nimic pe perete. Îți spunem ce ne asumăm și ne ținem de cuvânt.',
        'sheet_warranty' => 'Garanții',
        'areas_eyebrow' => 'Unde lucrăm',
        'areas_title' => 'Chișinău și toată Moldova.',
        'areas_intro' => 'Lucrăm în toate sectoarele Chișinăului și în restul țării. Pentru evaluări în oraș ajungem în aceeași săptămână.',
        'areas_sectors' => 'Sectoarele Chișinăului',
        'areas_cities' => 'Alte localități',
        'summary_title' => 'Energix pe scurt',
        'summary' => 'Energix este o firmă din Chișinău care execută instalații electrice complete, de la zero, în construcții — pentru apartamente, case și spații industriale, în toată Republica Moldova. Lucrează de 10 ani, acoperă toate cele cinci etape ale unei instalații (proiectare, trasee și cablare, tabloul electric, montajul final, verificarea și predarea), oferă preț fix în ofertă, evaluare gratuită și garanție pentru lucrări. Nu execută lucrări punctuale pe instalații existente.',
    ],

    'services_page' => [
        'h1' => 'Instalații electrice complete, pentru spațiul tău.',
        'lead' => 'Același ciclu complet — proiect, cablare, tablou electric, montaj, verificare — adaptat la tipul construcției. Alege circuitul.',
        'circuit' => 'Circuit',
        'choose' => 'Alege tipul de spațiu',
        'space_type' => 'Tipul de spațiu',
        'segments_eyebrow' => 'Trei tipuri de spațiu',
        'segments_title' => 'Fiecare spațiu, cu instalația lui.',
        'segments_intro' => 'Un apartament, o casă și o hală au aceleași cinci etape, dar altă schemă, alte secțiuni de cablu și alt tablou. Alege spațiul tău.',
        'stages_eyebrow' => 'Execuția',
        'stages_title' => 'Cinci etape, indiferent de spațiu.',
        'process_eyebrow' => 'Cum lucrăm',
        'process_title' => 'Patru pași, fără surprize.',
        'cta' => 'Spune-ne despre spațiul tău.',
    ],

    'gallery_page' => [
        'h1' => 'Lucrări de instalații electrice.',
        'lead' => 'De la tabloul electric al unui apartament până la distribuția unei hale. Comută circuitele ca să filtrezi.',
        'cta' => 'Ai un proiect asemănător?',
    ],

    'about_page' => [
        'h1' => 'Instalații electrice, făcute bine, de zece ani.',
        'lead' => 'Energix e o echipă de electricieni din Chișinău. Montăm instalații electrice complete pentru construcții: apartamente, case, spații industriale. Doar asta.',
        'meter' => 'Contor de vechime',
        'p1' => 'Când suni la Energix, nu ajungi la un call-center. Ajungi la echipa care vine efectiv pe șantierul tău.',
        'p2' => 'Am pornit de la o idee simplă: o instalație electrică se face o dată, bine. Ce se ascunde în perete rămâne acolo douăzeci de ani, iar clientul nu are cum să verifice singur ce e în spatele tencuielii. De asta lucrăm ca și cum cineva ar urma să deschidă peretele mâine.',
        'p3' => 'Îți arătăm schema înainte să începem, îți explicăm fiecare alegere și cât costă. Apoi facem exact ce am spus.',
        'values_eyebrow' => 'Aparatajul de protecție',
        'values_title' => 'Ce ne ține în frâu.',
        'values_intro' => 'Patru dispozitive care nu se scot din schemă, indiferent de lucrare.',
        'protection' => 'Protecție',
        'trust_eyebrow' => 'Ce îți garantăm',
        'trust_title' => 'Fapte, nu insigne.',
        'trust_intro' => 'Patru lucruri pe care ni le asumăm în scris, la fiecare lucrare.',
        'cta' => 'Hai să vorbim despre lucrarea ta.',
    ],

    'contact_page' => [
        'h1' => 'Sună. E cel mai rapid.',
        'where' => 'Unde suntem',
        'where_detail' => 'Deservim toată țara.',
        'form_title' => 'Sau închide circuitul în scris.',
        'form_intro' => 'Completează câmpurile — fiecare închide un segment. Cu cât ne spui mai multe despre proiect, cu atât oferta e mai exactă.',
    ],

    'form' => [
        'name' => 'Nume',
        'surname' => 'Prenume',
        'phone' => 'Telefon',
        'email' => 'Email',
        'message' => 'Ce ai nevoie',
        'placeholder' => 'Descrie pe scurt proiectul: tipul spațiului, suprafața, stadiul șantierului.',
        'honeypot' => 'Nu completa acest câmp',
        'submit' => 'Închide circuitul — trimite',
    ],

    'cta' => [
        'title' => 'Punem proiectul tău sub tensiune?',
        'switch' => 'Punere sub tensiune',
    ],

    'cookie' => [
        'title' => 'Cookie-uri',
        'body' => 'Folosim cookie-uri de analiză ca să înțelegem cum e folosit site-ul. Nu pornesc decât dacă ești de acord.',
        'details' => 'Detalii',
        'accept' => 'Accept',
        'decline' => 'Refuz',
    ],

    'error' => [
        '404_sheet' => 'Necunoscut',
        '404_status' => 'Circuit întrerupt',
        '404_title' => 'Circuit întrerupt.',
        '404_body' => 'Pagina pe care o cauți nu există sau s-a mutat. Hai înapoi la sursă.',
        '404_home' => 'Înapoi la sursă',
        '404_call' => 'Sună-ne',
    ],

    /*
     | Paginile legale. Se randează prin `pages/legal/page.blade.php`.
     | Atenție: fără `autoriz`, `certific`, `licenț` — vezi garda de excluderi.
     | „acces neîngăduit”, nu „acces neautorizat”. „Conexiune criptată HTTPS”,
     | nu „certificat SSL”.
     */
    'legal' => [
        'updated' => 'Ultima actualizare: iulie 2026',

        'terms' => [
            'title' => 'Termeni și condiții',
            'intro' => 'Prin folosirea site-ului energix.md ești de acord cu termenii de mai jos. Dacă nu ești de acord cu ei, te rugăm să nu folosești site-ul.',
            'sections' => [
                ['h' => 'Obiectul site-ului', 'p' => ['Acest site are rol de prezentare. Îți arată serviciile de instalații electrice pe care le executăm și îți oferă o cale de a ne contacta. Nu vinde nimic online, nu are conturi de utilizator și nu procesează plăți.']],
                ['h' => 'Proprietate intelectuală', 'p' => ['Textele, imaginile, codul și elementele de identitate vizuală de pe acest site ne aparțin. Le poți citi și distribui prin link, dar nu le poți copia sau reutiliza fără acordul nostru scris.']],
                ['h' => 'Formularul de contact', 'p' => ['Formularul servește exclusiv pentru cereri legate de serviciile noastre. Datele pe care le introduci sunt trimise pe email și nu sunt stocate într-o bază de date. Nu trimite prin formular date sensibile.']],
                ['h' => 'Limitarea răspunderii', 'p' => ['Informațiile de pe site sunt orientative. Estimările afișate — inclusiv cele produse de calculatorul de circuite — nu constituie ofertă fermă. Oferta reală se emite după evaluarea la fața locului.']],
                ['h' => 'Linkuri externe', 'p' => ['Site-ul conține linkuri către rețele sociale și alte site-uri terțe. Nu controlăm conținutul lor și nu răspundem pentru el.']],
                ['h' => 'Modificarea termenilor', 'p' => ['Putem actualiza acești termeni oricând. Versiunea publicată aici este cea în vigoare, iar data ultimei actualizări e afișată mai sus.']],
                ['h' => 'Legea aplicabilă', 'p' => ['Acești termeni sunt guvernați de legislația Republicii Moldova. Orice neînțelegere încercăm mai întâi să o rezolvăm pe cale amiabilă; dacă nu reușim, se soluționează de instanțele competente din Republica Moldova.']],
                ['h' => 'Contact', 'p' => ['Pentru orice întrebare legată de acești termeni, scrie-ne la contact@energix.md sau sună la +373 68 582 016.']],
            ],
        ],

        'privacy' => [
            'title' => 'Politica de confidențialitate',
            'intro' => 'Pe scurt: nu avem conturi de utilizator, nu vindem nimic online și nu ținem o bază de date cu datele tale. Singurul moment în care îți colectăm datele este atunci când ne scrii prin formularul de contact.',
            'sections' => [
                ['h' => 'Cine suntem', 'p' => ['Energix, Chișinău, Republica Moldova. Ne poți scrie la contact@energix.md.']],
                ['h' => 'Ce date colectăm', 'p' => ['Doar ce completezi tu în formular: numele, prenumele, telefonul, emailul și mesajul.']],
                ['h' => 'De ce le colectăm', 'p' => ['Ca să răspundem cererii tale și să îți pregătim oferta. Nimic altceva.']],
                ['h' => 'Cât le păstrăm', 'p' => ['Mesajul ajunge pe adresa noastră de email și rămâne acolo. Nu există o bază de date a site-ului în care să fie salvat. Poți cere ștergerea lui oricând.']],
                ['h' => 'Cine le vede', 'p' => ['Doar echipa Energix. Nu vindem, nu închiriem și nu transmitem datele tale nimănui.']],
                ['h' => 'Analiză și cookie-uri', 'p' => ['Folosim Google Tag Manager, dar numai după ce apeși „Accept” în banner-ul de cookie. Dacă refuzi, nu pornește nimic. Detaliile sunt în Politica de cookie.']],
                ['h' => 'Drepturile tale', 'p' => ['Datele sunt ale tale. Poți oricând să ceri:'], 'ul' => ['acces — să afli ce date avem despre tine;', 'rectificare — să corectăm ce e greșit;', 'ștergere — să ștergem datele tale;', 'opoziție — să nu mai folosim datele într-un anumit scop.']],
                ['h' => 'Plângeri', 'p' => ['Dacă îți refuzăm o cerere sau nu ești mulțumit de răspuns, ai dreptul să depui o plângere la Centrul Național pentru Protecția Datelor cu Caracter Personal — str. Serghei Lazo 48, Chișinău, centru@datepersonale.md.']],
                ['h' => 'Securitate', 'p' => ['Site-ul rulează pe conexiune criptată HTTPS. Formularul e protejat împotriva trimiterilor automate și a folosirii abuzive.']],
            ],
        ],

        'cookies' => [
            'title' => 'Politica de cookie',
            'intro' => 'Cookie-urile sunt fișiere mici pe care site-ul le pune pe dispozitivul tău. Le folosim cât mai puțin cu putință.',
            'sections' => [
                ['h' => 'Strict necesare', 'p' => ['Cookie-ul de sesiune și token-ul care protejează formularul împotriva trimiterilor din alte site-uri. Fără ele site-ul nu poate funcționa, deci nu îți cerem acordul pentru ele.']],
                ['h' => 'De analiză', 'p' => ['Google Tag Manager, care încarcă instrumente de măsurare a traficului. Se activează DOAR după ce apeși „Accept” în banner. Dacă refuzi, nu se încarcă nimic către Google.']],
                ['h' => 'Cum îți retragi consimțământul', 'p' => ['Șterge datele site-ului din setările browserului. La următoarea vizită banner-ul apare din nou și poți alege altfel.']],
                ['h' => 'Refuzul nu strică nimic', 'p' => ['Dacă refuzi cookie-urile de analiză, site-ul funcționează exact la fel. Nu îți ascundem conținut și nu îți limităm accesul.']],
                ['h' => 'Contact', 'p' => ['Întrebări despre cookie-uri: contact@energix.md.']],
            ],
        ],
    ],

    /*
     | Textul alternativ al cartonașului social. Stă în afara lui `seo`, fiindcă
     | `PageMetaTest` iterează `trans('site.seo')` așteptând perechi title/description.
     */
    'og_alt' => 'Energix — instalații electrice complete în Chișinău și toată Moldova',

    /*
     | Meta per pagină. Titluri ≤ 60 caractere, descrieri ≤ 158.
     */
    'seo' => [
        'home' => [
            'title' => 'Instalații electrice Chișinău — montaj complet | Energix',
            'description' => 'Instalații electrice complete pentru apartamente, case și spații industriale în Chișinău. Proiect, cablare, tablou electric, verificări. Preț fix, garanție.',
        ],
        'services' => [
            'title' => 'Servicii instalații electrice în Chișinău | Energix',
            'description' => 'Instalația electrică de la zero: proiectare, cablare, tablou electric, montaj final, verificări. Apartamente, case și spații industriale în toată Moldova.',
        ],

        /*
         | Cheile conțin un punct. `<x-seo.head>` le citește direct din array,
         | nu prin `data_get`, tocmai ca punctul să nu fie luat drept separator.
         */
        'services.apartamente' => [
            'title' => 'Instalație electrică apartament Chișinău | Energix',
            'description' => 'Instalație electrică completă pentru apartament: schemă pe circuite, cablare, tablou electric, prize, verificări. Preț fix și garanție, în Chișinău.',
        ],
        'services.case' => [
            'title' => 'Instalații electrice pentru case | Energix',
            'description' => 'Instalația electrică a casei, de la branșament la ultima priză: proiect pe etaje, cablare interioară și exterioară, împământare, măsurători la predare.',
        ],
        'services.industriale' => [
            'title' => 'Instalații electrice industriale Chișinău | Energix',
            'description' => 'Distribuție electrică pentru hale, depozite și spații comerciale: tablouri de forță, poduri de cabluri, alimentarea utilajelor, documentație la predare.',
        ],

        'gallery' => [
            'title' => 'Lucrări de instalații electrice | Energix',
            'description' => 'Tipurile de lucrări pe care le executăm: instalații electrice complete pentru apartamente, case și spații industriale, în Chișinău și toată Moldova.',
        ],
        'about' => [
            'title' => 'Despre Energix — 10 ani de instalații electrice',
            'description' => 'Echipă de electricieni din Chișinău. Montăm instalații electrice complete pentru construcții, de 10 ani. Preț fix, evaluare gratuită, garanție pentru lucrări.',
        ],
        'contact' => [
            'title' => 'Contact — cere ofertă instalații electrice | Energix',
            'description' => 'Sună la +373 68 582 016 sau completează formularul. Evaluare gratuită și preț fix în ofertă pentru instalații electrice în Chișinău și toată Moldova.',
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

];
