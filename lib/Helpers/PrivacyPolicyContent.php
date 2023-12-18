<?php
/**
 * PrivacyPolicyContent Class
 *
 * @package DMFA\Helpers
 */

namespace DMFA\Helpers;

/**
 * This class is in charge of Dark Mode.
 */
class PrivacyPolicyContent {

	/**
	 * Initialize the class and register all hooks.
	 */
	public function init() {
		add_action( 'admin_init', [ $this, 'add_privacy_policy_content' ] );
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
