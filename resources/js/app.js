/*
| Energix — interacțiunile „instalației vii”.
|
| Fără librării. Tot ce se mișcă e transform/opacity/culoare (GPU), pornit de
| IntersectionObserver, de acțiunea utilizatorului sau — pentru conductor și
| sondă — de un rAF throttled. Nimic paint-bound legat de scroll.
*/

const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const finePointer = window.matchMedia('(pointer: fine)').matches;

/**
 * Marcam documentul ca „are JS”. Fara asta, elementele cu [data-reveal] ar
 * ramane invizibile pentru utilizatorii fara JavaScript.
 */
document.documentElement.classList.add('js');

/* ------------------------------------------------------------------ reveal */

function observeReveals() {
    const targets = document.querySelectorAll('[data-reveal]');
    const glyphs = document.querySelectorAll('.wye-glyph');

    if (prefersReducedMotion) {
        targets.forEach((el) => el.classList.add('is-visible'));
        glyphs.forEach((el) => el.classList.add('is-live'));

        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (! entry.isIntersecting) {
                    return;
                }

                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            });
        },
        { rootMargin: '0px 0px -10% 0px', threshold: 0.1 },
    );

    targets.forEach((el) => observer.observe(el));

    const glyphObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (! entry.isIntersecting) {
                    return;
                }

                entry.target.classList.add('is-live');
                glyphObserver.unobserve(entry.target);
            });
        },
        { threshold: 0.5 },
    );

    glyphs.forEach((el) => glyphObserver.observe(el));
}

/* ---------------------------------------------------- CONDUCTORUL (spine) */

/**
 * Firul din marginea stanga se umple cu aur pe masura ce cobori in pagina,
 * iar punctul de sarcina coboara cu tine. Doar transformari — zero paint.
 */
function initSpine() {
    const spine = document.querySelector('[data-spine]');

    if (! spine || prefersReducedMotion) {
        return;
    }

    const fill = spine.querySelector('.spine-fill');
    const head = spine.querySelector('.spine-head');
    let ticking = false;

    const update = () => {
        ticking = false;

        const max = document.documentElement.scrollHeight - window.innerHeight;
        const progress = max > 0 ? Math.min(window.scrollY / max, 1) : 1;

        fill.style.transform = `scaleY(${progress})`;
        head.style.transform = `translate(-50%, ${progress * spine.clientHeight - 4.5}px)`;
    };

    /*
     | Throttle pe setTimeout, nu pe rAF: rAF ingheata in tab-urile de fundal,
     | iar update-ul e doar doua transformari — ieftin la 30fps.
     */
    const onScroll = () => {
        if (! ticking) {
            ticking = true;
            setTimeout(update, 33);
        }
    };

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll, { passive: true });
    update();
}

/* --------------------------------------------------------- SONDA (cursor) */

/**
 * O lumina calda urmareste pointerul pe suprafetele navy — site-ul raspunde
 * oriunde il atingi. Doar pe pointer fin, doar fara reduced-motion.
 */
function initProbe() {
    const probe = document.querySelector('[data-probe]');

    if (! probe || ! finePointer || prefersReducedMotion) {
        return;
    }

    let ticking = false;
    let x = 0;
    let y = 0;

    window.addEventListener(
        'pointermove',
        (event) => {
            x = event.clientX;
            y = event.clientY;

            // Aprinderea e imediata; doar pozitionarea e throttle-uita.
            probe.classList.add('is-on');

            if (! ticking) {
                ticking = true;
                requestAnimationFrame(() => {
                    ticking = false;
                    probe.style.setProperty('--gx', `${x}px`);
                    probe.style.setProperty('--gy', `${y}px`);
                });
            }
        },
        { passive: true },
    );
}

/* ------------------------------------------------- tabloul interactiv */

