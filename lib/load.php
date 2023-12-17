<?php
/**
 * Main plugin file to load other classes
 *
 * @package DMFA
 */

namespace DMFA;

use DMFA\Customizer\Configurations\DarkMode as DarkModeConfig;
use DMFA\Customizer\Configurations\SiteIcon;
use DMFA\Helpers\AssetsLoader;
use DMFA\Helpers\DarkMode;

/**
 * Init function of the plugin
 */
function init() {
	// Construct all modules to initialize.
	$modules = [
		'customizer_config_dark_mode' => new DarkModeConfig(),
		'customizer_config_site_icon' => new SiteIcon(),
		'helpers_assets_loader'       => new AssetsLoader(),
		'helpers_dark_mode'           => new DarkMode(),
	];

	// Initialize all modules.
	foreach ( $modules as $module ) {
		if ( is_callable( [ $module, 'init' ] ) ) {
			call_user_func( [ $module, 'init' ] );
		}
	}
}

add_action( 'plugins_loaded', 'DMFA\init' );
