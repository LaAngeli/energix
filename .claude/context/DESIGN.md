# Energix — direcție de design

Clientul a cerut **accent pe design și pe modul responsiv**. Acest fișier fixează punctul
de plecare; nu e o lege, e o bază de la care se argumentează schimbările.

## 🔴 Corecție de brand (2026-07-10): albastrul electric NU e în logo

Culorile au fost **extrase din pixelii logo-ului**, nu preluate din CSS-ul vechi:

| Culoare | Hex | De unde |
|---|---|---|
| Auriu | `#F2D147` | becul din logo (media pixelilor) |
| Bleumarin | `#091A31` | fundalul oficial (`energix-blue-bg.png`) |
| Alb | `#FFFFFF` | wordmark |

Cyan-ul `#00d4ff` din site-ul vechi era o **alegere de web design, nu culoare de brand**.
La fel și fundalul `#0a0e17`.

Marca: un bec cu raze, iar în interiorul lui un **„Y"** — care se citește ca simbolul
**conexiunii în stea (wye)** dintr-un sistem trifazat. E cel mai specific și mai ownable
semn al brandului.

### Paleta implementată

| Token | Hex | Rol | Contrast pe `ink` |
|---|---|---|---|
| `--color-ink` | `#091a31` | fundal | — |
| `--color-ink-raised` | `#0f2540` | carduri, suprafețe ridicate | — |
| `--color-line` | `#22395b` | conductori, hairline, contururi | — |
| `--color-gold` | `#f2d147` | **primar** — CTA, nodul Y, „sub tensiune" | 11.6:1 ✅ |
| `--color-cyan` | `#00d4ff` | **doar instrument** — schemă, măsurare | 10.8:1 ✅ |
| `--color-paper` | `#eaf1fb` | text principal | 15.4:1 ✅ |
| `--color-paper-dim` | `#93a6c4` | text secundar | 7.0:1 ✅ |

Banda luminoasă (o singură apariție pe site, secțiunea de răspunsuri):

| Token | Hex | Contrast pe `sheet` |
|---|---|---|
| `--color-sheet` | `#edf1f6` | — |
| `--color-graphite` | `#0e1b2e` | 15.2:1 ✅ |
| `--color-graphite-dim` | `#47566c` | 6.6:1 ✅ |

### 🔒 Reguli dure de paletă (verificate în browser)

1. **Auriul trăiește doar pe bleumarin.** Pe `sheet` dă **1.32:1** — invizibil.
   Pe foaie, indicatorii și glyph-urile sunt `graphite` / `graphite-dim`.
   Componenta `<x-signature.wye>` are prop-ul `:on-sheet` exact pentru asta.
2. **Cyan-ul nu iese din schemă.** Niciodată CTA, niciodată pe foaie.
3. Contrastele de mai sus sunt **măsurate**, nu estimate.

## Tipografie

Site-ul vechi folosea **Orbitron** (titluri) + **Rajdhani** (text), de la Google Fonts CDN.

### ✅ DECIS (2026-07-10): tipografia se schimbă

Motiv: Orbitron e un font display sci-fi/gaming — pe un business care vinde *siguranță
electrică* citește a template gratuit, nu a firmă serioasă. Rajdhani e condensat, cu
x-height mic, deci lizibilitate slabă la corp de text pe mobil — exact unde vine
majoritatea traficului.

**Ce se păstrează:** paleta electric-blue (`#00d4ff`) pe dark. E recognoscibilă și
funcționează. Schimbarea e strict tipografică.

### ✅ Perechea aleasă (implementată, verificată în browser)

| Rol | Font | Weight | Variabilă | Unde |
|---|---|---|---|---|
| Display | **Archivo** | 700 | `--font-display` | h1, h2, h3 |
| Corp | **IBM Plex Sans** | 400, 500 | `--font-sans` | text |
| Date / etichete | **IBM Plex Mono** | 500 | `--font-mono` | eyebrow, cifre, telefon, etichete de schemă |

Archivo (Omnibus-Type) e un grotesk compact, desenat pentru titluri: x-height mare,
terminații plate, autoritate fără sci-fi. IBM Plex e vocea documentației tehnice — se
potrivește unui business care vinde corectitudine, iar Plex Mono în rol de *etichetă de
schemă* e ce dă personalitate paginii, nu un al treilea grotesk decorativ.

Un singur weight pe display: ierarhia o face **scara**, nu grosimea.

### Diacritice — verificat, nu presupus

Româna cere `ș` `ț` `Ș` `Ț` (U+0218–U+021B) cu **virgulă dedesubt**, nu sedilă.

- **Bunny ignoră parametrul `subset`** și servește toate subseturile, fiecare cu propriul
  `unicode-range`. Opțiunea `subsets` din plugin e un **no-op** pentru providerul `bunny`.
  `latin-ext` (U+0100–02BA) vine automat.
