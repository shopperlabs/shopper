const mix = require("laravel-mix");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.disableNotifications();

mix.setPublicPath("public")
  .setResourceRoot("../") // turns assets paths in css relative to css file
  .sass("./resources/assets/sass/shopper.scss", "css")
  .js("./resources/assets/js/vendor.js", "js")
  .js("./resources/assets/js/global/template.js", "js")
  .js("./resources/assets/js/login.js", "js")
  .react("./resources/assets/ts/shopper.ts", "js")
  .webpackConfig({
    module: {
      rules: [
        {
          test: /\.tsx?$/,
          loader: "ts-loader",
          exclude: /node_modules/
        }
      ]
    },
    resolve: {
      extensions: ["*", ".js", ".jsx", ".ts", ".tsx"]
    }
  })
  .sourceMaps();

if (mix.inProduction()) {
  mix.version()
    .options({
      // optimize js minification process
      terser: {
        cache: true,
        parallel: true,
        sourceMap: true
      }
    });
} else {
  // Uses inline source-maps on development
  mix.webpackConfig({ devtool: "inline-source-map" });
}
