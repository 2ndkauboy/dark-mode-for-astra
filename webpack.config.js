/**
 * External dependencies
 */
const path = require( 'path' );
const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );

/**
 * Webpack config (Development mode)
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-scripts/#provide-your-own-webpack-config
 */
module.exports = {
	...defaultConfig,
	entry: {
		frontend: path.resolve( process.cwd(), 'src', 'frontend.js' ),
		customizer: path.resolve( process.cwd(), 'src', 'customizer.js' ),
	},
	module: {
		rules: [
			...defaultConfig.module.rules,
			{
				test: /\.svg$/,
				use: [ '@svgr/webpack', 'url-loader' ],
			},
		],
	},
};
