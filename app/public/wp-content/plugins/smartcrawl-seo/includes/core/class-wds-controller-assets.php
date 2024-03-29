<?php
/**
 * Class to control assets.
 *
 * @package wpmu-dev-seo
 */

/**
 * Assets controller.
 */
class Smartcrawl_Controller_Assets extends Smartcrawl_Base_Controller {

	use Smartcrawl_Singleton;

	const SUI_JS = 'wds-shared-ui';

	const ADMIN_JS = 'wds-admin';

	const OPENGRAPH_JS = 'wds-admin-opengraph';

	const QTIP2_JS = 'wds-qtip2-script';

	const MACRO_REPLACEMENT = 'wds-macro-replacement';

	const AUTOLINKS_PAGE_JS = 'wds-admin-autolinks';

	const ONPAGE_COMPONENTS = 'wds-onpage-components';

	const ONPAGE_JS = 'wds-admin-onpage';

	const SITEMAPS_PAGE_JS = 'wds-admin-sitemaps';

	const DASHBOARD_PAGE_JS = 'wds-admin-dashboard';

	const ONBOARDING_JS = 'wds-onboard';

	const EMAIL_RECIPIENTS_JS = 'wds-admin-email-recipients';

	const SOCIAL_PAGE_JS = 'wds-admin-social';

	const CONFIGS_JS = 'wds-configs';

	const THIRD_PARTY_IMPORT_JS = 'wds-third-party-import';

	const SETTINGS_PAGE_JS = 'wds-admin-settings';

	const NETWORK_SETTINGS_PAGE_JS = 'wds-admin-network-settings';

	const METABOX_COUNTER_JS = 'wds-metabox-counter';

	const METABOX_JS = 'wds-metabox';

	const METABOX_COMPONENTS_JS = 'wds-metabox-components';

	const METABOX_LINK_FORMAT_BUTTON = 'wds-link-format-button';

	const METABOX_LINK_REL_ATTRIBUTE_FIELD = 'wds-link-rel-attribute-field';

	const WP_POST_LIST_TABLE_JS = 'wds-admin-post-list-table';

	const WP_POST_LIST_TABLE_CSS = 'wds-admin-post-list-table-styling';

	const TERM_FORM_JS = 'wds-term-form';

	const QTIP2_CSS = 'wds-qtip2-style';

	const APP_CSS = 'wds-app';

	const WP_DASHBOARD_CSS = 'wds-wp-dashboard';

	const SCHEMA_JS = 'wds-admin-schema';

	const SCHEMA_TYPES_JS = 'wds-schema-types';

	const WELCOME_JS = 'wds-welcome-modal';

	const HEALTH_JS = 'wds-admin-health';

	const LIGHTHOUSE_JS = 'wds-admin-lighthouse';

	/**
	 * Bind listening actions
	 *
	 * @return bool
	 */
	public function init() {
		add_action( 'admin_enqueue_scripts', array( $this, 'register_assets' ), - 10 );

		return true;
	}

	/**
	 * Check if it's correct page.
	 *
	 * @param string $page Page name.
	 *
	 * @return bool
	 */
	private function is_page( $page ) {
		return $this->is_smartcrawl_page() && smartcrawl_get_array_value( $_GET, 'page' ) === $page; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	}