- Toate cele trei familii **conțin** glifele (verificat prin măsurare pe canvas, după
  `document.fonts.load()` — `measureText` singur nu declanșează încărcarea fețelor).
- Forma e cea corectă, cu virgulă detașată (verificat vizual la 120px).

### Preload: dezactivat, intenționat

`preload` nu poate filtra pe subset, doar pe weight. Cu `preload: true` browserul
preîncărca **15 fișiere (~200 KB)**, inclusiv chirilică, greacă și vietnameză — niciodată
folosite. CSS-ul cu `@font-face` e inline în `<head>`, deci descoperirea e oricum imediată
și se descarcă doar subsetul necesar. Fallback-urile metrice (fontaine) rămân active, deci
CLS e controlat.

## Reguli tehnice pentru implementare

1. **Tailwind v4** — tokens în `@theme` din `resources/css/app.css`, nu `tailwind.config.js`.
   Fără `:root { --var }` manual; folosește sintaxa v4.
2. **Fonturi self-hosted** via `laravel-vite-plugin/fonts` (Bunny). Deja configurat în
   `vite.config.js`, dar setat pe `Instrument Sans` — de înlocuit cu fontul final.
   Zero request-uri către `fonts.googleapis.com` (performanță + GDPR).
3. **Iconițe:** SVG inline sau componente Blade. **Fără** kit-ul Font Awesome de pe CDN
   (~70KB JS blocant pentru vreo 25 de iconițe).
4. **Imagini:** `<picture>` cu `webp` + fallback, `loading="lazy"` peste fold,
   `loading="eager"` + `fetchpriority="high"` doar pentru imaginea din hero.
   Asset-uri existente în `C:\xampp\htdocs\energix\images\` (9 imagini de conținut,
   logo + favicon, 1 video de fundal `.mp4` + `.webm`).
5. **Mobile-first.** Se scrie clasa de bază pentru mobil, apoi `sm:` / `md:` / `lg:`.
   Nu invers.
6. **Motion:** respectă `prefers-reduced-motion`. Site-ul vechi are linii electrice
   animate, scântei, contoare care numără — toate trebuie oprite pentru utilizatorii care
   au cerut mișcare redusă.
7. **Contrast:** vechiul `--text-muted #6b7688` pe `#0a0e17` dădea **4.30:1** — sub pragul
   WCAG AA de 4.5:1. **Corectat:** `--color-paper-dim #94a3b8` dă **7.4:1**.
8. **CTA telefon sticky pe mobil** — vezi `BUSINESS.md`, telefonul e conversia principală.

## 🔴 Conceptul (v3, 2026-07-10): „INSTALAȚIA VIE"

