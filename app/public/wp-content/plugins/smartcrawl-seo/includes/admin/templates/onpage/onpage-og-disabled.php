<?php
$message = esc_html__( 'OpenGraph is globally disabled.', 'smartcrawl-seo' );
if ( Smartcrawl_Settings_Admin::is_tab_allowed( Smartcrawl_Settings::TAB_SOCIAL ) ) {
	$social_page = Smartcrawl_Settings_Admin::admin_url( Smartcrawl_Settings::TAB_SOCIAL );
	$message     = sprintf(
		/* translators: 1: Message, 2: Anchor tag to Open Graph page */
		esc_html__( '%1$s You can enable it %2$s.', 'smartcrawl-seo' ),
		$message,
		sprintf(
			'<a href="%s">%s</a>',
			esc_url_raw( add_query_arg( 'tab', 'tab_open_graph', $social_page ) ),
			esc_html__( 'here', 'smartcrawl-seo' )
		)
	);
}

$this->render_view(
	'notice',
	array(
		'class'   => 'sui-notice-info',
		'message' => $message,
	)
);
