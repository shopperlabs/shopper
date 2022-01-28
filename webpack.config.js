const path = require('path')

module.exports = {
  output: { chunkFilename: 'js[name].js?id=[chunkhash]' },
  resolve: {
    extensions: ['*', '.js', '.jsx'],
    alias: {
      '@': path.resolve('./resources/js'),
      '@api': path.resolve('./resources/js/api'),
      '@components': path.resolve('./resources/js/components'),
      '@helpers': path.resolve('./resources/js/helpers'),
      'react': 'preact/compat',
      'react-dom': 'preact/compat'
    },
  },
}
