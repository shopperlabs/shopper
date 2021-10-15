const mix = require('laravel-mix');
const path = require('path');

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

mix.js('./resources/js/shopper.js', 'js')
  .postCss('resources/css/shopper.css', 'css').options({
    postCss: [
      require('tailwindcss'),
      require('autoprefixer'),
    ],
  })
  .webpackConfig({
    output: { chunkFilename: 'js/[name].js?id=[chunkhash]' },
    resolve: {
      extensions: ['*', '.js', '.jsx'],
      alias: {
        '@': path.resolve('./resources/js'),
      },
    }
  });

if (mix.inProduction()) {
  mix.version();
}
