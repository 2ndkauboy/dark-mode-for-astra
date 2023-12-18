<?php
/**
 * Main plugin file to load other classes
 *
 * @package DMFA
 */

namespace DMFA;

use DMFA\Customizer\ConfigurationLoader;
use DMFA\Customizer\HeaderBuilderItems;
use DMFA\Helpers\AssetsLoader;
use DMFA\Helpers\PrivacyPolicyContent;
use DMFA\Helpers\SiteIconAttributes;
use DMFA\Renderer\DarkModeToggler;

/**
 * Init function of the plugin
 */
function init() {
	// Construct all modules to initialize.
	$modules = [
		'customizer_configuration_loader' => new ConfigurationLoader(),
		'customizer_header_builder_items' => new HeaderBuilderItems(),
		'helpers_assets_loader'           => new AssetsLoader(),
		'helpers_privacy_policy_content'  => new PrivacyPolicyContent(),
		'helpers_site_icon_attributes'    => new SiteIconAttributes(),
		'renderer_dark_mode_toggler'      => new DarkModeToggler(),
	];

	// Initialize all modules.
	foreach ( $modules as $module ) {
		if ( is_callable( [ $module, 'init' ] ) ) {
			call_user_func( [ $module, 'init' ] );
		}
	}
}

add_action( 'plugins_loaded', 'DMFA\init' );