	/**
	 * Check if current page is Smartcrawl page.
	 *
	 * @return bool
	 */
	private function is_smartcrawl_page() {
		global $pagenow;
		$page = (string) smartcrawl_get_array_value( $_GET, 'page' ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		return 'admin.php' === $pagenow && strpos( $page, 'wds_' ) === 0;
	}

	/**
	 * Register assets.
	 *
	 * @return void
	 */
	public function register_assets() {
		$this->register_general_scripts();

		// Smartcrawl pages.
		$this->register_advanced_tools_scripts();
		$this->register_onpage_page_scripts();
		$this->register_sitemap_page_scripts();
		$this->register_dashboard_page_scripts();
		$this->register_social_page_scripts();
		$this->register_settings_page_scripts();
		$this->register_network_settings_page_scripts();
		$this->register_schema_settings_page_scripts();
		$this->register_health_settings_page_scripts();

		// WP pages.
		$this->register_metabox_scripts();
		$this->register_post_list_scripts();
		$this->register_term_form_scripts();

		// CSS.
		$this->register_general_styles();
		$this->register_wp_dashboard_styles();
		$this->register_post_list_styles();
	}

	/**
	 * Get version to be used for JS and Css files.
	 *
	 * @return string
	 */
	private function get_version() {
		$value = Smartcrawl_Loader::get_version();
		if ( defined( 'SMARTCRAWL_BUILD' ) ) {
			$value = sprintf( '%s-%s', $value, SMARTCRAWL_BUILD );
		}

		return $value;
	}

	/**
	 * Register Javascript.
	 *
	 * @param string       $handle Name of the script. Should be unique.
	 * @param string|false $src Relative URL of the script.
	 * @param string[]     $deps Optional. An array of registered script handles this script depends on. Default empty array.
	 *
	 * @return void
	 */
	private function register_js( $handle, $src, $deps = array() ) {
		wp_register_script( $handle, $this->base_url( $src ), $deps, $this->get_version(), true );
	}

	/**
	 * Register a CSS stylesheet.
	 *
	 * @param string       $handle Name of the stylesheet. Should be unique.
	 * @param string|false $src Relative URL of the stylesheet.
	 * @param string[]     $deps Optional. An array of registered stylesheet handles this stylesheet depends on. Default empty array.
	 *
	 * @return void
	 */
	private function register_css( $handle, $src, $deps = array() ) {
		wp_register_style( $handle, $this->base_url( $src ), $deps, $this->get_version() );
	}

	/**
	 * Get asset's full URL from plugin's base url.
	 *
	 * @param string $url Relative url of assets.
	 *
	 * @return string
	 */
	private function base_url( $url ) {
		return trailingslashit( SMARTCRAWL_PLUGIN_URL ) . "assets/$url";
	}

	/**
	 * Register generally required scripts.
	 *
	 * @return void
	 */
	private function register_general_scripts() {
		if (
			! $this->is_smartcrawl_page() &&
			! $this->is_post_list_page() &&
			! $this->is_term_edit_page() &&
			! $this->is_post_edit_screen()
		) {
			return;
		}

		// Shared UI.
		$this->register_js(
			self::SUI_JS,
			'js/build/shared-ui.min.js',
			array(
				'jquery',
				'clipboard',
			)
		);

		// Common JS functions and utils.
		$this->register_js(
			self::ADMIN_JS,
			'js/wds-admin.js',
			array(
				self::SUI_JS,
				'jquery',
			)
		);

		wp_localize_script(
			self::ADMIN_JS,
			'_wds_admin',
			array(
				'strings'    => array(
					'initializing' => esc_html__( 'Initializing ...', 'smartcrawl-seo' ),
					'running'      => esc_html__( 'Running SEO checks ...', 'smartcrawl-seo' ),
					'finalizing'   => esc_html__( 'Running final checks and finishing up ...', 'smartcrawl-seo' ),
					'characters'   => esc_html__( 'characters', 'smartcrawl-seo' ),
				),
				'plugin_url' => untrailingslashit( SMARTCRAWL_PLUGIN_URL ),
				'nonce'      => wp_create_nonce( 'wds-admin-nonce' ),
			)
		);

		$this->register_js(
			self::QTIP2_JS,
			'js/external/jquery.qtip.min.js',
			array(
				'jquery',
			)
		);
	}

	/**
	 * Register scripts for Advanced Tools page.
	 *
	 * @return void
	 */
	private function register_advanced_tools_scripts() {
		if ( ! $this->is_page( Smartcrawl_Settings::TAB_AUTOLINKS ) ) {
			return;
		}

		$autolinks_deps = $this->dynamic_dependencies(
			self::AUTOLINKS_PAGE_JS,
			array(
				'underscore',
				'jquery',
				self::ADMIN_JS,
			)
		);
		$this->register_js( self::AUTOLINKS_PAGE_JS, 'js/build/wds-admin-autolinks.min.js', $autolinks_deps );

		$this->set_script_translations( self::AUTOLINKS_PAGE_JS );

		$redirects_table = Smartcrawl_Redirects_Database_Table::get();
		wp_localize_script(
			self::AUTOLINKS_PAGE_JS,
			'_wds_autolinks',
			array(
				'default_redirect_type' => Smartcrawl_Redirect_Utils::get()->get_default_type(),
			)
		);

		wp_localize_script(
			self::AUTOLINKS_PAGE_JS,
			'_wds_redirects',
			array(
				'redirects'          => $redirects_table->get_deflated_redirects(),
				'nonce'              => wp_create_nonce( 'wds-redirects-nonce' ),
				'home_url'           => untrailingslashit( home_url( '/' ) ),
				'accepted-csv-types' => smartcrawl_csv_mime_types(),
			)
		);

		if ( smartcrawl_woocommerce_active() ) {
			$woo_data = new Smartcrawl_Woocommerce_Data();

			wp_localize_script(
				self::AUTOLINKS_PAGE_JS,
				'_wds_woo',
				array(
					'options'            => $woo_data->get_data(),
					'permalink_settings' => admin_url( 'options-permalink.php' ),
					'image_path'         => sprintf( '%s/assets/images/sitemap-disabled.svg', SMARTCRAWL_PLUGIN_URL ),
					'nonce'              => wp_create_nonce( 'wds-woo-nonce' ),
				)
			);
		}
	}

	/**
	 * Register scripts for Title & Meta page.
	 *
	 * @return void
	 */
	private function register_onpage_page_scripts() {
		if ( ! $this->is_page( Smartcrawl_Settings::TAB_ONPAGE ) ) {
			return;
		}

		$this->register_opengraph_script();
		$this->register_macro_replacement_script();

		$onpage_deps = $this->dynamic_dependencies(
			self::ONPAGE_COMPONENTS,
			array(
				'jquery',
				'underscore',
			)
		);
		$this->register_js( self::ONPAGE_COMPONENTS, 'js/build/wds-onpage-components.min.js', $onpage_deps );
		wp_localize_script(
			self::ONPAGE_COMPONENTS,
			'_wds_onpage_components',
			array(
				'random_posts' => Smartcrawl_Onpage_Settings::get_random_post_data(),
				'random_terms' => Smartcrawl_Onpage_Settings::get_random_terms(),
			)
		);

		$this->register_js(
			self::ONPAGE_JS,
			'js/wds-admin-onpage.js',
			array(
				'jquery',
				self::ADMIN_JS,
				self::OPENGRAPH_JS,
				self::ONPAGE_COMPONENTS,
				self::MACRO_REPLACEMENT,
			)
		);
		wp_localize_script(
			self::ONPAGE_JS,
			'_wds_onpage',
			array(
				'templates'         => array(
					'notice'  => Smartcrawl_Simple_Renderer::load( 'notice', array( 'message' => '{{- message }}' ) ),
					'preview' => Smartcrawl_Simple_Renderer::load( 'onpage/underscore-onpage-preview' ),
				),
				'home_url'          => home_url( '/' ),
				'nonce'             => wp_create_nonce( 'wds-onpage-nonce' ),
				'title_min'         => smartcrawl_title_min_length(),
				'title_max'         => smartcrawl_title_max_length(),
				'metadesc_min'      => smartcrawl_metadesc_min_length(),
				'metadesc_max'      => smartcrawl_metadesc_max_length(),
				'random_archives'   => Smartcrawl_Onpage_Settings::get_random_archives(),
				'random_buddypress' => Smartcrawl_Onpage_Settings::get_random_buddypress(),
			)
		);
	}

	/**
	 * Register scripts for Sitemaps page.
	 *
	 * @return void
	 */
	private function register_sitemap_page_scripts() {
		if ( ! $this->is_page( Smartcrawl_Settings::TAB_SITEMAP ) ) {
			return;
		}

		$this->register_email_recipients_js();

		wp_localize_script(
			self::EMAIL_RECIPIENTS_JS,
			'_wds_email_recipients',
			array(
				'id'         => 'wds-sitemap-email-recipients',
				'recipients' => Smartcrawl_Sitemap_Settings::get_email_recipients(),
				'field_name' => 'wds_sitemap_options[sitemap-email-recipients]',
			)
		);

		$sitemap_deps = $this->dynamic_dependencies(
			self::SITEMAPS_PAGE_JS,
			array(
				'jquery',
				self::ADMIN_JS,
				self::EMAIL_RECIPIENTS_JS,
			)
		);
		$this->register_js( self::SITEMAPS_PAGE_JS, 'js/build/wds-admin-sitemaps.min.js', $sitemap_deps );

		$this->set_script_translations( self::SITEMAPS_PAGE_JS );

		wp_localize_script(
			self::SITEMAPS_PAGE_JS,
			'_wds_sitemaps',
			array(
				'nonce'       => wp_create_nonce( 'wds-nonce' ),
				'sitemap_url' => smartcrawl_get_sitemap_url(),
				'strings'     => array(
					'manually_updated'          => esc_html__( 'Your sitemap has been updated.', 'smartcrawl-seo' ),
					'manually_notified_engines' => esc_html__( 'Search Engines are being notified with changes.', 'smartcrawl-seo' ),
				),
			)
		);

		$seo_service = Smartcrawl_Service::get( Smartcrawl_Service::SERVICE_SEO );
		$report      = $seo_service->get_report();

		wp_localize_script(
			self::SITEMAPS_PAGE_JS,
			'_wds_crawler',
			array(
				'nonce'              => wp_create_nonce( 'wds-crawler-nonce' ),
				'issues'             => $report->get_all_issues_grouped_by_type(),
				'advanced_tools_url' => Smartcrawl_Settings_Admin::admin_url( Smartcrawl_Settings::TAB_AUTOLINKS ) . '&tab=tab_url_redirection',
			)
		);

		$social_options  = Smartcrawl_Settings::get_component_options( Smartcrawl_Settings::COMP_SOCIAL );
		$schema_disabled = ! empty( $social_options['disable-schema'] );

		wp_localize_script(
			self::SITEMAPS_PAGE_JS,
			'_wds_news',
			array_merge(
				array(
					'news_sitemap_url' => smartcrawl_get_news_sitemap_url(),
					'schema_enabled'   => ! $schema_disabled,
				),
				$this->get_news_sitemap_data()
			)
		);
	}

	/**
	 * Register scripts for News sitemap.
	 *
	 * @return array
	 */
	private function get_news_sitemap_data() {
		$settings = Smartcrawl_Settings::get_component_options( Smartcrawl_Settings::COMP_SITEMAP );
		$data     = new Smartcrawl_News_Sitemap_Data();

		return $data->settings_to_data( $settings );
	}

	/**
	 * Register scripts for Dashboard page.
	 *
	 * @return void
	 */
	private function register_dashboard_page_scripts() {
		if ( ! $this->is_page( Smartcrawl_Settings::TAB_DASHBOARD ) ) {
			return;
		}

		$this->register_configs_script();

		$this->register_js(
			self::ONBOARDING_JS,
			'js/wds-admin-onboard.js',
			array(
				self::ADMIN_JS,
			)
		);

		wp_localize_script(
			self::ONBOARDING_JS,
			'_wds_onboard',
			array(
				'templates' => array(
					'progress' => Smartcrawl_Simple_Renderer::load( 'dashboard/onboard-progress' ),
				),
				'strings'   => array(
					'All done' => esc_html__( 'All done, please hold on...', 'smartcrawl-seo' ),
				),
				'nonce'     => wp_create_nonce( 'wds-onboard-nonce' ),
			)
		);

		$this->register_js(
			self::WELCOME_JS,
			'js/wds-welcome-modal.js',
			array(
				self::ADMIN_JS,
			)
		);

		$nonce = wp_create_nonce( 'wds-nonce' );
		wp_localize_script(
			self::WELCOME_JS,
			'_wds_welcome',
			array(
				'nonce' => $nonce,
			)
		);

		$this->register_js(
			self::DASHBOARD_PAGE_JS,
			'js/wds-admin-dashboard.js',
			array(
				'jquery',
				'underscore',
				self::ADMIN_JS,
				self::ONBOARDING_JS,
				self::WELCOME_JS,
			)
		);

		wp_localize_script(
			self::DASHBOARD_PAGE_JS,
			'_wds_dashboard',
			array(
				'nonce'                    => $nonce,
				'health_page_url'          => Smartcrawl_Settings_Admin::admin_url( Smartcrawl_Settings::TAB_HEALTH ),
				'lighthouse_widget_device' => Smartcrawl_Lighthouse_Options::dashboard_widget_device(),
				'lighthouse_nonce'         => wp_create_nonce( 'wds-lighthouse-nonce' ),
			)
		);
	}

	/**
	 * Register scripts for Social page.
	 *
	 * @return void
	 */
	private function register_social_page_scripts() {
		if ( ! $this->is_page( Smartcrawl_Settings::TAB_SOCIAL ) ) {
			return;
		}

		$this->register_js(
			self::SOCIAL_PAGE_JS,
			'js/wds-admin-social.js',
			array(
				'jquery',
				self::ADMIN_JS,
			)
		);
	}

	/**
	 * Get timezone string.
	 *
	 * @return string
	 */
	private function get_timezone() {
		$timezone = get_option( 'timezone_string' );
		if ( ! empty( $timezone ) ) {
			return $timezone;
		}

		$offset = (int) get_option( 'gmt_offset', 0 );

		return "UTC+$offset";
	}

	/**
	 * Register scripts for Settings page.
	 *
	 * @return void
	 */
	private function register_settings_page_scripts() {
		if ( ! $this->is_page( Smartcrawl_Settings::TAB_SETTINGS ) ) {
			return;
		}

		$this->register_configs_script();

		$import_deps = $this->dynamic_dependencies(
			self::THIRD_PARTY_IMPORT_JS,
			array(
				self::ADMIN_JS,
			)
		);
		$this->register_js(
			self::THIRD_PARTY_IMPORT_JS,
			'js/build/wds-third-party-import.min.js',
			$import_deps
		);

		$this->set_script_translations( self::THIRD_PARTY_IMPORT_JS );

		$aioseo_importer = new Smartcrawl_AIOSEOP_Importer();

		wp_localize_script(
			self::THIRD_PARTY_IMPORT_JS,
			'_wds_import',
			array(
				'nonce'               => wp_create_nonce( 'wds-io-nonce' ),
				'aioseop_data_exists' => ! ! $aioseo_importer->data_exists(),
				'is_multisite'        => ! ! is_multisite(),
				'index_settings_url'  => admin_url( 'admin.php?page=wds_onpage' ),
			)
		);

		$setting_deps = $this->dynamic_dependencies(
			self::SETTINGS_PAGE_JS,
			array(
				'jquery',
				self::ADMIN_JS,
				self::THIRD_PARTY_IMPORT_JS,
			)
		);
		$this->register_js( self::SETTINGS_PAGE_JS, 'js/build/wds-admin-settings.min.js', $setting_deps );

		$this->set_script_translations( self::SETTINGS_PAGE_JS );

		wp_localize_script(
			self::SETTINGS_PAGE_JS,
			'_wds_reset',
			array(
				'nonce'           => wp_create_nonce( 'wds-data-reset-nonce' ),
				'multisite_nonce' => wp_create_nonce( 'wds-multisite-data-reset-nonce' ),
			)
		);
	}

	/**
	 * Register scripts for metabox.
	 *
	 * @return void
	 */
	private function register_metabox_scripts() {
		if ( ! $this->is_post_edit_screen() ) {
			return;
		}

		$this->register_opengraph_script();
		$this->register_macro_replacement_script();

		if ( $this->is_block_editor_active() ) {
			$link_format_button_deps = $this->dynamic_dependencies(
				self::METABOX_LINK_FORMAT_BUTTON,
				array(
					'wp-block-editor',
					'wp-i18n',
					'wp-element',
					'wp-components',
					'wp-rich-text',
					'wp-html-entities',
					'wp-element',
					'wp-components',
					'wp-url',
				)
			);

			$this->register_js( self::METABOX_LINK_FORMAT_BUTTON, 'js/build/wds-link-format-button.min.js', $link_format_button_deps );
		} else {
			$this->register_js(
				self::METABOX_LINK_REL_ATTRIBUTE_FIELD,
				'js/wds-link-rel-attribute-field.js',
				array(
					'jquery',
					self::ADMIN_JS,
				)
			);
			wp_localize_script(
				self::METABOX_LINK_REL_ATTRIBUTE_FIELD,
				'_wds_link_rel_attr',
				array(
					'templates' => array(
						'field' => Smartcrawl_Simple_Renderer::load( 'metabox/underscore-link-rel-attribute' ),
					),
				)
			);
		}

		$options = Smartcrawl_Settings::get_options();
		if ( ! $this->is_block_editor_active() ) {
			$this->register_js( self::METABOX_COUNTER_JS, 'js/wds-metabox-counter.js', array() );
			wp_localize_script(
				self::METABOX_COUNTER_JS,
				'l10nWdsCounters',
				array(
					'title_length'       => esc_html__( '{TOTAL_LEFT} characters left', 'smartcrawl-seo' ),
					'title_longer'       => esc_html__( 'Over {MAX_COUNT} characters ({CURRENT_COUNT})', 'smartcrawl-seo' ),
					'main_title_longer'  => esc_html__( 'Over {MAX_COUNT} characters ({CURRENT_COUNT}) - make sure your SEO title is shorter', 'smartcrawl-seo' ),
					'title_min'          => smartcrawl_title_min_length(),
					'title_max'          => smartcrawl_title_max_length(),
					'metadesc_min'       => smartcrawl_metadesc_min_length(),
					'metadesc_max'       => smartcrawl_metadesc_max_length(),
					'main_title_warning' => ! ( defined( 'SMARTCRAWL_MAIN_TITLE_LENGTH_WARNING_HIDE' ) && SMARTCRAWL_MAIN_TITLE_LENGTH_WARNING_HIDE ),
				)
			);
		}

		$post_type    = $this->get_post_type();
		$title        = (string) smartcrawl_get_array_value( $options, 'title-' . $post_type );
		$description  = (string) smartcrawl_get_array_value( $options, 'metadesc-' . $post_type );
		$post_id      = $this->get_post_id_query_var();
		$metabox_deps = $this->dynamic_dependencies(
			self::METABOX_COMPONENTS_JS,
			array(
				'jquery',
				'underscore',
				'wp-api',
				'wp-api-fetch',
				'wp-date',
				self::ADMIN_JS,
			)
		);
		$this->register_js( self::METABOX_COMPONENTS_JS, 'js/build/wds-metabox-components.min.js', $metabox_deps );
		wp_localize_script(
			self::METABOX_COMPONENTS_JS,
			'_wds_metabox',
			array(
				'nonce'               => wp_create_nonce( 'wds-metabox-nonce' ),
				'meta_title'          => $title,
				'meta_desc'           => $description,
				'home_url'            => home_url( '/' ),
				'post_url'            => $post_id ? get_permalink( $post_id ) : '',
				'title_min_length'    => smartcrawl_title_min_length(),
				'title_max_length'    => smartcrawl_title_max_length(),
				'metadesc_min_length' => smartcrawl_metadesc_min_length(),
				'metadesc_max_length' => smartcrawl_metadesc_max_length(),
				'post_type'           => $this->get_post_type(),
				'taxonomies'          => $this->get_taxonomies(),
				'gutenberg_active'    => $this->is_block_editor_active(),
				'onpage_active'       => Smartcrawl_Settings::get_setting( 'onpage' ) && Smartcrawl_Settings_Admin::is_tab_allowed( Smartcrawl_Settings::TAB_ONPAGE ),
				'analysis_active'     => Smartcrawl_Settings::get_setting( 'analysis-readability' ) || Smartcrawl_Settings::get_setting( 'analysis-seo' ),
				'enforce_limits'      => ( isset( $options['metabox-lax_enforcement'] ) && ! ! $options['metabox-lax_enforcement'] ),
				'templates'           => array(
					'preview' => Smartcrawl_Simple_Renderer::load( 'metabox/underscore-google-preview' ),
				),
			)
		);

		$metabox_dependencies = array(
			'underscore',
			self::OPENGRAPH_JS,
			self::METABOX_COMPONENTS_JS,
			self::MACRO_REPLACEMENT,
		);
		if ( $this->is_block_editor_active() ) {
			if ( smartcrawl_is_switch_active( 'SMARTCRAWL_SHOW_GUTENBERG_LINK_FORMAT_BUTTON' ) ) {
				$metabox_dependencies[] = self::METABOX_LINK_FORMAT_BUTTON;
			}
		} else {
			$metabox_dependencies[] = self::METABOX_LINK_REL_ATTRIBUTE_FIELD;
			$metabox_dependencies[] = self::METABOX_COUNTER_JS;
			$metabox_dependencies[] = 'autosave';
		}
		$this->register_js( self::METABOX_JS, 'js/wds-metabox.js', $metabox_dependencies );
		wp_localize_script(
			self::METABOX_JS,
			'l10nWdsMetabox',
			array(
				'content_analysis_working' => esc_html__( 'Analyzing content, please wait a few moments', 'smartcrawl-seo' ),
			)
		);
	}

	/**
	 * Get macro replacements.
	 *
	 * @return array
	 */
	private function get_replacements() {
		$general_macros = Smartcrawl_Onpage_Settings::get_general_macros();
		$queried_entity = new Smartcrawl_Blog_Home();
		$map            = array();
		foreach ( $general_macros as $macro => $macro_desc ) {
			$map[ str_replace( '%', '', $macro ) ] = $queried_entity->apply_macros( $macro );
		}

		return $map;
	}

	/**
	 * Return if block editor is active or not.
	 *
	 * @return bool
	 */
	private function is_block_editor_active() {
		$screen = get_current_screen();
		if ( $screen && method_exists( $screen, 'is_block_editor' ) ) {
			return $screen->is_block_editor();
		}

		if ( function_exists( 'is_gutenberg_page' ) ) {
			return is_gutenberg_page();
		}

		return false;
	}

	/**
	 * Check if current page is post list page.
	 *
	 * @return bool
	 */
	private function is_post_list_page() {
		global $pagenow;

		return 'edit.php' === $pagenow;
	}

	/**
	 * Register post list scripts.
	 *
	 * @return void
	 */
	private function register_post_list_scripts() {
		if ( ! $this->is_post_list_page() ) {
			return;
		}

		$this->register_js(
			self::WP_POST_LIST_TABLE_JS,
			'js/wds-admin-post-list-table.js',
			array(
				'jquery',
				'underscore',
				self::ADMIN_JS,
				self::QTIP2_JS,
			)
		);

		wp_localize_script(
			self::WP_POST_LIST_TABLE_JS,
			'_wds_post_list',
			array(
				'strings'             => array(
					'loading' => __( 'Loading, please hold on...', 'smartcrawl-seo' ),
				),
				'nonce'               => wp_create_nonce( 'wds-metabox-nonce' ),
				'analyse_posts_delay' => (int) apply_filters( 'wds-list-table-delay', 500 ), // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
			)
		);
	}

	/**
	 * Check if current page is term edit page or not.
	 *
	 * @return bool
	 */
	private function is_term_edit_page() {
		global $pagenow;

		return 'term.php' === $pagenow;
	}

	/**
	 * Register scripts for term form.
	 *
	 * @return void
	 */
	private function register_term_form_scripts() {
		if ( ! $this->is_term_edit_page() ) {
			return;
		}

		$this->register_opengraph_script();

		$this->register_js(
			self::TERM_FORM_JS,
			'js/wds-term-form.js',
			array(
				'jquery',
				self::ADMIN_JS,
				self::OPENGRAPH_JS,
			)
		);
		wp_localize_script(
			self::TERM_FORM_JS,
			'_wds_term_form',
			array(
				'nonce' => wp_create_nonce( 'wds-metabox-nonce' ),
			)
		);
	}

	/**
	 * Register styles for generally used.
	 *
	 * @return void
	 */
	private function register_general_styles() {
		$this->register_css( self::QTIP2_CSS, 'css/external/jquery.qtip.min.css' );

		$this->register_css( self::APP_CSS, 'css/app.min.css' );
	}

	/**
	 * Register style sfor WP dashboard.
	 *
	 * @return void
	 */
	private function register_wp_dashboard_styles() {
		$this->register_css( self::WP_DASHBOARD_CSS, 'css/wp-dashboard.min.css', array() );
	}

	/**
	 * Register styles for post list.
	 *
	 * @return void
	 */
	private function register_post_list_styles() {
		$this->register_css(
			self::WP_POST_LIST_TABLE_CSS,
			'css/wp-post-list-table.min.css',
			array( self::QTIP2_CSS )
		);
	}

	/**
	 * Register scripts for network settings.
	 *
	 * @return void
	 */
	private function register_network_settings_page_scripts() {
		if ( ! $this->is_page( Smartcrawl_Network_Settings_Page_Controller::MENU_SLUG ) ) {
			return;
		}

		$this->register_js(
			self::NETWORK_SETTINGS_PAGE_JS,
			'js/wds-admin-network-settings.js',
			array(
				'jquery',
				self::ADMIN_JS,
			)
		);
	}

	/**
	 * Check if current page is post edit page or not.
	 *
	 * @return bool
	 */
	private function is_post_edit_screen() {
		global $pagenow;

		return 'post-new.php' === $pagenow || 'post.php' === $pagenow;
	}

	/**
	 * Get p ost id from URL query string.
	 *
	 * @return mixed|null
	 */
	private function get_post_id_query_var() {
		return smartcrawl_get_array_value( $_GET, 'post' ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	}

	/**
	 * Get post type for current post.
	 *
	 * @return string
	 */
	private function get_post_type() {
		$post = get_post();

		return ( $post instanceof WP_Post ) ? $post->post_type : 'post';
	}

	/**
	 * Get taxonomies for current post type.
	 *
	 * @return string[]|WP_Taxonomy[]
	 */
	private function get_taxonomies() {
		$post_type = $this->get_post_type();

		return get_object_taxonomies( $post_type );
	}

	/**
	 * Register scripts for Schema page.
	 *
	 * @return void
	 */
	private function register_schema_settings_page_scripts() {
		if ( ! $this->is_page( Smartcrawl_Settings::TAB_SCHEMA ) ) {
			return;
		}

		$this->register_js(
			self::SCHEMA_JS,
			'js/wds-admin-schema.js',
			array(
				'jquery',
				self::ADMIN_JS,
			)
		);

		wp_localize_script(
			self::SCHEMA_JS,
			'_wds_schema',
			array(
				'nonce'               => wp_create_nonce( 'wds-schema-nonce' ),
				'youtube_key_valid'   => esc_html__( 'Key valid!', 'smartcrawl-seo' ),
				'youtube_key_invalid' => esc_html__( 'Key invalid', 'smartcrawl-seo' ),
			)
		);

		$post_types     = array_map(
			function ( $post_type ) {
				return get_post_type_object( $post_type )->labels->singular_name;
			},
			smartcrawl_frontend_post_types()
		);
		$post_formats   = $this->get_post_formats();
		$page_templates = wp_get_theme()->get_page_templates();
		$user_roles     = array_map(
			function ( $role ) {
				return smartcrawl_get_array_value( $role, 'name' );
			},
			wp_roles()->roles
		);
		$taxonomies     = array_map(
			function ( $taxonomy ) {
				return $taxonomy->label;
			},
			smartcrawl_frontend_taxonomies()
		);

		$schema_types_deps = $this->dynamic_dependencies(
			self::SCHEMA_TYPES_JS,
			array(
				'jquery-ui-datepicker',
			)
		);
		$this->register_js( self::SCHEMA_TYPES_JS, 'js/build/wds-schema-types.min.js', $schema_types_deps );

		$this->set_script_translations( self::SCHEMA_TYPES_JS );

		wp_localize_script(
			self::SCHEMA_TYPES_JS,
			'_wds_schema_types',
			array(
				'plugin_version'       => SMARTCRAWL_VERSION,
				'post_types'           => $post_types,
				'post_formats'         => $post_formats,
				'page_templates'       => $page_templates,
				'taxonomies'           => $taxonomies,
				'post_type_taxonomies' => $this->get_post_type_taxonomies(),
				'user_roles'           => $user_roles,
				'ajax_url'             => admin_url( 'admin-ajax.php' ),
				'types'                => Smartcrawl_Controller_Schema_Types::get()->get_schema_types(),
				'woocommerce'          => smartcrawl_woocommerce_active(),
				'settings_updated'     => smartcrawl_get_array_value( $_GET, 'settings-updated' ), // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			)
		);
	}

	/**
	 * Get taxonomies for available post types.
	 *
	 * @return array
	 */
	private function get_post_type_taxonomies() {
		$post_types           = smartcrawl_frontend_post_types();
		$available_taxonomies = smartcrawl_frontend_taxonomies();
		$post_type_taxonomies = array();

		foreach ( $post_types as $post_type ) {
			$taxonomies      = get_object_taxonomies( $post_type, 'objects' );
			$taxonomies      = empty( $taxonomies ) ? array() : $taxonomies;
			$taxonomies      = array_filter(
				$taxonomies,
				function ( $taxonomy ) use ( $available_taxonomies ) {
					return array_key_exists( $taxonomy->name, $available_taxonomies );
				}
			);
			$post_type_label = get_post_type_object( $post_type )->labels->singular_name;
			if ( empty( $taxonomies ) ) {
				$post_type_taxonomies[ $post_type ] = $post_type_label;
				continue;
			}

			$post_type_taxonomies[ $post_type ] = array(
				'label'   => $post_type_label,
				'options' => array_merge(
					array(
						$post_type => $post_type_label,
					),
					array_map(
						function ( $taxonomy ) {
							return isset( $taxonomy->labels->singular_name )
								? $taxonomy->labels->singular_name
								: $taxonomy->label;
						},
						$taxonomies
					)
				),
			);
		}

		return $post_type_taxonomies;
	}

	/**
	 * Get post formats.
	 *
	 * @return array
	 */
	private function get_post_formats() {
		$post_formats = smartcrawl_get_array_value( get_theme_support( 'post-formats' ), 0 );
		$post_formats = empty( $post_formats ) ? array() : $post_formats;

		return array_combine( $post_formats, $post_formats );
	}

	/**
	 * Register scripts for email recipients.
	 *
	 * @return void
	 */
	private function register_email_recipients_js() {
		$recipient_deps = $this->dynamic_dependencies(
			self::EMAIL_RECIPIENTS_JS,
			array(
				self::ADMIN_JS,
			)
		);

		$this->register_js( self::EMAIL_RECIPIENTS_JS, 'js/build/wds-admin-email-recipients.min.js', $recipient_deps );

		$this->set_script_translations( self::EMAIL_RECIPIENTS_JS );
	}

	/**
	 * Register scripts for Health page.
	 *
	 * @return void
	 */
	private function register_health_settings_page_scripts() {
		if ( ! $this->is_page( Smartcrawl_Settings::TAB_HEALTH ) ) {
			return;
		}

		$this->register_email_recipients_js();

		$this->register_js(
			self::HEALTH_JS,
			'js/wds-admin-health.js',
			array(
				self::ADMIN_JS,
			)
		);

		wp_localize_script(
			self::HEALTH_JS,
			'_wds_health',
			array(
				'nonce' => wp_create_nonce( 'wds-health-nonce' ),
			)
		);

		wp_localize_script(
			self::EMAIL_RECIPIENTS_JS,
			'_wds_email_recipients',
			array(
				'id'         => 'wds-lighthouse-email-recipients',
				'recipients' => Smartcrawl_Lighthouse_Options::email_recipients(),
				'field_name' => 'wds_health_options[lighthouse-recipients]',
			)
		);

		$this->register_js(
			self::LIGHTHOUSE_JS,
			'js/build/wds-admin-lighthouse.min.js',
			array(
				self::ADMIN_JS,
				self::EMAIL_RECIPIENTS_JS,
				self::HEALTH_JS,
			)
		);

		$lighthouse = Smartcrawl_Service::get( Smartcrawl_Service::SERVICE_LIGHTHOUSE );

		wp_localize_script(
			self::LIGHTHOUSE_JS,
			'_wds_lighthouse',
			array(
				'start_time' => $lighthouse->get_start_time(),
				'nonce'      => wp_create_nonce( 'wds-lighthouse-nonce' ),
				'strings'    => array(
					'analyzing'                 => esc_html__( 'Analyzing data and preparing report...', 'smartcrawl-seo' ),
					'running'                   => esc_html__( 'Running SEO test...', 'smartcrawl-seo' ),
					'refreshing'                => esc_html__( 'Refreshing data. Please wait...', 'smartcrawl-seo' ),
					'audit_copied'              => esc_html__( 'The audit has been copied successfully.', 'smartcrawl-seo' ),
					'audit_copy_failed'         => esc_html__( 'Audit could not be copied to clipboard.', 'smartcrawl-seo' ),
					/* translators: %s: Remaining cool down minutes */
					'cooldown_message'          => esc_html__( 'SmartCrawl is just catching her breath - you can run another test in %s minutes.', 'smartcrawl-seo' ),
					/* translators: %s: Remaining cool down minutes */
					'cooldown_message_singular' => esc_html__( 'SmartCrawl is just catching her breath - you can run another test in %s minute.', 'smartcrawl-seo' ),
				),
			)
		);
	}

	/**
	 * Sets translated strings for a script.
	 *
	 * @param string $handle Script handle the textdomain will be attached to.
	 *
	 * @return void
	 */
	private function set_script_translations( $handle ) {
		if ( ! function_exists( 'wp_set_script_translations' ) ) {
			return;
		}

		wp_set_script_translations( $handle, 'smartcrawl-seo' );
	}

	/**
	 * Get script dependencies.
	 *
	 * @param string $file_name File name.
	 * @param array  $extra_deps Extra dependencies.
	 *
	 * @return array
	 */
	private function dynamic_dependencies( $file_name, $extra_deps = array() ) {
		$assets_path = SMARTCRAWL_PLUGIN_DIR . 'assets/js/build/assets.php';

		if ( ! file_exists( $assets_path ) ) {
			return $extra_deps;
		}

		$assets     = require $assets_path;
		$key        = "{$file_name}.min.js";
		$asset_data = smartcrawl_get_array_value( $assets, $key );

		if ( ! $asset_data ) {
			return $extra_deps;
		}

		$dependencies = smartcrawl_get_array_value( $asset_data, 'dependencies' );
		$dependencies = empty( $dependencies ) ? array() : $dependencies;

		return array_merge(
			$extra_deps,
			$dependencies
		);
	}

	/**
	 * Register script for opengraph.
	 *
	 * @return void
	 */
	private function register_opengraph_script() {
		$this->register_js(
			self::OPENGRAPH_JS,
			'js/wds-admin-opengraph.js',
			array(
				'underscore',
				'jquery',
				self::ADMIN_JS,
			)
		);

		wp_localize_script(
			self::OPENGRAPH_JS,
			'_wds_opengraph',
			array(
				'templates' => array(
					'item' => Smartcrawl_Simple_Renderer::load(
						'social-image-item',
						array(
							'id'         => '{{= id }}',
							'url'        => '{{= url }}',
							'field_name' => '{{= name }}',
						)
					),
				),
			)
		);
	}

	/**
	 * Register script for macro replacement.
	 *
	 * @return void
	 */
	private function register_macro_replacement_script() {
		$macro_replacement_deps = $this->dynamic_dependencies(
			self::MACRO_REPLACEMENT,
			array(
				'jquery',
				'underscore',
				'wp-api',
				'wp-api-fetch',
				'wp-date',
			)
		);
		$this->register_js( self::MACRO_REPLACEMENT, 'js/build/wds-macro-replacement.min.js', $macro_replacement_deps );

		wp_localize_script(
			self::MACRO_REPLACEMENT,
			'_wds_replacement',
			array(
				'date_format'         => get_option( 'date_format' ),
				'time_format'         => get_option( 'time_format' ),
				'metadesc_max_length' => smartcrawl_metadesc_max_length(),
				'taxonomies'          => $this->get_taxonomies(),
				'replacements'        => $this->get_replacements(),
				'omitted_shortcodes'  => apply_filters( 'wds-omitted-shortcodes', array() ), // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
			)
		);
	}

	/**
	 * Register script for Configs in Settings page.
	 *
	 * @return void
	 */
	private function register_configs_script() {
		$configs_deps = $this->dynamic_dependencies(
			self::CONFIGS_JS,
			array(
				'jquery',
				self::ADMIN_JS,
			)
		);
		$this->register_js( self::CONFIGS_JS, 'js/build/wds-configs.min.js', $configs_deps );

		$this->set_script_translations( self::CONFIGS_JS );

		$service = new Smartcrawl_Configs_Service();
		wp_localize_script(
			self::CONFIGS_JS,
			'_wds_config',
			array(
				'is_member'    => $service->is_member(),
				'nonce'        => wp_create_nonce( 'wds-configs-nonce' ),
				'configs'      => Smartcrawl_Config_Collection::get()->get_deflated_configs(),
				'timezone'     => $this->get_timezone(),
				'manage_url'   => Smartcrawl_Settings_Admin::admin_url( Smartcrawl_Settings::TAB_SETTINGS ) . '&tab=tab_configs',
				'default_icon' => sprintf( '%s/assets/images/default-config.svg', SMARTCRAWL_PLUGIN_URL ),
			)
		);
	}
}
