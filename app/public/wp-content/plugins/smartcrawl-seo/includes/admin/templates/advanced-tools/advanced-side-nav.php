<?php
$active_tab = empty( $active_tab ) ? '' : $active_tab;

$this->render_view(
	'vertical-tabs-side-nav',
	array(
		'active_tab' => $active_tab,
		'tabs'       => array_merge(
			array(
				array(
					'id'   => 'tab_automatic_linking',
					'name' => esc_html__( 'Automatic Links', 'smartcrawl-seo' ),
				),
				array(
					'id'   => 'tab_url_redirection',
					'name' => esc_html__( 'URL Redirection', 'smartcrawl-seo' ),
				),
			),
			smartcrawl_woocommerce_active() ? array(
				array(
					'id'   => 'tab_woo',
					'name' => esc_html__( 'WooCommerce SEO', 'smartcrawl-seo' ),
				),
			) : array(),
			array(
				array(
					'id'   => 'tab_moz',
					'name' => esc_html__( 'Moz', 'smartcrawl-seo' ),
				),
				array(
					'id'   => 'tab_robots_editor',
					'name' => esc_html__( 'Robots.txt Editor', 'smartcrawl-seo' ),
				),
			)
		),
	)
);
