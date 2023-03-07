<?php
$sitemap_available  = Smartcrawl_Settings_Admin::is_tab_allowed( Smartcrawl_Settings::TAB_SITEMAP );
$social_available   = Smartcrawl_Settings_Admin::is_tab_allowed( Smartcrawl_Settings::TAB_SOCIAL );
$service            = Smartcrawl_Service::get( Smartcrawl_Service::SERVICE_SITE );
$robots_file_exists = Smartcrawl_Controller_Robots::get()->file_exists();
// Check if current language is supported for readability analysis.
$lang_supported = Smartcrawl_Controller_Readability::get()->is_language_supported();
?>

<div class="wds-separator-top">
	<?php
	$this->render_view(
		'toggle-item',
		array(
			'field_name'       => 'analysis-enable',
			'item_label'       => esc_html__( 'SEO & Readability Analysis', 'smartcrawl-seo' ),
			'item_description' => esc_html__( 'Have your pages and posts analyzed for SEO and readability improvements to improve your search ranking', 'smartcrawl-seo' ),
			'checked'          => true,
			'attributes'       => array(
				'data-processing' => esc_attr__( 'Activating SEO & Readability Analysis', 'smartcrawl-seo' ),
			),
		)
	);
	if ( ! $lang_supported ) {
		$this->render_view(
			'notice',
			array(
				'class'   => 'sui-notice-yellow',
				'message' => sprintf(
				// translators: %s link to documentation.
					__( 'This feature may not work as expected as our SEO analysis engine doesn\'t support your current site language. For better results, change the language in WordPress settings to one of the <a href="%s" target="_blank">supported languages</a>.', 'smartcrawl-seo' ),
					'https://wpmudev.com/docs/wpmu-dev-plugins/smartcrawl/#in-post-analysis'
				),
			)
		);
	}
	?>
</div>

<?php if ( $sitemap_available ) : ?>
	<div class="wds-separator-top">
		<?php
		$this->render_view(
			'toggle-item',
			array(
				'field_name'       => 'sitemaps-enable',
				'item_label'       => esc_html__( 'Sitemaps', 'smartcrawl-seo' ),
				'item_description' => esc_html__( 'Sitemaps expose your site content to search engines and allow them to discover it more easily.', 'smartcrawl-seo' ),
				'checked'          => true,
				'attributes'       => array(
					'data-processing' => esc_attr__( 'Activating Sitemaps', 'smartcrawl-seo' ),
				),
			)
		);
		?>
	</div>
<?php endif; ?>

<div class="wds-separator-top">
	<?php
	$robots_attributes = array(
		'data-processing' => esc_attr__( 'Activating Robots.txt file', 'smartcrawl-seo' ),
	);
	if ( $robots_file_exists ) {
		$robots_attributes['disabled'] = 'disabled';
	}
	$this->render_view(
		'toggle-item',
		array(
			'field_name'       => 'robots-txt-enable',
			'item_label'       => esc_html__( 'Robots.txt File', 'smartcrawl-seo' ),
			'item_description' => esc_html__( 'All sites are recommended to have a robots.txt file that instructs search engines what they can and can’t crawl. We will create a default robots.txt file which you can customize later.', 'smartcrawl-seo' ),
			'checked'          => ! $robots_file_exists,
			'attributes'       => $robots_attributes,
		)
	);
	if ( $robots_file_exists ) {
		$this->render_view(
			'notice',
			array(
				'message' => smartcrawl_format_link(
					// translators: %s link to robots.txt file.
					esc_html__( "We've detected an existing %s file that we are unable to edit. You will need to remove it before you can enable this feature.", 'smartcrawl-seo' ),
					smartcrawl_get_robots_url(),
					'robots.txt',
					'_blank'
				),
			)
		);
	}
	?>
</div>

<?php if ( $social_available ) : ?>
	<div class="wds-separator-top">
		<?php
		$this->render_view(
			'toggle-item',
			array(
				'field_name'       => 'opengraph-twitter-enable',
				'item_label'       => esc_html__( 'OpenGraph & Twitter Cards', 'smartcrawl-seo' ),
				'item_description' => esc_html__( 'Enhance how your posts and pages look when shared on Twitter and Facebook by adding extra meta tags to your page output.', 'smartcrawl-seo' ),
				'checked'          => true,
				'attributes'       => array(
					'data-processing' => esc_attr__( 'Activating OpenGraph & Twitter Cards', 'smartcrawl-seo' ),
				),
			)
		);
		?>
	</div>
<?php endif; ?>
