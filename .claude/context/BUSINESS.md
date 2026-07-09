# Energix — nișă și context de business

## Ce este business-ul

Firmă de **servicii electrice** (electrician / instalații electrice) din **Chișinău**,
care deservește toată **Republica Moldova**. Model B2C + B2B mic:

- **B2C** — proprietari de apartamente/case, renovări, construcții noi.
- **B2B** — spații comerciale, magazine, hale, depozite, mici unități de producție.

Vânzarea este **lead-based**: site-ul nu vinde nimic direct. Obiectivul unic al fiecărei
pagini este să genereze un **apel telefonic** sau o **cerere de ofertă**. Ciclul de decizie
e scurt, intenția e locală și adesea urgentă („mi-a picat curentul").

## Implicații pentru design și conținut

Acestea nu sunt preferințe estetice, sunt consecințe ale modelului de business:

1. **Telefonul este CTA-ul principal**, nu formularul. Pe mobil trebuie să fie
   permanent la un tap distanță (buton sticky / bară de acțiune).
2. **Trust > estetică**. Într-un domeniu unde o greșeală arde casa clientului, semnalele
   de încredere (garanție, lucrări reale, ani de experiență, recenzii) contează mai mult
   decât animațiile.
3. **Trafic majoritar mobil**, adesea din Google Maps / Facebook. Mobile-first, nu
   desktop-first cu breakpoint-uri adăugate ulterior.
4. **Local SEO** e canalul principal: `schema.org` LocalBusiness, `areaServed` Moldova,
   NAP consistent, pagina de contact bogată.
5. **Fotografii reale ale lucrărilor** vând mai bine decât stock. Galeria e o unealtă de
   vânzare, nu decor.

## Servicii — starea DUPĂ excluderile cerute

Clientul a cerut explicit **excluderea** a două lucruri (2026-07-10). Se aplică peste tot.

### ❌ EXCLUS: „Electricieni Autorizați" — și orice altă afirmație de certificare

**Decis de client 2026-07-10:** excluderea acoperă **orice** afirmație de licențiere sau
certificare, nu doar formularea aceea. Site-ul nu revendică nicio autorizație.

Nu apare nicăieri, sub nicio formă. Include, dar nu se limitează la:

- badge-ul din hero „Electrician Autorizat";
- `<title>` de pe homepage („Energix - Electrician Autorizat | …");
- item-ul „Electricieni Autorizați" din secțiunea „De Ce Noi";
- subtitlul de pe `/servicii` („…executate de electricieni autorizați");
- cardurile de echipă de pe `/despre` („Electricieni Autorizați",
  **„Electricieni certificați ANRE"**);
- `"@type": "Electrician"` din JSON-LD → se folosește `LocalBusiness` /
  `ElectricalContractor` fără afirmații de licențiere.

⚠️ Consecință de care trebuie ținut cont: „electrician autorizat" era probabil cel mai
valoros cuvânt-cheie al site-ului. Eliminarea lui lasă un gol de semnal de încredere care
**trebuie umplut** cu altceva (garanție pentru lucrări, ani de experiență, portofoliu,
recenzii).

**Verificare obligatorie înainte de a marca o pagină ca terminată:** caută în ea
`autoriz`, `ANRE`, `certific`, `licenț`, `atestat`. Zero rezultate.

### ❌ EXCLUS: „Automatizări și Smart Home"

Serviciul dispare complet:

- cardul „Smart Home" de pe homepage;
- secțiunea `#automation` de pe `/servicii` și item-ul din nav-ul de servicii;
- link-ul „Smart Home" din footer (pe toate paginile);
- filtrul `smart` din galerie și cele 2 lucrări din categoria respectivă
  („Automatizare Locuință Inteligentă", „Sistem Iluminat Inteligent");
- mențiunile „automatizări" din hero subtitle, meta description, keywords;
- **„Automatizări Industriale"** — sub-punctul din secțiunea Instalații Industriale.
  Decis de client 2026-07-10: **se elimină și el**. Cuvântul „automatizare" nu apare pe
  site, indiferent de context.

**Verificare obligatorie:** caută `smart`, `automatiz`, `inteligent` în pagină. Zero
rezultate.

### ✅ Servicii care rămân (4)

| # | Serviciu | Ancoră | Conținut cheie |
|---|---|---|---|
| 1 | Instalații Electrice Rezidențiale | `#rezidentiale` | tablouri electrice, prize și întrerupătoare, cablare completă, verificări și măsurători |
| 2 | Instalații Electrice Industriale | `#industriale` | tablouri de distribuție, cablare utilaje, monitorizare consum ⚠️ *fără* „Automatizări Industriale" |
| 3 | Reparații și Mentenanță | `#reparatii` | intervenții de urgență, diagnosticare, reparații, mentenanță preventivă |
| 4 | Audit Energetic | `#audit` | analiza consumului, identificare pierderi, plan de optimizare, raport detaliat |

## Proces de lucru (rămâne, 4 pași)

1. **Contactare** — apel sau formular, consultație gratuită.
2. **Evaluare** — deplasare la fața locului.
3. **Ofertă** — preț fix, termene clare.
4. **Execuție** — profesional, curat, în termen.

## Valori (pagina Despre)

Siguranță · Calitate · Încredere · Inovație

## Avantaje / „De ce noi" — după excludere

Rămân 3 din 4. Slotul liber trebuie umplut (vezi întrebări deschise):

- Garanție pentru lucrări
- Disponibilitate non-stop (urgențe 24/7)
- Prețuri transparente

## Echipă

- **Dima Sandulescu** — Fondator & Electrician Principal.
- „Echipa Tehnică" și „Suport Clienți" erau carduri generice fără persoane reale.

## Cifre afișate pe site vechi (NEVERIFICATE)

| Metrică | Valoare |
|---|---|
| Proiecte finalizate | 500 |
| Clienți mulțumiți | 350 |
| Ani experiență | 8 |
| Localități deservite | 50 |

⚠️ **Contradicție în site-ul vechi:** homepage-ul spune „8 Ani Experiență", dar pagina
Despre spune că Dima are „peste 10 ani de experiență". Trebuie clarificat cu clientul
înainte de a reafișa cifrele.

## Program

- Luni–Vineri: 08:00 – 20:00
- Sâmbătă: 09:00 – 17:00
- Urgențe: 24/7

## Ton de voce

Româna (`ro`), adresare la persoana a II-a singular („proiectul tău", „contactează-ne").
Direct, practic, fără jargon tehnic inutil. Se păstrează.

## Decizii luate (2026-07-10)

- ✅ Excluderea acoperă **orice** afirmație de certificare/licențiere, nu doar sintagma.
- ✅ „Automatizări Industriale" **se elimină** odată cu Smart Home.

## Întrebări încă deschise

1. **Ce umple al 4-lea slot din „De ce noi"?** Locul lăsat liber de „Electricieni
   Autorizați". Candidați: garanție extinsă, portofoliu verificabil, recenzii reale,
   deplasare gratuită pentru evaluare. Fără el, secțiunea rămâne cu 3 itemi (acceptabil
   vizual, dar pierdem un semnal de încredere exact acolo unde e nevoie de el).
2. **Se confirmă cifrele (500/350/8/50)?** Și care e numărul real de ani — homepage-ul
   vechi zice 8, pagina Despre zice „peste 10". Nu reafișăm cifre contradictorii.
3. **Există fotografii reale ale lucrărilor?** Cele 9 imagini din site-ul vechi arată a
   stock/AI. Într-o nișă bazată pe încredere, galeria falsă face mai mult rău decât bine.
4. **Timp de răspuns:** homepage promite „Răspuns în 1 Oră", formularul zice „maxim 24 de
   ore". De ales unul.
