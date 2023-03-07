<?php
/**
 * Deprecated site-wide settings class.
 *
 * @package    Smartcrawl
 */

/**
 * Class Smartcrawl_Sitewide_Deprecation_Controller
 *
 * @deprecated 3.4.0
 */
class Smartcrawl_Sitewide_Deprecation_Controller extends Smartcrawl_Base_Controller {

	use Smartcrawl_Singleton;

	const SITEWIDE_DEPRECATION_TIMESTAMP = 'wds_sitewide_deprecation_timestamp';

	/**
	 * Check if class needs to be run.
	 *
	 * @since 2.13
	 *
	 * @return bool
	 */
	public function should_run() {
		return is_multisite() && $this->is_network_sitewide();
	}

	/**
	 * Initialize the class.
	 *
	 * @since 2.13
	 *
	 * @return void
	 */
	protected function init() {
		add_action( 'wds_plugin_update', array( $this, 'remove_deprecated_option' ) );
	}

	/**
	 * Delete old deprecated option used for deprecation.
	 *
	 * @since 2.13
	 *
	 * @return void
	 */
	public function remove_deprecated_option() {
		delete_site_option( self::SITEWIDE_DEPRECATION_TIMESTAMP );
	}

	/**
	 * Check if SmartCrawl is network-wide active.
	 *
	 * @since 2.13
	 *
	 * @return false|mixed
	 */
	private function is_network_sitewide() {
		return get_site_option( 'wds_sitewide_mode', true );
	}
}
