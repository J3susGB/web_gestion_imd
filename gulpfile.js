const { src, dest, watch, parallel } = require('gulp');

// CSS
const sass = require('gulp-sass')(require('sass'));
const plumber = require('gulp-plumber');
const autoprefixer = require('autoprefixer');
const cssnano = require('cssnano');
const postcss = require('gulp-postcss');
const sourcemaps = require('gulp-sourcemaps');

// Imagenes
const cache = require('gulp-cache');
const imagemin = require('gulp-imagemin');
const webp = require('gulp-webp');
const avif = require('gulp-avif');

// Javascript
const terser = require('gulp-terser-js');
const concat = require('gulp-concat');
const rename = require('gulp-rename')


const paths = {
    scss: 'src/scss/**/*.scss',
    js: 'src/js/**/*.js',
    imagenes: 'src/img/**/*'
}
function css() {
    return src(paths.scss)
        .pipe( sourcemaps.init())
        .pipe( sass({outputStyle: 'expanded'}))
        // .pipe( postcss([autoprefixer(), cssnano()]))
        .pipe( sourcemaps.write('.'))
        .pipe(  dest('public/build/css') );
}
function javascript() {
    return src(paths.js)
      .pipe(sourcemaps.init())
      .pipe(concat('bundle.js')) 
      .pipe(terser())
      .pipe(sourcemaps.write('.'))
      .pipe(rename({ suffix: '.min' }))
      .pipe(dest('./public/build/js'))
}

function imagenes() {
    return src(paths.imagenes)
        .pipe( cache(imagemin({ optimizationLevel: 3})))
        .pipe( dest('public/build/img'))
}

function versionWebp( done ) {
    const opciones = {
        quality: 50
    };
    src('src/img/**/*.{png,jpg}')
        .pipe( webp(opciones) )
        .pipe( dest('public/build/img') )
    done();
}

function versionAvif( done ) {
    const opciones = {
        quality: 50
    };
    src('src/img/**/*.{png,jpg}')
        .pipe( avif(opciones) )
        .pipe( dest('public/build/img') )
    done();
}

function dev(done) {
    watch( paths.scss, css );
    watch( paths.js, javascript );
    watch( paths.imagenes, imagenes)
    watch( paths.imagenes, versionWebp)
    watch( paths.imagenes, versionAvif)
    done()
}

exports.css = css;
exports.js = javascript;
exports.imagenes = imagenes;
exports.versionWebp = versionWebp;
exports.versionAvif = versionAvif;
exports.dev = parallel( css, imagenes, versionWebp, versionAvif, javascript, dev) ;
exports.build = parallel( css, imagenes, versionWebp, versionAvif, javascript) ;


// const gulp = require('gulp');
// const { src, dest, watch } = require('gulp');

// // CSS
// const sass = require('gulp-sass')(require('sass'));
// const plumber = require('gulp-plumber');
// const autoprefixer = require('autoprefixer');
// const cssnano = require('cssnano');
// const postcss = require('gulp-postcss');
// const sourcemaps = require('gulp-sourcemaps');

// // Imágenes
// const cache = require('gulp-cache');
// const imagemin = require('gulp-imagemin');
// const webp = require('gulp-webp');
// const avif = require('gulp-avif');

// // JavaScript
// const terser = require('gulp-terser-js');
// const concat = require('gulp-concat');
// const rename = require('gulp-rename');

// // Rutas
// const paths = {
//     scss: 'src/scss/**/*.scss',
//     js: 'src/js/**/*.js',
//     imagenes: 'src/img/**/*'
// };

// // Tarea CSS
// function css() {
//     return src(paths.scss)
//         .pipe(sourcemaps.init())
//         .pipe(plumber())
//         .pipe(sass({ outputStyle: 'expanded' }))
//         .pipe(postcss([autoprefixer(), cssnano()]))
//         .pipe(sourcemaps.write('.'))
//         .pipe(dest('public/build/css'));
// }

// // Tarea JavaScript
// function javascript() {
//     return src(paths.js)
//         .pipe(sourcemaps.init())
//         .pipe(concat('bundle.js'))
//         .pipe(terser())
//         .pipe(sourcemaps.write('.'))
//         .pipe(rename({ suffix: '.min' }))
//         .pipe(dest('public/build/js'));
// }

// // Optimización de imágenes
// function imagenes() {
//     return src(paths.imagenes)
//         .pipe(cache(imagemin({ optimizationLevel: 3 })))
//         .pipe(dest('public/build/img'));
// }

// // Versiones WebP
// function versionWebp(done) {
//     const opciones = { quality: 50 };
//     src('src/img/**/*.{png,jpg}')
//         .pipe(webp(opciones))
//         .pipe(dest('public/build/img'));
//     done();
// }

// // Versiones Avif
// function versionAvif(done) {
//     const opciones = { quality: 50 };
//     src('src/img/**/*.{png,jpg}')
//         .pipe(avif(opciones))
//         .pipe(dest('public/build/img'));
//     done();
// }

// // Tarea Watch
// function dev(done) {
//     watch(paths.scss, css);
//     watch(paths.js, javascript);
//     watch(paths.imagenes, imagenes);
//     watch(paths.imagenes, versionWebp);
//     watch(paths.imagenes, versionAvif);
//     done();
// }

// // Tareas individuales
// gulp.task('css', css);
// gulp.task('javascript', javascript);
// gulp.task('imagenes', imagenes);
// gulp.task('versionWebp', versionWebp);
// gulp.task('versionAvif', versionAvif);
// gulp.task('dev', dev);

// // Tarea por defecto
// gulp.task('default', gulp.series(
//     'css',
//     'imagenes',
//     'versionWebp',
//     'versionAvif',
//     'javascript',
//     'dev'
// ));
