<?php

namespace DMFA\Helpers;
class SiteIconAttributes {

	/**
	 * Initialize the class.
	 *
	 * @return void
	 */
	public function init() {
		add_filter( 'astra_replace_header_attr', [ $this, 'dark_mode_image_attribute' ] );
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
						$size_array = [ absint( $width ), absint( $height ) ];
						$srcset     = wp_calculate_image_srcset( $size_array, $src, $image_meta, $dark_mode_logo_id );
						$sizes      = wp_calculate_image_sizes( $size_array, $src, $image_meta, $dark_mode_logo_id );
						if ( $srcset && $sizes ) {
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
