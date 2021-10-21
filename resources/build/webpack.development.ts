/**
 * The external dependencies.
 */
 import url from 'url';
 import { Configuration as WebpackConfiguration, ProvidePlugin, WatchIgnorePlugin } from "webpack";
 import type { Configuration as WebpackDevServerConfiguration } from "webpack-dev-server";
 import { CleanWebpackPlugin }  from "clean-webpack-plugin";
 import MiniCssExtractPlugin from "mini-css-extract-plugin";
 import { WebpackManifestPlugin } from "webpack-manifest-plugin";
 import chokidar from "chokidar";
 import get from  "lodash/get";
 import DependencyExtractionWebpackPlugin  from "@wordpress/dependency-extraction-webpack-plugin";
 
 interface Configuration extends WebpackConfiguration {
   devServer?: WebpackDevServerConfiguration;
 }
 
 
 /**
  * The internal dependencies.
  */
 import * as utils from "./lib/utils";
 import configLoader from "./config-loader";
 import spriteSmith from "./spritesmith";
//  import spriteSvg from "./spritesvg";
 import  postcss from "./postcss";
 
 /**
  *  Wordpress Gutenberg
  */
//  import sveltePreprocess from "svelte-preprocess";
 const mode = process.env.NODE_ENV || 'development';
 const prod = mode === 'production';
 import toml from "toml";
 
 // const config: webpack.Configuration =  {}
 // const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
 // const BrowserSyncPlugin = require( 'browser-sync-webpack-plugin' );
 let localEnv = '';
 
 
 // Check if local.json exists
 try {
   localEnv = require( './local.json' ).devURL;
 } catch ( err ) {
   // Fallback if it does not
   localEnv = 'https://iceberg.test';
 }
 
 /**
  * Setup the environment.
  */
 const env = utils.detectEnv();
 const userConfig = utils.getUserConfig();
 const devPort = get(userConfig, 'development.port', 3000);
 const devHotUrl = new URL(get(userConfig, 'development.hotUrl', 'http://localhost/').replace(/\/$/, ''));
 
 /**
  * Setup babel loader.
  */
 const babelLoader = {
   loader: 'babel-loader',
 };
 
 /**
  * Setup webpack plugins.
  */
 const plugins = [
   new CleanWebpackPlugin(),
   new WatchIgnorePlugin({
     paths: [utils.distImagesPath('sprite.png'),
     utils.distImagesPath('sprite@2x.png'), ]
   }),
   new ProvidePlugin({
     $: 'jquery',
     jQuery: 'jquery',
   }),
   new MiniCssExtractPlugin( { filename: '[name].css' }),
 
   new MiniCssExtractPlugin({
     filename: `styles/[name]${env.filenameSuffix}.css`,
     chunkFilename: '[id].css',
   }),
   spriteSmith,
  //  spriteSvg,
   new DependencyExtractionWebpackPlugin({}),
   new WebpackManifestPlugin({
     writeToFileEmit: true,
   }),
 ];
 
 /**
  * Export the configuration.
  */
 const developmentConfig: Configuration = {
   /**
    * The input.
    */
   entry: require('./webpack/entry'),
 
   /**
    * The output.
    */
   output: {
     ...require('./webpack/output'),
     ...(env.isHot
       // Required to work around https://github.com/webpack/webpack-dev-server/issues/1385
       ? { publicPath: `${devHotUrl.protocol}//${devHotUrl.host}:${devPort}/` }
       : {}
     ),
   },
 
   /**
    * Resolve utilities.
    */
   resolve: require('./webpack/resolve'),
 
   /**
    * Resolve the dependencies that are available in the global scope.
    */
   externals: require('./webpack/externals'),
    
   /**
    * Setup the transformations.
    */
   module: {
     rules: [
       /**
        * Add support for blogs in import statements.
        */
       // {
       // 	test: /.toml/,
       // 	type: 'json',
       // 	parser: {
       // 		parse: toml.parse,
       // 	},
       // },
       {
         test: /\.js$/,
         enforce: "pre",
         use: ["source-map-loader"],
       },
       {
         enforce: 'pre',
         test: /\.(js|ts|tsx|jsx|css|scss|sass)$/,
         use: 'import-glob',
       },
       
       // {
       //   test: /\.(ts|tsx|js)$/,
       //   exclude: /node_modules/,
       //   use: [{
       //       loader: 'ts-loader',
       //   }]
       // },
 
       /**
        * Handle config.json.
        */
       {
         type: 'javascript/auto',
         test: utils.rootPath('config.json'),
         use: configLoader,
       },
 
       /**
        * Handle scripts.
        */
       {
         test: utils.tests.scripts,
         exclude: /node_modules/,
         use: babelLoader,
       },
 
       /**
        *  Svelte
        */
 
      //  {
      //    // required to prevent errors from Svelte on Webpack 5+
      //    test: /node_modules\/svelte\/.*\.mjs$/,
      //    resolve: {
      //      fullySpecified: false
      //    }
      //  }, 
 
      //  {
      //    test: /\.(html|svelte)$/,
      //    use: [
      //      // { loader: "babel-loader" },
      //      {
      //        loader: 'svelte-loader',
      //          options: {
      //            compilerOptions: {
      //              dev: !prod
      //            },
      //          customElement:true,
      //          emitCss: true,
      //          preprocess: require('svelte-preprocess')({
      //            // defaults: {
      //            //   style: "scss",
      //            // },
      //            sourceMap: !prod,
      //            postcss: postcss
      //          }),
             
      //        }
 
      //      }
 
      //    ]
      //  },
 
       /**
        * 
        */
       
       //  {
       //   test: /\.(p?css)$/,
       //   use: [{
       //      loader: MiniCssExtractPlugin.loader,
       //       options: {
       //         publicPath: '../',
       //         // hmr: env.isHot,
       //       }
       //      }, 
       //      'css-loader',
       //      {
       //       loader: 'postcss-loader',
       //       options: {
       //         postcssOptions: postcss,
       //       },
       //     }
       //     ],
         
       //   sideEffects: true
       // },
 
 
       /**
        * Handle styles.
        */
       {
         test: utils.tests.styles,
         use: [
           {
             loader: MiniCssExtractPlugin.loader,
             options: {
               publicPath: '../',
               // hmr: env.isHot,
             },
           },
           'css-loader',
           {
             loader: 'postcss-loader',
             options: {
               postcssOptions: postcss,
             },
           },
           'sass-loader',
         ],
       },
 
       /**
        * Handle images.
        */
       {
         test: utils.tests.images,
         use: [
           {
             loader: 'file-loader',
             options: {
               name: ( file: any ) => `[name].${utils.filehash(file).substr(0, 10)}.[ext]`,
               outputPath: 'images',
             },
           },
         ],
       },
 
       /**
        * Handle SVG sprites.
        */
      //  {
      //    test: utils.tests.spriteSvgs,
      //    use: [
      //      {
      //        loader: 'svg-sprite-loader',
      //        options: {
      //          extract: false,
      //        },
      //      },
      //    ],
      //  },
 
       /**
        * Handle fonts.
        */
       {
         test: utils.tests.fonts,
         use: [
           {
             loader: 'file-loader',
             options: {
               name: ( file: any ) => `[name].${utils.filehash(file).substr(0, 10)}.[ext]`,
               outputPath: 'fonts',
             },
           },
         ],
       },
     ],
   },
 
   /**
    * Setup the transformations.
    */
   plugins,
 
   /**
    * Setup the development tools.
    */
   mode: 'development',
   cache: true,
   bail: false,
   watch: true,
   devtool: 'source-map',
   devServer: {
     hot: true,
     host : devHotUrl.host,
     port: devPort,
     disableHostCheck: true,
     headers: {
       'Access-Control-Allow-Origin': '*',
     },
     overlay: true,
 
     // Reload on view file changes.
     before: (app, server) => {
       chokidar
         .watch([
           './views/**/*.php',
           './*.php',
         ])
         .on('all', () => {
           server.sockWrite(server.sockets, 'content-changed');
         });
     },
   },
 };
 
 export default developmentConfig