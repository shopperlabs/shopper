const path = require('path');

module.exports = {
  output: { chunkFilename: 'js[name].js?id=[chunkhash]' },
  resolve: {
    extensions: ['*', '.js', '.jsx'],
    alias: {
      '@': path.resolve('resources/js'),
      '@api': path.resolve('./resources/js/api'),
      '@components': path.resolve('./resources/js/components'),
      '@helpers': path.resolve('./resources/js/helpers'),
    },
    modules: [
      'node_modules',
      __dirname + '/vendor/spatie/laravel-medialibrary-pro/resources/js',
    ]
  },
};
