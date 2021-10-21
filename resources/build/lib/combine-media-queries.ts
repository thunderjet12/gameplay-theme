/**
 * The external dependencies.
 */
import postcss from 'postcss';
declare let module : any
/**
 * Combine @media rules at the end of the file.
 *
 * @param   {Object}   options
 * @returns {Function}
 */
const Plugin : any = ( options: any ) => {
  return ( root: any ) => {
    const rules: any = {};

    root.walkAtRules('media', ( rule: any ) => {
      const id: number = rule.params;

      if (rules[id] === undefined) {
        rules[id] = postcss.atRule({
          name: rule.name,
          params: rule.params,
        });
      }

      rule.nodes.forEach(( node: any ) => rules[id].append(node.clone()));

      rule.remove();
    });

    Object.keys(rules).forEach(id => root.append(rules[id]));
  };
};

//  const pluginExport = postcss.plugin('wpemerge-combine-media-queries', plugin);
//  export default pluginExport

module.exports =  ( options: any = {} ) => ({

      postcssPlugin: "wpemerge-combine-media-queries",
      Once ( root: any, { result, postcss } ) {
        ( root: any ) => {
          const rules: any = {};
      
          root.walkAtRules('media', ( rule: any ) => {
            const id: number = rule.params;
      
            if (rules[id] === undefined) {
              rules[id] = postcss.atRule({
                name: rule.name,
                params: rule.params,
              });
            }
      
            rule.nodes.forEach(( node: any ) => rules[id].append(node.clone()));
      
            rule.remove();
          });
      
          Object.keys(rules).forEach(id => root.append(rules[id]));
        };
      }
 });
    

module.exports.wpemergeCombineMediaQueries = true