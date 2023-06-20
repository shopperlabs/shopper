const defaultTheme = require('tailwindcss/defaultTheme')
const colors = require('tailwindcss/colors')

module.exports = {
  darkMode: 'class',
  presets: [
    require('./packages/admin/tailwind.admin.config.js'),
  ],
  content: [
    './packages/admin/resources/views/**/*.blade.php',
    './packages/admin/src/**/*.php',
    '../vendor/rappasoft/laravel-livewire-tables/resources/views/**/*.blade.php',
    '../vendor/wire-elements/modal/resources/views/*.blade.php',
    '../vendor/wireui/wireui/resources/**/*.blade.php',
    '../vendor/wireui/wireui/ts/**/*.ts',
    '../vendor/wireui/wireui/src/View/**/*.php',
  ],
  theme: {},
  plugins: [],
}
