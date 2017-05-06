var gulp = require('gulp');
var ghPages = require('gulp-gh-pages');

gulp.task('deploy', function() {
  return gulp.src('./nuntius-doc/static/**/*')
    .pipe(ghPages());
});
