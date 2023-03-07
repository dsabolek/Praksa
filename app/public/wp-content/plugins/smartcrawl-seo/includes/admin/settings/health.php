<?php
/**
 * Health settings
 *
 * @package Smartcrawl
 */

/**
 * Smartcrawl_Health_Settings
 */
class Smartcrawl_Health_Settings extends Smartcrawl_Settings_Admin {

	use Smartcrawl_Singleton;

	/**
	 * Page title.
	 *
	 * @var string
	 */
	public $page_title;

	/**
	 * Validate.
	 *
	 * @param array $input Input.
	 *
	 * @return array
	 */
	public function validate( $input ) {
		return array();
	}

	/**
	 * Initialize.
	 *
	 * @return void
	 */
	public function init() {
		$this->option_name = 'wds_health_options';
		$this->name        = Smartcrawl_Settings::COMP_HEALTH;
		$this->slug        = Smartcrawl_Settings::TAB_HEALTH;
		$this->action_url  = admin_url( 'options.php' );
		$this->page_title  = __( 'SmartCrawl Wizard: SEO Health', 'smartcrawl-seo' );

		add_action( 'wp_ajax_wds-save-health-settings', array( $this, 'save_health_settings' ) );

		parent::init();
	}

	/**
	 * Save health settings.
	 *
	 * @return void
	 */
	public function save_health_settings() {
		$data = $this->get_request_data();
		if ( empty( $data ) ) {
			wp_send_json_error();
		}

		Smartcrawl_Lighthouse_Options::save_form_data( wp_unslash( $_GET['wds_health_options'] ) ); // phpcs:ignore WordPress.Security.NonceVerification, WordPress.Security.ValidatedSanitizedInput

		wp_send_json_success();
	}

	/**
	 * Get the title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'SEO Health', 'smartcrawl-seo' );
	}

	/**
	 * Render the page content.
	 *
	 * @return void
	 */
	public function options_page() {
		wp_enqueue_script( Smartcrawl_Controller_Assets::LIGHTHOUSE_JS );

		$lighthouse = Smartcrawl_Service::get( Smartcrawl_Service::SERVICE_LIGHTHOUSE );

		$device      = empty( $_GET['device'] ) ? 'desktop' : sanitize_text_field( wp_unslash( $_GET['device'] ) ); // phpcs:ignore WordPress.Security.NonceVerification
		$last_report = get_option( Smartcrawl_Lighthouse_Service::OPTION_ID_LAST_REPORT, false );

		if ( empty( $last_report ) ) {
			$report_data = array(
				'no_data' => true,
				'image'   => sprintf( '%s/assets/images/graphic-lighthouse-disabled.svg', SMARTCRAWL_PLUGIN_URL ),
			);
		} elseif ( ! empty( $last_report['error'] ) ) {
			$report_data = array(
				'error'   => smartcrawl_get_array_value( $last_report, 'code' ),
				'message' => smartcrawl_get_array_value( $last_report, 'message' ),
			);
		} else {
			$device_report = smartcrawl_get_array_value( $last_report, array( 'data', $device ) );
			if ( ! $device_report ) {
				$report_data = array(
					'error' => 'unexpected-error',
				);
			} else {
				$report_data = smartcrawl_get_array_value( $device_report, array( 'metrics' ) );
			}
		}

		$page_on_front = get_option( 'page_on_front' );
		$show_on_front = get_option( 'show_on_front' );

		$has_static_homepage = 'posts' !== $show_on_front && $page_on_front;

		if ( ! $has_static_homepage || ! current_user_can( 'edit_page', $page_on_front ) ) {
			$homepage_url = '';
		} else {
			$homepage_url = get_edit_post_link( $page_on_front );
		}

		$posts_on_front = 'posts' === $show_on_front || 0 === (int) $page_on_front;

		if ( $posts_on_front ) {
			$home = new Smartcrawl_Blog_Home();
		} else {
			$home = new Smartcrawl_Product( $page_on_front );
		}

		$home_robots = $home->get_robots();

		$service = new Smartcrawl_Configs_Service();

		$args = array(
			'start_time' => $lighthouse->get_start_time(),
			'is_member'  => $service->is_member(),
			'report'     => $report_data,
			'nonce'      => wp_create_nonce( 'wds-lighthouse-nonce' ),
		);

		if ( ! isset( $report_data['error'] ) && ! isset( $report_data['no_data'] ) ) {
			$args = array_merge(
				$args,
				array(
					'homepage_url'             => $homepage_url,
					'timestamp'                => smartcrawl_get_array_value( $last_report, array( 'data', 'time' ) ),
					'testing_tool'             => sprintf( 'https://search.google.com/test/rich-results?url=%s&user_agent=2', urlencode( home_url() ) ), // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions
					'admin_url'                => admin_url(),
					'plugin_install_url'       => is_multisite() && is_super_admin() ?
						network_admin_url( 'plugin-install.php?s=hreflang&tab=search&type=term' ) :
						( current_user_can( 'install_plugins' ) ?
							admin_url( 'plugin-install.php?s=hreflang&tab=search&type=term' ) :
							false ),
					'is_tab_onpage_allowed'    => Smartcrawl_Settings_Admin::is_tab_allowed( Smartcrawl_Settings::TAB_ONPAGE ),
					'tab_onpage_url'           => Smartcrawl_Settings_Admin::admin_url( Smartcrawl_Settings::TAB_ONPAGE ),
					'is_tab_autolinks_allowed' => Smartcrawl_Settings_Admin::is_tab_allowed( Smartcrawl_Settings::TAB_AUTOLINKS ),
					'tab_autolinks_url'        => Smartcrawl_Settings_Admin::admin_url( Smartcrawl_Settings::TAB_AUTOLINKS ),
					'is_tab_schema_allowed'    => Smartcrawl_Settings_Admin::is_tab_allowed( Smartcrawl_Settings::TAB_SCHEMA ),
					'tab_schema_url'           => Smartcrawl_Settings_Admin::admin_url( Smartcrawl_Settings::TAB_SCHEMA ),
					'is_multisite'             => ! ! is_multisite(),
					'is_blog_public'           => ! ! get_option( 'blog_public' ),
					'is_home_no_index'         => strpos( $home_robots, 'noindex' ) !== false,
				)
			);
		}

		wp_localize_script( Smartcrawl_Controller_Assets::LIGHTHOUSE_JS, '_wds_lighthouse', $args );

		$this->render_view( 'lighthouse/lighthouse-settings' );
	}

	/**
	 * Save defaults.
	 *
	 * @return void
	 */
	public function defaults() {
		Smartcrawl_Lighthouse_Options::save_defaults();
	}

	/**
	 * Get view defaults.
	 *
	 * @return array
	 */
	protected function get_view_defaults() {
		$mode_defaults = Smartcrawl_Lighthouse_Renderer::get()->view_defaults();

		return array_merge(
			array(
				'active_tab' => $this->get_active_tab( 'tab_lighthouse' ),
			),
			$mode_defaults,
			parent::get_view_defaults()
		);
	}

	/**
	 * Get request data.
	 *
	 * @return array
	 */
	private function get_request_data() {
		return isset( $_POST['_wds_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wds_nonce'] ) ), 'wds-health-nonce' ) ? $_POST : array();
	}
}
