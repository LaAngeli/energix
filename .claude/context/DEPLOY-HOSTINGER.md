# Energix — deploy pe Hostinger shared hosting

Ținta de hosting este **shared hosting Hostinger**. Totul de mai jos e **verificat prin SSH
pe server** (2026-07-10), nu presupus.

## Acces SSH

Alias configurat în `~/.ssh/config` pe mașina de dezvoltare:

```
Host energix-host
    HostName 45.84.205.155
    Port 65002
    User u890366835
    IdentityFile ~/.ssh/energix_host
    IdentitiesOnly yes
```

Conectare: `ssh energix-host`. Cheie ed25519 dedicată, comentariu `claude-deploy-energix`,
fingerprint `SHA256:6ApX7CxeCOi5gvgU7Zs4gk8pWkgGT7v6Y+wL36iJkTY`.

⚠️ Comenzile `ssh` cu ghilimele **nu** funcționează prin unealta PowerShell (PS 5.1 strică
argumentele pentru executabile native). Folosește unealta **Bash** pentru orice `ssh`.

## Mediul serverului (verificat)

| Fapt | Valoare |
|---|---|
| OS | CloudLinux (Linux 5.14, el9), `lt-bnk-web504.main-hosting.eu` |
| Home | `/home/u890366835` |
| PHP CLI implicit | **8.3.30** — prea vechi |
| PHP 8.4 | `/opt/alt/php84/usr/bin/php` → **8.4.19** ✅ |
| Composer | `/usr/local/bin/composer` → 2.9.8 |
| Git | 2.47.3 |
| `unzip` | prezent |
| **Node / npm** | **ABSENT** |
| Crontab | gol (niciun job) |
| Extensii pe 8.4 | toate cele necesare; lipsește doar `sodium` (Laravel nu-l cere) |
| Bonus disponibil | `redis`, `opcache`, `imagick`, `intl` |

## 🔴 Constrângerea numărul unu: PHP 8.4 obligatoriu

`composer.lock` cere `PHP_VERSION_ID >= 80401`, pentru că Laravel 13 trage **Symfony 8**,
iar toate componentele Symfony 8 (`http-kernel`, `routing`, `mailer`, `console`…) declară
`php: >=8.4.1`. Astea sunt dependențe de **producție**, nu de dev — deci nici măcar
`composer install --no-dev` nu scapă de constrângere.

Consecințe:

1. **PHP-ul web al domeniului `energix.md` trebuie setat pe 8.4 în hPanel**
   (Websites → energix.md → Advanced → PHP Configuration). Altfel: eroare 500 la prima
   cerere, din `vendor/composer/platform_check.php`.
2. **Pe CLI, `php` implicit e 8.3 și crapă.** Orice comandă rulează explicit cu 8.4:
   ```bash
   /opt/alt/php84/usr/bin/php artisan config:cache
   /opt/alt/php84/usr/bin/php /usr/local/bin/composer install --no-dev -o
   ```
   Practic: pune un alias în `~/.bashrc` sau folosește calea completă. Nu presupune `php`.

Notă: `advista.marketing` (același cont) rulează Laravel 13 cu vendor rezolvat pe Symfony 7
(`php: ^8.3`), deci acolo 8.3 e suficient. **Nu extrapola de la el.** Lock-ul nostru e
diferit fiindcă a fost rezolvat pe o mașină cu PHP 8.4.

## Alte constrângeri

| Constrângere | Consecință |
|---|---|
| Fără `supervisor` / procese persistente | **Fără queue worker.** `QUEUE_CONNECTION=sync`. |
| Fără Node.js pe server | **`npm run build` se face local**, iar `public/build/` se urcă. |
| `DO_NOT_UPLOAD_HERE` în rădăcina domeniului | Docroot-ul e fix pe `public_html`. Nu se schimbă din hPanel. |
| Cron disponibil (hPanel) | Momentan neutilizat. Nu ne trebuie. |

## ✅ DECIS (2026-07-10): proiectul rulează FĂRĂ bază de date

Site de prezentare, fără login, fără vânzări, conținut static în Blade.
Singurul flux dinamic e formularul de contact.

Config-ul implicit din `.env` cerea DB pentru **trei** lucruri deodată
(`SESSION_DRIVER`, `CACHE_STORE`, `QUEUE_CONNECTION` = `database`) — o dependență de
bază de date pentru un site care n-are date. Se schimbă în:

```
SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
```

Consecințe, de respectat în tot codul:

- Emailul de contact se trimite **sincron**: `Mail::send()`, **nu** `Mail::queue()`.
  Latența de ~1s la SMTP e acceptabilă pe un formular cu trafic mic și elimină nevoia
  de worker (care oricum nu poate rula pe shared hosting).
- **Fără migrări**, fără `php artisan migrate` la deploy, fără tabele.
- Nu se creează modele Eloquent. Nu se instalează nimic care cere DB.
- `storage/framework/{sessions,cache,views}` trebuie să fie scriibile pe server.

