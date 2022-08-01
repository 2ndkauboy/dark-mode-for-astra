<?php
/**
 * Class to register client-side assets (scripts and stylesheets) for the Gutenberg block.
 *
 * @package DMFA\Helpers
 */

namespace DMFA\Helpers;

/**
 * Class AssetsLoader
 */
class AssetsLoader {
	/**
	 * Registers all block assets so that they can be enqueued through Gutenberg in the corresponding context.
	 *
	 * @see https://wordpress.org/gutenberg/handbook/blocks/writing-your-first-block-type/#enqueuing-block-scripts
	 */
	public function init() {
		add_action( 'init', [ $this, 'register_assets' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_block_editor_assets' ], 11 );
		add_action( 'wp_enqueue_scripts', [ $this, 'wp_enqueue_scripts' ], 11 );
		add_action( 'wp_footer', [ $this, 'include_svg_icons' ], 9999 );
		add_action( 'admin_footer', [ $this, 'include_svg_icons' ], 9999 );
	}

	/**
	 * Register the assets for all blocks.
	 */
	public function register_assets() {
		$frontend_assets_path  = 'build/frontend.asset.php';
		$frontend_scripts_path = 'build/frontend.js';
		if ( is_rtl() ) {
			$frontend_style_path = 'build/frontend-rtl.css';
		} else {
			$frontend_style_path = 'build/frontend.css';
		}

		if ( file_exists( DMFA_PATH . $frontend_assets_path ) ) {
			$frontend_asset = require DMFA_PATH . $frontend_assets_path;
		} else {
			$frontend_asset = [
				'dependencies' => [],
				'version'      => DMFA_VERSION,
			];
		}

		// Register optional frontend only JS file.
		if ( file_exists( DMFA_PATH . $frontend_scripts_path ) ) {
			wp_register_script(
				'dmfa-frontend',
				DMFA_URL . $frontend_scripts_path,
				$frontend_asset['dependencies'],
				$frontend_asset['version'],
				true
			);
		}

		// Register optional frontend only styles.
		if ( file_exists( DMFA_PATH . $frontend_style_path ) ) {
			wp_register_style(
				'dmfa-frontend',
				DMFA_URL . $frontend_style_path,
				[],
				$frontend_asset['version']
			);
		}

		wp_set_script_translations( 'dmfa-editor', 'dmfa', plugin_dir_path( DMFA_FILE ) . 'languages' );
	}

	/**
	 * Enqueue the block editor assets.
	 */
	public function enqueue_block_editor_assets() {
		wp_enqueue_script( 'dmfa-editor' );
		wp_enqueue_style( 'dmfa-editor' );
	}

	/**
	 * Enqueue the frontend assets.
	 */
	public function wp_enqueue_scripts() {
		wp_enqueue_script( 'dmfa-frontend' );
		wp_enqueue_style( 'dmfa-frontend' );
	}

	/**
	 * Add SVG definitions to footer.
	 */
	public function include_svg_icons() {
		$svg_icons = DMFA_PATH . 'build/images/icons/sprite.svg';

		if ( file_exists( $svg_icons ) ) {
			echo '<div style="position: absolute; width: 0; height: 0; overflow: hidden;">';
			require_once $svg_icons;
			echo '</div>';
		}
	}
}
