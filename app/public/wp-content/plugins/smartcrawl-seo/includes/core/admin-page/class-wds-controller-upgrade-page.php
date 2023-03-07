<?php
/**
 * Class Smartcrawl_Controller_Upgrade_Page
 *
 * @package SmartCrawl
 */

class Smartcrawl_Controller_Upgrade_Page extends Smartcrawl_Admin_Page {

	use Smartcrawl_Singleton;

	const MENU_SLUG = 'wds_upgrade';

	protected function init() {
		parent::init();

		add_action( 'admin_menu', array( $this, 'add_page' ), 20 );
	}

	public function add_page() {
		$submenu_page = add_submenu_page(
			'wds_wizard',
			esc_html__( 'SmartCrawl Pro', 'smartcrawl-seo' ),
			esc_html__( 'SmartCrawl Pro', 'smartcrawl-seo' ),
			'manage_options',
			self::MENU_SLUG,
			array( $this, 'upgrade_page' )
		);

		add_action( "admin_print_styles-{$submenu_page}", array( $this, 'admin_styles' ) );
	}

	public function admin_styles() {
		wp_enqueue_style( Smartcrawl_Controller_Assets::APP_CSS );
	}

	public function upgrade_page() {
		wp_enqueue_script( Smartcrawl_Controller_Assets::ADMIN_JS );

		Smartcrawl_Simple_Renderer::render( 'upgrade-page' );
	}

	public function get_menu_slug() {
		return self::MENU_SLUG;
	}
}
