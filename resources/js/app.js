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

function initStages() {
    const root = document.querySelector('[data-stages]');

    if (! root) {
        return;
    }

    const tabs = [...root.querySelectorAll('[role="tab"]')];
    const panels = [...root.querySelectorAll('[role="tabpanel"]')];
    const fill = root.querySelector('[data-stage-fill]');

    function select(index, { focus = false } = {}) {
        tabs.forEach((tab, i) => {
            const active = i === index;
            tab.setAttribute('aria-selected', String(active));
            tab.tabIndex = active ? 0 : -1;
            panels[i].hidden = ! active;
        });

        if (fill) {
            fill.style.width = `${(index / Math.max(tabs.length - 1, 1)) * 100}%`;
        }

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

    select(0);
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

/* ------------------------------------- consola de segmente (pagina servicii) */

function initServicesSwitcher() {
    const root = document.querySelector('[data-seg-switcher]');

    if (! root) {
        return;
    }

    const tabs = [...root.querySelectorAll('[role="tab"]')];
    const panels = [...root.querySelectorAll('[role="tabpanel"]')];

    function select(index, { focus = false } = {}) {
        tabs.forEach((tab, i) => {
            const active = i === index;
            tab.setAttribute('aria-selected', String(active));
            tab.tabIndex = active ? 0 : -1;
            panels[i].hidden = ! active;
        });

        if (focus) {
            tabs[index].focus();
        }
    }

    tabs.forEach((tab, i) => {
        tab.addEventListener('click', () => select(i));

        tab.addEventListener('keydown', (event) => {
            const delta = { ArrowRight: 1, ArrowLeft: -1, ArrowDown: 1, ArrowUp: -1 }[event.key];

            if (! delta) {
                return;
            }

            event.preventDefault();
            select((i + delta + tabs.length) % tabs.length, { focus: true });
        });
    });

    /** Ancorele vechi (#apartamente, #case, #industriale) selecteaza tab-ul. */
    function selectFromHash() {
        const slug = window.location.hash.replace('#', '');
        const index = panels.findIndex((panel) => panel.dataset.slug === slug);

        if (index >= 0) {
            select(index);
        }
    }

    window.addEventListener('hashchange', selectFromHash);
    selectFromHash();

    if (! tabs.some((tab) => tab.getAttribute('aria-selected') === 'true')) {
        select(0);
    }
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

    fields.forEach((field) => {
        field.addEventListener('input', check);
        field.addEventListener('blur', check);
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
    initServicesSwitcher();
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
