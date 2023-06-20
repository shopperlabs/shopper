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

mix.js('packages/admin/resources/js/shopper.js', 'js')
  .postCss('packages/admin/resources/css/shopper.css', 'css',[
    tailwindcss('./tailwind.config.js'),
  ])
  .options({
    processCssUrls: false,
  })
