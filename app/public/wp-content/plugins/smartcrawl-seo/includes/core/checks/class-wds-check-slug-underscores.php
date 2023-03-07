<?php
/**
 * Underscores check for page URLs.
 *
 * @since   3.4.0
 * @package Smartcrawl
 */

/**
 * Class Smartcrawl_Check_Slug_Underscores
 */
class Smartcrawl_Check_Slug_Underscores extends Smartcrawl_Check_Post_Abstract {

	/**
	 * Holds state reference
	 *
	 * @var bool
	 */
	private $state;

	/**
	 * Get the message for the check.
	 *
	 * @since 3.4.0
	 *
	 * @return string
	 */
	public function get_status_msg() {
		return false === $this->state
			? __( 'Your URL contains underscores', 'smartcrawl-seo' )
			: __( 'Your URL doesn’t contain underscores', 'smartcrawl-seo' );
	}

	/**
	 * Apply check to the subject.
	 *
	 * @since 3.4.0
	 *
	 * @return bool
	 */
	public function apply() {
		$this->state = false === strpos( $this->get_markup(), '_' );

		return $this->state;
	}

	/**
	 * Get markup data for the check.
	 *
	 * @since 3.4.0
	 *
	 * @return mixed|string|WP_Post
	 */
	public function get_markup() {
		$post_id = $this->get_post_id();
		// Get parent ID if post revision.
		$post_parent = wp_is_post_revision( $post_id );
		// If it's a revision use parent post ID.
		if ( $post_parent ) {
			$post_id = $post_parent;
		}

		if ( function_exists( 'get_sample_permalink' ) ) {
			list( , $name ) = get_sample_permalink( $post_id );

			return $name;
		}

		return '';
	}

	/**
	 * Get the recommendation texts.
	 *
	 * @since 3.4.0
	 *
	 * @return string|null
	 */
	public function get_recommendation() {
		return false === $this->state
			? sprintf(
			// translators: %1$s current post url, %2$s link to documentation.
				__( 'We have detected one or more underscores in the URL {%1$s}. Please consider removing them or replacing them with a hyphen (-). However, if you have already published this page, we don\'t recommend removing the underscores (_) as it can cause short-term ranking loss. If you decide to remove the underscores in the URL of a published page, set up a 301 Redirect using the URL Redirection tool to direct traffic to the new URL. Learn more about <strong>URL Redirection</strong> on our <a href="%2$s" target="_blank">documentation</a>.', 'smartcrawl-seo' ),
				$this->get_markup(),
				'https://wpmudev.com/docs/wpmu-dev-plugins/smartcrawl/#url-redirection'
			)
			: sprintf(
			// translators: %1$s current post url.
				__( 'We didn’t detect underscores in your page URL {%s}. Good job!', 'smartcrawl-seo' ),
				$this->get_markup()
			);
	}

	/**
	 * Get more info text.
	 *
	 * @since 3.4.0
	 *
	 * @return string|null
	 */
	public function get_more_info() {
		return __( 'Google recommends using hyphens to separate words in the URLs instead of underscores, which helps search engines easily identify the page topic.', 'smartcrawl-seo' );
	}
}
