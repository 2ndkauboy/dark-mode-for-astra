<?php
/**
 * Dark Mode for Astra
 *
 * @package 2ndkauboy/dark-mode-for-astra
 * @author  Bernhard Kau
 * @license GPLv3
 *
 * @wordpress-plugin
 * Plugin Name: Dark Mode for Astra
 * Plugin URI: https://github.com/2ndkauboy/dark-mode-for-astra
 * Description: A plugin to add a dark mode toggle to the frontend using one of the color schemes defined in the customizer.
 * Version: 1.0.0-alpha-9
 * Author: Bernhard Kau
 * Author URI: https://kau-boys.de
 * Text Domain: dark-mode-for-astra
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * GitHub Plugin URI: https://github.com/2ndkauboy/dark-mode-for-astra
 */

define( 'DMFA_VERSION', '1.0.0-alpha-9' );
define( 'DMFA_FILE', __FILE__ );
define( 'DMFA_PATH', plugin_dir_path( DMFA_FILE ) );
define( 'DMFA_URL', plugin_dir_url( DMFA_FILE ) );

// The pre_init functions check the compatibility of the plugin and calls the init function, if check were successful.
dmfa_pre_init();

/**
 * Pre init function to check the plugin's compatibility.
 */
function dmfa_pre_init() {
	// Load the translation, as they might be needed in pre_init.
	add_action( 'plugins_loaded', 'dmfa_load_textdomain' );

	// Check, if the min. required PHP version is available and if not, show an admin notice.
	if ( version_compare( PHP_VERSION, '5.6', '<' ) ) {
		add_action( 'admin_notices', 'dmfa_min_php_version_error' );

		// Stop the further processing of the plugin.
		return;
	}

	// Check, if Astra is not active.
	if ( 'astra' !== get_template() ) {
		add_action( 'admin_notices', 'dmfa_astra_missing' );

		// Stop the further processing of the plugin.
		return;
	}

	if ( file_exists( DMFA_PATH . 'composer.json' ) && ! file_exists( DMFA_PATH . 'vendor/autoload.php' ) ) {
		add_action( 'admin_notices', 'dmfa_autoloader_missing' );

		// Stop the further processing of the plugin.
		return;
	} else {
		$autoloader = DMFA_PATH . 'vendor/autoload.php';

		if ( is_readable( $autoloader ) ) {
			include $autoloader;
		}
	}

	// If all checks were successful, load the plugin.
	require_once DMFA_PATH . 'lib/load.php';
}

/**
 * Load plugin textdomain.
 */
function dmfa_load_textdomain() {
	load_plugin_textdomain( 'dark-mode-for-astra', false, basename( __DIR__ ) . '/languages' );
}

/**
 * Show an admin notice error message, if the PHP version is too low.
 */
function dmfa_min_php_version_error() {
	echo '<div class="error"><p>';
	esc_html_e( 'Dark Mode for Astra requires PHP version 5.6 or higher to function properly. Please upgrade PHP or deactivate Dark Mode for Astra.', 'dark-mode-for-astra' );
	echo '</p></div>';
}

/**
 * Show an admin notice error message, if the `vendor` folder is missing.
 */
function dmfa_autoloader_missing() {
	echo '<div class="error"><p>';
	esc_html_e( 'Dark Mode for Astra is missing the Composer autoloader file. Please run `composer install --no-dev -o` in the root folder of the plugin or use a release version including the `vendor` folder.', 'dark-mode-for-astra' );
	echo '</p></div>';
}

/**
 * Show an admin notice error message, if Astra is not active.
 */
function dmfa_astra_missing() {
	echo '<div class="error"><p>';
	printf(
		wp_kses(
		/* translators: %s - Astra theme install URL. */
			__( 'Dark Mode for Astra requires <strong>Astra</strong> to be your active theme. <a href="%s">Install and activate now.</a>', 'dark-mode-for-astra' ),
			array(
				'a'      => array(
					'href'   => array(),
					'target' => array(),
					'rel'    => array(),
				),
				'strong' => array(),
			)
		),
		esc_url( self_admin_url( 'theme-install.php?theme=astra' ) )
	);
	echo '</p></div>';
}
