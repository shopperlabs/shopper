const { ThemeBuilder, Theme } = require('tailwindcss-theming');
const colors = require('@tailwindcss/ui/colors');

const lightTheme = new Theme()
  .name('light')
  .default()
  .assignable()
  .colors({
    // Default Theme
    'primary-light': '#048DDB',
    'primary-default': '#007cc3',
    'primary-dark': '#1d4670',
    'primary-text': colors.gray[600],
    background: colors.gray[100],
    'input-text': colors.gray[800],
    'input-bg': colors.white,
    'input-border': colors.gray[300],

    white: "#ffffff",
  });

const darkTheme = new Theme()
  .name('dark')
  .colors({
    // Default Theme
    'primary-light': colors.gray[700],
    'primary-default': colors.gray[800],
    'primary-dark': colors.gray[900],
    'primary-text': colors.gray[100],
    background: '#202830',
    'input-text': colors.gray[200],
    'input-bg': colors.gray[700],
    'input-border': colors.gray[800],

    white: "#374151",
  });

module.exports = new ThemeBuilder()
  .asClass()
  .default(lightTheme)
  .dark(darkTheme);
