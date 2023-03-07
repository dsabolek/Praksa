<?php
/**
 * Class to check subheading keywords.
 *
 * @package wpmu-dev-seo
 */

/**
 * Class to check subheading keywords.
 */
class Smartcrawl_Check_Subheadings_Keywords extends Smartcrawl_Check_Abstract {

	/**
	 * Status of check result.
	 *
	 * @var bool|null
	 */
	private $state = null;

	/**
	 * Number of subheadings with focus keywords.
	 *
	 * @var int
	 */
	private $count;

	/**
	 * Returns status message.
	 *
	 * @return string
	 */
	public function get_status_msg() {
		if ( is_null( $this->state ) ) {
			return __( "You don't have any subheadings", 'smartcrawl-seo' );
		}

		if ( $this->is_primary_keyword() ) {
			return false === $this->state
				? __( 'You haven\'t used your primary keyword in any subheadings', 'smartcrawl-seo' )
				/* translators: %d: Subheading count */
				: sprintf( __( 'Your primary keyword was found in %d subheadings', 'smartcrawl-seo' ), $this->count );
		} else {
			return false === $this->state
				? __( 'You haven\'t used this secondary keyword in any subheadings.', 'smartcrawl-seo' )
				/* translators: %d: Subheading count */
				: sprintf( __( 'This secondary keyword was found in %d subheading(s).', 'smartcrawl-seo' ), $this->count );
		}
	}

	/**
	 * Applies to get check result.
	 *
	 * @return bool
	 */
	public function apply() {
		$subjects = Smartcrawl_Html::find_content( 'h1,h2,h3,h4,h5,h6', $this->get_markup() );
		if ( empty( $subjects ) ) {
			return false;
		} // No subheadings, nothing to check.

		$count = 0;
		foreach ( $subjects as $subject ) {
			/**
			 * Convert subject into plain text to strip tags
			 */
			if ( $this->has_focus( Smartcrawl_Html::plaintext( $subject ) ) ) {
				$count ++;
			}
		}

		$this->state = (bool) $count;
		$this->count = $count;

		return ! ! $this->state;
	}

	/**
	 * Retrieves recommendation message.
	 *
	 * @return string
	 */
	public function get_recommendation() {
		if ( is_null( $this->state ) ) {
			$message = __( "Using subheadings in your content (such as H2's or H3's) will help both the user and search engines quickly figure out what your article is about. It also helps visually section your content which in turn is great user experience. We recommend you have at least one subheading.", 'smartcrawl-seo' );
		} elseif ( $this->state ) {
			if ( $this->is_primary_keyword() ) {
				/* translators: %d: Subheading count */
				$message = sprintf( __( "You've used this keyword in %d of your subheadings which will help both the user and search engines quickly figure out what your article is about, good work!", 'smartcrawl-seo' ), $this->count );
			} else {
				/* translators: %d: Subheading count */
				$message = sprintf( __( 'You\'ve used this secondary keyword in %d subheading(s), which will help the user and search engines quickly figure out the content on your page. Good work!', 'smartcrawl-seo' ), $this->count );
			}
		} else {
			if ( $this->is_primary_keyword() ) {
				$message = __( "Using keywords in any of your subheadings (such as H2's or H3's) will help both the user and search engines quickly figure out what your article is about. It's best practice to include your focus keywords in at least one subheading if you can.", 'smartcrawl-seo' );
			} else {
				$message = __( 'You have not used this secondary keyword in any of your subheadings. It\'s best practice to include your secondary keywords in at least one subheading if possible.', 'smartcrawl-seo' );
			}
		}

		return $message;
	}

	/**
	 * Retrieves more info message.
	 *
	 * @return string
	 */
	public function get_more_info() {
		return __( "When trying to rank for certain keywords, those keywords should be found in as many key places as possible. Given that you're writing about the topic it only makes sense that you mention it in at least one of your subheadings. Headings are important for users as they break up your content and help readers figure out what the text is about. Same goes for search engines. With that said, don't force keywords into all your titles - keep it natural, readable, and use moderation!", 'smartcrawl-seo' );
	}
}

