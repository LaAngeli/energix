# Energix — context de proiect

Fișier de context permanent. Orice sesiune nouă (inclusiv fork) trebuie să pornească de aici.

## Ce construim

Rescrierea în Laravel a site-ului **energix.md** — site de **prezentare** pentru o firmă de
servicii electrice din Chișinău, Republica Moldova.

- **Fără** autentificare / cabinet de client.
- **Fără** vânzări online, coș, plăți, produse.
- Singura interacțiune server-side reală: **formularul de contact** (trimite email).
- Accent explicit al clientului pe **design** și **responsivitate** (mobile-first).

Site-ul vechi (static HTML) trăiește în `C:\xampp\htdocs\energix` și e folosit **doar ca sursă
de conținut și referință**. Nu se copiază codul lui.

## Stack confirmat (verificat, nu presupus)

| Componentă | Versiune |
|---|---|
| PHP | 8.4.23 (Herd) |
| Laravel | 13.19.0 |
| Tailwind CSS | 4.3.2 |
| Vite | 8.x |
| Pest | 4.7.5 |
| Pint | 1.29.3 |
| Laravel Boost | 2.4.12 |

Frontend: **Blade + Tailwind v4 + Vite**. Fără Livewire, fără Inertia, fără React/Vue —
nimic nu justifică costul lor pe un site de prezentare.

## ⚠️ Capcană de mediu: două instalări PHP

`C:\laragon\bin\php\php-8.3.30\php.exe` apare înaintea Herd în PATH-ul din **Bash**.
`composer.lock` cere `>= 8.4.1`, deci `php artisan` **crapă** din Bash cu
`platform_check.php` RuntimeException.

- **Folosește unealta PowerShell pentru orice comandă `php` / `artisan` / `pint`.** Acolo
  `php` se rezolvă la `~\.config\herd\bin\php.bat` → PHP 8.4.23. Verificat că merge.
- Din Bash, dacă chiar trebuie: `~/.config/herd/bin/php84/php.exe artisan ...`
- MCP-ul `laravel-boost` pornește corect (folosește același `php` ca PowerShell).

## URL local

Servit de Herd la `https://energix.test` (vezi `APP_URL`). Nu porni `php artisan serve`.

## Structură țintă a paginilor

Mapare 1:1 după site-ul vechi, minus ce s-a exclus (vezi `BUSINESS.md`):

| Rută | Pagină veche | Note |
|---|---|---|
| `/` | `index.html` | hero, servicii, de ce noi, proces, preview galerie, CTA |
| `/servicii` | `services.html` | 4 secțiuni (era 5) |
| `/galerie` | `galery.html` | filtre: rezidențial, industrial (era + smart) |
| `/despre` | `about.html` | echipă, valori, cifre |
| `/contacte` | `contacts.html` | date contact + formular |
| `/termeni-si-conditii` | `terms_conditions.html` | |
| `/politica-de-confidentialitate` | `privacy_policy.html` | |
| `/politica-cookie` | `cookie_policy.html` | |

Notă: vechiul URL era `galery.html` (typo). La rescriere folosim `/galerie` și punem
redirect 301 din `/galery.html` ca să nu pierdem ce indexare există.

## Reguli de lucru

- Convențiile din `CLAUDE.md` (Laravel Boost) sunt obligatorii — inclusiv `search-docs`
  înainte de modificări de cod și `vendor/bin/pint --dirty` după modificări PHP.
- Skill-uri active pentru acest proiect: `laravel-best-practices`, `pest-testing`,
  `tailwindcss-development`, `frontend-design`, `seo-audit`.
- Nu se adaugă dependențe fără aprobare.
- Nu se creează foldere de bază noi fără aprobare.
