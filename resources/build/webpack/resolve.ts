/**
 * The internal dependencies.
 */

declare let module : any
declare let require : any


import * as utils from '../lib/utils';
module.exports = {
  modules: [utils.srcScriptsPath(), 'node_modules'],
  extensions: ['.js', '.jsx', '.ts', '.tsx', '.json', '.css', '.scss'],
  alias: {
    '@config': utils.rootPath('config.json'),
    '@scripts': utils.srcScriptsPath(),
    '@styles': utils.srcStylesPath(),
    '@images': utils.srcImagesPath(),
    '@fonts': utils.srcFontsPath(),
    '@vendor': utils.srcVendorPath(),
    '@dist': utils.distPath(),
    '~': utils.rootPath('node_modules'),
    'isotope': 'isotope-layout',
    'masonry': 'masonry-layout',
    'jquery-ui': 'jquery-ui-dist/jquery-ui.js',
  },
  fallback: { "path": require.resolve("path-browserify") }

};

// module.exports = true