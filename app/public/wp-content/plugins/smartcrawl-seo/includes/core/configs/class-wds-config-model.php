<?php

class Smartcrawl_Config_Model {
	private $id          = null;
	private $hub_id      = null;
	private $name        = '';
	private $description = '';
	private $configs     = array();
	private $strings     = array();
	private $editable    = true;
	private $removable   = true;
	private $official    = false;
	private $timestamp   = 0;

	public static function create_from_plugin_snapshot( $name = '', $description = '' ) {
		$configs  = Smartcrawl_Export::load()->get_all();
		$instance = new self();
		$strings  = $instance->prepare_strings();
		return $instance
			->set_id( uniqid() )
			->set_name( $name )
			->set_description( $description )
			->set_configs( $configs )
			->set_strings( $strings )
			->set_timestamp( time() );
	}

	public static function create_from_hub_data( $hub_config_data ) {
		$hub_config_json = smartcrawl_get_array_value( $hub_config_data, 'config' );
		$hub_id          = smartcrawl_get_array_value( $hub_config_data, 'id' );
		if ( ! $hub_config_json || ! $hub_id ) {
			return null;
		}
		$hub_config = json_decode( $hub_config_json, true );
		if ( ! $hub_config ) {
			return null;
		}
		$created_time = smartcrawl_get_array_value( $hub_config_data, 'created_time_utc' );
		return ( new self() )
			->set_id( uniqid() )
			->set_hub_id( (int) $hub_id )
			->set_name( smartcrawl_get_array_value( $hub_config_data, 'name' ) )
			->set_description( smartcrawl_get_array_value( $hub_config_data, 'description' ) )
			->set_configs( smartcrawl_get_array_value( $hub_config, 'configs' ) )
			->set_strings( smartcrawl_get_array_value( $hub_config, 'strings' ) )
			->set_editable( (bool) smartcrawl_get_array_value( $hub_config_data, 'is_editable' ) )
			->set_removable( (bool) smartcrawl_get_array_value( $hub_config_data, 'is_removable' ) )
			->set_official( (bool) smartcrawl_get_array_value( $hub_config_data, 'is_official' ) )
			->set_timestamp( (int) strtotime( $created_time ) );
	}

	/**
	 * Strings which be visible on the configs screen. The structure is going to be different from how settings are saved in the DB.
	 */
	private function prepare_strings() {
		return array(
			'health'   => $this->prepare_health_string(),
			'onpage'   => $this->prepare_onpage_string(),
			'schema'   => $this->prepare_schema_string(),
			'social'   => $this->prepare_social_string(),
			'sitemap'  => $this->prepare_sitemap_string(),
			'advanced' => $this->prepare_advanced_string(),
			'settings' => $this->prepare_settings_strings(),
		);
	}

	public function get_label( $item ) {
		$labels = array(
			'health'   => esc_html__( 'Health', 'smartcrawl-seo' ),
			'onpage'   => esc_html__( 'Title & Meta', 'smartcrawl-seo' ),
			'schema'   => esc_html__( 'Schema', 'smartcrawl-seo' ),
			'social'   => esc_html__( 'Social', 'smartcrawl-seo' ),
			'sitemap'  => esc_html__( 'Sitemap', 'smartcrawl-seo' ),
			'advanced' => esc_html__( 'Advanced Tools', 'smartcrawl-seo' ),
			'settings' => esc_html__( 'Settings', 'smartcrawl-seo' ),
		);

		return (string) smartcrawl_get_array_value( $labels, $item );
	}

	private function prepare_health_string() {
		$reporting_status = $this->prepare_lighthouse_reporting_status();

		$parts[] = esc_html__( 'SEO test - Active', 'smartcrawl-seo' );
		$parts[] = sprintf(
			/* translators: %s: Report status */
			esc_html__( 'Scheduled performance reports - %s', 'wsd' ),
			$reporting_status
		);
		return implode( "\n", $parts );
	}

	private function prepare_onpage_string() {
		return Smartcrawl_Settings::get_setting( 'onpage' )
			? esc_html__( 'Active', 'smartcrawl-seo' )
			: esc_html__( 'Inactive', 'smartcrawl-seo' );
	}

	private function prepare_schema_string() {
		$social          = Smartcrawl_Settings::get_component_options( Smartcrawl_Settings::COMP_SOCIAL );
		$schema_disabled = ! empty( $social['disable-schema'] );
		return $schema_disabled
			? esc_html__( 'Inactive', 'smartcrawl-seo' )
			: esc_html__( 'Active', 'smartcrawl-seo' );
	}

