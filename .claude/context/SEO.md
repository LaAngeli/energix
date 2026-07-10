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

## SEO — ce s-a făcut

- `<title>` ≤ 60 caractere, `description` ≤ 158, **unice pe fiecare pagină și limbă**
  (testat în `PageMetaTest`).
- **H1 dublu**: linia de brand („Curentul ajunge unde trebuie.") rămâne dominantă vizual,
  iar sub ea, în același `<h1>`, expresia-cheie. Poezia nu costă rankingul.
- `alt` descriptiv pe imaginile de conținut, cu localitatea. Imaginile decorative rămân
  `alt=""` + `aria-hidden` — corect, nu o omisiune.
- Canonical, `hreflang` (`ro-MD`, `ru-MD`, `x-default` → RO) și sitemap generate din
  **aceeași** tabelă de rute. Sitemap cu `xhtml:link` per URL.
- 404: fără canonical, cu `noindex, follow`.
- Redirect-uri 301 de la vechile `.html`.

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

## GEO — ambele sensuri, fiindcă amândouă contează aici

**Generative Engine Optimization:**
- Blocul „Energix pe scurt" de pe homepage: un paragraf factual, autonom, care numește
  entitatea, ce face, unde, de când, cum lucrează și ce nu face. Scris ca să poată fi
  citat întreg. E și `description` în JSON-LD.
- `hasOfferCatalog` cu cele trei servicii, `knowsLanguage`, `foundingDate` calculat din
  `experience_years`.

**Geographic / local:**
- `ElectricalContractor` (NU `Electrician` — ar implica autorizare, exclusă) cu
  `areaServed` ca listă de `City`, `openingHoursSpecification`, NAP consistent.
- Secțiunea „Unde lucrăm": sectoarele Chișinăului + localitățile, ca text real, nu doar
  în schema.
- `BreadcrumbList` pe paginile interioare.

## Ce rămâne de făcut (nu s-a putut de aici)

1. **Conectează Ahrefs / Search Console.** Fără ele nu avem volume, poziții, nici date
   despre ce aduce trafic. E prima investiție.
2. **Google Business Profile** — pentru un business local e adesea mai important decât
   site-ul. Nu se poate face din cod.
3. **Fotografii reale**, cu `alt` descriptiv. Galeria stock e un pasiv, nu un activ.
4. **Recenzii** — `AggregateRating` în JSON-LD e permis doar dacă sunt reale și vizibile
   pe site. Nu inventa.
5. **Handle vanity de Facebook** (acum e `profile.php?id=…`) — semnal `sameAs` mai curat.
6. Un blog / ghiduri (`Câte circuite are nevoie o bucătărie`, `Ce secțiune de cablu`)
   ar acoperi long-tail-ul informațional. Efort mare, câștig pe termen lung.
