const defaultTheme = require('tailwindcss/defaultTheme')
const colors = require('tailwindcss/colors')

module.exports = {
  darkMode: 'class',
  content: [
    './resources/js/**/*.js',
    './resources/views/**/*.blade.php',
    './src/**/*.php',
    './vendor/rappasoft/laravel-livewire-tables/resources/views/**/*.blade.php',
    './vendor/wire-elements/modal/resources/views/*.blade.php',
    './vendor/wireui/wireui/resources/**/*.blade.php',
    './vendor/wireui/wireui/ts/**/*.ts',
    './vendor/wireui/wireui/src/View/**/*.php',
    './vendor/filament/**/*.blade.php',
  ],
  safelist: [
    'md:max-w-xl',
    'sm:max-w-2xl',
    'sm:max-w-3xl',
    'sm:max-w-4xl',
    'lg:max-w-2xl',
    'lg:max-w-3xl',
    'xl:max-w-4xl',
  ],
  theme: {
    extend: {
      colors: {
        primary: colors.blue,
        indigo: colors.blue,
        secondary: colors.slate,
        orange: colors.orange,
        positive: colors.emerald,
        success: colors.emerald,
        warning: colors.amber,
        negative: colors.red,
        danger: colors.red,
        info: colors.sky,
      },
      inset: {
        '-0.5': '-0.125rem',
      },
      spacing: {
        44: '11rem',
        18: '4.5rem',
        95: '23.75rem',
        125: '31.25rem',
        140: '35rem'
      },
      opacity: {
        15: '.15',
        30: '0.3',
        40: '0.4',
      },
      minHeight: {
        '(screen-content)': 'calc(100vh - 9.625rem)',
      },
      fontFamily: {
        sans: ['Inter var', ...defaultTheme.fontFamily.sans]
      },
      maxWidth: {
        '8xl': '88rem',
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
  ]
}
