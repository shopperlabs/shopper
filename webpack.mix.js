const mix = require('laravel-mix');

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

mix.disableSuccessNotifications();

mix.setPublicPath('public')
  .setResourceRoot('../');

mix.js('./resources/js/shopper.js', 'js').react()
  .postCss('resources/css/shopper.css', 'css',[
    require('tailwindcss'),
    require('autoprefixer'),
  ])
  .webpackConfig(require('./webpack.config'));

if (mix.inProduction()) {
  mix.version();
}
