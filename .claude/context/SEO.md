# Energix — strategie SEO / AEO / GEO

Stabilită 2026-07-10, după auditul cu skill-ul `seo-audit`.

⚠️ **Nu am avut date reale de volum.** Conectorii Ahrefs / Semrush / Search Console cer
autentificare și n-au putut fi folosiți. Prioritățile de mai jos vin din cercetare pe web
și din raționament pe intenție. Cu Ahrefs conectat, tabelul se umple cu cifre.

## Ce a arătat piața

Aproape toți competitorii din Chișinău ([instalelectro.md](https://instalelectro.md/),
[manole.md](https://manole.md/rom/servicii-de-electrician), [bec.md](https://bec.md/lucrari-electrice/),
[electric24.md](https://electric24.md/ro/home)) se bazează pe **„electrician autorizat"** —
exact expresia pe care clientul a interzis-o — și aproape toți vând reparații și urgențe.

Două goluri exploatabile:

1. **Nișa „de la zero"**. Concurenții amestecă instalații cu reparații. Energix face doar
   instalații complete în construcții. `электромонтаж под ключ` / `instalație completă la
   cheie` e un teritoriu mai puțin aglomerat și cu intenție comercială mai mare.
2. **Rusa.** Într-un oraș bilingv, competitorii de top **nu au versiune rusă**. Piața
   rusofonă din Chișinău caută `электромонтажные работы Кишинев`.

## Harta de cuvinte-cheie

| Prioritate | RO | RU | Unde |
|---|---|---|---|
| Primar | instalații electrice Chișinău | электромонтажные работы Кишинёв | `<title>` home, H1-sub |
| Primar | instalații electrice complete / de la zero | электромонтаж под ключ | H1-sub, lead, servicii |
| Secundar | instalație electrică apartament | электромонтаж квартиры | segment 1 |
| Secundar | instalații electrice casă | электромонтаж дома | segment 2 |
| Secundar | instalații electrice industriale | промышленный электромонтаж | segment 3 |
| Secundar | cablare electrică, tablou electric | прокладка кабеля, электрощит | etape, features |
| Long-tail | cât costă instalația electrică apartament | сколько стоит электромонтаж квартиры | FAQ 1 |
| Long-tail | câte circuite are nevoie un apartament | сколько линий нужно квартире | FAQ 4 |
| Long-tail | ce include instalația la cheie | что входит в электромонтаж под ключ | FAQ 3 |

**Interzis ca țintă**, oricât de mare ar fi volumul: `electrician autorizat`,
`reparații electrice`, `electrician urgent`, `ремонт электрики`, `аварийный электрик`.
Sunt cuvintele concurenței, dar contrazic modelul de business. Vezi `BUSINESS.md`.

## Arhitectura de conținut: o pagină pe intenție

Decis 2026-07-10, după auditul al doilea. Cele trei segmente trăiau ca **tab-uri** pe
`/servicii`: un singur `<title>`, un singur `<h1>`, un singur URL pentru trei intenții
comerciale distincte. **Google nu rankează fragmente** (`#apartamente`) ca pagini.

Acum:

| Pagină | Rol |
|---|---|
| `/servicii` | pilon — trimite mai departe, nu ține conținutul |
| `/servicii/apartamente` · `/case` · `/industriale` | câte o intenție, ~500 cuvinte fiecare |

Fiecare segment are `<title>`, `<h1>`, `description`, `Service` cu `url` propriu,
firimituri pe trei niveluri și **FAQ propriu** — diferit de cel de pe homepage, fiindcă
același Q&A marcat `FAQPage` pe două URL-uri e conținut duplicat.

Ancorele vechi `#apartamente` nu pot fi redirectate 301 (fragmentul nu ajunge la server).
`initLegacySegmentHash()` le traduce pe client, cu `location.replace`.

⚠️ **Textul ancorei e semnal de ranking.** Link-ul către fiecare segment scrie
„Instalații electrice pentru apartamente în Chișinău", nu „Detalii complete →".
`SeoTest` interzice explicit șirul „Detalii complete".

## SEO — ce s-a făcut

- `<title>` ≤ 60 caractere, `description` ≤ 158, **unice pe fiecare pagină și limbă**
  (testat în `PageMetaTest`).
- **H1 dublu**: linia de brand („Curentul ajunge unde trebuie.") rămâne dominantă vizual,
  iar sub ea, în același `<h1>`, expresia-cheie. Poezia nu costă rankingul.
- `alt` descriptiv pe imaginile de conținut, cu localitatea. Imaginile decorative rămân
  `alt=""` + `aria-hidden` — corect, nu o omisiune.
- Canonical, `hreflang` (`ro-MD`, `ru-MD`, `x-default` → RO) și sitemap generate din
  **aceeași** tabelă de rute. Sitemap cu `xhtml:link` per URL, 22 de URL-uri.
- **`lastmod` din mtime-ul surselor** (vederea + fișierul de limbă), niciodată `now()`:
  o dată care se schimbă la fiecare cerere e o minciună, iar Google, odată ce o prinde,
  ignoră `lastmod` pe tot site-ul. `rsync -a` păstrează mtime-urile la deploy.
- **`changefreq` și `priority` au fost scoase.** Google le ignoră declarat de ani buni.
- Legături contextuale în corpul paginilor (`<x-related-segments>`), nu doar navbar și
  footer — acelea apar identic pe fiecare pagină, deci nu spun nimic despre relații.
- 404: fără canonical, cu `noindex, follow`.
- Redirect-uri 301 de la vechile `.html`.
- **Open Graph complet**: cartonaș social 1200×630 PNG **pe fiecare limbă**, cu
  `og:image:{width,height,type,alt,secure_url}`, `og:locale:alternate` și
  `twitter:image:alt`. Vezi `DESIGN.md` § „Cartonașul social". Vechiul `og:image` era
  un WebP transparent de 669×543 — adică partajări fără imagine pe Facebook/LinkedIn.

## AEO — optimizare pentru motoarele de răspuns

Motoarele de răspuns (AI Overviews, ChatGPT, Perplexity) citează **fragmente scurte și
autonome**. De aceea:

- Secțiune de **întrebări frecvente** pe homepage, în ambele limbi, marcată `FAQPage`
  în JSON-LD. Șase întrebări, alese după ce se caută înainte de un apel: preț, durată,
  ce include, câte circuite, acoperire geografică, ce NU facem.
- Fiecare răspuns se înțelege **scos din context**. Fără „vezi mai sus", fără pronume
  care trimit la paragraful anterior.
- Ultima întrebare declară explicit ce **nu** face firma. Un motor de răspuns care
  citează asta ne trimite lead-uri calificate, nu apeluri de depanare.
- **`/llms.txt`** (convenția llmstxt.org), generat de `LlmsTxtController` din `lang/` și
  din tabela de rute — nu un fișier static, care ar fi rămas în urmă tăcut. Conține
  rezumatul în ambele limbi, cele 5 etape, cele 3 servicii, toate FAQ-urile, NAP-ul și
  secțiunea „Ce NU face". Anunțat în `robots.txt`.

⚠️ Textul din `llms.txt` respectă **aceleași excluderi** ca paginile — `ContentExclusionsTest`
îl scanează. O negație nu ajută: „nu facem urgențe" fixează oricum asocierea
„Energix + urgențe" în modelul care o citește.

## GEO — ambele sensuri, fiindcă amândouă contează aici

**Generative Engine Optimization:**
- Blocul „Energix pe scurt" de pe homepage: un paragraf factual, autonom, care numește
  entitatea, ce face, unde, de când, cum lucrează și ce nu face. Scris ca să poată fi
  citat întreg. E și `description` în JSON-LD.
- `hasOfferCatalog` cu cele trei servicii, `knowsLanguage`, `foundingDate` calculat din
  `experience_years`.

**Geographic / local:**
- `@type: Electrician` — subtipul valid de LocalBusiness pentru un business electric —
  cu `areaServed` ca listă de `City`, `openingHoursSpecification`, NAP consistent.

⚠️ **Corecție 2026-07-10 (validator.schema.org):** tipul era `ElectricalContractor`, ales
ca „alternativă fără autorizare". Dar **acela nu e un tip definit de schema.org** —
validatorul îl respinge (3 erori), iar entitatea cade la `Thing`: Google nu mai recunoaște
firma ca business local, deci toată semantica NAP/program/oferte atârnă în gol. `Electrician`
e o **categorie** citită de motoare, nu textul vizibil pe care îl viza excluderea; fără
`hasCredential`, fără afirmație de licențiere. Verificat: schema.org validator → 0 erori.
- Secțiunea „Unde lucrăm": sectoarele Chișinăului + localitățile, ca text real, nu doar
  în schema.
- **Firimituri**, vizibile pe pagină **și** marcate `BreadcrumbList`, dintr-o singură
  sursă: `App\Support\Breadcrumbs::trail()`. Ierarhia se deduce din numele rutei
  (`services.apartamente` → Acasă / Servicii / Apartamente); nivelurile intermediare
  fără pagină proprie (`legal`) se sar. Homepage-ul și 404 nu au firimituri.

⚠️ Google **compară** marcajul cu ce vede pe pagină și îl ignoră când diferă. Înainte,
`BreadcrumbList` folosea `<title>`-ul („Lucrări de instalații electrice | Energix" —
inclusiv numele de brand), iar firimiturile vizibile existau doar pe paginile de segment,
unde scriau altceva. `BreadcrumbsTest` compară acum cele două, pe fiecare pagină.

## Ce rămâne de făcut

### În cod (se poate face de aici)

1. ✅ **`www` → non-`www` și `http` → `https`** — **făcut** (2026-07-10) în
   `public/.htaccess`, plus `URL::forceScheme('https')` în producție.
   Redirectarea exista, dar venea din `.htaccess`-ul site-ului vechi, care dispare la
   cutover. Detalii și matricea de verificare: `DEPLOY-HOSTINGER.md`.
2. ✅ **Nume de fișiere de imagine** — **făcut** (2026-07-11), odată cu setul stock
   curat: `apartament-doze.webp`, `casa-tablou.webp`, `industrial-poduri-cabluri.webp`…
   Numele descriu conținutul, în limba site-ului. Se păstrează convenția la
   fotografiile reale.

### Blocate pe date de la client

3. **Conectează Ahrefs / Search Console.** Fără ele nu avem volume, poziții, nici date
   despre ce aduce trafic. E prima investiție — fără ea nu putem măsura dacă separarea
   paginilor de servicii a funcționat.
4. **Google Business Profile** — pentru un business local apare deasupra rezultatelor
   organice și aduce, de regulă, mai multe apeluri decât site-ul. Nu se poate face din cod.
5. **`streetAddress`, `geo`, `priceRange` în JSON-LD.** Lipsesc pentru că nu avem adresa
   și coordonatele. **Nu se inventează.** Schema declară doar `addressLocality: Chișinău`.
6. **Fotografii reale**, cu `alt` descriptiv. Galeria stock e un pasiv, nu un activ.
7. **Recenzii** — `AggregateRating` în JSON-LD e permis doar dacă sunt reale și vizibile
   pe site. Nu inventa.
8. **Handle vanity de Facebook** (acum e `profile.php?id=…`) — semnal `sameAs` mai curat.

### Deliberat amânate

9. **Pagini pe localități** („instalații electrice Bălți"). Dacă textul e același cu numele
   orașului schimbat, sunt *doorway pages* — tratate ca spam. Merită doar cu lucrări reale
   și conținut propriu. Până atunci, secțiunea „Unde lucrăm" de pe homepage e răspunsul.
10. **Blog / ghiduri.** Prematur: fără Search Console am scrie la nimereală. Întâi datele.
