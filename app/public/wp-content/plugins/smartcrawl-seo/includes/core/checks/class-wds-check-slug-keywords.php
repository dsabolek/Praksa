<?php
/**
 * Class to check slug keywords.
 *
 * @package wpmu-dev-seo
 */

/**
 * Class to check slug keywords.
 */
class Smartcrawl_Check_Slug_Keywords extends Smartcrawl_Check_Post_Abstract {

	/**
	 * Status of check result.
	 *
	 * @var bool|null
	 */
	private $state;

	/**
	 * Prepare focus keywords.
	 *
	 * @param array $keywords Keywords as an array.
	 *
	 * @return array
	 */
	protected function prepare_focus( $keywords ) {
		$kwds = array();
		foreach ( $keywords as $k ) {
			$keyword_string = new Smartcrawl_String( $k, $this->get_language() );
			$kwds           = array_merge( $kwds, $keyword_string->get_keywords() );
		}

		return array_map( 'sanitize_title', array_unique( array_keys( $kwds ) ) );
	}

	/**
	 * Returns status message.
	 *
	 * @return string
	 */
	public function get_status_msg() {
		return false === $this->state
			? __( "You haven't used your focus keywords in the page URL", 'smartcrawl-seo' )
			: __( "You've used your focus keyword in the page URL", 'smartcrawl-seo' );
	}

	/**
	 * Applies to get check result.
	 *
	 * @return bool
	 */
	public function apply() {
		$text    = $this->get_markup();
		$subject = join( ' ', preg_split( '/[\-_]/', $text ) );

		$this->state = $this->has_focus( $subject );

		return ! ! $this->state;
	}

	/**
	 * Retrieves subject markup.
	 *
	 * @return string
	 */
	public function get_markup() {
		$subject = $this->get_subject();

		if ( is_object( $subject ) ) {
			if ( ! empty( $subject->ID ) && wp_is_post_revision( $subject->ID ) ) {
				$post = get_post( wp_is_post_revision( $subject->ID ) );
			} else {
				$post = $subject;
			}
			if ( function_exists( 'get_sample_permalink' ) ) {
				list( , $draft_name ) = get_sample_permalink( $post->ID );
			} else {
				$draft_name = '';
			}
			$post_name = $post->post_name;
			$subject   = ! empty( $post_name ) ? $post_name : $draft_name;
		}

		return $subject;
	}

	/**
	 * Retrieves recommendation message.
	 *
	 * @return string
	 */
	public function get_recommendation() {
		if ( $this->state ) {
			$message = __( "You've got your focus keywords in the page slug which can help your page rank as you have a higher chance of matching search terms, and Google does index your page URL, great stuff!", 'smartcrawl-seo' );
		} else {
			$message = __( 'Google does index your page URL. Using your focus keywords in the page slug can help your page rank as you have a higher chance of matching search terms. Try getting your focus keywords in there.', 'smartcrawl-seo' );
		}

		return $message;
	}

	/**
	 * Retrieves more info message.
	 *
	 * @return string
	 */
	public function get_more_info() {
		return __( "The page URL you use for this post will be visible in search engine results, so it's important to also include words that the searcher is looking for (your focus keywords). It's debatable whether keywords in the slug are of any real search engine ranking benefit. One could assume that because the slug does get indexed, the algorithm may favour slugs more closely aligned with the topic being searched.", 'smartcrawl-seo' );
	}
}
