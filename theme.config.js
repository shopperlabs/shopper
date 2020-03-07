const { ThemeBuilder, Theme } = require('tailwindcss-theming');

const lightTheme = new Theme()
  .name('light')
  .default()
  .assignable()
  .colors({
    transparent: 'transparent',

    brand: '#007cc3',
    'brand-hover': '#048DDB',
    secondary: '#1d4670',
  });

const darkTheme = new Theme()
  .name('dark')
  .colors({
    brand: '#16191C',
    'brand-hover': '#404B55',
    secondary: '#262E35',
  });

module.exports = new ThemeBuilder()
  .asClass()
  .default(lightTheme)
  .dark(darkTheme);
