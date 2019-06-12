let gulp      = require('gulp');
let sass      = require('gulp-sass');
let concat    = require('gulp-concat');
let minify    = require('gulp-minify-css');
let uglify    = require('gulp-uglify-es').default;
let plumber   = require('gulp-plumber');
let flatten   = require('gulp-flatten');
let prefix    = require('gulp-autoprefixer');
let order     = require('gulp-order');
let rename    = require('gulp-rename');

let path = {
  source: 'src/',
  assets: 'public/assets/'
}

gulp.task('scss', function() {
  gulp.src([path.source + 'scss/app.scss'])
    .pipe(plumber())
    .pipe(sass({errLogToConsole: true}))
    .pipe(prefix({browsers: ['ie >= 10','ff >= 30', 'chrome >= 34', 'safari >= 7', 'ios >= 7']}))
    .pipe(concat('styles.min.css'))
    .pipe(minify({compatibility: 'ie8'}))
    .pipe(gulp.dest(path.assets))
})

gulp.task('js', function() {
  gulp.src([path.source + '/js/*.js'])
    .pipe(plumber())
    .pipe(uglify())
    .pipe(rename({suffix: '.min'}))
    .pipe(gulp.dest(path.assets))
})

gulp.task('images', function() {
  gulp.src([path.source + '/images/**/*'])
    .pipe(gulp.dest(path.assets + '/images/'));
})

gulp.task('watch', function() {
  gulp.watch(path.source + '/**/*', ['scss', 'js', 'images']);
})

gulp.task('default', ['js', 'scss', 'images', 'watch']);