function initPanel() {
    const panel = document.querySelector('[data-panel]');

    if (! panel) {
        return;
    }

    const master = panel.querySelector('[data-master]');
    const masterState = panel.querySelector('[data-master-state]');
    const circuits = [...panel.querySelectorAll('[data-circuit]')];
    const voltmeter = panel.querySelector('[data-voltmeter]');
    const status = panel.querySelector('[data-panel-status]');
    const rcdTest = panel.querySelector('[data-rcd-test]');

    let voltFrame = null;
    let voltFallback = null;

    const isLive = () => panel.dataset.live === 'true';
    const breakerOf = (circuit) => circuit.querySelector('[data-breaker]');
    const breakerOn = (circuit) => breakerOf(circuit).getAttribute('aria-checked') === 'true';

    const announce = (text) => {
        if (status) {
            status.textContent = text;
        }
    };

    /** Voltmetrul numara pana la tinta. 230 V e o constanta fizica, nu marketing. */
    function setVoltage(target) {
        cancelAnimationFrame(voltFrame);
        clearTimeout(voltFallback);

        const from = parseInt(voltmeter.textContent, 10) || 0;

        if (prefersReducedMotion || from === target) {
            voltmeter.textContent = String(target);

            return;
        }

        const started = performance.now();
        const duration = 650;

        const tick = (now) => {
            const t = Math.min((now - started) / duration, 1);
            const eased = 1 - Math.pow(1 - t, 3);
            voltmeter.textContent = String(Math.round(from + (target - from) * eased));

            if (t < 1) {
                voltFrame = requestAnimationFrame(tick);
            }
        };

        voltFrame = requestAnimationFrame(tick);

        // rAF ingheata in tab-urile de fundal; valoarea finala e garantata.
        voltFallback = setTimeout(() => {
            cancelAnimationFrame(voltFrame);
            voltmeter.textContent = String(target);
        }, duration + 150);
    }

    function refresh({ pulse = false } = {}) {
        const live = isLive();

        circuits.forEach((circuit) => {
            const on = live && breakerOn(circuit);
            const wasOn = circuit.classList.contains('is-live');
            circuit.classList.toggle('is-live', on);

            if (pulse && on && ! wasOn && ! prefersReducedMotion) {
                circuit.classList.remove('just-on');
                void circuit.offsetWidth;
                circuit.classList.add('just-on');
            }
        });

        setVoltage(live ? 230 : 0);

        if (masterState) {
            masterState.textContent = live ? 'Pornit' : 'Oprit';
        }
    }

    function setMaster(on, { pulse = true } = {}) {
        master.setAttribute('aria-checked', String(on));
        panel.dataset.live = String(on);
        refresh({ pulse });
        announce(on ? 'Tabloul este sub tensiune.' : 'Tabloul este scos de sub tensiune.');
    }

    master.addEventListener('click', () => setMaster(! isLive()));

    circuits.forEach((circuit) => {
        const breaker = breakerOf(circuit);

        breaker.addEventListener('click', () => {
            const next = ! breakerOn(circuit);
            breaker.setAttribute('aria-checked', String(next));
            refresh({ pulse: true });

            const name = breaker.getAttribute('aria-label');
            announce(next ? `${name}: pornit.` : `${name}: oprit.`);
        });
    });

    let tripping = false;

    rcdTest?.addEventListener('click', () => {
        if (! isLive() || tripping) {
            announce('Testul funcționează doar cu separatorul pornit.');

            return;
        }

        tripping = true;
        panel.classList.add('is-tripped');
        panel.dataset.live = 'false';
        refresh();
        announce('Test diferențial: declanșat.');

        setTimeout(() => {
            panel.classList.remove('is-tripped');
            panel.dataset.live = 'true';
            refresh({ pulse: true });
            announce('Test diferențial: OK. Reanclanșat.');
            tripping = false;
        }, prefersReducedMotion ? 350 : 950);
    });

    if (prefersReducedMotion) {
        setMaster(true, { pulse: false });

        return;
    }

    const starter = new IntersectionObserver(
        ([entry]) => {
            if (! entry.isIntersecting) {
                return;
            }

            starter.disconnect();
            setTimeout(() => setMaster(true), 350);
        },
        { threshold: 0.35 },
    );

    starter.observe(panel);
}

/* ------------------------------------------------------ etapele pe cablu */

/** Cat dureaza unda de curent: o baza, plus un timp pentru fiecare etapa sarita. */
const STAGE_BASE_MS = 260;
const STAGE_STEP_MS = 140;

function initStages() {
    document.querySelectorAll('[data-stages]').forEach(setupStages);
}

function setupStages(root) {
    const tabs = [...root.querySelectorAll('[role="tab"]')];
    const panels = [...root.querySelectorAll('[role="tabpanel"]')];
    const fill = root.querySelector('[data-stage-fill]');

    if (! tabs.length || ! fill) {
        return;
    }

    const track = fill.parentElement;
    let current = 0;

    // Cat timp cablul n-a fost trasat, nimic nu are voie sa-i repicteze latimea.
    let drawn = prefersReducedMotion;

    /**
     * Latimea barei = distanta pana la centrul REAL al cifrei tinta.
     *
     * Nu un procent: nodurile sunt centrate in coloanele grilei, la `(i + 0.5) / n`,
     * nu la capetele cablului. Masurarea ramane corecta si daca se schimba `gap`-ul
     * sau numarul de etape.
     *
     * Se masoara din <button>, nu din nod: nodul selectat are `scale(1.06)`, care ii
     * deformeaza dreptunghiul. Butonul nu se scaleaza, iar nodul e centrat in el.
     */
    function fillWidthFor(index) {
        const tab = tabs[index].getBoundingClientRect();

        return tab.left + tab.width / 2 - track.getBoundingClientRect().left;
    }

    function paint(index, duration) {
        fill.style.transitionDuration = `${duration}ms`;
        fill.style.width = `${Math.round(fillWidthFor(index))}px`;
    }

    function select(index, { focus = false, animate = true } = {}) {
        const from = current;
        const steps = Math.abs(index - from);
        drawn = true;

        // Un salt de la 01 la 05 dureaza mai mult decat un pas — unda se vede trecand.
        const duration = ! animate || prefersReducedMotion
            ? 0
            : STAGE_BASE_MS + steps * STAGE_STEP_MS;

        paint(index, duration);

        tabs.forEach((tab, i) => {
            const active = i === index;

            tab.setAttribute('aria-selected', String(active));
            tab.tabIndex = active ? 0 : -1;
            panels[i].hidden = ! active;

            /*
             | Fiecare nod dintre punctul de plecare si tinta se aprinde (sau se stinge)
             | exact cand unda ajunge la el. Nodurile din afara traseului comuta imediat.
             */
            const peTraseu = steps > 0 && (i - from) * (i - index) <= 0 && i !== from;
            const delay = peTraseu ? (Math.abs(i - from) / steps) * duration : 0;

            tab.style.setProperty('--node-delay', `${Math.round(delay)}ms`);
            tab.classList.toggle('is-passed', i <= index);
        });

        current = index;

        if (focus) {
            tabs[index].focus();
        }
    }

    tabs.forEach((tab, i) => {
        tab.addEventListener('click', () => select(i));

        tab.addEventListener('keydown', (event) => {
            const delta = { ArrowRight: 1, ArrowLeft: -1 }[event.key];

            if (! delta) {
                return;
            }

            event.preventDefault();
            select((i + delta + tabs.length) % tabs.length, { focus: true });
        });
    });

    select(0, { animate: false });

    /*
     | Prima trasare a cablului se face cand sectiunea intra in cadru — acelasi
     | limbaj ca restul site-ului: energizare la intrare, o data.
     */
    if (! prefersReducedMotion) {
        drawn = false;
        fill.style.width = '0px';

        const starter = new IntersectionObserver(
            ([entry]) => {
                if (! entry.isIntersecting) {
                    return;
                }

                starter.disconnect();
                setTimeout(() => {
                    drawn = true;
                    paint(current, 600);
                }, 200);
            },
            { threshold: 0.3 },
        );

        starter.observe(root);
    }

    /*
     | Latimea e in pixeli, deci trebuie recalculata cand se schimba latimea paginii
     | sau cand fonturile se incarca si nodurile isi schimba pozitia. Dar niciodata
     | inainte ca sectiunea sa fi fost vazuta — altfel cablul apare deja trasat.
     */
    const reflow = () => {
        if (drawn) {
            paint(current, 0);
        }
    };

    let resizeTimer = null;
    window.addEventListener(
        'resize',
        () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(reflow, 120);
        },
        { passive: true },
    );

    document.fonts?.ready.then(reflow);
}

