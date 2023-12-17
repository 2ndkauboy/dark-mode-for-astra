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
	// Check, if Astra is not active.
	if ( ! function_exists( 'astra_get_option' ) ) {
		add_action( 'admin_notices', 'dmfa_astra_missing' );

		// Stop the further processing of the plugin.
		return;
	}

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

add_action( 'after_setup_theme', 'DMFA\init' );
