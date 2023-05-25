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
				'onClick'      => 'astraToggleDarkMode()',
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
			#dark-mode-toggler > span {
				margin-<?php echo is_rtl() ? 'right' : 'left'; ?>: 5px;
			}
			#dark-mode-toggler > span::before {
				content: '<?php esc_attr_e( 'Off', 'dark-mode-for-astra' ); ?>';
			}
			#dark-mode-toggler[aria-pressed="true"] > span::before {
				content: '<?php esc_attr_e( 'On', 'dark-mode-for-astra' ); ?>';
			}
			<?php if ( is_admin() || wp_is_json_request() ) : ?>
				.components-editor-notices__pinned ~ .edit-post-visual-editor #dark-mode-toggler {
					z-index: 20;
				}
				.is-dark-theme.is-dark-theme #dark-mode-toggler:not(:hover):not(:focus) {
					color: var(--global--color-primary);
				}
				@media only screen and (max-width: 782px) {
					#dark-mode-toggler {
						margin-top: 32px;
					}
				}
			<?php endif; ?>
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

	/**
	 * Adds information to the privacy policy.
	 *
	 * @return void
	 */
	public function add_privacy_policy_content() {
		if ( ! function_exists( 'wp_add_privacy_policy_content' ) ) {
			return;
		}
		$content = '<p class="privacy-policy-tutorial">' . __( 'Dark Mode for Astra uses LocalStorage when Dark Mode support is enabled.', 'dark-mode-for-astra' ) . '</p>'
				   . '<strong class="privacy-policy-tutorial">' . __( 'Suggested text:', 'dark-mode-for-astra' ) . '</strong> '
				   . __( 'This website uses LocalStorage to save the setting when Dark Mode support is turned on or off.<br> LocalStorage is necessary for the setting to work and is only used when a user clicks on the Dark Mode button.<br> No data is saved in the database or transferred.', 'dark-mode-for-astra' );
		wp_add_privacy_policy_content( __( 'Dark Mode for Astra', 'dark-mode-for-astra' ), wp_kses_post( wpautop( $content, false ) ) );
	}
}
