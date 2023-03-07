<?php
/**
 * Class to check paragraph keywords.
 *
 * @package wpmu-dev-seo
 */

/**
 * Class to check paragraph keywords.
 */
class Smartcrawl_Check_Para_Keywords extends Smartcrawl_Check_Abstract {

	/**
	 * Status of check result.
	 *
	 * @var bool|null
	 */
	private $state;

	/**
	 * Returns status message.
	 *
	 * @return string
	 */
	public function get_status_msg() {
		return false === $this->state
			? __( "You haven't included the focus keywords in the first paragraph of your article", 'smartcrawl-seo' )
			: __( 'The focus keyword appears in the first paragraph of your article', 'smartcrawl-seo' );
	}

	/**
	 * Applies to get check result.
	 *
	 * @return bool
	 */
	public function apply() {
		$raw     = $this->get_markup();
		$content = wp_strip_all_tags( $raw );
		if ( ! ( $content ) ) {
			$this->state = false;

			return false;
		}

		$subjects = Smartcrawl_Html::find_content( 'p', $raw );
		if ( empty( $subjects ) ) {
			$this->state = true;

			return true;
		} // No paragraphs whatsoever, nothing to check.

		$subject = reset( $subjects );
		if ( empty( $subject ) ) {
			$this->state = false;

			return false;
		} // First paragraph empty, this fails.

		/**
		 * Convert subject into plain text to strip tags
		 */
		$this->state = $this->has_focus( Smartcrawl_Html::plaintext( $subject ) );

		return ! ! $this->state;
	}

	/**
	 * Retrieves recommendation message.
	 *
	 * @return string
	 */
	public function get_recommendation() {
		if ( $this->state ) {
			$message = __( "You've included your focus keywords in the first paragraph of your content, which will help search engines and visitors quickly scope the topic of your article. Well done!", 'smartcrawl-seo' );
		} else {
			$message = __( "It's good practice to include your focus keywords in the first paragraph of your content so that search engines and visitors can quickly scope the topic of your article.", 'smartcrawl-seo' );
		}

		return $message;
	}

	/**
	 * Retrieves more info message.
	 *
	 * @return string
	 */
	public function get_more_info() {
		return __( 'You should clearly formulate what your post is about in the first paragraph. In printed texts, a writer usually starts off with some kind of teaser, but there is no time for that if you are writing for the web. You only have seconds to gain your reader’s attention. Make sure the first paragraph tells the main message of your post. That way, you make it easy for your reader to figure out what your post is about. Doing this also tells Google what your post is about. Don’t forget to put your focus keyword in that first paragraph!', 'smartcrawl-seo' );
	}
}