⚠️ Compromisul acceptat: dacă SMTP-ul pică, cererea clientului **se pierde**. Nu există
copie în DB. Mitigare fără bază de date: `Log::error()` pe excepție + un email de
fallback. Dacă asta devine inacceptabil, revenim la o singură tabelă `contact_messages`
pe MySQL — dar e o schimbare de decizie, nu ceva de strecurat pe parcurs.

## Securitate formular (obligatoriu)

Site-ul vechi avea `send_email_php.php` fără **nicio** protecție:
fără CSRF, fără rate limit, fără honeypot, cu `Access-Control-Allow-Origin: *`,
și cu parola SMTP hardcodată. A fost, efectiv, un relay de spam deschis.

În versiunea Laravel:

- `@csrf` (automat prin `web` middleware);
- `throttle:5,1` pe ruta de POST;
- honeypot (câmp ascuns) + verificare `min-time-to-submit`;
- `FormRequest` cu validare strictă (`name`, `prenume`, `phone`, `email`, `message`);
- fără `Access-Control-Allow-Origin: *` — formularul e same-origin;
- credențiale doar din `.env`.

## Structura pe server — tiparul dovedit

`advista.marketing` de pe **același cont** rulează deja Laravel 13 exact așa. Îl copiem:

```
~/domains/energix.md/
├── DO_NOT_UPLOAD_HERE          (marker Hostinger, se ignoră)
├── laravel/                    ← aplicația Laravel, integral
│   ├── app/ bootstrap/ config/ resources/ routes/ storage/ vendor/
│   ├── public/                 ← docroot real
│   └── .env                    ← doar pe server, niciodată în Git
└── public_html -> laravel/public   (SYMLINK)
```

Cheia tiparului: `public_html` e un **symlink** către `laravel/public`. Astfel
`public/index.php` rămâne **nemodificat** — `__DIR__` se rezolvă la calea reală, deci
`__DIR__.'/../vendor/autoload.php'` nimerește corect în `laravel/vendor`. Nu se umblă la
căile din `index.php`, cum se face în tutorialele proaste.

Aplicația stă în afara docroot-ului, deci `.env`, `storage/` și `vendor/` nu sunt
accesibile prin HTTP. Ăsta e și motivul pentru care tiparul e corect, nu doar convenabil.

## Cutover (o singură dată)

Starea actuală: `~/domains/energix.md/public_html/` este un **director real** cu site-ul
static vechi (inclusiv un `.git/` — servit cu 403 de LiteSpeed, dar tot n-are ce căuta acolo).

```bash
cd ~/domains/energix.md
# 1. urcă aplicația în laravel/ (rsync/scp, fără node_modules, .git, tests)
# 2. păstrează vechiul site ca plasă de siguranță
mv public_html public_html_old_static
# 3. comută
ln -s laravel/public public_html
```

Rollback, dacă ceva pică: `rm public_html && mv public_html_old_static public_html`.

## Checklist de deploy

Prescurtare folosită mai jos: `PHP84=/opt/alt/php84/usr/bin/php`

1. **Local:** `npm run build` → produce `public/build/`. (Pe server nu există Node.)
2. **Urcă** proiectul în `~/domains/energix.md/laravel/`, **fără** `node_modules/`,
   `.env`, `.git/`, `tests/`.
3. **🔑 ROTAȚIA PAROLEI — pas obligatoriu, decis 2026-07-10 să se facă exact aici.**
   Înainte de a scrie `.env`-ul de producție:
   a. Generează o parolă aleatoare (min. 20 caractere) în password manager.
   b. Schimb-o în hPanel → Emails → energix.md → contul `contact@` → Change password.
   c. Abia apoi o pui în `MAIL_PASSWORD` din `.env`-ul de pe server.
   Parola veche e commit-uită în istoricul repo-ului vechi. Vezi `SECURITY-NOTES.md`.
   **Nu continua cu pasul 4 dacă acest pas nu e făcut.**

4. **`.env` de producție** se creează direct pe server:
   `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://energix.md`,
   `SESSION_DRIVER=file`, `CACHE_STORE=file`, `QUEUE_CONNECTION=sync`, `MAIL_*`.
5. `$PHP84 /usr/local/bin/composer install --no-dev --optimize-autoloader`
6. `$PHP84 artisan key:generate` (o singură dată).
7. `$PHP84 artisan config:cache && $PHP84 artisan route:cache && $PHP84 artisan view:cache`
8. Permisiuni: `chmod -R 775 storage bootstrap/cache`
9. **hPanel:** PHP-ul domeniului pe **8.4**. Fără asta, 500 garantat.
10. Verifică: `curl -sI https://energix.md` → 200, și `curl -sI https://energix.md/.env` → 403/404.
11. **Verifică canonicalizarea domeniului** — `public_html` nu mai are `.htaccess`-ul vechi,
    deci de-abia acum se vede dacă regulile din `laravel/public/.htaccess` chiar rulează:

    ```bash
    curl -sI http://energix.md/servicii      | grep -i '^location'   # -> https://energix.md/servicii
    curl -sI https://www.energix.md/servicii | grep -i '^location'   # -> https://energix.md/servicii
    curl -s -o /dev/null -w '%{http_code}\n' https://energix.md/servicii   # -> 200, NU 301
    ```

    Al treilea `curl` e cel important: dacă dă 301, ai o **buclă de redirect** și site-ul e
    căzut. Rollback imediat (vezi mai sus).
    Verifică și canonical-ul: `curl -s https://energix.md/servicii | grep canonical` trebuie
    să conțină `https://`, nu `http://`.