/* --------------------------------------- rânduri-segment expandabile (home) */

function initSegmentRows() {
    const rows = document.querySelectorAll('[data-segment-row]');

    rows.forEach((row) => {
        const trigger = row.querySelector('[data-segment-toggle]');

        trigger?.addEventListener('click', () => {
            const open = ! row.classList.contains('is-open');

            rows.forEach((other) => {
                other.classList.toggle('is-open', other === row && open);
                other.querySelector('[data-segment-toggle]')?.setAttribute('aria-expanded', String(other === row && open));
            });
        });
    });
}

/* ------------------------------------------ ancorele vechi de pe /servicii */

/**
 * `/servicii#apartamente` -> `/servicii/apartamente`.
 *
 * Segmentele erau tab-uri pe o singura pagina. Fragmentul nu ajunge niciodata la
 * server, deci un 301 e imposibil: singurul loc unde poate fi tradus e clientul.
 * `replace()`, nu `assign()`, ca butonul „inapoi” sa nu se blocheze intre cele doua.
 */
function initLegacySegmentHash() {
    const root = document.querySelector('[data-segment-hash]');

    if (! root) {
        return;
    }

    const jump = () => {
        const slug = window.location.hash.replace('#', '');

        if (! slug) {
            return;
        }

        const card = root.querySelector(`[data-segment-card="${CSS.escape(slug)}"] a[href]`);

        if (card) {
            window.location.replace(card.href);
        }
    };

    window.addEventListener('hashchange', jump);
    jump();
}

/* --------------------------------------------- circuitul formularului */

/**
 * Formularul e un circuit: fiecare camp valid inchide un segment; cand toate
 * sunt inchise, butonul se armeaza. Pur vizual — nu blocheaza nimic.
 */
function initFormCircuit() {
    const form = document.querySelector('[data-circuit-form]');

    if (! form) {
        return;
    }

    const fields = [...form.querySelectorAll('[data-circuit-field]')];
    const circuit = form.querySelector('[data-form-circuit]');
    const segments = circuit ? [...circuit.querySelectorAll('.seg')] : [];
    const submit = form.querySelector('[data-submit]');

    const check = () => {
        let done = 0;

        fields.forEach((field, i) => {
            const ok = field.value.trim() !== '' && field.checkValidity();
            segments[i]?.classList.toggle('is-done', ok);

            if (ok) {
                done++;
            }
        });

        const complete = done === fields.length;
        circuit?.classList.toggle('is-complete', complete);
        submit?.classList.toggle('is-armed', complete);
    };

    fields.forEach((field, i) => {
        field.addEventListener('input', check);
        field.addEventListener('blur', check);

        // Segmentul campului focalizat se evidentiaza: „aici masori acum”.
        field.addEventListener('focus', () => segments[i]?.classList.add('is-active'));
        field.addEventListener('blur', () => segments[i]?.classList.remove('is-active'));
    });

    check();
}

/* ------------------------------------------- comutatorul de armare (CTA) */

function initArmSwitch() {
    const zone = document.querySelector('[data-arm-zone]');
    const arm = zone?.querySelector('[data-arm]');

    if (! zone || ! arm) {
        return;
    }

    arm.addEventListener('click', () => {
        const on = arm.getAttribute('aria-checked') !== 'true';
        arm.setAttribute('aria-checked', String(on));
        zone.classList.toggle('is-armed', on);
    });
}

/* ---------------------------------------------- instrumentele din hero */

