var gulp = require('gulp');
// var source = require('vinyl-source-stream');
// var buffer = require('vinyl-buffer');
var gutil = require('gulp-util');
// var uglify = require('gulp-uglify');
// var sourcemaps = require('gulp-sourcemaps');
// var reactify = require('reactify');
var sass = require('gulp-sass');
// var fs = require('fs-extra');
var exec = require('child_process').exec;
var path = require('path');
// var install = require("gulp-install");

gulp.task('style', function() {
    gulp.src('./scss/style.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(gulp.dest('./css'));;
});

gulp.task('style:watch', function() {
    gulp.watch('./scss/style.scss', ['style']);
});

gulp.task('clean', function() {
    fs.removeSync(__dirname+'/bower_components');
    fs.removeSync(__dirname+'/lib');
});

gulp.task('install', function() {
    // gulp.src(['./bower.json', './package.json']) // FIXME Doesn't work
    //     .pipe(install());
});

gulp.task('bootstrap', ['install', 'style']);

gulp.task('watch', ['bootstrap', 'style:watch']);

gulp.task('default', ['watch']);

