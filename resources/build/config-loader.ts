/**
 * The external dependencies.
 */
import path from 'path';


const configLoader = {
  
  loader: path.join(__dirname, 'lib', 'config-loader.ts'),
  options: {
    sassOutput: 'resources/styles/[name].config.scss',
  },
};
export default configLoader