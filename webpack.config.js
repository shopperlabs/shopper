const path = require('path')

module.exports = {
  output: { chunkFilename: 'js[name].js?id=[chunkhash]' },
  resolve: {
    extensions: ['*', '.js',],
    alias: {
      '@': path.resolve('./resources/js'),
      '@api': path.resolve('./resources/js/api'),
      '@helpers': path.resolve('./resources/js/helpers'),
    },
  },
}
