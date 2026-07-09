import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

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
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
