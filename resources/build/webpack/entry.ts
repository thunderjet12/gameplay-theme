/**
 * The internal dependencies.
 */
declare let module : any

import * as utils from '../lib/utils';
import keyBy from 'lodash/keyBy';
import mapValues from 'lodash/mapValues';

module.exports = mapValues(
  keyBy(utils.getUserConfig().bundles, bundle => bundle),
  bundle => utils.srcScriptsPath(`${bundle}/index.ts`)
);

// module.exports.entry = true