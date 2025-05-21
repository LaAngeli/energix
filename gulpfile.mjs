import gulp from 'gulp';
import uglify from 'gulp-uglify';
import cleanCSS from 'gulp-clean-css';
import htmlMinifierTerser from 'html-minifier-terser';
import through2 from 'through2';
import { deleteAsync } from 'del';

// Task: Clean dist folder
export function clean() {
    return deleteAsync(['dist/**', '!dist']);
}

// Task: Minify HTML files
export function minifyHtml() {
    return gulp.src('*.html')
        .pipe(through2.obj(async function (file, _, cb) {
            if (file.isBuffer()) {
                try {
                    const minifiedContent = await htmlMinifierTerser.minify(file.contents.toString(), {
                        collapseWhitespace: true,
                        removeComments: true,
                        minifyCSS: true,
                        minifyJS: true,
                        ignoreCustomFragments: [/<script type="application\/ld\+json">[\s\S]*?<\/script>/]
                    });
                    file.contents = Buffer.from(minifiedContent);
                } catch (err) {
                    return cb(err);
                }
            }
            cb(null, file);
        }))
        .pipe(gulp.dest('dist'));
}

// Task: Minify CSS files
export function styles() {
    return gulp.src('*.css')
        .pipe(cleanCSS())
        .pipe(gulp.dest('dist'));
}

// Task: Minify JS files
export function scripts() {
    return gulp.src('*.js')
        .pipe(uglify())
        .pipe(gulp.dest('dist'));
}

// Build task: Clean and then minify all files
export const build = gulp.series(
    clean,
    gulp.parallel(styles, scripts, minifyHtml)
);
