const mix = require('laravel-mix')
const tailwindcss = require('tailwindcss')

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

mix.js('packages/admin/resources/js/shopper.js', 'public')
mix.postCss('packages/admin/resources/css/shopper.css', 'public', [
    tailwindcss('./packages/admin/tailwind.config.js'),
    require('autoprefixer')
  ])
  .options({
    processCssUrls: false,
  })