	private function prepare_social_string() {
		$options       = Smartcrawl_Settings::get_options();
		$social_active = (bool) smartcrawl_get_array_value( $options, 'social' );
		if ( ! $social_active ) {
			return $this->get_status_string( $social_active );
		}

		$og_active      = (bool) smartcrawl_get_array_value( $options, 'og-enable' );
		$twitter_active = (bool) smartcrawl_get_array_value( $options, 'twitter-card-enable' );

		return join(
			"\n",
			array(
				esc_attr__( 'OpenGraph Support - ', 'smartcrawl-seo' ) . $this->get_status_string( $og_active ),
				esc_attr__( 'Twitter Cards - ', 'smartcrawl-seo' ) . $this->get_status_string( $twitter_active ),
			)
		);
	}

	private function prepare_sitemap_string() {
		return Smartcrawl_Settings::get_setting( 'sitemap' )
			? esc_html__( 'Active', 'smartcrawl-seo' )
			: esc_html__( 'Inactive', 'smartcrawl-seo' );
	}

	private function prepare_advanced_string() {
		$autolinks_active = Smartcrawl_Settings::get_setting( 'autolinks' );
		$redirects_table  = Smartcrawl_Redirects_Database_Table::get();
		$redirects_count  = $redirects_table->get_count();

		$options        = Smartcrawl_Settings::get_options();
		$moz_access_id  = smartcrawl_get_array_value( $options, 'access-id' );
		$moz_secret_key = smartcrawl_get_array_value( $options, 'secret-key' );
		$moz_active     = $moz_access_id && $moz_secret_key;

		$robots_controller = Smartcrawl_Controller_Robots::get();
		$robots_active     = $robots_controller->robots_active();

		return join(
			"\n",
			array(
				esc_attr__( 'Automatic Links - ', 'smartcrawl-seo' ) . $this->get_status_string( $autolinks_active ),
				esc_attr__( 'URL Redirection - ', 'smartcrawl-seo' ) . $this->get_status_string( $redirects_count ),
				esc_attr__( 'Moz - ', 'smartcrawl-seo' ) . $this->get_status_string( $moz_active ),
				esc_attr__( 'Robots.txt Editor - ', 'smartcrawl-seo' ) . $this->get_status_string( $robots_active ),
			)
		);
	}

	private function get_status_string( $active ) {
		return $active
			? esc_html__( 'Active', 'smartcrawl-seo' )
			: esc_html__( 'Inactive', 'smartcrawl-seo' );
	}

	private function prepare_settings_strings() {
		$options                      = Smartcrawl_Settings::get_options();
		$seo_analysis_enabled         = (bool) smartcrawl_get_array_value( $options, 'analysis-seo' );
		$readability_analysis_enabled = (bool) smartcrawl_get_array_value( $options, 'analysis-readability' );
		$keep_settings_on_uninstall   = (bool) smartcrawl_get_array_value( $options, 'keep_settings_on_uninstall' );
		$keep_data_on_uninstall       = (bool) smartcrawl_get_array_value( $options, 'keep_data_on_uninstall' );
		$high_contrast                = (bool) smartcrawl_get_array_value( $options, 'high-contrast' );

		return join(
			"\n",
			array(
				esc_attr__( 'In-Post Page Analysis - ', 'smartcrawl-seo' ) . $this->get_status_string( $seo_analysis_enabled ),
				esc_attr__( 'In-Post Readability Analysis - ', 'smartcrawl-seo' ) . $this->get_status_string( $readability_analysis_enabled ),
				esc_attr__( 'Preserve settings on uninstall - ', 'smartcrawl-seo' ) . $this->get_status_string( $keep_settings_on_uninstall ),
				esc_attr__( 'Keep data on uninstall - ', 'smartcrawl-seo' ) . $this->get_status_string( $keep_data_on_uninstall ),
				esc_attr__( 'High Contrast Mode - ', 'smartcrawl-seo' ) . $this->get_status_string( $high_contrast ),
			)
		);
	}

	private function prepare_lighthouse_reporting_status() {
		if ( ! Smartcrawl_Lighthouse_Options::is_cron_enabled() ) {
			return esc_html__( 'Inactive', 'smartcrawl-seo' );
		}

		$recipients = Smartcrawl_Lighthouse_Options::email_recipients();
		$frequency  = smartcrawl_get_array_value(
			Smartcrawl_Controller_Cron::get()->get_frequencies(),
			Smartcrawl_Lighthouse_Options::reporting_frequency()
		);

		return sprintf(
			/* translators: 1: Frequency, 2: Receipients */
			esc_html__( 'Active and sending %1$s to %2$d recipients', 'smartcrawl-seo' ),
			$frequency,
			count( $recipients )
		);
	}

