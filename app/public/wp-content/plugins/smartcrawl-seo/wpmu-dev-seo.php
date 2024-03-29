<?php
/**
 * Plugin Name: SmartCrawl
 * Plugin URI: https://wpmudev.com/project/smartcrawl-wordpress-seo/
 * Description: Every SEO option that a site requires, in one easy bundle.
 * Version: 3.4.4
 * Network: true
 * Text Domain: smartcrawl-seo
 * Author: WPMU DEV
 * Author URI: https://wpmudev.com
 *
 * Copyright 2010-2011 Incsub (http://incsub.com/)
 * Author - Ulrich Sossou (Incsub)
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package Smartcrawl
 */

if ( ! class_exists( 'Smartcrawl_Loader' ) ) {
	/**
	 * Class Smartcrawl_Loader
	 */
	class Smartcrawl_Loader {

		const LAST_VERSION_OPTION_ID = 'wds_last_version';

		const VERSION_OPTION_ID = 'wds_version';

		/**
		 * Construct the Plugin object
		 */
		public function __construct() {}

		/**
		 * Init Plugin
		 */
		public function plugin_init() {
			require_once plugin_dir_path( __FILE__ ) . 'constants.php';
			require_once SMARTCRAWL_PLUGIN_DIR . 'core/trait-singleton.php';

			// Init plugin.
			require_once SMARTCRAWL_PLUGIN_DIR . 'init.php';

			add_action( 'plugins_loaded', array( $this, 'set_version_options' ) );
		}

		/**
		 * Activate the plugin
		 *
		 * @return void
		 */
		public static function activate() {
			require_once plugin_dir_path( __FILE__ ) . 'constants.php';

			// Init plugin.
			require_once SMARTCRAWL_PLUGIN_DIR . 'init.php';

			Smartcrawl_Settings_Dashboard::get()->defaults();

			Smartcrawl_Health_Settings::get()->defaults();

			Smartcrawl_Onpage_Settings::get()->defaults();

			Smartcrawl_Schema_Settings::get()->defaults();

			Smartcrawl_Social_Settings::get()->defaults();

			Smartcrawl_Sitemap_Settings::get()->defaults();

			Smartcrawl_Autolinks_Settings::get()->defaults();

			Smartcrawl_Settings_Settings::get()->defaults();

			self::save_free_installation_timestamp();
		}

		/**
		 * Save timestamp for free version.
		 *
		 * @return void
		 */
		private static function save_free_installation_timestamp() {
			$service = self::get_service();
			if ( $service->is_member() ) {
				return;
			}

			$free_install_date = get_site_option( 'wds-free-install-date' );
			if ( empty( $free_install_date ) ) {
				update_site_option( 'wds-free-install-date', current_time( 'timestamp' ) ); // phpcs:ignore
			}
		}

		/**
		 * Set plugin version details.
		 *
		 * @return void
		 */
		public function set_version_options() {
			$version = get_option( self::VERSION_OPTION_ID, false );
			if ( ! $version || version_compare( $version, SMARTCRAWL_VERSION, '!=' ) ) {
				update_option( self::LAST_VERSION_OPTION_ID, $version );
				update_option( self::VERSION_OPTION_ID, SMARTCRAWL_VERSION );

				do_action( 'wds_plugin_update', SMARTCRAWL_VERSION, $version );
			}
		}

		/**
		 * Get service instance.
		 *
		 * @return Smartcrawl_Site_Service
		 */
		private static function get_service() {
			return Smartcrawl_Service::get( Smartcrawl_Service::SERVICE_SITE );
		}

		/**
		 * Deactivate the plugin
		 *
		 * @return void
		 */
		public static function deactivate() {
		}

		/**
		 * Get the last version number.
		 *
		 * @return false|mixed|void
		 */
		public static function get_last_version() {
			return get_option( self::LAST_VERSION_OPTION_ID, false );
		}

		/**
		 * Gets the version number string
		 *
		 * @return string Version number info
		 */
		public static function get_version() {
			static $version;
			if ( empty( $version ) ) {
				$version = defined( 'SMARTCRAWL_VERSION' ) && SMARTCRAWL_VERSION ? SMARTCRAWL_VERSION : null;
			}

			return $version;
		}
	}
}

require_once 'autoloader.php';

require_once 'vendor/autoload.php';

if ( ! defined( 'SMARTCRAWL_PLUGIN_BASENAME' ) ) {
	define( 'SMARTCRAWL_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}

// Plugin Activation and Deactivation hooks.
register_activation_hook( __FILE__, array( 'Smartcrawl_Loader', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Smartcrawl_Loader', 'deactivate' ) );

if ( defined( 'SMARTCRAWL_CONDITIONAL_EXECUTION' ) && SMARTCRAWL_CONDITIONAL_EXECUTION ) {
	add_action(
		'plugins_loaded',
		array( new Smartcrawl_Loader(), 'plugin_init' )
	);
} else {
	( new Smartcrawl_Loader() )->plugin_init();
}
