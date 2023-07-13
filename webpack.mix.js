const mix = require('laravel-mix')

mix.disableSuccessNotifications()
mix.options({
  terser: {
    extractComments: false,
  },
})
mix.setPublicPath('packages/admin/public')
mix.setResourceRoot('packages/admin/resources')
mix.sourceMaps()
mix.version()

mix.js('packages/admin/resources/js/shopper.js', 'public/js')
  .postCss('packages/admin/resources/css/shopper.css', 'public/css')
  .options({
    processCssUrls: false,
  })
