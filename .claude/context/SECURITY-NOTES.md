# Energix — constatări de securitate din site-ul vechi

Descoperite în timpul analizei `C:\xampp\htdocs\energix` (2026-07-10). Nu sunt teoretice.

## 🚨 1. Parolă SMTP în clar, commit-uită în Git — CRITIC

**Fișier:** `send_email_php.php` (atribuirea `$mail->Password = '...'`)
**Repo:** `https://github.com/LaAngeli/energix.git` (remote `origin`, fișier urmărit de Git)

Parola căsuței `contact@energix.md` este scrisă literal în cod și se află în istoricul
Git. Cine are acces la repo are controlul complet al căsuței: citire corespondență,
trimitere email în numele firmei, reset de parolă la alte servicii legate de acea adresă.

În plus, parola este derivată din numele fondatorului plus o cifră — exact tiparul pe care
îl încearcă primul orice atac de dicționar.

**Acțiuni, în ordine:**

1. **Schimbă parola** căsuței `contact@energix.md` în hPanel Hostinger. Acum.
   Ștergerea fișierului **nu** e suficientă — parola rămâne în istoricul Git.
2. Verifică dacă repo-ul e public. Dacă da, presupune că parola e deja compromisă
   și verifică log-urile de trimitere pentru abuz.
3. Parola nouă: generată aleator, min. 20 caractere, păstrată în password manager,
   introdusă **doar** în `.env` (`MAIL_PASSWORD`), niciodată în cod.
4. Opțional, dacă repo-ul e privat și vrei curățenie: rescrie istoricul
   (`git filter-repo`) — dar tot după ce ai schimbat parola.

## 🔴 2. Endpoint de email neprotejat — relay de spam deschis

`send_email_php.php` accepta orice `POST` de pe orice origine
(`Access-Control-Allow-Origin: *`, metode `POST, GET, OPTIONS`).

Fără CSRF, fără rate limiting, fără CAPTCHA, fără honeypot. Oricine putea trimite
volume nelimitate de email prin serverul SMTP al firmei — ceea ce duce la
blacklisting-ul domeniului `energix.md` și la pierderea deliverabilității.

Nu se reimplementează. Vezi `DEPLOY-HOSTINGER.md` § „Securitate formular".

## 🟠 3. GTM se încarcă înainte de consimțământ

Snippet-ul Google Tag Manager (`GTM-K3K2BR3B`) rulează în `<head>`, înaintea
banner-ului de cookie. Banner-ul e decorativ: „Accept" nu controlează nimic.
Sub GDPR (aplicabil — se colectează date de la utilizatori europeni), asta e
neconformitate. La rescriere, GTM se inițializează **după** consimțământ.

## 🟡 4. `$_POST` folosit fără validare

Câmpurile intrau direct în subiectul și corpul emailului, cu `htmlspecialchars()` ca unică
apărare. Fără validare de tip, fără lungime verificată server-side (doar `maxlength`
în HTML, trivial de ocolit), fără verificare că `email` e chiar un email.
Subiectul emailului era construit din input de utilizator — vector de
header injection dacă `htmlspecialchars` n-ar fi fost acolo.

## 🟡 5. Bibliotecă PHPMailer copiată în repo

`src/PHPMailer.php`, `src/SMTP.php`, `src/Exception.php` + `language/` — vendorizate
manual, fără Composer, deci fără actualizări de securitate. În Laravel, mailer-ul e
inclus și întreținut.

---

**Concluzie:** niciuna dintre aceste probleme nu se transferă în proiectul nou, dar
**punctul 1 trebuie rezolvat independent de rescriere** — parola e compromisă acum,
nu la deploy.
