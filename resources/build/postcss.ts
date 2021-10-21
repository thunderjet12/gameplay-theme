/**
 * The internal dependencies.
 */
import * as utils from './lib/utils';

/**
 * Setup PostCSS plugins.
 */
const plugins = [
  // require('tailwindcss')(utils.srcPath('build/tailwind.config.js')),
  require('postcss-discard-comments'),
  require('autoprefixer'),
  require('./lib/combine-media-queries'),
];

/**
 * Prepare the configuration.
 */
const config = {
  postcssOptions: {
  plugins,
  }
};

export default config;