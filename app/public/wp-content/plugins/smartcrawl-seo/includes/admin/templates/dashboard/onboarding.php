<?php
$this->render_view(
	'modal',
	array(
		'id'                      => 'wds-onboarding',
		'title'                   => esc_html__( 'Quick setup', 'smartcrawl-seo' ),
		'description'             => esc_html__( "Welcome to SmartCrawl, the hottest SEO plugin for WordPress! Let's quickly set up the basics for you, then you can fine tune each setting as you go - our recommendations are on by default.", 'smartcrawl-seo' ),
		'header_actions_template' => 'dashboard/onboard-modal-header-button',
		'body_template'           => 'dashboard/onboard-modal-body',
		'footer_template'         => 'dashboard/onboard-modal-footer',
	)
);
