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

mix.disableNotifications();

mix.setPublicPath('public')
  .setResourceRoot('../')
  .js('./resources/js/shopper.js', 'js')
  .sass('./resources/sass/shopper.scss', 'css')
  .options({
    processCssUrls: false,
    postCss: [require('tailwindcss')],
  })
  .webpackConfig({
    output: { chunkFilename: 'js/[name].js?id=[chunkhash]' },
    resolve: {
      extensions: ['*', '.js', '.jsx'],
      alias: {
        '@': path.resolve('./resources/js'),
        '@components': path.resolve('./resources/js/src/components'),
        '@hooks': path.resolve('./resources/js/src/hooks'),
        '@pages': path.resolve('./resources/js/src/pages'),
        '@utils': path.resolve('./resources/js/src/utils'),
      },
    }
  })
  .version();
