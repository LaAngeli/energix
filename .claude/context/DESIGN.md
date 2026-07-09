# Energix — direcție de design

Clientul a cerut **accent pe design și pe modul responsiv**. Acest fișier fixează punctul
de plecare; nu e o lege, e o bază de la care se argumentează schimbările.

## Identitatea existentă (site vechi)

Temă **dark**, „electric / tech". Tokens extrase din `styles.css`:

```
--bg-primary      #0a0e17   (fundal principal, aproape negru-albastru)
--bg-secondary    #0f1420
--bg-tertiary     #151b2b
--bg-card         rgba(15, 20, 32, 0.8)

--electric-blue   #00d4ff   ← culoarea de brand / accent principal
--electric-purple #7b2cbf   ← a doua culoare din gradient
--amber           #ffb800   ← accent secundar
--amber-light     #ffd54f
--success         #00e676
--danger          #ff5252

--text-primary    #ffffff
--text-secondary  #a8b2c1
--text-muted      #6b7688
```

Gradient de brand: `linear-gradient(135deg, #00d4ff, #7b2cbf)`
`theme-color` / `color-scheme`: `#0a0e17` / `dark`

Radius: 8 / 12 / 20px. Container: 1200px. Secțiuni: 100px vertical.

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

## Componente Blade de anticipat

`layouts/app`, `partials/navbar`, `partials/footer`, `partials/cookie-banner`,
`components/section-header`, `components/service-card`, `components/cta-band`,
`components/stat-item`, `components/back-to-top`.

## Skill-uri de folosit

- `frontend-design` — pentru direcția vizuală, înainte de a scrie markup.
- `tailwindcss-development` — pentru orice clasă Tailwind.
- `laravel-best-practices` (`rules/blade-views.md`) — pentru structura Blade.
