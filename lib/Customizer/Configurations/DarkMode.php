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
		add_filter( 'astra_header_desktop_items', [ $this, 'header_items' ] );
		add_filter( 'astra_header_mobile_items', [ $this, 'header_items' ] );
		add_action( 'astra_render_header_components', [ $this, 'render_header_components' ] );
	}

	/**
	 * Add new Customizer configurations for the dark mode alternative image.
	 *
	 * @param array $configurations The Customizer configurations.
	 *
	 * @return array
	 */
	public function configuration( $configurations ) {
		$_section       = 'section-dark-mode-for-astra';
		$color_palettes = [
			'palette_1' => __( 'Style 1', 'dark-mode-for-astra' ),
			'palette_2' => __( 'Style 2', 'dark-mode-for-astra' ),
			'palette_3' => __( 'Style 3', 'dark-mode-for-astra' ),
		];
		$_configs       = [
			/**
			 * Header Builder section
			 */
			[
				'name'     => $_section,
				'type'     => 'section',
				'priority' => 80,
				'title'    => __( 'Dark Mode', 'dark-mode-for-astra' ),
				'panel'    => 'panel-header-builder-group',
			],

			/**
			 * Option: Header Builder Tabs
			 */
			[
				'name'        => ASTRA_THEME_SETTINGS . '[dmfa-header-dark-mode-tabs]',
				'section'     => $_section,
				'type'        => 'control',
				'control'     => 'ast-builder-header-control',
				'priority'    => 0,
				'description' => '',
				'divider'     => [ 'ast_class' => 'ast-bottom-spacing' ],
			],

			/**
			 * Color Scheme
			 */
			[
				'name'       => ASTRA_THEME_SETTINGS . '[dmfa-dark-mode-color-scheme]',
				'default'    => astra_get_option( 'dmfa-dark-mode-color-scheme', 'none' ),
				'section'    => $_section,
				'title'      => __( 'Dark Mode Colors', 'dark-mode-for-astra' ),
				'type'       => 'control',
				'control'    => 'ast-select',
				'priority'   => 5,
				'choices'    => $color_palettes,
				'partial'    => [
					'selector'        => '.ast-dfma-dark-mode-color-scheme-wrapper .ast-dfma-dark-mode-color-scheme .trail-items',
					'render_callback' => [ 'DarkMode', 'render_button' ],
				],
				'context'    => Astra_Builder_Helper::$general_tab,
				'responsive' => false,
				'renderAs'   => 'text',
				'divider'    => [ 'ast_class' => 'ast-section-spacing' ],
			],

			/**
			 * Fixed Bottom
			 */
			[
				'name'     => ASTRA_THEME_SETTINGS . '[dmfa-fixed-to-bottom]',
				'default'  => astra_get_option( 'dmfa-fixed-to-bottom' ),
				'type'     => 'control',
				'section'  => $_section,
				'title'    => __( 'Make button sticky to the bottom', 'dark-mode-for-astra' ),
				'priority' => 10,
				'control'  => 'ast-toggle-control',
				'divider'  => [ 'ast_class' => 'ast-section-spacing' ],
			],

			/**
			 * Option: Light Mode Color Heading
			 */
			[
				'name'        => ASTRA_THEME_SETTINGS . '[dmfa-light-color-color-divider-divider]',
				'type'        => 'control',
				'control'     => 'ast-heading',
				'section'     => $_section,
				'context'     => Astra_Builder_Helper::$design_tab,
				'title'       => __( 'Light Mode Button Colors', 'dark-mode-for-astra' ),
				'priority'    => 15,
				'settings'    => [],
				'divider'     => [ 'ast_class' => 'ast-section-spacing' ],
				'input_attrs' => [
					'class' => 'ast-control-reduce-top-space',
				],
			],

			/**
			 * Group: Light Mode Button Color Group
			 */
			[
				'name'      => ASTRA_THEME_SETTINGS . '[dmfa-light-color-button-color-group]',
				'default'   => astra_get_option( 'dmfa-light-color-button-color-group' ),
				'type'      => 'control',
				'control'   => 'ast-color-group',
				'title'     => __( 'Text Color', 'dark-mode-for-astra' ),
				'section'   => $_section,
				'context'   => Astra_Builder_Helper::$design_tab,
				'transport' => 'postMessage',
				'priority'  => 20,
			],
			/**
			 * Option: Button Color
			 */
			[
				'name'              => 'dmfa-light-button-color',
				'parent'            => ASTRA_THEME_SETTINGS . '[dmfa-light-color-button-color-group]',
				'default'           => astra_get_option( 'dmfa-light-button-color' ),
				'transport'         => 'postMessage',
				'type'              => 'sub-control',
				'section'           => $_section,
				'context'           => Astra_Builder_Helper::$design_tab,
				'control'           => 'ast-color',
				'sanitize_callback' => [ 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ],
				'title'             => __( 'Normal', 'dark-mode-for-astra' ),
				'priority'          => 21,
			],
			/**
			 * Option: Button Hover Color
			 */
			[
				'name'              => 'dmfa-light-button-h-color',
				'parent'            => ASTRA_THEME_SETTINGS . '[dmfa-light-color-button-color-group]',
				'default'           => astra_get_option( 'dmfa-light-button-h-color' ),
				'transport'         => 'postMessage',
				'type'              => 'sub-control',
				'section'           => $_section,
				'context'           => Astra_Builder_Helper::$design_tab,
				'control'           => 'ast-color',
				'sanitize_callback' => [ 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ],
				'title'             => __( 'Hover', 'dark-mode-for-astra' ),
				'priority'          => 21,
			],

			/**
			 * Group: Light Mode Button Background Colors Group
			 */
			[
				'name'      => ASTRA_THEME_SETTINGS . '[dmfa-light-color-button-bg-color-group]',
				'default'   => astra_get_option( 'dmfa-light-color-button-bg-color-group' ),
				'type'      => 'control',
				'control'   => 'ast-color-group',
				'title'     => __( 'Background Color', 'dark-mode-for-astra' ),
				'section'   => $_section,
				'context'   => Astra_Builder_Helper::$design_tab,
				'transport' => 'postMessage',
				'priority'  => 30,
			],
			/**
			 * Option: Button Background Color
			 */
			[
				'name'              => 'dmfa-light-button-bg-color',
				'parent'            => ASTRA_THEME_SETTINGS . '[dmfa-light-color-button-bg-color-group]',
				'default'           => astra_get_option( 'dmfa-light-button-bg-color' ),
				'transport'         => 'postMessage',
				'type'              => 'sub-control',
				'section'           => $_section,
				'context'           => Astra_Builder_Helper::$design_tab,
				'control'           => 'ast-color',
				'sanitize_callback' => [ 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ],
				'title'             => __( 'Normal', 'dark-mode-for-astra' ),
				'priority'          => 31,
			],
			/**
			 * Option: Button Background Hover Color
			 */
			[
				'name'              => 'dmfa-light-button-bg-h-color',
				'parent'            => ASTRA_THEME_SETTINGS . '[dmfa-light-color-button-bg-color-group]',
				'default'           => astra_get_option( 'dmfa-light-button-bg-h-color' ),
				'transport'         => 'postMessage',
				'type'              => 'sub-control',
				'section'           => $_section,
				'context'           => Astra_Builder_Helper::$design_tab,
				'control'           => 'ast-color',
				'sanitize_callback' => [ 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ],
				'title'             => __( 'Hover', 'dark-mode-for-astra' ),
				'priority'          => 31,
			],

			/**
			 * Group: Light Mode Button Border Group
			 */
			[
				'name'      => ASTRA_THEME_SETTINGS . '[dmfa-light-color-button-border-color-group]',
				'default'   => astra_get_option( 'dmfa-light-color-button-border-color-group' ),
				'type'      => 'control',
				'control'   => 'ast-color-group',
				'section'   => $_section,
				'context'   => Astra_Builder_Helper::$design_tab,
				'transport' => 'postMessage',
				'title'     => __( 'Border Color', 'dark-mode-for-astra' ),
				'priority'  => 40,
				'divider'   => [ 'ast_class' => 'ast-bottom-dotted-divider' ],
			],
			/**
			 * Option: Global Button Border Color
			 */
			[
				'name'              => 'dmfa-light-color-button-border-group-border-color',
				'parent'            => ASTRA_THEME_SETTINGS . '[dmfa-light-color-button-border-color-group]',
				'default'           => astra_get_option( 'dmfa-light-color-button-border-group-border-color' ),
				'transport'         => 'postMessage',
				'type'              => 'sub-control',
				'section'           => $_section,
				'context'           => Astra_Builder_Helper::$design_tab,
				'control'           => 'ast-color',
				'sanitize_callback' => [ 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ],
				'title'             => __( 'Normal', 'dark-mode-for-astra' ),
				'priority'          => 41,
			],
			/**
			 * Option: Global Button Border Hover Color
			 */
			[
				'name'              => 'dmfa-light-color-button-border-group-border-h-color',
				'default'           => astra_get_option( 'dmfa-light-color-button-border-group-border-h-color' ),
				'parent'            => ASTRA_THEME_SETTINGS . '[dmfa-light-color-button-border-color-group]',
				'transport'         => 'postMessage',
				'type'              => 'sub-control',
				'section'           => $_section,
				'context'           => Astra_Builder_Helper::$design_tab,
				'control'           => 'ast-color',
				'sanitize_callback' => [ 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ],
				'title'             => __( 'Hover', 'dark-mode-for-astra' ),
				'priority'          => 41,
			],

			/**
			 * Option: Dark Mode Color Heading
			 */
			[
				'name'     => ASTRA_THEME_SETTINGS . '[dmfa-dark-color-color-divider-divider]',
				'type'     => 'control',
				'control'  => 'ast-heading',
				'section'  => $_section,
				'context'  => Astra_Builder_Helper::$design_tab,
				'title'    => __( 'Dark Mode Button Colors', 'dark-mode-for-astra' ),
				'priority' => 50,
				'settings' => [],
				'divider'  => [ 'ast_class' => 'ast-section-spacing' ],
			],

			/**
			 * Group: Dark Mode Button Color Group
			 */
			[
				'name'      => ASTRA_THEME_SETTINGS . '[dmfa-dark-color-button-color-group]',
				'default'   => astra_get_option( 'dmfa-dark-color-button-color-group' ),
				'type'      => 'control',
				'control'   => 'ast-color-group',
				'title'     => __( 'Text Color', 'dark-mode-for-astra' ),
				'section'   => $_section,
				'context'   => Astra_Builder_Helper::$design_tab,
				'transport' => 'postMessage',
				'priority'  => 60,
			],
			/**
			 * Option: Button Color
			 */
			[
				'name'              => 'dmfa-dark-button-color',
				'parent'            => ASTRA_THEME_SETTINGS . '[dmfa-dark-color-button-color-group]',
				'default'           => astra_get_option( 'dmfa-dark-button-color' ),
				'transport'         => 'postMessage',
				'type'              => 'sub-control',
				'section'           => $_section,
				'context'           => Astra_Builder_Helper::$design_tab,
				'control'           => 'ast-color',
				'sanitize_callback' => [ 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ],
				'title'             => __( 'Normal', 'dark-mode-for-astra' ),
				'priority'          => 61,
			],
			/**
			 * Option: Button Hover Color
			 */
			[
				'name'              => 'dmfa-dark-button-h-color',
				'parent'            => ASTRA_THEME_SETTINGS . '[dmfa-dark-color-button-color-group]',
				'default'           => astra_get_option( 'dmfa-dark-button-h-color' ),
				'transport'         => 'postMessage',
				'type'              => 'sub-control',
				'section'           => $_section,
				'context'           => Astra_Builder_Helper::$design_tab,
				'control'           => 'ast-color',
				'sanitize_callback' => [ 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ],
				'title'             => __( 'Hover', 'dark-mode-for-astra' ),
				'priority'          => 61,
			],

			/**
			 * Group: Dark Mode Button Background Colors Group
			 */
			[
				'name'      => ASTRA_THEME_SETTINGS . '[dmfa-dark-color-button-bg-color-group]',
				'default'   => astra_get_option( 'dmfa-dark-color-button-bg-color-group' ),
				'type'      => 'control',
				'control'   => 'ast-color-group',
				'title'     => __( 'Background Color', 'dark-mode-for-astra' ),
				'section'   => $_section,
				'context'   => Astra_Builder_Helper::$design_tab,
				'transport' => 'postMessage',
				'priority'  => 70,
			],
			/**
			 * Option: Button Background Color
			 */
			[
				'name'              => 'dmfa-dark-button-bg-color',
				'parent'            => ASTRA_THEME_SETTINGS . '[dmfa-dark-color-button-bg-color-group]',
				'default'           => astra_get_option( 'dmfa-dark-button-bg-color' ),
				'transport'         => 'postMessage',
				'type'              => 'sub-control',
				'section'           => $_section,
				'context'           => Astra_Builder_Helper::$design_tab,
				'control'           => 'ast-color',
				'sanitize_callback' => [ 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ],
				'title'             => __( 'Normal', 'dark-mode-for-astra' ),
				'priority'          => 71,
			],
			/**
			 * Option: Button Background Hover Color
			 */
			[
				'name'              => 'dmfa-dark-button-bg-h-color',
				'parent'            => ASTRA_THEME_SETTINGS . '[dmfa-dark-color-button-bg-color-group]',
				'default'           => astra_get_option( 'dmfa-dark-button-bg-h-color' ),
				'transport'         => 'postMessage',
				'type'              => 'sub-control',
				'section'           => $_section,
				'context'           => Astra_Builder_Helper::$design_tab,
				'control'           => 'ast-color',
				'sanitize_callback' => [ 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ],
				'title'             => __( 'Hover', 'dark-mode-for-astra' ),
				'priority'          => 71,
			],

			/**
			 * Group: Dark Mode Button Border Group
			 */
			[
				'name'      => ASTRA_THEME_SETTINGS . '[dmfa-dark-color-button-border-color-group]',
				'default'   => astra_get_option( 'dmfa-dark-color-button-border-color-group' ),
				'type'      => 'control',
				'control'   => 'ast-color-group',
				'title'     => __( 'Border Color', 'dark-mode-for-astra' ),
				'section'   => $_section,
				'context'   => Astra_Builder_Helper::$design_tab,
				'transport' => 'postMessage',
				'priority'  => 80,
				'divider'   => [ 'ast_class' => 'ast-bottom-dotted-divider' ],
			],
			/**
			 * Option: Global Button Border Color
			 */
			[
				'name'              => 'dmfa-dark-color-button-border-group-border-color',
				'parent'            => ASTRA_THEME_SETTINGS . '[dmfa-dark-color-button-border-color-group]',
				'default'           => astra_get_option( 'dmfa-dark-color-button-border-group-border-color' ),
				'transport'         => 'postMessage',
				'type'              => 'sub-control',
				'section'           => $_section,
				'context'           => Astra_Builder_Helper::$design_tab,
				'control'           => 'ast-color',
				'sanitize_callback' => [ 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ],
				'title'             => __( 'Normal', 'dark-mode-for-astra' ),
				'priority'          => 81,
			],
			/**
			 * Option: Global Button Border Hover Color
			 */
			[
				'name'              => 'dmfa-dark-color-button-border-group-border-h-color',
				'default'           => astra_get_option( 'dmfa-dark-color-button-border-group-border-h-color' ),
				'parent'            => ASTRA_THEME_SETTINGS . '[dmfa-dark-color-button-border-color-group]',
				'transport'         => 'postMessage',
				'type'              => 'sub-control',
				'section'           => $_section,
				'context'           => Astra_Builder_Helper::$design_tab,
				'control'           => 'ast-color',
				'sanitize_callback' => [ 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ],
				'title'             => __( 'Hover', 'dark-mode-for-astra' ),
				'priority'          => 81,
			],
		];

		$configurations = array_merge( $configurations, $_configs );

		return $configurations;
	}

	/**
	 * Add the Dark Mode component to the header icons.
	 *
	 * @param array $items The current header builder items.
	 *
	 * @return array
	 */
	public function header_items( $items ) {
		$items['dark-mode'] = [
			'name'    => __( 'Dark Mode Toggle Button', 'dark-mode-for-astra' ),
			'icon'    => 'menu',
			'section' => 'section-dark-mode-for-astra',
		];

		return $items;
	}

	/**
	 * Render the component in the header builder.
	 *
	 * @param string $astra_header_component_slug The array key of the component.
	 *
	 * @return void
	 */
	public function render_header_components( $astra_header_component_slug ) {
		if ( 'dark-mode' !== $astra_header_component_slug ) {
			return;
		}
		?>
		<div class="ast-builder-layout-element site-header-focus-item ast-header-dark-mode" data-section="section-dark-mode-for-astra">
			<?php do_action( 'dmfa_render_toggle' ); ?>
		</div>
		<?php
	}
}
