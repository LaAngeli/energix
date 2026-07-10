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

## Site bilingv: RO la rădăcină, RU sub `/ru`

| Rută RO | Rută RU | Pagină veche |
|---|---|---|
| `/` | `/ru` | `index.html` |
| `/servicii` | `/ru/uslugi` | `services.html` |
| `/galerie` | `/ru/raboty` | `galery.html` |
| `/despre` | `/ru/o-nas` | `about.html` |
| `/contacte` | `/ru/kontakty` | `contacts.html` |
| `/termeni-si-conditii` | `/ru/usloviya` | `terms_conditions.html` |
| `/politica-de-confidentialitate` | `/ru/konfidencialnost` | `privacy_policy.html` |
| `/politica-cookie` | `/ru/cookie` | `cookie_policy.html` |

Notă: vechiul URL era `galery.html` (typo). Redirect 301 din `/galery.html`.

### Cum funcționează localizarea

- **Structura** (slug-uri, imagini, amperaje, program numeric) → `config/energix.php`.
- **Tot textul** → `lang/ro/site.php` și `lang/ru/site.php`. Vederile folosesc `__('site.…')`.
- **Rutele** sunt înregistrate de două ori: `services` (RO) și `ru.services` (RU).
  Vederile nu știu asta — folosesc `URL::localized('services')`, un macro definit în
  `AppServiceProvider`. Pentru cealaltă limbă: `URL::inLocale('ru', 'services')`.
- **Limba NU se ghicește din `Accept-Language`.** Fiecare limbă are URL-ul ei, iar o
  redirectare automată ar sparge indexarea: Googlebot crawlează cu un singur set de headere.
- `canonical`, `hreflang` și `sitemap.xml` se generează toate din tabela de rute, deci
  dintr-o singură sursă (`APP_URL`). Un canonical care nu se potrivește cu hreflang îi
  spune lui Google să ignore ambele.

⚠️ **Un site bilingv se strică tăcut.** Dacă adaugi o cheie doar în `ro`, Blade afișează
`site.faq.6.q` în pagină, fără să crape. `ContentTest` compară cheile celor două fișiere,
iar `PagesTest` caută chei brute în HTML-ul randat.

⚠️ **Excluderile de business se aplică și în rusă.** `ремонт` e cuvântul normal pentru
renovare — de aceea `ContentExclusionsTest` vânează rădăcini în ambele limbi.

## Git — istorie deliberat separată de a site-ului vechi

Repo: `https://github.com/LaAngeli/energix.git` (remote `origin`).

`origin/main` conține încă **site-ul static vechi**, cu o parolă SMTP commit-uită în clar
(vezi `SECURITY-NOTES.md`). Decis 2026-07-10: **nu facem merge** cu acel istoric.

Starea:

| Ref | Ce e |
|---|---|
| `laravel-rewrite` | **ramura de lucru.** Istorie orfană, fără părinte comun cu `origin/main`. Trackează `origin/laravel-rewrite`. |
| `origin/main` | site-ul static vechi. **Neatins.** Se rescrie doar la cutover. |
| `legacy-static-site` | copie locală a lui `origin/main` dinainte de rescriere |
| tag `site-static-2024` | același commit, marcat |

Nu există branch `main` local — intenționat, ca să nu-l comiți din greșeală.

**De ce istorie orfană:** e singurul drum prin care parola scursă dispare efectiv de pe
GitHub. Un `merge -s ours` ar fi păstrat commit-urile vechi ca strămoși, deci secretul ar
fi rămas accesibil pe vecie în repo-ul nou.

**Cutover-ul pe GitHub (NU s-a făcut încă):**

```bash
git push --force origin laravel-rewrite:main
```

Se face **doar** când site-ul nou e gata, **doar la cererea explicită a userului** și
**doar după** ce parola a fost schimbată în hPanel. `--force` e acceptabil: repo personal,
nimeni altcineva nu a clonat. Istoricul vechi rămâne local pe `legacy-static-site` /
`site-static-2024`. **Nu-l urca pe GitHub** — ar readuce secretul.

⚠️ `.gitignore` ignoră `/public/build`. Deci **nu poți face deploy prin `git pull` pe
server** (unde nu există Node). Asset-urile compilate se urcă separat, prin `rsync`/`scp`.

## Reguli de lucru

- Convențiile din `CLAUDE.md` (Laravel Boost) sunt obligatorii — inclusiv `search-docs`
  înainte de modificări de cod și `vendor/bin/pint --dirty` după modificări PHP.
- Skill-uri active pentru acest proiect: `laravel-best-practices`, `pest-testing`,
  `tailwindcss-development`, `frontend-design`, `seo-audit`.
- Nu se adaugă dependențe fără aprobare.
- Nu se creează foldere de bază noi fără aprobare.
