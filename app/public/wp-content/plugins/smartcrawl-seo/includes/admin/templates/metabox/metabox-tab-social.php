<?php
$default_settings_message = smartcrawl_format_link(
	/* translators: %s: Link to Titles & Meta page */
	esc_html__( "Customize this posts title, description and featured images for social shares. You can also configure the default settings for this post type in SmartCrawl's %s area.", 'smartcrawl-seo' ),
	Smartcrawl_Settings_Admin::admin_url( Smartcrawl_Settings::TAB_ONPAGE ),
	esc_html__( 'Titles & Meta', 'smartcrawl-seo' )
);
$social_sections = empty( $social_sections ) ? array() : $social_sections;
if ( empty( $social_sections ) ) {
	return;
}
?>
<div class="wds-metabox-section wds-social-settings-metabox-section sui-box-body">
	<p><?php echo wp_kses_post( $default_settings_message ); ?></p>

	<?php
	foreach ( $social_sections as $template => $args ) {
		$this->render_view( $template, $args );
	}
	?>
</div>
