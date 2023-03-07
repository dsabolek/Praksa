<?php
/**
 * Welcome modal functionality.
 *
 * @package Smartcrawl
 */

/**
 * Class Smartcrawl_Controller_Welcome
 */
class Smartcrawl_Controller_Welcome extends Smartcrawl_Base_Controller {

	use Smartcrawl_Singleton;

	const WELCOME_MODAL_DISMISSED_OPTION = 'wds-welcome-modal-dismissed';

	/**
	 * Initialize the modal.
	 *
	 * @return void
	 */
	protected function init() {
		add_action( 'wp_ajax_wds-close-welcome-modal', array( $this, 'close_modal' ) );
		add_action( 'wds-dshboard-after_settings', array( $this, 'show_modal' ) );
	}

	/**
	 * Close the welcome modal and set the flag.
	 *
	 * @since 3.4.0
	 *
	 * @return void
	 */
	public function close_modal() {
		$request_data = $this->get_request_data();
		if ( empty( $request_data ) ) {
			wp_send_json_error();
		}

		// Set flag for dismissal.
		Smartcrawl_Settings::update_specific_options(
			self::WELCOME_MODAL_DISMISSED_OPTION,
			SMARTCRAWL_VERSION
		);

		wp_send_json_success();
	}

	/**
	 * Show welcome modal when required.
	 *
	 * @since 3.4.0 Renamed method.
	 *
	 * @return void
	 */
	public function show_modal() {
		$dismissed_version = Smartcrawl_Settings::get_specific_options( self::WELCOME_MODAL_DISMISSED_OPTION, '1.0.0' );
		$not_dismissed     = version_compare( $dismissed_version, SMARTCRAWL_VERSION, '<' );
		$onboarding_done   = Smartcrawl_Settings::get_specific_options( Smartcrawl_Controller_Onboard::ONBOARDING_DONE_OPTION );
		$is_fresh_install  = ! Smartcrawl_Loader::get_last_version();
		if ( $onboarding_done && $not_dismissed && ! $is_fresh_install ) {
			Smartcrawl_Simple_Renderer::render( 'dashboard/dashboard-welcome-modal' );
		}
	}

	/**
	 * Get request data from ajax.
	 *
	 * @return array|mixed
	 */
	private function get_request_data() {
		return isset( $_POST['_wds_nonce'] ) && wp_verify_nonce( wp_unslash( $_POST['_wds_nonce'] ), 'wds-nonce' ) ? stripslashes_deep( $_POST ) : array(); // phpcs:ignore
	}
}
