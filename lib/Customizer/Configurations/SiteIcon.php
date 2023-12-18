<?php

namespace DMFA\Customizer\Configurations;

use Astra_Builder_Helper;
use Astra_Customizer_Config_Base;
use WP_Customize_Manager;

/**
 * Class SiteIcon
 */
class SiteIcon extends Astra_Customizer_Config_Base {
	/**
	 * Add new Customizer configurations for the dark mode alternative image.
	 *
	 * @param array $configurations Astra Customizer Configurations.
	 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
	 *
	 * @return array
	 */
	public function register_configuration( $configurations, $wp_customize ) {
		$_section = 'title_tagline';
		$_configs = [
			[
				'name'      => ASTRA_THEME_SETTINGS . '[dmfa-different-dark-mode-logo]',
				'type'      => 'control',
				'control'   => 'ast-toggle-control',
				'section'   => $_section,
				'title'     => __( 'Different Logo For Dark Mode?', 'dark-mode-for-astra' ),
				'default'   => astra_get_option( 'dmfa-different-dark-mode-logo' ),
				'priority'  => 4,
				'transport' => 'postMessage',
				'divider'   => [ 'ast_class' => 'ast-top-dotted-divider' ],
				'context'   => [
					[
						'setting'  => 'custom_logo',
						'operator' => '!=',
						'value'    => '',
					],
					Astra_Builder_Helper::$general_tab_config,
				],
				'partial'   => [
					'selector'            => '.site-branding',
					'container_inclusive' => false,
					'render_callback'     => 'Astra_Builder_Header::site_identity',
				],
			],

			/**
			 * Option: Dark Mode logo selector
			 */
			[
				'name'              => ASTRA_THEME_SETTINGS . '[ast-header-dmfa-dark-mode-logo]',
				'default'           => astra_get_option( 'ast-header-dmfa-dark-mode-logo' ),
				'type'              => 'control',
				'control'           => 'image',
				'sanitize_callback' => 'esc_url_raw',
				'section'           => 'title_tagline',
				'context'           => [
					[
						'setting'  => ASTRA_THEME_SETTINGS . '[dmfa-different-dark-mode-logo]',
						'operator' => '!=',
						'value'    => 0,
					],
					Astra_Builder_Helper::$general_tab_config,
				],
				'priority'          => 4.5,
				'title'             => __( 'Darf Mode Logo', 'dark-mode-for-astra' ),
				'library_filter'    => [ 'gif', 'jpg', 'jpeg', 'png', 'ico' ],
				'transport'         => 'postMessage',
				'partial'           => [
					'selector'            => '.site-branding',
					'container_inclusive' => false,
					'render_callback'     => 'Astra_Builder_Header::site_identity',
				],
			],
		];

		$configurations = array_merge( $configurations, $_configs );

		return $configurations;
	}
}
