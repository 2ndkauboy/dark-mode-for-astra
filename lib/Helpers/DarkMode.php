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
		add_action( 'admin_init', [ $this, 'add_privacy_policy_content' ] );
		add_action( 'wp_footer', [ $this, 'maybe_inject_fixed_button' ] );
		add_action( 'wp_footer', [ $this, 'astra_color_palettes' ] );
		add_shortcode( 'dark-mode-for-astra-toggle', [ $this, 'the_html' ] );
	}

	/**
	 * Print the dark-mode switch HTML.
	 *
	 * Inspired from https://codepen.io/aaroniker/pen/KGpXZo (MIT-licensed)
	 *
	 * @param array $attrs The attributes to add to our <button> element.
	 */
	public function the_html( $attrs = [] ) {
		$attrs = shortcode_atts(
			[
				'id'           => 'dark-mode-toggler',
				'class'        => '',
				'aria-pressed' => 'false',
			],
			$attrs
		);

		ob_start();

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
				<?php echo is_rtl() ? 'margin-right' : 'margin-left'; ?>: 5px;
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

		return ob_get_clean();
	}

	/**
	 * When the shortcode is not used in any sidebar, inject the button as a fixed button in the `wp_footer`.
	 *
	 * @return void
	 */
	public function maybe_inject_fixed_button() {
		if ( $this->is_using_shortcode_in_sidebar() ) {
			return;
		}

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $this->the_html( [ 'class' => 'fixed-bottom' ] );
	}

	/**
	 * A helper function that tries to find the shortcode in all widgets in all sidebars.
	 *
	 * @return bool
	 */
	private function is_using_shortcode_in_sidebar() {
		// Get the sidebar widgets.
		$sidebar_widgets = wp_get_sidebars_widgets();

		// Loop through all sidebars.
		foreach ( $sidebar_widgets as $sidebar_widget ) {
			// Loop through each widget in the current sidebar.
			foreach ( $sidebar_widget as $widget_id ) {
				// Get the widget instance.
				$widget_base_pair = wp_parse_widget_id( $widget_id );
				$widget_instance  = get_option( 'widget_' . $widget_base_pair['id_base'] );

				// Check if the widget instance exists.
				if ( false !== $widget_instance ) {
					// Access the content property of the widget instance.
					$widget_content = isset( $widget_instance['_multiwidget'] ) ? $widget_instance[ $widget_base_pair['number'] ]['content'] : '';

					// Check if the shortcode is used in the widget text.
					if ( has_shortcode( $widget_content, 'dark-mode-for-astra-toggle' ) ) {
						return true;
					}
				}
			}
		}

		return false;
	}

	/**
	 * Print the CSS for the Astra color palette to be used for the dark mode.
	 */
	public function astra_color_palettes() {
		$color_palettes           = get_option( 'astra-color-palettes' );
		$light_mode_color_palette = $color_palettes['currentPalette'];
		$dark_mode_color_palette  = astra_get_option( 'dmfa-dark-mode-color-scheme', 'palette_2' );

		$light_button_colors = [
			'button-text-color'              => astra_get_option(
				'dmfa-light-button-color',
				$color_palettes['palettes'][ $dark_mode_color_palette ][4]
			),
			'button-text-color-active'       => astra_get_option(
				'dmfa-light-button-h-color',
				$color_palettes['palettes'][ $dark_mode_color_palette ][4]
			),
			'button-background-color'        => astra_get_option(
				'dmfa-light-button-bg-color',
				$color_palettes['palettes'][ $dark_mode_color_palette ][0]
			),
			'button-background-color-active' => astra_get_option(
				'dmfa-light-button-bg-h-color',
				$color_palettes['palettes'][ $dark_mode_color_palette ][1]
			),
			'button-border-color'            => astra_get_option(
				'dmfa-light-color-button-border-group-border-color',
				$color_palettes['palettes'][ $dark_mode_color_palette ][0]
			),
			'button-border-color-active'     => astra_get_option(
				'dmfa-light-color-button-border-group-border-h-color',
				$color_palettes['palettes'][ $dark_mode_color_palette ][1]
			),
		];
		$dark_button_colors  = [
			'button-text-color'              => astra_get_option(
				'dmfa-dark-button-color',
				$color_palettes['palettes'][ $light_mode_color_palette ][4]
			),
			'button-text-color-active'       => astra_get_option(
				'dmfa-dark-button-h-color',
				$color_palettes['palettes'][ $light_mode_color_palette ][4]
			),
			'button-background-color'        => astra_get_option(
				'dmfa-dark-button-bg-color',
				$color_palettes['palettes'][ $light_mode_color_palette ][0]
			),
			'button-background-color-active' => astra_get_option(
				'dmfa-dark-button-bg-h-color',
				$color_palettes['palettes'][ $light_mode_color_palette ][1]
			),
			'button-border-color'            => astra_get_option(
				'dmfa-dark-color-button-border-group-border-color',
				$color_palettes['palettes'][ $light_mode_color_palette ][0]
			),
			'button-border-color-active'     => astra_get_option(
				'dmfa-dark-color-button-border-group-border-h-color',
				$color_palettes['palettes'][ $light_mode_color_palette ][1]
			),
		];
		?>
		<style>
			html:not(.is-dark-theme) {
			<?php
			foreach ( $light_button_colors as $key => $value ) {
				printf(
					'--ast-dark-mode--%s: %s;',
					esc_attr( $key ),
					esc_attr( $value )
				);
			}
			?>
			}

			html.is-dark-theme {
			<?php
			foreach ( $color_palettes['palettes'][ $dark_mode_color_palette ] as $key => $value ) {
				printf(
					'--ast-global-color-%d: %s;',
					(int) $key,
					esc_attr( $value )
				);
			}
			foreach ( $dark_button_colors as $key => $value ) {
				printf(
					'--ast-dark-mode--%s: %s;',
					esc_attr( $key ),
					esc_attr( $value )
				);
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
		$content = sprintf(
			'<p class="privacy-policy-tutorial">%1$s</p><strong class="privacy-policy-tutorial">%2$s</strong> %3$s',
			__( 'Dark Mode for Astra uses LocalStorage when Dark Mode support is enabled.', 'dark-mode-for-astra' ),
			__( 'Suggested text:', 'dark-mode-for-astra' ),
			__(
				'This website uses LocalStorage to save the setting when Dark Mode support is turned on or off.<br> LocalStorage is necessary for the setting to work and is only used when a user clicks on the Dark Mode button.<br> No data is saved in the database or transferred.',
				'dark-mode-for-astra'
			)
		);
		wp_add_privacy_policy_content( __( 'Dark Mode for Astra', 'dark-mode-for-astra' ), wp_kses_post( wpautop( $content, false ) ) );
	}
}
