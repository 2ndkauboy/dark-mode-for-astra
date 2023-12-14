<?php
/**
 * Class to register client-side assets (scripts and stylesheets) for the Gutenberg block.
 *
 * @package DMFA\Helpers
 */

namespace DMFA\Customizer\Configurations;

use Astra_Builder_Helper;

/**
 * Class SiteIcon
 */
class DarkMode {
	/**
	 * Registers all block assets so that they can be enqueued through Gutenberg in the corresponding context.
	 *
	 * @see https://wordpress.org/gutenberg/handbook/blocks/writing-your-first-block-type/#enqueuing-block-scripts
	 */
	public function init() {
		add_filter( 'astra_customizer_configurations', [ $this, 'configuration' ] );
	}

	/**
	 * Add new Customizer configurations for the dark mode alternative image.
	 *
	 * @param array $configurations The Customizer configurations.
	 *
	 * @return array
	 */
	public function configuration( $configurations ) {
		$color_palettes = [
			'palette_1' => __( 'Style 1', 'dark-mode-for-astra' ),
			'palette_2' => __( 'Style 2', 'dark-mode-for-astra' ),
			'palette_3' => __( 'Style 3', 'dark-mode-for-astra' ),
		];
		$_configs       = [
			[
				'name'     => 'panel-dark-mode-for-astra',
				'type'     => 'panel',
				'priority' => 70,
				'title'    => __( 'Dark Mode', 'dark-mode-for-astra' ),
			],
			[
				'name'     => 'section-dark-mode-for-astra',
				'type'     => 'section',
				'priority' => 5,
				'title'    => __( 'Color Palettes', 'dark-mode-for-astra' ),
				'panel'    => 'panel-dark-mode-for-astra',
			],

			/**
			 * Color Scheme
			 */
			[
				'name'       => ASTRA_THEME_SETTINGS . '[dmfa-dark-mode-color-scheme]',
				'default'    => astra_get_option( 'dmfa-dark-mode-color-scheme', 'none' ),
				'section'    => 'section-dark-mode-for-astra',
				'title'      => __( 'Dark Mode Colors', 'dark-mode-for-astra' ),
				'type'       => 'control',
				'control'    => 'ast-select',
				'priority'   => 5,
				'choices'    => $color_palettes,
				'partial'    => [
					'selector'            => '.ast-dfma-dark-mode-color-scheme-wrapper .ast-dfma-dark-mode-color-scheme .trail-items',
					'container_inclusive' => false,
				],
				'context'    => Astra_Builder_Helper::$general_tab,
				'responsive' => false,
				'renderAs'   => 'text',
				'divider'    => [ 'ast_class' => 'ast-section-spacing' ],
			],
		];

		$configurations = array_merge( $configurations, $_configs );

		return $configurations;
	}
}
