const path = require('path');
const { VueLoaderPlugin } = require('vue-loader');
const HtmlWebpackPlugin = require('html-webpack-plugin');

module.exports = {
  mode: 'development',
  entry: './src/chat.js',
  output: {
    path: path.resolve(__dirname, 'dist'),
    filename: '[name].js',
    chunkFilename: '[id].[chunkhash].js',
  },
  module: {
    rules: [
      {
        test: /\.vue$/,
        loader: 'vue-loader',
      },
      {
        test: /\.js$/,
        loader: 'babel-loader',
        exclude: /node_modules/,
      },
      {
        test: /\.css$/,
        use: ['style-loader', 'vue-style-loader', 'css-loader', 'postcss-loader'],
      },
    ],
  },
  devtool: 'inline-source-map',
  plugins: [
    new VueLoaderPlugin(),
    new HtmlWebpackPlugin({
      title: 'Development',
    }),
  ],
  devServer: {
    static: {
      directory: path.join(__dirname, 'dist'),
      serveIndex: true,
    },
    historyApiFallback: true,
    watchFiles: ['src/**/*'],
    port: 9000,
    hot: true,
  },
  optimization: {
    runtimeChunk: 'single',
  },
};
