module.exports = {
  plugins: loader => [
    require('autoprefixer')({
      browsers: [
        'last 5 version',
        '> 1%',
        'maintained node versions',
        'not dead'
      ],
    }),
    require('postcss-import')({ root: loader.resourcePath }),
    require('postcss-preset-env')(),
    require('cssnano')()
  ]
};
