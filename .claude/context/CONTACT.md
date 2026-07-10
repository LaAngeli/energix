# Energix — date de contact, social, identificatori

Sursă: site-ul vechi `C:\xampp\htdocs\energix` (index/services/about/contacts.html,
JSON-LD, footer). Extras 2026-07-10.

**Aceste date sunt sursa unică de adevăr.** În Laravel se pun în `config/energix.php`
(sau `config/contact.php`) și se citesc cu `config()` — nu se hardcodează în Blade.

## NAP (Name / Address / Phone)

| Câmp | Valoare |
|---|---|
| Denumire | Energix |
| Telefon | `+373 68 582 016` |
| Telefon (tel:) | `tel:+37368582016` |
| Email | `contact@energix.md` |
| Localitate | Chișinău |
| Țară | Republica Moldova (`MD`) |
| Zonă deservită | Republica Moldova (toată țara) |
| Domeniu | `https://energix.md` (fără `www`, doar HTTPS) |

Nu există adresă stradală publică pe site-ul vechi — doar „Chișinău, Moldova".
Nu există coordonate GPS și nici hartă încorporată.

## Program

- Luni–Vineri: 08:00 – 20:00
- Sâmbătă: 09:00 – 17:00
- Duminică: închis (implicit — nu era menționat)
- ~~Urgențe: 24/7~~ — era pe site-ul vechi; **eliminat pe site-ul nou** (2026-07-10,
  business-ul nu face intervenții — vezi `BUSINESS.md`).

Promisiunea de răspuns pe site-ul nou (unică): „Te sunăm înapoi în aceeași zi lucrătoare."

## Rețele sociale și mesagerie

| Canal | URL |
|---|---|
| Facebook | `https://facebook.com/profile.php?id=61567185351755` |
| Instagram | `https://instagram.com/energix_electrician_moldova_/` |
| Telegram | `https://t.me/energix_md` |
| WhatsApp | `https://wa.me/37368582016` |
| Viber | `viber://chat?number=37368582016` |

Note:
- Facebook e un URL de tip `profile.php?id=…` — urât și fragil. Merită cerut clientului
  un handle vanity (`facebook.com/energix.md`).
- Viber folosește schema `viber://`, care nu funcționează pe desktop fără client instalat.
  De afișat condiționat sau cu fallback.
- Handle-ul de Instagram conține „electrician" — vezi excluderile din `BUSINESS.md`
  înainte de a-l folosi ca text vizibil.

## Servicii terțe / identificatori

| Serviciu | ID |
|---|---|
| Google Tag Manager | `GTM-K3K2BR3B` |
| Font Awesome Kit | `a0de0edd34` |

⚠️ Font Awesome via kit CDN + Google Fonts CDN erau două request-uri blocante externe.
La rescriere: iconițe SVG inline (sau `blade-icons`), fonturi self-hosted prin
`laravel-vite-plugin/fonts` (Bunny). Vezi `DESIGN.md`.

⚠️ GTM încarcă cookie-uri înainte de consimțământ în site-ul vechi. La rescriere,
GTM trebuie inițializat **după** accept în banner-ul de cookie (GDPR).

## Email / SMTP

Site-ul vechi trimitea prin PHPMailer direct la Hostinger:

```
Host:  smtp.hostinger.com
Port:  465  (SMTPS / SSL)
User:  contact@energix.md
From:  contact@energix.md ("Energix")
To:    contact@energix.md
```

🚨 **INCIDENT DE SECURITATE — de rezolvat înainte de orice altceva.**
Parola SMTP a căsuței `contact@energix.md` este scrisă în clar în
`send_email_php.php`, iar fișierul este **commit-uit** în repo-ul Git
`https://github.com/LaAngeli/energix.git`. Parola trebuie **schimbată**, nu doar
ștearsă din fișier — istoricul Git o păstrează. Vezi `SECURITY-NOTES.md`.

În proiectul nou: credențialele merg **exclusiv** în `.env`
(`MAIL_*`), iar `.env` rămâne în `.gitignore`.

## Autor / agenție

Site-ul vechi e semnat „Created by AdVista" → `https://advista.marketing`.
De confirmat dacă se păstrează creditul în footer.

## Copyright

`© 2026 Energix. Toate drepturile rezervate.` — anul trebuie generat dinamic, nu hardcodat.