/** /servicii — comuti consumatorii, instrumentul numara circuitele si modulele. */
function initCircuitsCalc() {
    const root = document.querySelector('[data-circuits-calc]');

    if (! root) {
        return;
    }

    const base = parseInt(root.dataset.base, 10) || 0;
    const toggles = [...root.querySelectorAll('[data-calc-toggle]')];
    const circuitsOut = root.querySelector('[data-calc-circuits]');
    const modulesOut = root.querySelector('[data-calc-modules]');

    const recount = () => {
        const extra = toggles.filter((t) => t.getAttribute('aria-checked') === 'true').length;
        const circuits = base + extra;

        circuitsOut.textContent = String(circuits);
        // separator (2 module) + diferential (2) + cate un modul pe circuit
        modulesOut.textContent = String(circuits + 4);
    };

    toggles.forEach((toggle) => {
        toggle.addEventListener('click', () => {
            toggle.setAttribute('aria-checked', String(toggle.getAttribute('aria-checked') !== 'true'));
            recount();
        });
    });

    recount();
}

/** /galerie — contorul cu cifre rostogolite; alegerea filtreaza si galeria. */
function initWorksCounter() {
    const root = document.querySelector('[data-works-counter]');

    if (! root) {
        return;
    }

    const counts = JSON.parse(root.dataset.counts || '{}');
    const digits = [...root.querySelectorAll('[data-odo-digit]')];
    const picks = [...root.querySelectorAll('[data-works-pick]')];
    const status = root.querySelector('[data-works-status]');

    const roll = (value) => {
        const text = String(value).padStart(digits.length, '0');

        digits.forEach((strip, i) => {
            strip.style.transform = `translateY(${-Number(text[i]) * 2.2}rem)`;
        });
    };

    picks.forEach((pick) => {
        pick.addEventListener('click', () => {
            const slug = pick.dataset.worksPick;

            picks.forEach((other) => other.setAttribute('aria-pressed', String(other === pick)));
            roll(counts[slug] ?? 0);

            if (status) {
                status.textContent = `${counts[slug] ?? 0} lucrări pe circuitul ${pick.textContent.trim()}.`;
            }

            // Instrumentul comanda pagina: filtrul real al galeriei se comuta si el.
            document.querySelector(`[data-gallery] [data-filter="${slug}"]`)?.click();
        });
    });

    roll(counts.toate ?? 0);
}

/**
 * /despre — sigla „Constructie”: portarea vectoriala a animatiei livrate de client
 * („Energix Loop C — Constructie”).
 *
 * Formulele sunt copiate identic din sursa (`energix-logo.jsx`, scena InnerC):
 * conturul becului se traseaza cu o bila de curent in varf, filamentul „Y” creste
 * din soclu, barele soclului sar la loc, razele ies in evantai in sens orar, un
 * flash aprinde totul, literele urca in cadru — iar la final o scanteie se naste
 * in bec si zboara pe o curba pana devine punctul de pe „i”.
 *
 * UNDE SE OPRESTE. Sursa e o bucla de 7s: ultima secunda (t = 5.95…6.95)
 * DEZASAMBLEAZA logo-ul, ca sa poata reporni din nimic. Clientul cere o singura
 * rulare, cu inghet la final — deci rulam pana la `END`, dupa care inghetam pe
 * sigla aprinsa. Nu e o aproximare: fiecare factor de demontare din sursa e de
 * forma `1 - seg(t, a, b)` cu `a >= 5.95`, deci pe intervalul [0, END] valoreaza
 * exact 1. Termenii aceia sunt omisi mai jos fiindca sunt constanti, nu ignorati.
 */
