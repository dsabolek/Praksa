<?php
/**
 * No follow links check for external links.
 *
 * @since   3.4.0
 * @package Smartcrawl
 */

/**
 * Class Smartcrawl_Check_No_Follow_Links
 */
class Smartcrawl_Check_Nofollow_Links extends Smartcrawl_Check_Abstract {

	/**
	 * Holds check state
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
		return false === $this->state ?
			__( 'Nofollow external links', 'smartcrawl-seo' ) :
			__( 'A dofollow external link(s) was found', 'smartcrawl-seo' );
	}

	/**
	 * Apply the check to subject.
	 *
	 * @return bool
	 */
	public function apply() {
		$links = Smartcrawl_Html::find( 'a', $this->get_markup() );
		// If no link we don't need it.
		if ( empty( $links ) ) {
			$this->set_hidden();
			$this->state = true;

			return true;
		}

		$external_links          = 0;
		$external_nofollow_links = 0;

		foreach ( $links as $link ) {
			$url = $link->getAttribute( 'href' );
			$rel = $link->getAttribute( 'rel' );
			// Regex for external links.
			$regex = sprintf( '/^(?:%s|#|\/)/i', preg_quote( untrailingslashit( site_url() ), '/' ) );
			if ( ! preg_match( $regex, $url ) ) {
				$external_links ++;
				// If nofollow.
				if ( strpos( $rel, 'nofollow' ) !== false ) {
					$external_nofollow_links ++;
				}
			}
		}

		// No external links.
		if ( $external_links <= 0 ) {
			$this->set_hidden();
			$this->state = true;

			return true;
		}

		// The count should be different.
		$this->state = $external_nofollow_links !== $external_links;

		return $this->state;
	}

	/**
	 * Get the recommendation texts.
	 *
	 * @since 3.4.0
	 *
	 * @return string
	 */
	public function get_recommendation() {
		return false === $this->state ?
			__( 'We detected that all external links on this page are nofollow links. We recommend adding at least one external dofollow link to your content.', 'smartcrawl-seo' ) :
			__( 'At least one dofollow external link was found on the content of this page. Good job!', 'smartcrawl-seo' );
	}

	/**
	 * Get more info text.
	 *
	 * @since 3.4.0
	 *
	 * @return string
	 */
	public function get_more_info() {
		return __( 'It might feel absurd to link to external web pages as it will redirect your traffic to another site. However, adding relevant outbound links helps improve your credibility, gives your user more value, and helps search engines determine the usefulness and quality of your content.', 'smartcrawl-seo' );
	}
}
