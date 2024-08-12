# Control Panel
The control panel may be customized in a number of different ways. You may add new pages, menus, a stylesheet, or maybe you just want to add some arbitrary Javascript.

When you need to add features to your Shopper administration, you can first set up some configurations

## Adding CSS and JS assets
Shopper can load extra stylesheets and Javascript files located in the `public/` directory.

You may register assets to be loaded in the Control Panel using the `scripts` and `stylesheets` keys of the resources in the `config/shopper/admin.php` config file. This will accept a array of links.

You can load the links locally or using cdn. They will be automatically loaded in the control panel

``` php
'resources' => [
  'stylesheets' => [
      '/css/admin.css',
      'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css',
    ],
  'scripts' => [
    'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js',
    '/js/admin.js',
  ],
],
```

:::info
Depending on how you will use your css and js files, the order is important
:::

These commands will make Shopper expect files at `public/css/admin.css` and `public/js/admin.js` respectively for local links.

## Customize Shopper theme
Shopper is built using Tallstack presets, but you are not limited to that because the base css file is already built for production.

But if you want to customize your dashboard using Tailwind css you must first install Tailwind in your project. You can read the [documentation](https://tailwindcss.com/docs/guides/laravel)

Shopper using Tailwind CSS, there are some Tailwind plugins you need to install first: Plugin Forms and Typography, Autoprefixer. You can install them via NPM or Yarn:

```bash
yarn add -D tailwindcss @tailwindcss/forms @tailwindcss/typography autoprefixer
```

After installing Tailwind, you need to create a `tailwind.config.js` file at the root of your project and add this content

```js
import forms from '@tailwindcss/forms'
import typography from '@tailwindcss/typography'
import colors from 'tailwindcss/colors'
import filamentPreset from './vendor/filament/support/tailwind.config.preset' // [tl! focus]
import preset from './vendor/shopper/framework/tailwind.config.preset' // [tl! focus]

/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: 'class',
  presets: [filamentPreset, preset],
  content: [
    './resources/**/*.blade.php',
    './vendor/shopper/framework/**/*.blade.php', // [tl! focus:start]
    './vendor/shopper/framework/src/**/*.php',
    './vendor/filament/**/*.blade.php', // [tl! focus:end]
  ],
  theme: {
    extends: {
      colors: {
        primary: colors.blue,  // [tl! focus]
      }
    }
  },
  plugins: [forms, typography],  // [tl! focus]
}
```

New versions of Laravel come with vite by default so if you want to customize the Shopper admin, you need to switch to `Laravel Mix` and in your `webpack.mix.js` file, register Tailwind CSS as a PostCSS plugin:

```js
const mix = require('laravel-mix')

mix.postCss('resources/css/admin.css', 'public/css', [
  require('tailwindcss'), // [tl! focus]
])
```

Just load the default Tailwind CSS directives inside your `./resources/css/admin.css`

```css
@tailwind base;
@tailwind components;
@tailwind utilities;
```

Then run `yarn run dev`

:::tip
Keep in mind the `admin.css` file must be load on the `resources` key of your `shopper/admin.php` config file
:::

And add Tailwind to the `postcss.config.js` file:

```js
module.export = {
  plugins: {
    tailwindcss: {},
    autoprefixer: {},
  },
}
```

### Branding Logo
After update (or not) the colors of your administration theme to reflect your brand. You'll probably want to change the logo to display

By default, Laravel Shopper logo is used next to your application name in the administration panel.

You can choose between 2 options

The first, and simplest, is to modify the value of the `brand` key in your `shopper/admin.php` configuration file, by entering the link to your logo.

```php
  /*
  |--------------------------------------------------------------------------
  | Admin Brand Name
  |--------------------------------------------------------------------------
  |
  | This will be displayed on the login page and in the sidebar's header.
  |
  */

  'brand' => 'img/logo.svg',
```

This will load using the Laravel `asset()` helper function.

The 2nd option is to create a resources/views/vendor/shopper/components/brand.blade.php file to provide a customized logo:

:::info
It can be a simple svg or an image tag.
:::

```html
<img class="w-auto h-12" src="{{ asset('shopper/images/shopper-icon.svg') }}" alt="Laravel Shopper" />
```

## Adding control panel routes
If you need to have custom routes for the control panel:

1. Create a routes file. Name it whatever you want, for example: `routes/shopper.php`
2. Then add this to your `shopper/routes.php` file so that all routes are dynamically loaded:
    ```php
     'custom_file' => base_path('routes/shopper.php'),
    ```
3. If you want to add middleware to further control access to the routes available in this file you can add in the key `middleware` of the `shopper/routes.php` file

	```php
    'middleware' => [
      'my-custom-middleware', 
      'permission:can-access',
    ],
	```