function initLogoBuild() {
    const root = document.querySelector('[data-logo-build]');

    if (! root) {
        return;
    }

    /*
     | Ciclul sursei e 7s, dar ultimele 1.5s sunt demontarea. Ne oprim la 5.5s:
     | unda punctului s-a stins (5.3s), iar sigla e complet aprinsa.
     */
    const END = 5.5;
    const TAU = Math.PI * 2;
    const GOLD = '#f2d147'; // tokenul de brand, nu auriul #f1c232 al machetei
    const BRIGHT = '#ffde6e';
    const HOT = '#fff4c8';

    const clamp01 = (v) => (v < 0 ? 0 : v > 1 ? 1 : v);

    // Easing-urile, copiate din sursa (`animations.jsx`), scrise fara mutatie de parametru.
    const easeInOutCubic = (t) => (t < 0.5 ? 4 * t * t * t : (t - 1) * (2 * t - 2) * (2 * t - 2) + 1);
    const easeOutCubic = (t) => (t - 1) ** 3 + 1;
    const easeInOutQuad = (t) => (t < 0.5 ? 2 * t * t : -1 + (4 - 2 * t) * t);
    const easeOutBack = (t) => {
        const c1 = 1.70158;
        const c3 = c1 + 1;

        return 1 + c3 * (t - 1) ** 3 + c1 * (t - 1) ** 2;
    };

    /** Rampa 0→1 intre `a` si `b`, optional cu easing. */
    const seg = (t, a, b, ease) => {
        const p = clamp01((t - a) / (b - a));

        return ease ? ease(p) : p;
    };

    /** Cocoasa 0→1→0 care incepe la `t0` si tine `dur`. */
    const bumpAt = (t, t0, dur) => {
        const d = t - t0;

        return d > 0 && d < dur ? Math.sin(Math.PI * (d / dur)) : 0;
    };

    const lerpHex = (a, b, k) => {
        k = clamp01(k);
        const pa = parseInt(a.slice(1), 16);
        const pb = parseInt(b.slice(1), 16);
        const ch = (sh) => Math.round(((pa >> sh) & 255) + (((pb >> sh) & 255) - ((pa >> sh) & 255)) * k);

        return `rgb(${ch(16)},${ch(8)},${ch(0)})`;
    };

    const glow = (px, alpha) => `drop-shadow(0 0 ${px.toFixed(1)}px rgba(255,213,74,${alpha.toFixed(2)}))`;

    // ---- geometria conturului becului, pentru pozitionarea bilei de curent ----
    const CX = 322;
    const CY = 178;
    const R = 90;
    const GAPX = 31;
    const NECK_Y0 = 262.5;
    const NECK_Y1 = 290;
    const NECK_LEN = NECK_Y1 - NECK_Y0;
    const ARC_A0 = 110.14;
    const ARC_SPAN = 320.28;
    const ARC_LEN = (ARC_SPAN / 360) * TAU * R;
    const TOT_LEN = ARC_LEN + 2 * NECK_LEN;
    const L1 = (NECK_LEN / TOT_LEN) * 100;
    const L2 = 100 - L1;

    /** Punctul de pe contur, la procentul `s` din lungimea traseului. */
    function bulbPoint(s) {
        if (s <= L1) {
            return [CX - GAPX, NECK_Y1 - NECK_LEN * (s / L1)];
        }

        if (s >= L2) {
            return [CX + GAPX, NECK_Y0 + NECK_LEN * ((s - L2) / L1)];
        }

        const th = ((ARC_A0 + ARC_SPAN * ((s - L1) / (L2 - L1))) * Math.PI) / 180;

        return [CX + R * Math.cos(th), CY + R * Math.sin(th)];
    }

    const JX = 322;
    const RAYS = [270, 315, 0, 45, 135, 180, 225];
    const RIN = 120;
    const ROUT = 157;

    // Scanteia se naste in bec si aterizeaza pe „i”, pe o curba Bezier patratica.
    const FLY_FROM = { x: 322, y: 198 };
    const FLY_CTRL = { x: 448, y: 168 };
    const DOT_Y = 408;

    const halo = root.querySelector('[data-build-halo]');
    const core = root.querySelector('[data-build-core]');
    const bulb = root.querySelector('[data-build-bulb]');
    const filament = root.querySelector('[data-build-filament]');
    const stem = root.querySelector('[data-build-stem]');
    const arms = [...root.querySelectorAll('[data-build-arm]')];
    const rays = [...root.querySelectorAll('[data-build-ray]')];
    const barGroups = [...root.querySelectorAll('[data-build-bar]')];
    const letters = [...root.querySelectorAll('[data-build-letter]')];
    const flash = root.querySelector('[data-build-flash]');
    const ring = root.querySelector('[data-build-ring]');
    const dot = root.querySelector('[data-build-dot]');
    const fly = root.querySelector('[data-build-fly]');

    const NS = 'http://www.w3.org/2000/svg';
    const makeCircle = (parent, fill, blur) => {
        const c = document.createElementNS(NS, 'circle');
        c.setAttribute('fill', fill);
        c.setAttribute('opacity', '0');
        c.style.filter = glow(blur, 0.95);
        parent.appendChild(c);

        return c;
    };

    // In scena C exista o singura bila (varful trasajului) si o singura scanteie.
    const bead = makeCircle(root.querySelector('[data-build-beads]'), '#fff3c4', 6);
    const spark = makeCircle(root.querySelector('[data-build-sparks]'), '#ffe9a0', 5);

    /** Trasajul unui path cu `pathLength=100`: `draw` e fractiunea desenata. */
    const drawPath = (el, draw) => {
        if (draw <= 0.002) {
            el.setAttribute('opacity', '0');

            return;
        }

        el.setAttribute('opacity', '1');
        el.setAttribute('stroke-dasharray', draw >= 1 ? 'none' : `${(draw * 100).toFixed(2)} 200`);
    };

    let dotCx = 500; // rescris de `layoutWord()`

    /**
     * Literele se aseaza dupa latimile lor REALE, masurate dupa ce fontul s-a
     * incarcat. Punctul auriu — si tinta scanteii — depind de aceeasi masurare.
     */
    function layoutWord() {
        const widths = letters.map((el) => el.getComputedTextLength());
        const gap = 5;
        const total = widths.reduce((a, b) => a + b, 0) + gap * (widths.length - 1);
        let x = 330 - total / 2;

        letters.forEach((el, i) => {
            const cx = x + widths[i] / 2;
            el.setAttribute('x', cx.toFixed(1));
            el.dataset.cx = cx.toFixed(1);
            x += widths[i] + gap;
        });

        if (letters[5]) {
            dotCx = +letters[5].dataset.cx;
        }

        [dot, ring].forEach((el) => {
            el.setAttribute('cx', dotCx.toFixed(1));
            el.setAttribute('cy', String(DOT_Y));
        });
    }

    /** Un cadru la momentul `t`: exact formulele scenei C din sursa. */
    function frame(t) {
        // 1. conturul becului se traseaza, cu o bila de curent in varf
        const bulbDraw = seg(t, 0.2, 1.35, easeInOutCubic);

        drawPath(bulb, bulbDraw);

        if (t > 0.24 && t < 1.33) {
            const [bx, by] = bulbPoint(bulbDraw * 100);
            bead.setAttribute('cx', bx.toFixed(1));
            bead.setAttribute('cy', by.toFixed(1));
            bead.setAttribute('r', '7');
            bead.setAttribute('opacity', '0.95');
        } else {
            bead.setAttribute('opacity', '0');
        }

        // 2. filamentul creste: intai piciorul, apoi bratele
        drawPath(stem, seg(t, 1.05, 1.35, easeOutCubic));
        const armsDraw = seg(t, 1.28, 1.7, easeOutCubic);
        arms.forEach((el) => drawPath(el, armsDraw));

        // 3. aprinderea: flash la 2.92s, apoi lumina se aseaza si ramane
        const envHold = seg(t, 2.92, 3.3, easeOutCubic);
        const breathe = Math.sin((TAU * (t - 3)) / 1.75);
        const filBright = envHold * (0.95 + 0.15 * breathe);
        const bloom = envHold * (0.55 + 0.4 * bumpAt(t, 2.92, 0.9) + 0.07 * breathe);
        const bulbBright = 0.5 * envHold;

        halo.setAttribute('opacity', (clamp01(bloom) * 0.5).toFixed(3));
        core.setAttribute('opacity', (clamp01(bloom) * 0.85).toFixed(3));
        flash.setAttribute('opacity', clamp01(0.55 * bumpAt(t, 2.92, 0.55)).toFixed(3));

        bulb.setAttribute('stroke', lerpHex(GOLD, BRIGHT, bulbBright));
        bulb.style.filter = bulbBright > 0.03 ? glow(10 * bulbBright, 0.8 * bulbBright) : '';

        filament.setAttribute('stroke', lerpHex(GOLD, HOT, Math.min(1, filBright)));
        filament.style.filter = filBright > 0.03
            ? glow(9 * Math.min(filBright, 1.7), Math.min(0.95, 0.7 * filBright))
            : '';

        // 4. barele soclului sar la loc, cu un mic overshoot, de sus in jos
        barGroups.forEach((g, i) => {
            const sx = easeOutBack(seg(t, 1.6 + i * 0.18, 2.1 + i * 0.18));

            if (sx <= 0.01) {
                g.setAttribute('opacity', '0');

                return;
            }

            const hw = +g.dataset.hw * Math.min(sx, 1.15);
            const fl = bumpAt(t, 2.92, 0.6) * 0.5;

            g.setAttribute('opacity', '1');

            [...g.children].forEach((line) => {
                line.setAttribute('x1', (CX - hw).toFixed(1));
                line.setAttribute('x2', (CX + hw).toFixed(1));
            });

            const coreLine = g.lastElementChild;
            coreLine.setAttribute('stroke', lerpHex('#ffffff', BRIGHT, fl));
            coreLine.style.filter = fl > 0.03 ? glow(7 * fl, 0.8 * fl) : '';
        });

        // 5. razele ies in evantai, in sens orar, si se aprind la flash
        const rayBr = 0.9 * bumpAt(t, 2.95, 0.7) + 0.18 * envHold;

        rays.forEach((line, i) => {
            const ext = easeOutBack(seg(t, 2.15 + i * 0.1, 2.65 + i * 0.1));

            if (ext <= 0.02) {
                line.setAttribute('opacity', '0');

                return;
            }

            const a = (RAYS[i] * Math.PI) / 180;
            const c = Math.cos(a);
            const s = Math.sin(a);
            const rEnd = RIN + (ROUT - RIN) * ext;

            line.setAttribute('opacity', '1');
            line.setAttribute('x1', (CX + c * RIN).toFixed(1));
            line.setAttribute('y1', (CY + s * RIN).toFixed(1));
            line.setAttribute('x2', (CX + c * rEnd).toFixed(1));
            line.setAttribute('y2', (CY + s * rEnd).toFixed(1));
            line.setAttribute('stroke', lerpHex(GOLD, '#ffec9e', rayBr));
            line.style.filter = rayBr > 0.03 ? glow(8 * rayBr, 0.85 * rayBr) : '';
        });

        // 6. literele urca in cadru, una dupa alta
        letters.forEach((el, i) => {
            const oIn = seg(t, 3.25 + i * 0.11, 3.7 + i * 0.11, easeOutCubic);

            el.setAttribute('opacity', oIn.toFixed(3));
            el.setAttribute('transform', `translate(0 ${(46 * (1 - oIn)).toFixed(2)})`);
        });

        // 7. scanteia se aduna in bec...
        if (t > 3.88 && t <= 4.05) {
            spark.setAttribute('cx', String(FLY_FROM.x));
            spark.setAttribute('cy', String(FLY_FROM.y));
            spark.setAttribute('r', (13 * seg(t, 3.88, 4.05, easeOutCubic)).toFixed(2));
            spark.setAttribute('opacity', '1');
        } else {
            spark.setAttribute('opacity', '0');
        }

        // ...si zboara pe o Bezier patratica pana peste „i”
        if (t > 4.05 && t < 4.6) {
            const p = seg(t, 4.05, 4.6, easeInOutQuad);
            const q = 1 - p;
            const x = q * q * FLY_FROM.x + 2 * q * p * FLY_CTRL.x + p * p * dotCx;
            const y = q * q * FLY_FROM.y + 2 * q * p * FLY_CTRL.y + p * p * DOT_Y;

            fly.setAttribute('cx', x.toFixed(1));
            fly.setAttribute('cy', y.toFixed(1));
            fly.setAttribute('r', (15 * (1 + 0.2 * Math.sin(Math.PI * p))).toFixed(2));
            fly.setAttribute('opacity', '1');
            fly.style.filter = glow(16, 0.95);
        } else {
            fly.setAttribute('opacity', '0');
        }

        // 8. aterizarea: punctul se turteste, iar o unda se propaga din el
        const landK = bumpAt(t, 4.6, 0.42);
        const dotBr = Math.min(1, 0.8 * bumpAt(t, 4.6, 0.8) + 0.15 * envHold);

        dot.setAttribute('opacity', t >= 4.58 ? '1' : '0');
        dot.setAttribute('fill', lerpHex(GOLD, '#ffe99a', dotBr));
        dot.setAttribute(
            'transform',
            `translate(${dotCx.toFixed(1)} ${DOT_Y}) scale(${(1 + 0.18 * landK).toFixed(3)} ${(1 - 0.3 * landK).toFixed(3)}) translate(${(-dotCx).toFixed(1)} ${-DOT_Y})`,
        );
        dot.style.filter = dotBr > 0.02 ? glow(18 * dotBr, 0.9 * dotBr) : '';

        const ringP = seg(t, 4.64, 5.3);

        if (ringP > 0 && ringP < 1) {
            ring.setAttribute('r', (15 * (1 + 2.2 * ringP)).toFixed(2));
            ring.setAttribute('opacity', ((1 - ringP) * 0.9).toFixed(3));
        } else {
            ring.setAttribute('opacity', '0');
        }
    }

    let started = false;

    function play() {
        if (started) {
            return;
        }

        started = true;

        // Miscare redusa: starea finala, instant. Nicio bucla rAF.
        if (prefersReducedMotion) {
            frame(END);

            return;
        }

        const t0 = performance.now();
        let raf = requestAnimationFrame(function tick(now) {
            const t = (now - t0) / 1000;

            if (t >= END) {
                frame(END); // sigla aprinsa, apoi inghetata pana la un nou acces al paginii

                return;
            }

            frame(t);
            raf = requestAnimationFrame(tick);
        });

        /*
         | rAF ingheata in tab-urile de fundal. Garantam starea finala chiar daca
         | vizitatorul a deschis pagina intr-un tab din spate.
         */
        setTimeout(() => {
            cancelAnimationFrame(raf);
            frame(END);
        }, END * 1000 + 250);
    }

    function boot() {
        /*
         | Fontul schimba latimile literelor, deci wordmark-ul se aseaza abia dupa ce
         | fata e incarcata.
         |
         | `document.fonts.ready` NU e suficient: se rezolva inainte ca o fata inca
         | nefolosita sa intre in coada de incarcare, iar `getComputedTextLength()` ar
         | masura atunci metricile fontului de rezerva. Cerem explicit fata, cu textul
         | care ne intereseaza — aceeasi capcana ca la verificarea diacriticelor.
         */
        const wordmark = document.fonts?.load('600 160px Quicksand', 'energıx') ?? Promise.resolve();

        wordmark.catch(() => {}).then(() => {
            layoutWord();
            frame(0); // cadrul zero: pagina goala, nimic desenat inca

            const io = new IntersectionObserver(
                ([entry]) => {
                    if (! entry.isIntersecting) {
                        return;
                    }

                    io.disconnect();
                    play();
                },
                { threshold: 0.35 },
            );

            io.observe(root);
        });
    }

    /*
     | Sub `lg` sigla e `display: none`, deci nu are ce anima. Fara garda, apelul
     | `fonts.load()` ar descarca totusi Quicksand (~15 KB) pe fiecare telefon, ca
     | sa masoare litere invizibile. Pornim doar cand coloana chiar exista.
     */
    const desktop = window.matchMedia('(min-width: 64rem)');

    if (desktop.matches) {
        boot();

        return;
    }

    desktop.addEventListener('change', function once(event) {
        if (event.matches) {
            desktop.removeEventListener('change', once);
            boot();
        }
    });
}