	/**
	 * @return null
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * @param string $id ID.
	 *
	 * @return Smartcrawl_Config_Model
	 */
	public function set_id( $id ) {
		$this->id = $id;
		return $this;
	}

	/**
	 * @return $this
	 */
	public function refresh_id() {
		return $this->set_id( uniqid() );
	}

	/**
	 * @return string|null
	 */
	public function get_hub_id() {
		return $this->hub_id;
	}

	/**
	 * @param int $hub_id Hub ID.
	 *
	 * @return Smartcrawl_Config_Model
	 */
	public function set_hub_id( $hub_id ) {
		$this->hub_id = $hub_id;
		return $this;
	}

	/**
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * @param string $name Name.
	 *
	 * @return Smartcrawl_Config_Model
	 */
	public function set_name( $name ) {
		$this->name = sanitize_text_field( $name );
		return $this;
	}

	/**
	 * @return string
	 */
	public function get_description() {
		return $this->description;
	}

	/**
	 * @param string $description Description.
	 *
	 * @return Smartcrawl_Config_Model
	 */
	public function set_description( $description ) {
		$this->description = sanitize_text_field( $description );
		return $this;
	}

	/**
	 * @return array
	 */
	public function get_configs() {
		return $this->configs;
	}

	/**
	 * @param array $configs Configs.
	 *
	 * @return Smartcrawl_Config_Model
	 */
	public function set_configs( $configs ) {
		$this->configs = $configs;
		return $this;
	}

	/**
	 * @return array
	 */
	public function get_strings() {
		return $this->strings;
	}

	/**
	 * @param array $strings
	 *
	 * @return Smartcrawl_Config_Model
	 */
	public function set_strings( $strings ) {
		$this->strings = $strings;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function is_editable() {
		return $this->editable;
	}

	/**
	 * @param bool $editable Is editable.
	 *
	 * @return Smartcrawl_Config_Model
	 */
	public function set_editable( $editable ) {
		$this->editable = $editable;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function is_removable() {
		return $this->removable;
	}

	/**
	 * @param bool $removable Is removable.
	 *
	 * @return Smartcrawl_Config_Model
	 */
	public function set_removable( $removable ) {
		$this->removable = $removable;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function is_official() {
		return $this->official;
	}

	/**
	 * @param bool $official Is official.
	 *
	 * @return Smartcrawl_Config_Model
	 */
	public function set_official( $official ) {
		$this->official = $official;
		return $this;
	}

	/**
	 * @return int
	 */
	public function get_timestamp() {
		return $this->timestamp;
	}

	/**
	 * @param int $timestamp Timestamp.
	 *
	 * @return Smartcrawl_Config_Model
	 */
	public function set_timestamp( $timestamp ) {
		$this->timestamp = $timestamp;
		return $this;
	}

	public function get_filename() {
		return 'smartcrawl-config-' . str_replace( ' ', '-', $this->get_name() );
	}

	public static function inflate( $data ) {
		return ( new self() )
			->set_id( smartcrawl_get_array_value( $data, 'id' ) )
			->set_hub_id( (int) smartcrawl_get_array_value( $data, 'hub_id' ) )
			->set_name( smartcrawl_get_array_value( $data, 'name' ) )
			->set_description( smartcrawl_get_array_value( $data, 'description' ) )
			->set_configs( smartcrawl_get_array_value( $data, 'configs' ) )
			->set_strings( smartcrawl_get_array_value( $data, 'strings' ) )
			->set_editable( (bool) smartcrawl_get_array_value( $data, 'editable' ) )
			->set_removable( (bool) smartcrawl_get_array_value( $data, 'removable' ) )
			->set_official( (bool) smartcrawl_get_array_value( $data, 'official' ) )
			->set_timestamp( (int) smartcrawl_get_array_value( $data, 'timestamp' ) );
	}

	public function deflate() {
		return array(
			'id'          => $this->get_id(),
			'hub_id'      => $this->get_hub_id(),
			'name'        => $this->get_name(),
			'description' => $this->get_description(),
			'configs'     => $this->get_configs(),
			'strings'     => $this->get_strings(),
			'editable'    => $this->is_editable(),
			'removable'   => $this->is_removable(),
			'official'    => $this->is_official(),
			'timestamp'   => $this->get_timestamp(),
		);
	}
}
