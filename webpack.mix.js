const mix = require('laravel-mix');

mix.disableSuccessNotifications();
mix.options({
  terser: {
    extractComments: false,
  },
});
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
