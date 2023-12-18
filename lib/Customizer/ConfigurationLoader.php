<?php

namespace DMFA\Customizer;

use DMFA\Customizer\Configurations\DarkMode;
use DMFA\Customizer\Configurations\SiteIcon;

/**
 * Class SiteIcon
 */
class ConfigurationLoader {
	/**
	 * Registers all block assets so that they can be enqueued through Gutenberg in the corresponding context.
	 *
	 * @see https://wordpress.org/gutenberg/handbook/blocks/writing-your-first-block-type/#enqueuing-block-scripts
	 */
	public function init() {
		add_action( 'customize_register', [ $this, 'load_configurations' ], 3 );
	}

	public function load_configurations() {
		new DarkMode();
		new SiteIcon();
	}
}
