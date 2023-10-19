const gulp = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const autoprefixer = require('gulp-autoprefixer');
const cleanCSS = require('gulp-clean-css');

const sourceDir = './client/src/scss';
const destinationDir = './client/dist/css';


gulp.task('sass', function () {
    return gulp
        .src(`${sourceDir}/*.scss`)
        .pipe(sass().on('error', sass.logError))
        .pipe(autoprefixer())
        .pipe(cleanCSS())
        .pipe(gulp.dest(destinationDir));
});


gulp.task('watch', function () {
    gulp.watch(`${sourceDir}/*.scss`, gulp.series('sass'));
});


gulp.task('default', gulp.series('sass', 'watch'));