/** /contacte — starea liniei: deschis ACUM sau cand revenim, plus testul ceremonial. */
function initLineStatus() {
    const root = document.querySelector('[data-line-status]');

    if (! root) {
        return;
    }

    const schedule = JSON.parse(root.dataset.schedule || '[]');
    const t = JSON.parse(root.dataset.i18n || '{}');
    const headline = root.querySelector('[data-line-headline]');
    const detail = root.querySelector('[data-line-detail]');
    const leds = root.querySelector('[data-line-leds]');
    const test = root.querySelector('[data-line-test]');

    const pad = (n) => `${String(n).padStart(2, '0')}:00`;
    const fill = (template, values) =>
        Object.entries(values).reduce((text, [key, value]) => text.replaceAll(`:${key}`, value), template);

    function compute(now = new Date()) {
        const today = schedule[now.getDay()];
        const hour = now.getHours() + now.getMinutes() / 60;

        if (today && hour >= today[0] && hour < today[1]) {
            return {
                open: true,
                headline: t.open,
                detail: fill(t.openDetail, { hour: pad(today[1]) }),
            };
        }

        // Cautam urmatoarea deschidere, incepand cu azi (daca inca n-am deschis).
        for (let offset = 0; offset <= 7; offset++) {
            const day = (now.getDay() + offset) % 7;
            const slot = schedule[day];

            if (! slot || (offset === 0 && hour >= slot[0])) {
                continue;
            }

            const when = offset === 0 ? t.today : (offset === 1 ? t.tomorrow : t.days[day]);

            return {
                open: false,
                headline: t.closed,
                detail: fill(t.closedDetail, { when, hour: pad(slot[0]) }),
            };
        }

        return { open: false, headline: t.closed, detail: t.closedPlain };
    }

    function render(state) {
        headline.textContent = state.headline;
        headline.classList.toggle('text-gold', state.open);
        headline.classList.toggle('text-paper', ! state.open);
        detail.textContent = state.detail;

        [...leds.children].forEach((led, i) => {
            led.classList.toggle('is-on', state.open || i === 0);
        });
    }

    render(compute());

    /*
     | Testul e ceremonial: LED-urile fac o verificare scurta, apoi starea se
     | reafiseaza (recalculata — poate intre timp s-a facut ora inchiderii).
     */
    let testing = false;

    test?.addEventListener('click', () => {
        if (testing) {
            return;
        }

        testing = true;

        if (prefersReducedMotion) {
            render(compute());
            testing = false;

            return;
        }

        leds.classList.add('line-chase');
        headline.textContent = t.testing;
        detail.textContent = ' ';

        setTimeout(() => {
            leds.classList.remove('line-chase');
            render(compute());
            testing = false;
        }, 650);
    });
}

