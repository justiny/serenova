const mix = require('laravel-mix');
let styleLintPlugin = require("stylelint-webpack-plugin");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.autoload({
  jquery: ['$', 'window.jQuery', "jQuery", "window.$", "jquery", "window.jquery"]
});

 mix.webpackConfig({
  plugins: [
    new styleLintPlugin({
      configFile: ".stylelintrc",
      context: "src/scss",
      files: [
        "*/.scss",
        "helpers/*.scss",
        "components/*.scss",
        "layout/*.scss",
        "utilities/*.scss"
      ],
      syntax: "scss",
      failOnError: false,
      quiet: false
    })
  ]
});

mix
  .extract(['vue','jquery'])
  .js("src/js/app.js", "wp-content/themes/serenova/assets/js/app.js")
  .js("src/js/components/polyfills.js", "wp-content/themes/serenova/assets/js/polyfills.js")
  .sass("src/scss/styles.scss", "wp-content/themes/serenova/assets/css/styles.css")
  .options({
    processCssUrls: false,
    postCss: [require("cssnano")()]
  });


  mix.browserSync({
    proxy: 'https://www.serenova.com/',
    serveStatic: ['wp-content/themes/serenova/assets/css', 'wp-content/themes/serenova/assets/js'],
    files: ['wp-content/themes/serenova/assets/js/app.js','wp-content/themes/serenova/assets/js/polyfills.js','wp-content/themes/serenova/assets/css/styles.css'],
    rewriteRules: [
      {
        match: new RegExp('(?:https?)?:?\/\/(?:www\.)?(?:.*)(?:\/wp-content\/themes\/serenova\/assets\/js\/app.*?).js'),
        fn: function() {
          return '/app.js';
        }
      },
      {
        match: new RegExp('(?:https?)?:?\/\/(?:www\.)?(?:.*)(?:\/wp-content\/themes\/serenova\/assets\/js\/polyfills.*?).js'),
        fn: function() {
          return '/polyfills.js';
        }
      },
      {
        match: new RegExp('https://www.serenova.com/wp-content/cache/min/1/627aa6e99fd7cfbedbcc4defd1cc90fd.css'),
        fn: function() {
          return '/styles.css';
        }
      }
  ]
});
