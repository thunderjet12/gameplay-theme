
/**
 * The external dependencies.
 */
import fs from 'fs';
import path from "path";
import crypto from 'crypto';
import pick from 'lodash/pick';

// declare module "pathbrowserif" {
//   let pattt : any
//   export default pattt
// };
/**
 * User config cache.
 */
let userConfig: null = null;

/**
 * API.
 */
export const rootPath = (basePath = '', destPath = '') =>
  path.resolve( __dirname, '../../', basePath, destPath);

export const srcPath = (basePath = '', destPath = '') =>
  path.resolve(__dirname, '../../', basePath, destPath);

export const distPath : any = (basePath = '', destPath = '') =>
  path.resolve(__dirname, '../../dist', basePath, destPath);

export const srcScriptsPath : any = ( destPath: string ) =>
  exports.srcPath('scripts', destPath);

export const srcStylesPath : any = ( destPath: string ) =>
  exports.srcPath('styles', destPath);

export const srcImagesPath : any = ( destPath: string ) =>
  exports.srcPath('images', destPath);

export const srcFontsPath : any = ( destPath: string ) =>
  exports.srcPath('fonts', destPath);

export const srcVendorPath : any = ( destPath: string ) =>
  exports.srcPath('vendor', destPath);

export const distScriptsPath : any = ( destPath: string ) =>
  exports.distPath('scripts', destPath);

export const distStylesPath : any = ( destPath: string ) =>
  exports.distPath('styles', destPath);

export const distImagesPath : any = ( destPath: string ) =>
  exports.distPath('images', destPath);

export const distFontsPath : any = ( destPath: string ) =>
  exports.distPath('fonts', destPath);

export const tests : any = {
  scripts: /\.(ts|js|jsx|tsx)x?$/,
  styles: /\.(css|scss|sass)$/,
  spriteSvgs: /(resources|dist|node_modules)[\\/]images[\\/]sprite-svg[\\/].*\.svg$/,
  images: /(resources|dist|node_modules)[\\/](?!images[\\/]sprite-svg|fonts).*\.(ico|jpg|jpeg|png|svg|gif)$/,
  fonts: /(resources|dist|node_modules)[\\/](?!images[\\/]sprite-svg).*\.(eot|svg|ttf|woff|woff2)$/,
};

export const detectEnv : any = () => {
  const env = process.env.NODE_ENV || 'development';
  const isDev = env === 'development';
  const isHot = env === 'hot';
  const isDebug = env === 'debug';
  const isProduction = env === 'production';

  return {
    env,
    isDev,
    isHot,
    isDebug,
    isProduction,
    minify: isProduction,
    filenameSuffix: isDev || isProduction ? '.min' : '',
  };
};

export const getWhitelistedUserConfig : any = ( config: any ) => {
  const whitelist = config.release.configWhitelist || [];
  return pick(config, whitelist);
};

export const getUserConfig : any  = ( file: any , whitelisted = false) => {
  const userConfigPath = file || path.join(process.cwd(), 'config.json');

  if (userConfig !== null) {
    return userConfig;
  }

  if (!fs.existsSync(userConfigPath)) {
    console.error('\x1B[31mCould not find your config.json file. Please make a copy of config.json.dist and adjust as needed.\x1B[0m');
    process.exit(1);
  }

  try {
    userConfig = JSON.parse(fs.readFileSync( userConfigPath, 'utf-8' ));
  } catch (e) {
    console.error('\x1B[31mCould not parse your config.json file. Please make sure it is a valid JSON file.\x1B[0m');
    process.exit(1);
  }

  if (whitelisted) {
    return module.exports.getWhitelistedUserConfig(userConfig);
  }

  return userConfig;
};

export const filehash : any = ( file: any ) => {
  const hash = crypto.createHash('sha1');
  hash.update(fs.readFileSync(file));
  return hash.digest('hex');
};