/* ------------------------------------------------------------- meniu mobil */

function initNav() {
    const toggle = document.querySelector('[data-nav-toggle]');
    const menu = document.querySelector('[data-nav-menu]');

    if (! toggle || ! menu) {
        return;
    }

    const setOpen = (open) => {
        toggle.setAttribute('aria-expanded', String(open));
        menu.hidden = ! open;
        document.body.style.overflow = open ? 'hidden' : '';
    };

    toggle.addEventListener('click', () => {
        setOpen(toggle.getAttribute('aria-expanded') !== 'true');
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') {
            setOpen(false);
            toggle.focus();
        }
    });

    menu.addEventListener('click', (event) => {
        if (event.target.closest('a')) {
            setOpen(false);
        }
    });
}

/* ---------------------------------------------- navbar: fundal la scroll */

function initNavbarScroll() {
    const navbar = document.querySelector('[data-navbar]');

    if (! navbar) {
        return;
    }

    const sentinel = document.createElement('div');
    sentinel.setAttribute('aria-hidden', 'true');
    sentinel.style.cssText = 'position:absolute;top:0;height:1px;width:1px;pointer-events:none';
    document.body.prepend(sentinel);

    new IntersectionObserver(
        ([entry]) => navbar.classList.toggle('is-scrolled', ! entry.isIntersecting),
        { threshold: 0 },
    ).observe(sentinel);
}

