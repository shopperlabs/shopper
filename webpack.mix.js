const mix = require("laravel-mix");
const path = require('path');

require('laravel-mix-tailwind');

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

mix.setPublicPath("public")
  .setResourceRoot("../") // turns assets paths in css relative to css file
  .sass("./resources/sass/shopper.scss", "css")
  .react("./resources/js/shopper.js", "js")
  .react("./resources/js/initialization.js", "js")
  .options({ processCssUrls: false })
  .tailwind("./tailwind.config.js")
  .webpackConfig({
    output: { chunkFilename: 'js/[name].js?id=[chunkhash]' },
    resolve: {
      extensions: ["*", ".js", ".jsx"],
      alias: {
        '@': path.resolve('./resources/js'),
        '@components': path.resolve('./resources/js/src/components'),
        '@hooks': path.resolve('./resources/js/src/hooks'),
        '@pages': path.resolve('./resources/js/src/pages'),
        '@utils': path.resolve('./resources/js/src/utils'),
      },
    }
  })
  .sourceMaps();

if (mix.inProduction()) {
  mix.version()
    .options({
      // optimize js minification process
      terser: {
        cache: true,
        parallel: true,
        sourceMap: true,
      },
    });
} else {
  // Uses inline source-maps on development
  mix.webpackConfig({ devtool: 'inline-source-map' });
}
