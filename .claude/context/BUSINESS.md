# Energix — nișă și context de business

## Ce este business-ul (redefinit de client, 2026-07-10)

Firmă din **Chișinău** care execută **instalații electrice complete, de la zero, în
construcții**: toate etapele de instalare și conectare a rețelei electrice și a
elementelor aferente, pentru **apartamente, case și zone industriale**. Deservește
toată **Republica Moldova**.

- **B2C** — proprietari de apartamente/case: bloc nou, casă în construcție, renovare completă.
- **B2B** — spații comerciale, hale, depozite, mici unități de producție.

🔴 **Business-ul NU face service.** Fără intervenții urgente 24/7, fără reparații
izolate, fără mentenanță, fără chemări legate de funcționarea proastă a electricității.
Vezi excluderea #4 de mai jos.

Vânzarea este **lead-based**: site-ul nu vinde nimic direct. Obiectivul unic al fiecărei
pagini este să genereze un **apel telefonic** sau o **cerere de ofertă**. Ciclul de
decizie e de tip proiect (zile–săptămâni), nu de tip urgență.

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

### ❌ EXCLUS: „Audit Energetic"

Decis de client 2026-07-10. Serviciul dispare complet:

- secțiunea `#audit` de pe `/servicii` și item-ul „Audit" din nav-ul de servicii;
- link-ul „Audit Energetic" din footer (pe toate paginile);
- mențiunile „audit energetic" din meta description și `<meta keywords>`;
- textul din hero-ul homepage-ului.

**Verificare obligatorie:** caută `audit`, `energetic` în pagină. Zero rezultate.

### ❌ EXCLUS: reparații, mentenanță, intervenții urgente (decis 2026-07-10)

Business-ul face **doar instalații complete de la zero**. Dispar complet:

- serviciul „Reparații și Mentenanță" (fost al 3-lea serviciu);
- orice promisiune de „Intervenții urgente 24/7" / „non-stop" (era în promises, footer,
  program, răspunsuri, proces);
- rândul „Urgențe: 24/7" din program (pe toate paginile);
- formulări de tip „reparăm ce s-a stricat", „depanare", „defect".

**Verificare obligatorie (în `ContentExclusionsTest`):** caută `reparat`, `mentenan`,
`urgen`, `non-stop`, `24/7`, `depan`. Zero rezultate în HTML-ul randat.

### ✅ Oferta reală: instalația completă, pentru 3 segmente

Un singur serviciu — **toate etapele instalației** — pentru trei tipuri de spații:

| # | Segment | Ancoră | Conținut cheie |
|---|---|---|---|
| 1 | Apartamente | `#apartamente` | schema pe circuite, trasee/doze/cablare, tablou echipat, prize/iluminat, verificări și punere sub tensiune |
| 2 | Case | `#case` | proiect pe etaje + exterior, racord de la branșament, cablare int/ext, consumatori mari, împământare + predare cu schemă |
| 3 | Spații industriale | `#industriale` | tablouri de distribuție și forță, jgheaburi/poduri de cabluri, alimentare utilaje, iluminat industrial, documentație |

Cele **5 etape** (conținutul secțiunii interactive de pe homepage, `config('energix.stages')`):
Proiectare → Trasee și cablare → Tabloul electric → Montajul final → Verificare și predare.

## Proces de lucru (drumul clientului, 4 pași)

1. **Ne suni** — ne spui despre proiect: apartament, casă sau spațiu industrial.
2. **Venim și ne uităm** — la fața locului sau direct pe planuri. Evaluare gratuită.
3. **Primești oferta** — preț fix, în scris, cu termen și etape.
4. **Executăm și predăm** — toate etapele; la final măsurători + schema tabloului.

## Valori (pagina Despre)

Siguranță · Transparență · Curățenie · Punctualitate

## Avantaje / „De ce noi" (`config('energix.promises')`)

Toate 4 sloturile pline — fără urgențe, fără certificări:

- Garanție pentru lucrări
- Preț fix în ofertă
- Evaluare gratuită
- **Predare cu măsurători** (umple slotul rămas liber: primești instalația verificată
  și schema tabloului)

## Echipă / semnal uman

🔴 **Numele fondatorului NU apare pe site** (decis de client 2026-07-10). Semnalul de
încredere este **vechimea: 10 ani** (`config('energix.experience')`), afișată pe
homepage, footer și pagina Despre.

## Cifre

- ✅ **Ani de experiență: 10** — confirmat de client 2026-07-10. Singura cifră afișată.
- ❌ 500 proiecte / 350 clienți / 50 localități — NEVERIFICATE, rămân ascunse
  (`stats_enabled = false`).

## Program

- Luni–Vineri: 08:00 – 20:00
- Sâmbătă: 09:00 – 17:00
- Duminică: închis
- ~~Urgențe: 24/7~~ — **eliminat**; business-ul nu face intervenții.

Promisiunea de răspuns (unică, peste tot): „Te sunăm înapoi în aceeași zi lucrătoare."

## Ton de voce

Româna (`ro`), adresare la persoana a II-a singular („proiectul tău", „contactează-ne").
Direct, practic, fără jargon tehnic inutil. Se păstrează.

## Decizii luate (2026-07-10)

- ✅ Excluderea acoperă **orice** afirmație de certificare/licențiere, nu doar sintagma.
- ✅ „Automatizări Industriale" **se elimină** odată cu Smart Home.
- ✅ „Audit Energetic" **se elimină** din serviciile oferite.
- ✅ **Reparații / mentenanță / urgențe se elimină** — doar instalații complete de la zero.
- ✅ Serviciile devin **3 segmente**: apartamente, case, spații industriale.
- ✅ Numele fondatorului **nu apare** pe site; înlocuit cu vechimea.
- ✅ Ani de experiență: **10**.
- ✅ Timp de răspuns unic: „în aceeași zi lucrătoare".

## Întrebări încă deschise

1. **Există fotografii reale ale lucrărilor?** Cele 9 imagini rămân de umplutură,
   declarate onest ca ilustrative pe `/galerie`. Prioritate: poze de pe șantierele proprii.
2. **Se confirmă restul cifrelor (500/350/50)?** Rămân ascunse până la confirmare.
