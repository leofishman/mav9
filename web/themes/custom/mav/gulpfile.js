let gulp = require('gulp'),
    autoprefixer = require('autoprefixer'),
    browserSync = require('browser-sync').create(),
    cleanCss = require('gulp-clean-css'),
    fs = require('fs'),
    glob = require('gulp-sass-glob'),
    jsYaml = require('js-yaml');
    sass = require('gulp-sass')
    sassLint = require('gulp-sass-lint')
    sourcemaps = require('gulp-sourcemaps')
    rename = require('gulp-rename')
    postcss = require('gulp-postcss')
    sass.compiler = require('node-sass');

const paths = {
  scss: {
    src: './components/**/*.scss',
    dest: './css',
    watch: ['./components/**/*.scss'],
    bootstrap: './node_modules/bootstrap/scss/bootstrap.scss'
  },
  js: {
    bootstrap: './node_modules/bootstrap/dist/js/bootstrap.min.js',
    bootstrap: './node_modules/bootstrap/dist/js/bootstrap.min.js.map',
    popper: './node_modules/popper.js/dist/umd/popper.min.js',
    popper: './node_modules/popper.js/dist/umd/popper.min.js.map',
    dest: './js'
  }
}

// Compile sass into CSS & auto-inject into browsers.
function styles () {
  return gulp.src([paths.scss.bootstrap, paths.scss.src])
    .pipe(sourcemaps.init())
    .pipe(glob())
    .pipe(sassLint())
    .pipe(sassLint.format())
    .pipe(sass().on('error', sass.logError))
    .pipe(postcss([autoprefixer({})]))
    .pipe(sourcemaps.write())
    .pipe(gulp.dest(paths.scss.dest))
    .pipe(cleanCss())
    .pipe(rename({ suffix: '.min' }))
    .pipe(gulp.dest(paths.scss.dest))
    .pipe(browserSync.stream())
}

// Move the javascript files into our js folder.
function js () {
  return gulp.src([paths.js.bootstrap, paths.js.popper])
    .pipe(gulp.dest(paths.js.dest))
    .pipe(browserSync.stream())
}

// Static Server + watching scss/html files.
function serve () {
  browserSync.init({
    proxy: 'https://mav9.lndo.site',
  })

  gulp.watch(paths.scss.watch, styles).on('change', browserSync.reload)
}

function styleLint() {
  const configFile = jsYaml.safeLoad(fs.readFileSync('.sass-lint.yml', 'utf-8'));
  return gulp
    .src(['components/**/*.s+(a|c)ss', '!components/component/z*.s+(a|c)ss'])
    .pipe(sassLint(configFile))
    .pipe(sassLint.format())
    .pipe(sassLint.failOnError())
}

const build = gulp.series(styles, gulp.parallel(js))
const watch = gulp.series(styles, gulp.parallel(js, serve));
const lint = gulp.series(styleLint);

exports.styles = styles
exports.js = js
exports.serve = serve
exports.lint = lint;
exports.default = build
exports.watch = watch;
