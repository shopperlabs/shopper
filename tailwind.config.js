const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors');

module.exports = {
  mode: 'jit',
  darkMode: 'class',
  presets: [
    require('./vendor/ph7jack/wireui/tailwind.config.js')
  ],
  purge: {
    content: [
      './resources/js/**/*.js',
      './resources/views/**/*.blade.php',
      './resources/lang/**/*.php',
      './src/**/*.php',
      './vendor/livewire-ui/modal/resources/views/*.blade.php',
      './vendor/rappasoft/laravel-livewire-tables/resources/views/tailwind/**/*.blade.php',
      './vendor/ph7jack/wireui/resources/**/*.blade.php',
      './vendor/ph7jack/wireui/ts/**/*.ts',
      './vendor/ph7jack/wireui/src/View/**/*.php'
    ],
    options: {
      safelist: [
        'sm:max-w-2xl',
        'sm:max-w-4xl'
      ]
    }
  },
  theme: {
    extend: {
      colors: {
        primary: colors.blue,
        secondary: colors.blueGray,
        gray: colors.blueGray,
        orange: colors.orange,
        success: colors.green,
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
  variants: {
    extend: {
      backgroundColor: ['group-hover', 'focus-within', 'odd'],
      borderWidth: ['odd'],
      display: ['dark'],
      textColor: ['group-hover', 'focus-within', 'odd'],
      opacity: ['group-hover'],
      translate: ['group-hover', 'dark'],
    }
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/line-clamp'),
  ]
};
