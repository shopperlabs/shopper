const mix = require('laravel-mix')

mix.disableSuccessNotifications()
mix.options({
  terser: {
    extractComments: false,
  },
})
mix.setPublicPath('public')
mix.setResourceRoot('resources')
mix.sourceMaps()
mix.version()

mix.js('resources/js/shopper.js', 'public')
mix.postCss('resources/css/shopper.css', 'public', [
    require('tailwindcss'),
  ])
  .options({
    processCssUrls: false,
  })
