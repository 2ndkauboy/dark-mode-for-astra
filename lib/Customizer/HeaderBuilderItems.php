<?php

namespace DMFA\Customizer;

/**
 * Class SiteIcon
 */
class HeaderBuilderItems {
	/**
	 * Initialize the class.
	 * @return void
	 */
	public function init() {
		add_filter( 'astra_header_desktop_items', [ $this, 'header_items' ] );
		add_filter( 'astra_header_mobile_items', [ $this, 'header_items' ] );
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
}
