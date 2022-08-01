<?php
/**
 * Dark Mode Class
 *
 * @package DMFA\Helpers
 */

namespace DMFA\Helpers;

/**
 * This class is in charge of Dark Mode.
 */
class DarkMode {

	/**
	 * Initialize the class and register all hooks.
	 */
	public function init() {
		// Add the switch on the frontend.
		add_action( 'wp_footer', [ $this, 'the_html' ] );
		add_action( 'wp_footer', [ $this, 'astra_color_palettes' ] );
	}

	/**
	 * Print the dark-mode switch HTML.
	 *
	 * Inspired from https://codepen.io/aaroniker/pen/KGpXZo (MIT-licensed)
	 *
	 * @param array $attrs The attributes to add to our <button> element.
	 */
	public function the_html( $attrs = [] ) {
		$attrs = wp_parse_args(
			$attrs,
			[
				'id'           => 'dark-mode-toggler',
				'class'        => 'fixed-bottom',
				'aria-pressed' => 'false',
			]
		);
		echo '<button';
		foreach ( $attrs as $key => $val ) {
			echo ' ' . esc_attr( $key ) . '="' . esc_attr( $val ) . '"';
		}
		echo '>';
		printf(
		/* translators: %s: On/Off */
			esc_html__( 'Dark Mode: %s', 'dark-mode-for-astra' ),
			'<span aria-hidden="true"></span>'
		);
		echo '</button>';
		?>
		<style>
			#dark-mode-toggler > span::before {
				content: '<?php esc_attr_e( 'Off', 'dark-mode-for-astra' ); ?>';
			}

			#dark-mode-toggler[aria-pressed="true"] > span::before {
				content: '<?php esc_attr_e( 'On', 'dark-mode-for-astra' ); ?>';
			}
		</style>
		<?php
	}

	/**
	 * Print the CSS for the Astra color palette to be used for the dark mode.
	 */
	public function astra_color_palettes() {
		$color_palettes = get_option( 'astra-color-palettes' );

		/**
		 * Filters for the color palett to be used for the dark mode.
		 *
		 * @param string $color_palette The option's index key of the color palette to be used. Default: 'palette_2'.
		 */
		$dark_mode_color_palette = apply_filters( 'dmfa_color_palett', 'palette_2' );
		?>
		<style>
			:root .is-dark-theme {
			<?php
			foreach ( $color_palettes['palettes'][ $dark_mode_color_palette ] as $key => $value ) {
				echo "--ast-global-color-$key: $value;"; // phpcs:ignore
			}
			?>
			}
		</style>
		<?php
	}
}
