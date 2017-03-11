var gulp = require('gulp');
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');
var moduleImporter = require('sass-module-importer');


gulp.task('default', ['styles', 'scripts', 'watch']);


gulp.task('styles', function() {
    gulp.src('static/scss/style.scss')
        .pipe(sourcemaps.init())
            .pipe(sass({
                includePaths: ['static/scss/partials'],
                importer: moduleImporter()
            }).on('error', sass.logError))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('./assets/css'));
});

gulp.task('scripts', function() {
    gulp.src('static/js/script.js')
        .pipe(gulp.dest('./assets/js'));
});

gulp.task('watch', function() {
    gulp.watch('static/scss/**/*.scss', ['styles']);
    gulp.watch('static/js/**/*.js', ['scripts']);
});
