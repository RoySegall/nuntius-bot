var gulp = require('gulp');
var ghPages = require('gulp-gh-pages');
var run = require('gulp-run');
var browserSync = require('browser-sync').create();

/**
 * Deploy the site.
 */
gulp.task('deploy', function() {
  return gulp.src('./docs/static/**/*')
    .pipe(ghPages());
});

/**
 * Generating the text.
 */
gulp.task('generate', function() {
  return run('vendor/bin/daux --source=docs/source/ --destination=docs/static').exec();
});

/**
 * Live reload for the static generate task.
 */
gulp.task('serve', ['generate'], function() {
  browserSync.init({
    server : './docs/static',
    open: true
  });

  gulp.watch('./docs/source/**/*', ['generate']).on('change', function() {

    function sleep (time) {
      return new Promise((resolve) => setTimeout(resolve, time));
    }

    sleep(500).then(() => {
      // Wait for a 0.2 seconds since daux take time until the files are
      // generated.
      browserSync.reload();
    });

  });
});
