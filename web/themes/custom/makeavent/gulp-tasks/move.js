'use strict';

// Include gulp
const { src, dest } = require('gulp');

// Include Our Plugins
const rename = require('gulp-rename');

// Export our tasks.
module.exports = {
  moveFonts: function () {
    return src(
      [
        './src/global/fonts/**/*.woff',
        './src/global/fonts/**/*.woff2',
        './src/global/fonts/**/*.eot',
        './src/global/fonts/**/*.ttf',
        './src/global/fonts/**/*.svg'
      ],
      { base: './' }
    )
      .pipe(
        rename(function (path) {
          path.dirname = '';
          return path;
        })
      )
      .pipe(dest('./dist/fonts/assets'));
  }
  // Move CSS specific to styling Pattern Lab.
  // movePatternCSS: function () {
  //   return src(['./src/styleguide/**/*.css'], { base: './' })
  //     .pipe(
  //       rename(function (path) {
  //         path.dirname = '';
  //         return path;
  //       })
  //     )
  //     .pipe(dest('./dist/css'));
  // }
};
