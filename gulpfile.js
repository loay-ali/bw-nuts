const minify = require('gulp-minify');
const gulp = require('gulp');

function js(cb) {
    
    return gulp.src('assets/js/resources/app.js')
        .pipe(minify())
        .pipe(gulp.dest('assets/js'));
}

exports.js = js;