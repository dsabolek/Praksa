<?php

$this->render_view(
	'disabled-component',
	array(
		'content'     => sprintf(
			'%s<br/>%s',
			esc_html__( 'Automatically generate a full sitemap, regularly send updates to search engines and set up', 'smartcrawl-seo' ),
			esc_html__( 'SmartCrawl to automatically check URLs are discoverable by search engines.', 'smartcrawl-seo' )
		),
		'image'       => 'sitemap-disabled.svg',
		'component'   => 'sitemap',
		'button_text' => esc_html__( 'Activate Sitemap', 'smartcrawl-seo' ),
	)
);
