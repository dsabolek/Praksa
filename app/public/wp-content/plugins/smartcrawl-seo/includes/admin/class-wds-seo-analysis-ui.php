<?php
/**
 * SEO analysis meta-box module.
 *
 * @link    http://wpmudev.com
 * @package SmartCrawl
 */

/**
 * Class Smartcrawl_SEO_Analysis_UI
 *
 * @package SmartCrawl
 */
class Smartcrawl_SEO_Analysis_UI extends Smartcrawl_Base_Controller {

	use Smartcrawl_Singleton;

	/**
	 * Check if this module should rune.
	 *
	 * @return bool
	 */
	public function should_run() {
		return Smartcrawl_Settings::get_setting( 'analysis-seo' );
	}

	/**
	 * Init the module.
	 *
	 * @return void
	 */
	protected function init() {
		add_filter( 'wds-sections-metabox-seo', array( $this, 'add_analysis_section' ), 10, 2 );
		add_filter( 'wds-metabox-nav-item', array( $this, 'add_issue_count' ), 10, 2 );
	}

	/**
	 * Add analysis section to metabox.
	 *
	 * @param array        $sections Sections.
	 * @param null|WP_Post $post     Post object.
	 *
	 * @return array
	 */
	public function add_analysis_section( $sections, $post = null ) {
		if ( empty( $post ) ) {
			return $sections;
		}

		$focus_keywords = array();
		if ( ! empty( $post->ID ) ) {
			$smartcrawl_post = Smartcrawl_Post_Cache::get()->get_post( $post->ID );
			$focus_keywords  = $smartcrawl_post ? $smartcrawl_post->get_focus_keywords() : array();
		}

		$sections['metabox/metabox-seo-analysis-container'] = array(
			'post'           => $post,
			'focus_keywords' => $focus_keywords,
		);

		return $sections;
	}

	/**
	 * Add issue count.
	 *
	 * @param string $tab_name Tab name.
	 * @param string $tab_id   Tab ID.
	 *
	 * @return string
	 */
	public function add_issue_count( $tab_name, $tab_id ) {
		return 'wds_seo' === $tab_id
			? $tab_name . '<span class="wds-issues"><span></span></span>'
			: $tab_name;
	}
}