/* -------------------------------------------------------- galerie: filtre */

function initGalleryFilters() {
    const root = document.querySelector('[data-gallery]');

    if (! root) {
        return;
    }

    const buttons = root.querySelectorAll('[data-filter]');
    const items = root.querySelectorAll('[data-category]');
    const count = root.querySelector('[data-gallery-count]');

    const applyCount = () => {
        if (count) {
            count.textContent = String([...items].filter((item) => ! item.hidden).length);
        }
    };

    buttons.forEach((button) => {
        button.addEventListener('click', () => {
            const filter = button.dataset.filter;

            buttons.forEach((other) => other.setAttribute('aria-pressed', String(other === button)));

            items.forEach((item) => {
                item.hidden = filter !== 'toate' && item.dataset.category !== filter;
            });

            applyCount();
        });
    });

    applyCount();
}

/* ------------------------------------------------ cookie: GTM dupa accept */

const CONSENT_KEY = 'energix.cookie-consent';

function loadTagManager(id) {
    if (! id || window.__energixGtmLoaded) {
        return;
    }

    window.__energixGtmLoaded = true;
    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({ 'gtm.start': Date.now(), event: 'gtm.js' });

    const script = document.createElement('script');
    script.async = true;
    script.src = `https://www.googletagmanager.com/gtm.js?id=${encodeURIComponent(id)}`;
    document.head.appendChild(script);
}

function initCookieBanner() {
    const banner = document.querySelector('[data-cookie-banner]');

    if (! banner) {
        return;
    }

    const gtmId = banner.dataset.gtmId;
    const decision = localStorage.getItem(CONSENT_KEY);

    if (decision === 'accepted') {
        loadTagManager(gtmId);

        return;
    }

    if (decision === 'declined') {
        return;
    }

    banner.hidden = false;
    banner.focus({ preventScroll: true });

    banner.querySelector('[data-cookie-accept]')?.addEventListener('click', () => {
        localStorage.setItem(CONSENT_KEY, 'accepted');
        banner.hidden = true;
        loadTagManager(gtmId);
    });

    banner.querySelector('[data-cookie-decline]')?.addEventListener('click', () => {
        localStorage.setItem(CONSENT_KEY, 'declined');
        banner.hidden = true;
    });
}

/* ---------------------------------------------------------------- pornire */

function boot() {
    observeReveals();
    initSpine();
    initProbe();
    initPanel();
    initStages();
    initSegmentRows();
    initLegacySegmentHash();
    initCircuitsCalc();
    initWorksCounter();
    initLogoBuild();
    initLineStatus();
    initFormCircuit();
    initArmSwitch();
    initNav();
    initNavbarScroll();
    initGalleryFilters();
    initCookieBanner();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
} else {
    boot();
}
