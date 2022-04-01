const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors');

module.exports = {
  darkMode: 'class',
  content: [
    './resources/js/**/*.js',
    './resources/views/**/*.blade.php',
    './resources/lang/**/*.php',
    './src/**/*.php',
    './vendor/rappasoft/laravel-livewire-tables/resources/views/tailwind/**/*.blade.php',
    './vendor/wire-elements/modal/resources/views/*.blade.php',
    './vendor/wireui/wireui/resources/**/*.blade.php',
    './vendor/wireui/wireui/ts/**/*.ts',
    './vendor/wireui/wireui/src/View/**/*.php'
  ],
  safelist: [
    'md:max-w-xl',
    'sm:max-w-2xl',
    'sm:max-w-3xl',
    'sm:max-w-4xl',
    'lg:max-w-2xl',
    'lg:max-w-3xl',
    'lg:max-w-4xl',
  ],
  theme: {
    extend: {
      colors: {
        primary: colors.blue,
        secondary: colors.slate,
        gray: colors.slate,
        orange: colors.orange,
        positive: colors.emerald,
        warning: colors.amber,
        negative: colors.red,
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
      }
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/line-clamp'),
  ]
};