⚠️ Ordinea contează: `config:cache` **după** ce `.env` e final. Un `.env` schimbat după
`config:cache` nu are niciun efect — e capcana clasică pe shared hosting.

## Migrarea de la site-ul vechi (SEO)

Redirect-uri 301 obligatorii, altfel se pierde indexarea existentă:

| Vechi | Nou |
|---|---|
| `/index.html` | `/` |
| `/services.html` | `/servicii` |
| `/galery.html` | `/galerie` |
| `/about.html` | `/despre` |
| `/contacts.html` | `/contacte` |
| `/terms_conditions.html` | `/termeni-si-conditii` |
| `/privacy_policy.html` | `/politica-de-confidentialitate` |
| `/cookie_policy.html` | `/politica-cookie` |

De păstrat din site-ul vechi:
- ✅ **forțare HTTPS + non-`www`** — **făcut** (2026-07-10), în `public/.htaccess`.
- header-ele de securitate: `X-Frame-Options`, `X-Content-Type-Options`,
  `Referrer-Policy`, `Strict-Transport-Security` (în Laravel: middleware, nu `.htaccess`);
- `robots.txt` și `sitemap.xml` (sitemap-ul are `lastmod` din 2024 — se regenerează).

⚠️ `.htaccess`-ul vechi conține un comentariu-fosilă: „Redirecționează /index.html către
`https://advista.marketing/`" — copy-paste de la alt proiect. Nu se preia.

## 🔴 Canonicalizarea domeniului: de ce a trebuit rescrisă

Redirectarea `www` → non-`www` **funcționa deja** înainte de cutover. Nu venea însă de la
Hostinger, ci din `.htaccess`-ul **site-ului vechi**, aflat în `public_html/`. La cutover,
`public_html` devine symlink către `laravel/public` — deci fișierul acela **dispare odată
cu el**, iar `https://www.energix.md/` ar fi început să răspundă 200. Două site-uri
identice care își împart semnalele de ranking. `<link rel="canonical">` atenuează, dar nu
înlocuiește un 301.

Regulile stau acum în `public/.htaccess`, înaintea front controller-ului:

1. `/.well-known/` nu se redirectează niciodată — altfel se rupe reînnoirea certificatului.
2. `www.*` → gazda fără `www`, un singur salt.
3. `http` → `https`, cu gardă pe `X-Forwarded-Proto` ca să nu intre în buclă în spatele
   unui proxy care termină TLS.

Domeniul **nu e scris în clar**: `%1` și `%{HTTP_HOST}` îl preiau din cerere.

**Verificat pe un Apache 2.4 real** (XAMPP, `httpd -X` pe un port liber), nu presupus —
matrice: apex/www × http/`X-Forwarded-Proto: https`, ACME challenge, query string,
gazdă cu majuscule, gazdă `wwwx.` (nu trebuie redirectată). Herd rulează nginx local, deci
`.htaccess` nu se poate testa din Pest; `tests/Feature/HtaccessTest.php` apără doar
prezența și **ordinea** regulilor — scheletul Laravel regenerează fișierul la upgrade.

### `URL::forceScheme('https')` în producție

`route()` ia schema din **cerere**, nu din `APP_URL`. Azi LiteSpeed servește direct și pune
`HTTPS=on`. Dar `advista.marketing`, de pe **același cont**, rulează în spatele Cloudflare.
Dacă `energix.md` ajunge vreodată acolo, proxy-ul termină TLS și trimite mai departe `http`,
iar Laravel ar genera `canonical`, `hreflang` și `sitemap.xml` cu `http://` — exact opusul
redirectărilor 301 de mai sus.

**Nu** folosim `trustProxies(at: '*')`: ar face `X-Forwarded-For` demn de încredere, iar
`throttle:5,1` de pe formular se cheiește pe IP. Oricine l-ar putea ocoli rotind antetul.

### HSTS: `preload` a fost lăsat afară, intenționat

Site-ul vechi trimitea `Strict-Transport-Security: … preload`. Middleware-ul nostru nu.
`preload` nu face nimic dacă domeniul nu e înscris la hstspreload.org, iar înscrierea e o
ușă cu sens unic: scoaterea din listă durează luni și trece prin release-urile browserelor.
De activat doar dacă cineva chiar înscrie domeniul.
