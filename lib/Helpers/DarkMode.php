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
			#dark-mode-toggler > span {
				margin- <?php echo is_rtl() ? 'right' : 'left'; ?>: 5px;
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
		$color_palettes = get_option( 'astra-color-palettes' );
		$astra_settings = get_option( 'astra-settings' );

		/**
		 * Filters for the color palette to be used for the dark mode.
		 *
		 * @param string $color_palette The option's index key of the color palette to be used. Default: 'palette_2'.
		 */
		$light_mode_color_palette = apply_filters( 'dmfa_color_palett', 'palette_1' );
		$dark_mode_color_palette  = apply_filters( 'dmfa_color_palett', 'palette_2' );

		$light_color_text              = $this->get_color_from_settings(
			$color_palettes['palettes'][ $light_mode_color_palette ],
			$astra_settings['button-color']
		);
		$light_color_background        = $this->get_color_from_settings(
			$color_palettes['palettes'][ $light_mode_color_palette ],
			$astra_settings['button-bg-color']
		);
		$light_color_border            = $this->get_color_from_settings(
			$color_palettes['palettes'][ $light_mode_color_palette ],
			$astra_settings['theme-button-border-group-border-color']
		);
		$light_color_text_active       = $this->get_color_from_settings(
			$color_palettes['palettes'][ $light_mode_color_palette ],
			$astra_settings['button-h-color']
		);
		$light_color_background_active = $this->get_color_from_settings(
			$color_palettes['palettes'][ $light_mode_color_palette ],
			$astra_settings['button-bg-h-color']
		);
		$light_color_border_active     = $this->get_color_from_settings(
			$color_palettes['palettes'][ $light_mode_color_palette ],
			$astra_settings['theme-button-border-group-border-h-color']
		);

		$dark_color_text              = $this->get_color_from_settings(
			$color_palettes['palettes'][ $dark_mode_color_palette ],
			$astra_settings['button-color']
		);
		$dark_color_background        = $this->get_color_from_settings(
			$color_palettes['palettes'][ $dark_mode_color_palette ],
			$astra_settings['button-bg-color']
		);
		$dark_color_border            = $this->get_color_from_settings(
			$color_palettes['palettes'][ $dark_mode_color_palette ],
			$astra_settings['theme-button-border-group-border-color']
		);
		$dark_color_text_active       = $this->get_color_from_settings(
			$color_palettes['palettes'][ $dark_mode_color_palette ],
			$astra_settings['button-h-color']
		);
		$dark_color_background_active = $this->get_color_from_settings(
			$color_palettes['palettes'][ $dark_mode_color_palette ],
			$astra_settings['button-bg-h-color']
		);
		$dark_color_border_active     = $this->get_color_from_settings(
			$color_palettes['palettes'][ $dark_mode_color_palette ],
			$astra_settings['theme-button-border-group-border-h-color']
		);
		?>
		<style>
			html:not(.is-dark-theme) {
				--ast-dark-mode--button-text-color: <?php echo esc_attr( $light_color_text ); ?>;
				--ast-dark-mode--button-border-color: <?php echo esc_attr( $light_color_background ); ?>;
				--ast-dark-mode--button-background-color: <?php echo esc_attr( $light_color_border ); ?>;
				--ast-dark-mode--button-text-color-active: <?php echo esc_attr( $light_color_text_active ); ?>;
				--ast-dark-mode--button-border-color-active: <?php echo esc_attr( $light_color_background_active ); ?>;
				--ast-dark-mode--button-background-color-active: <?php echo esc_attr( $light_color_border_active ); ?>;
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
			?>
			--ast-dark-mode--button-text-color: <?php echo esc_attr( $dark_color_text ); ?>;
				--ast-dark-mode--button-border-color: <?php echo esc_attr( $dark_color_background ); ?>;
				--ast-dark-mode--button-background-color: <?php echo esc_attr( $dark_color_border ); ?>;
				--ast-dark-mode--button-text-color-active: <?php echo esc_attr( $dark_color_text_active ); ?>;
				--ast-dark-mode--button-border-color-active: <?php echo esc_attr( $dark_color_background_active ); ?>;
				--ast-dark-mode--button-background-color-active: <?php echo esc_attr( $dark_color_border_active ); ?>;
			}
		</style>
		<?php
	}

	/**
	 * Find a setting's value in the color palette.
	 *
	 * @param array  $color_palette The color palette array.
	 * @param string $setting The setting to find in the color palette.
	 *
	 * @return mixed
	 */
	public function get_color_from_settings( $color_palette, $setting ) {
		// Check, if the color is using a global variable.
		preg_match( '/var\(--ast-global-color-(\d+)\)/', $setting, $matches );
		if ( isset( $matches[1] ) ) {
			return $color_palette[ $matches[1] ];
		} else {
			return $setting;
		}
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
