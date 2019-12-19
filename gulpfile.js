"use strict";

// Load plugins
const browserSync = require('browser-sync');
const gulp = require('gulp');
const sass = require('gulp-sass');
const sourcemaps = require('gulp-sourcemaps');
const autoprefixer = require('gulp-autoprefixer');
const jshint = require('gulp-jshint');
const path = require('path');
const rename = require('gulp-rename');

// Start browserSync server
gulp.task('browserSync', function () {
  browserSync.init({
    proxy: "http://localhost/btpc/"
  });
})

// BrowserSync Reload
gulp.task('browserSyncReload', function (done) {
  browserSync.reload();
  done();
})

// Compile SASS Files
gulp.task('sass', function () {
  return gulp.src('sass/style.scss')
    .pipe(sourcemaps.init())
    .pipe(sass({
      outputStyle: 'expanded', // nested, compact, expanded, compressed
      precision: 10,
    }).on('error', sass.logError))
    .pipe(autoprefixer({
      browsers: [
        'last 5 versions'
      ]
    }))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest('./')) // Outputs it in the root folder
    .pipe(browserSync.stream());
})

// Compile SASS Files
gulp.task('icon_fonts', function () {
  return gulp.src('sass/icon_fonts.scss')
    .pipe(sass({
      outputStyle: 'expanded', // nested, compact, expanded, compressed
      precision: 10,
    }).on('error', sass.logError))
    .pipe(autoprefixer({
      browsers: [
        'last 5 versions'
      ]
    }))
    .pipe(gulp.dest('./css')) // Outputs it in the root folder
    .pipe(browserSync.stream());
})

// Report JS Problems
gulp.task('lint', function () {
  return gulp.src('js/*.js')
    .pipe(jshint())
    .pipe(jshint.reporter('jshint-stylish'));
})

// Watch the files
gulp.task('watch', function () {
  gulp.watch('**/*.scss', gulp.series('sass'));
  gulp.watch('**/*.html', gulp.series('browserSyncReload'));
  gulp.watch('**/*.php', gulp.series('browserSyncReload'));
  gulp.watch('**/js/**/*.js', gulp.series('lint', 'browserSyncReload'));
})




// Build Sequences
// ---------------
gulp.task('default', function (callback) {
  return gulp.series(gulp.parallel('sass'), gulp.parallel('watch', 'browserSync'))();
})