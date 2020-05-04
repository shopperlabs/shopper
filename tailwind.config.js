const defaultTheme = require('tailwindcss/defaultTheme');
const rgba = require('hex-to-rgba');

module.exports = {
  purge: {
    enable: false,
    content: [],
    options: {
      defaultExtractor: content => {
        // Capture as liberally as possible, including things like `h-(screen-1.5)`
        const broadMatches = content.match(/[^<>"'`\s]*[^<>"'`\s:]/g) || [];

        // Capture classes within other delimiters like .block(class="w-1/2") in Pug
        const innerMatches = content.match(/[^<>"'`\s.()]*[^<>"'`\s.():]/g) || [];

        return broadMatches.concat(innerMatches)
      }
    }
  },
  theme: {
    extend: {
      colors: {
        'primary-light': 'rgb(var(--color-primary-light))',
        'primary-default': 'rgb(var(--color-primary-default))',
        'primary-dark': 'rgb(var(--color-primary-dark))',
        'primary-text': 'rgb(var(--color-primary-text))',
        'input-text': 'rgb(var(--color-input-text))',
        'input-bg': 'rgb(var(--color-input-bg))',
        'input-border': 'rgb(var(--color-input-border))',
        'on-primary': '#ffffff',
        background: 'rgb(var(--color-background))',
        white: 'rgb(var(--color-white))',

        brand: {
          50: '#048DDB',
          100: '#048DDB',
          200: '#048DDB',
          300: '#048DDB',
          400: '#007cc3',
          500: '#0069AF',
          600: '#0069AF',
          700: '#0069AF',
          800: '#0069AF',
          900: '#1d4670',
        }
      },
      boxShadow: theme => ({
        smooth: '0 2px 20px 0 rgba(0, 0, 0, 0.05)',
        bigger: '0 10px 20px 0 rgba(0, 0, 0, 0.01)',
        'outline-brand': `0 0 0 3px ${rgba(theme('colors.brand.400'), 0.45)}`,
      }),
      spacing: {
        125: '31.25rem',
        140: '35rem',
      },
      fontFamily: {
        body: ["Inter var", ...defaultTheme.fontFamily.sans],
      },
    },
    customForms: theme => ({
      default: {
        'input, textarea, select, multiselect, checkbox, radio': {
          borderWidth: defaultTheme.borderWidth[2],
          '&:focus': {
            outline: 'none',
            boxShadow: 'none',
            borderColor: theme('colors.brand.400'),
          },
        },
      }
    }),
    spinner: (theme) => ({
      default: {
        color: theme('colors.brand.500'), // color you want to make the spinner
        size: '1em', // size of the spinner (used for both width and height)
        border: '2px', // border-width of the spinner (shouldn't be bigger than half the spinner's size)
        speed: '500ms', // the speed at which the spinner should rotate
      },
    }),
  },
  variants: {
    translate: ['responsive', 'hover', 'focus', 'active', 'group-hover'],
    backgroundColor: ['responsive', 'hover', 'focus', 'group-hover', 'focus-within'],
    textColor: ['responsive', 'hover', 'focus', 'group-hover', 'focus-within'],
    fontFamily: ['responsive', 'hover', 'focus'],
    zIndex: ['responsive', 'focus'],
    spinner: ['responsive'],
  },
  plugins: [
    require('@tailwindcss/ui'),
    require('./theme.config.js'),
    require('tailwindcss-spinner')(),
  ],
};