Clientul a respins structura clasică de site de prezentare („designul și structura au
rămas aceleași") și a cerut **un site viu, cu numeroase elemente interactive relevante
domeniului**. Conceptul curent: site-ul întreg se comportă ca o instalație sub tensiune.

Sistemele globale (pe toate paginile):

| Sistem | Ce face | Implementare |
|---|---|---|
| **Conductorul** (`.spine`) | fir fix în marginea stângă (xl+) care se umple cu auriu la scroll; un punct de sarcină luminos coboară cu tine | `scaleY(progres)`, transform-only, throttle pe `setTimeout` (rAF îngheață în tab-uri de fundal) |
| **Sonda** (`.probe`) | lumină aurie care urmărește cursorul pe suprafețele navy | gradient radial pe un singur element fix, vars CSS, doar `pointer: fine` + motion-safe |
| **Fișa de lucrare** (`<x-job-sheet>`) | fiecare pagină se deschide ca un document tehnic: cod, obiect, index, LED „sub tensiune" | metadata mono reală, nu decor |
| **Navbar-tablou** | paginile ca circuite numerotate cu LED; circuitul activ e aprins | LED prin `[aria-current]` / `.is-on` |
| **Footer-plăcuță** (`.nameplate`) | datele firmei ca plăcuță de identificare nituită | nituri din `radial-gradient` în colțuri |

Instrumentele per pagină:

- **Home**: hero full-height cu TABLOUL ca piesă centrală; segmentele ca rânduri
  expandabile (`<x-segment-row>`, grid-rows 0fr→1fr); etapele pe cablu; CTA final =
  comutator mare „PUNERE SUB TENSIUNE" care armează bloom-ul telefonului
  (pur ceremonial — telefonul e apelabil oricând, nu se gate-uiește conversia).
- **Servicii**: consolă cu 3 comutatoare de segment (tablist) care schimbă conținutul;
  ancorele vechi `#apartamente/#case/#industriale` selectează tab-ul (hashchange).
  Hero: **calculatorul de circuite** — comuți consumatorii (plită, boiler, climă…),
  instrumentul numără circuitele dedicate și modulele de tablou (`+4` = separator +
  diferențial). Estimare declarată orientativă.
- **Lucrări**: filtrele ca disjunctoare cu LED + contor „N lucrări pe circuit".
  Hero: **contorul electromecanic** — cifre care se rostogolesc (odometru); alegerea
  segmentului **comandă filtrul real** al galeriei de sub el.
- **Despre**: contor de vechime (cifra 10 pe scală gradată) + valorile ca „aparataj de
  protecție". Hero: **nivela cu bulă** — bula urmărește cursorul; adusă la centru,
  confirmă „Drept — la cotă". Pe tactil/reduced-motion stă fixă la zero.
- **Contacte**: formularul e un circuit — fiecare câmp valid închide un segment; toate
  valide → nodul + butonul se armează. Pur vizual, validarea reală rămâne pe server.
  Hero: **starea liniei** — deschis/închis calculat live din program, cu „revenim {zi}
  la {oră}" și buton „Testează linia" (verificare ceremonială cu LED-uri).
  ⚠️ Orarul din `line-status.blade.php` oglindește `config('energix.hours')` — la
  schimbarea programului se actualizează amândouă.

Instrumentele din hero apar doar pe `lg+` (spațiul gol există doar pe desktop);
pe mobil hero-urile rămân text, fără cost.

⚠️ Lecție de mediu: rAF **și** evenimentele de scroll îngheață în tab-uri de fundal
(cadrele vin doar la screenshot în automatizare). Orice stare care contează folosește
`setTimeout` de gardă sau se aplică imediat, nu doar în rAF.

## Elementul-semnătură: TABLOUL DE DISTRIBUȚIE INTERACTIV

Înlocuiește schema monofilară statică (client, 2026-07-10: „inutil de simplistă").
Hero-ul are un **tablou electric funcțional** (`<x-signature.panel>`), nu o ilustrație:

- **Separator general** — comutator real (`role="switch"`); pune tabloul sub tensiune.
- **Voltmetru** — numără 0 → 230 V la energizare (constantă fizică, nu cifră de marketing).
- **Diferențial 30 mA** — LED cyan + buton **TEST** care chiar declanșează totul și
  reanclanșează după ~1s, exact ca pe un tablou real.
- **6 disjunctoare** cu amperaje corecte per circuit (Iluminat 10 A, Prize 16 A,
  Bucătărie 20 A, Boiler 16 A, Climă 16 A, Forță 25 A) — fiecare comutabil; firul și
  consumatorul lui se aprind auriu, cu un puls de curent care coboară pe fir.
- **Prima energizare**: o dată, când tabloul intră în cadru (IntersectionObserver).
  După aceea nimic nu se mișcă fără acțiunea utilizatorului.

Starea „sub tensiune" curge în CSS după topologia reală: sursă → separator → busbar →
disjunctor → consumator. Implementat în HTML/CSS (nu SVG lat): responsive nativ,
3 circuite pe rând pe mobil, 6 pe desktop. Accesibil: `role="switch"`, anunțuri
`role="status"` la fiecare comutare.

Al doilea element interactiv: **etapele pe cablu** (`<x-signature.stages>`) — cele 5
etape ale instalației ca tab-uri (tablist real, săgeți stânga/dreapta), cu un cablu care
se umple auriu până la etapa selectată.

⚠️ Capcane plătite deja:
- `transform` din CSS suprascrie atributul `transform` din SVG (de-asta panoul e HTML).
- `requestAnimationFrame` îngheață în tab-uri de fundal — voltmetrul are un `setTimeout`
  de gardă care garantează valoarea finală.

## Componente Blade (implementate)

`layouts/app` · `partials/{navbar,footer,sticky-call,cookie-banner}` ·
`seo/{head,json-ld}` · `signature/{wye,panel,stages}` · `section-header` · `service-card` ·
`answer-cote` · `promise-item` · `process-step` · `value-item` · `gallery-grid` ·
`cta-band` · `contact-form`

## Motion

Un singur limbaj: **energizare la intrare, o dată, niciodată în buclă.**

- Load: schema se alimentează (secvența de mai sus). Singurul moment orchestrat.
- Scroll: `IntersectionObserver` → `opacity` + `translateY` mic. Glyph-urile Y comută
  `line → gold`. **Niciodată legat de poziția scroll-ului** (paint-bound, janează).
- Hover: cardurile se încălzesc spre auriu; CTA primește un bloom auriu.
- **Zero** mișcare ambientală, zero count-up pe cifre neverificate.
- `prefers-reduced-motion`: **starea finală, instant** — nu o versiune mai lentă.
  Gardat pe două niveluri: `@media` în CSS **și** `matchMedia` în JS.

## Skill-uri de folosit

- `frontend-design` — pentru direcția vizuală, înainte de a scrie markup.
- `tailwindcss-development` — pentru orice clasă Tailwind.
- `laravel-best-practices` (`rules/blade-views.md`) — pentru structura Blade.
