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

mix.js('resources/js/shopper.js', 'public/js')
  .postCss('resources/css/shopper.css', 'public/css', [
    require('tailwindcss'),
  ])
  .options({
    processCssUrls: false,
  })
