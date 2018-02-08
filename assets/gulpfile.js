//Variablen
var srcSASS = 'scss';
var srcJS = 'js/**/*.js';
var destSASS = '../src/Resources/public/css';
var destJS = '../src/Resources/public/js';

//Dependencies
var srcJSDep = [
	'node_modules/bootstrap/dist/js/bootstrap.bundle.min.js', //Bootstrap
];

var srcCSSDep = [
];

// gulp.js einbauen
var gulp = require('gulp');

//Plugins einbauen
var changed = require('gulp-changed'),
	jshint = require('gulp-jshint'),
	concat = require('gulp-concat'),
	concatCSS = require('gulp-concat-css'),
	uglify = require('gulp-uglify'),
	sass = require('gulp-sass'),
	autoprefixer = require('gulp-autoprefixer'),
	cleanCSS = require('gulp-clean-css'),
	sourcemaps = require('gulp-sourcemaps'),
	gutil = require('gulp-util');

//Gulp Standardaufgabe
gulp.task('default', ['watch']);

gulp.task('buildDep', ['DepJS', 'DepCSS', 'styles', 'build-js',] );

//SCSS verarbeiten
gulp.task('styles', function(){
	gulp.src(srcSASS + 'bundle.scss')
		.pipe(sass()) //SASS wird zu CSS kompeliert
		.pipe(autoprefixer({ //Prefixe werden hinzugefügt
			browsers: [
						"Android 2.3",
						"Android >= 4",
						"Chrome >= 20",
						"Firefox >= 24",
						"Explorer >= 8",
						"iOS >= 6",
						"Opera >= 12",
						"Safari >= 6"
					  ],
			cascade: false
		}))
		.pipe(cleanCSS()) //CSS wird kompremiert
		.pipe(gulp.dest(destSASS))
});
//Javascript wird auf Fehler überprüft
gulp.task('jshint', function(){
	return gulp.src('sources/javascript/**/*.js')
		.pipe(jshint())
		.pipe(jshint.reporter('jshint-stylish'));
});

//Javascript wird zusammengeführt und komprimiert
gulp.task('build-js', function(){
	return gulp.src(srcJS)
		.pipe(sourcemaps.init())
			.pipe(concat('bundle.js'))
			.pipe(uglify())
			.pipe(gutil.noop())
		.pipe(sourcemaps.write())
		.pipe(gulp.dest(destJS))
});


//Javascript Dependencies werden gebündelt, minimiert und auf den Server geschoben
gulp.task('DepJS', function(){
	gulp.src(srcJSDep)
	.pipe(sourcemaps.init())
		.pipe(concat('bundleDep.js'))
		.pipe(uglify())
		.pipe(gutil.noop())
	.pipe(sourcemaps.write())
	.pipe(gulp.dest(destJS))
});

//CSS Dependencies werden gebündelt, minimiert und auf den Server geschoben
gulp.task('DepCSS', function(){
	gulp.src(srcCSSDep)
		.pipe(concatCSS('bundleDep.css'))
		.pipe(cleanCSS()) //CSS wird kompremiert
		.pipe(gulp.dest(destSASS))
});

//Aufgabe wird ausgeführt wenn sich eine Datei ändert
gulp.task('watch', function(){
	gulp.watch(srcSASS + '**/*.scss', ['styles']);
	gulp.watch(srcJS, ['jshint','build-js']);
});