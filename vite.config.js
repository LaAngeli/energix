import { readdirSync, readFileSync, rmSync, writeFileSync } from 'node:fs';
import { join } from 'node:path';
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

/**
 * `laravel-vite-plugin/fonts` emite pentru fiecare subset DOUA reguli @font-face
 * separate, cu descriptori identici (family + weight + style + unicode-range):
 * mai intai woff2, apoi woff. Regula care vine a doua castiga, deci browserele
 * moderne descarcau woff (legacy, ~30% mai mare) si nu atingeau niciodata woff2.
 *
 * Verificat in browser: 9 fisiere .woff descarcate, 0 .woff2.
 *
 * Aici stergem regulile de woff din CSS-ul generat si fisierele .woff de pe disc.
 * Suportul woff2 e universal in browserele care conteaza (2017+).
 */
function woff2Only() {
    return {
        name: 'energix:woff2-only',
        apply: 'build',
        closeBundle() {
            const assets = join(process.cwd(), 'public', 'build', 'assets');

            let files;
            try {
                files = readdirSync(assets);
            } catch {
                return;
            }

            const cssFile = files.find((f) => f.startsWith('fonts-') && f.endsWith('.css'));
            if (!cssFile) return;

            const cssPath = join(assets, cssFile);
            const css = readFileSync(cssPath, 'utf8');

            // Pastram doar regulile @font-face al caror `src` indica un .woff2.
            const kept = css
                .split('@font-face')
                .filter((chunk, i) => i === 0 || chunk.includes('.woff2'))
                .join('@font-face');

            writeFileSync(cssPath, kept);

            const removed = files.filter((f) => f.endsWith('.woff'));
            removed.forEach((f) => rmSync(join(assets, f), { force: true }));

            const before = (css.match(/@font-face/g) ?? []).length;
            const after = (kept.match(/@font-face/g) ?? []).length;
            this.info(`woff2-only: ${before} -> ${after} reguli @font-face, ${removed.length} fisiere .woff sterse`);
        },
    };
}

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            // Bunny ignora parametrul `subset` si serveste toate subseturile, fiecare cu
            // propriul unicode-range. Diacriticele romanesti (ș ț Ș Ț, U+0218–U+021B) intra
            // in latin-ext si sunt acoperite automat; nu e nevoie de optiunea `subsets`.
            //
            // `preload` nu poate selecta pe subset, doar pe weight — deci `true` ar preincarca
            // si chirilica, greaca si vietnameza (15 fisiere, ~200 KB, degeaba). CSS-ul cu
            // @font-face e inline in <head>, deci browserul descopera fonturile imediat si
            // descarca doar subsetul de care are nevoie. Preload: dezactivat peste tot.
            fonts: [
                // Display: titluri. Un singur weight — ierarhia o face scara, nu grosimea.
                bunny('Archivo', {
                    alias: 'archivo',
                    variable: '--font-archivo',
                    weights: [700],
                    preload: false,
                }),
                // Corp de text.
                bunny('IBM Plex Sans', {
                    alias: 'plex',
                    variable: '--font-plex',
                    weights: [400, 500],
                    preload: false,
                }),
                // Etichete de schema, cifre, numar de telefon.
                bunny('IBM Plex Mono', {
                    alias: 'plex-mono',
                    variable: '--font-plex-mono',
                    weights: [500],
                    preload: false,
                }),
            ],
        }),
        tailwindcss(),
        woff2Only(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
