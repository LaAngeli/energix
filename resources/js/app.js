/*
| Energix — interacțiuni.
|
| Fără librării. Tot ce se mișcă folosește doar opacity/transform/culoare (GPU)
| și se declanșează prin IntersectionObserver sau prin acțiunea utilizatorului —
| niciodată legat de poziția scroll-ului.
*/

const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

/**
 * Marcam documentul ca „are JS”. Fara asta, elementele cu [data-reveal] ar
 * ramane invizibile pentru utilizatorii fara JavaScript.
 */
document.documentElement.classList.add('js');

/* ------------------------------------------------------------------ reveal */

function observeReveals() {
    const targets = document.querySelectorAll('[data-reveal]');
    const glyphs = document.querySelectorAll('.wye-glyph');

    // Miscare redusa: starea finala, instant. Nu o versiune mai lenta.
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

    // Glyph-ul Y se „energizeaza” cand sectiunea lui intra in cadru.
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

        /*
         | rAF e inghetat in tab-urile din fundal. Valoarea finala e starea
         | corecta a instrumentului, deci o garantam indiferent de throttling.
         */
        voltFallback = setTimeout(() => {
            cancelAnimationFrame(voltFrame);
            voltmeter.textContent = String(target);
        }, duration + 150);
    }

    /** Aplica starea „sub tensiune” pe fiecare circuit, dupa topologie. */
    function refresh({ pulse = false } = {}) {
        const live = isLive();

        circuits.forEach((circuit) => {
            const on = live && breakerOn(circuit);
            const wasOn = circuit.classList.contains('is-live');
            circuit.classList.toggle('is-live', on);

            if (pulse && on && ! wasOn && ! prefersReducedMotion) {
                circuit.classList.remove('just-on');
                void circuit.offsetWidth; // reporneste animatia
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

    /*
     | Butonul TEST al diferentialului face exact ce face pe un tablou real:
     | declanseaza (totul cade), apoi se reanclanseaza. Starile disjunctoarelor
     | individuale se pastreaza.
     */
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

    /*
     | Prima energizare: o singura data, cand tabloul intra in cadru.
     | Sub prefers-reduced-motion: direct starea finala.
     */
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
            // Cablul se umple pana la nodul activ.
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

    buttons.forEach((button) => {
        button.addEventListener('click', () => {
            const filter = button.dataset.filter;

            buttons.forEach((other) => other.setAttribute('aria-pressed', String(other === button)));

            items.forEach((item) => {
                item.hidden = filter !== 'toate' && item.dataset.category !== filter;
            });
        });
    });
}

/* ------------------------------------------------ cookie: GTM dupa accept */

const CONSENT_KEY = 'energix.cookie-consent';

/**
 * Google Tag Manager se incarca DOAR dupa consimtamant explicit.
 * Site-ul vechi il pornea in <head>, inaintea banner-ului — neconformitate GDPR.
 */
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

    /*
     | Are role="dialog", deci focusul trebuie sa ajunga in el. Nu e modal si
     | nu prindem focusul: nu blocam pe nimeni in banner.
     */
    banner.focus({ preventScroll: true });

    banner.querySelector('[data-cookie-accept]')?.addEventListener('click', () => {
        localStorage.setItem(CONSENT_KEY, 'accepted');
        banner.hidden = true;
        loadTagManager(gtmId);
    });

    // Refuzul e o alegere de prim rang, nu un link ascuns.
    banner.querySelector('[data-cookie-decline]')?.addEventListener('click', () => {
        localStorage.setItem(CONSENT_KEY, 'declined');
        banner.hidden = true;
    });
}

/* ---------------------------------------------------------------- pornire */

function boot() {
    observeReveals();
    initPanel();
    initStages();
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
