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
class SiteIcon {
	/**
	 * Registers all block assets so that they can be enqueued through Gutenberg in the corresponding context.
	 *
	 * @see https://wordpress.org/gutenberg/handbook/blocks/writing-your-first-block-type/#enqueuing-block-scripts
	 */
	public function init() {
		add_filter( 'astra_customizer_configurations', [ $this, 'configuration' ] );
		add_filter( 'astra_replace_header_attr', [ $this, 'dark_mode_image_attribute' ] );
	}

	/**
	 * Add new Customizer configurations for the dark mode alternative image.
	 *
	 * @param array $configurations The Customizer configurations.
	 *
	 * @return array
	 */
	public function configuration( $configurations ) {
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

	/**
	 * Add a data attribute for the dark mode src to the site icon image.
	 *
	 * @param array $attr The image tag attributes.
	 *
	 * @return array
	 */
	public function dark_mode_image_attribute( $attr ) {
		$diff_dark_mode_logo = astra_get_option( 'dmfa-different-dark-mode-logo' );
		if ( $diff_dark_mode_logo ) {
			$dark_mode_logo = astra_get_option( 'ast-header-dmfa-dark-mode-logo' );
			if ( apply_filters( 'dmfa_astra_main_header_dark_mode', true ) && '' !== $dark_mode_logo ) {
				$attr['data-dark-mode-src'] = $dark_mode_logo;
				// Generate "srcset" and "sizes" attributes for the dark mode logo.
				$dark_mode_logo_id = attachment_url_to_postid( $dark_mode_logo );
				$image_meta        = wp_get_attachment_metadata( $dark_mode_logo_id );
				$image             = wp_get_attachment_image_src( $dark_mode_logo_id, 'ast-transparent-logo-size', false );
				if ( $image ) {
					list( $src, $width, $height ) = $image;
					if ( is_array( $image_meta ) ) {
						$size_array = array( absint( $width ), absint( $height ) );
						$srcset     = wp_calculate_image_srcset( $size_array, $src, $image_meta, $dark_mode_logo_id );
						$sizes      = wp_calculate_image_sizes( $size_array, $src, $image_meta, $dark_mode_logo_id );
						if ( $srcset && ( $sizes || ! empty( $attr['sizes'] ) ) ) {
							$attr['data-dark-mode-srcset'] = $srcset;
							$attr['data-dark-mode-sizes']  = $sizes;
						}
					}
				}
			}
		}

		return $attr;
	}
}
