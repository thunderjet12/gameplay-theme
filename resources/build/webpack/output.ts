/**
 * The internal dependencies.
 */
declare let module : any

import * as utils from'../lib/utils';

const env = utils.detectEnv();

module.exports  = {
  path: utils.distPath(),
  filename: `[name]${env.filenameSuffix}.js`,
};

module.exportsoutput = true