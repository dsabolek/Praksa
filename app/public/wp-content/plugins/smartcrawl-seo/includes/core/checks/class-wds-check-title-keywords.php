<?php
/**
 * Title keyword check.
 *
 * @since   3.4.0
 * @package Smartcrawl
 */

/**
 * Class Smartcrawl_Check_Title_Keywords
 */
class Smartcrawl_Check_Title_Keywords extends Smartcrawl_Check_Post_Abstract {

	/**
	 * Holds check state
	 *
	 * @since 3.4.0
	 *
	 * @var int|bool
	 */
	protected $state;

	/**
	 * Get the message for the check.
	 *
	 * @since 3.4.0
	 *
	 * @return string
	 */
	public function get_status_msg() {
		if ( - 1 === $this->state ) {
			return __( 'We couldn\'t find a title to check for keywords', 'smartcrawl-seo' );
		}

		return false === $this->state
			? __( "Your focus keyword(s) aren't used in the SEO title", 'smartcrawl-seo' )
			: __( 'The SEO title contains your focus keyword(s)', 'smartcrawl-seo' );
	}

	/**
	 * Apply check to the subject.
	 *
	 * @return bool
	 */
	public function apply() {
		$post = $this->get_subject();

		if ( ! is_object( $post ) || empty( $post->ID ) ) {
			$subject = $this->get_markup();
		} else {
			$smartcrawl_post = Smartcrawl_Post_Cache::get()->get_post( $post->ID );
			$subject         = $smartcrawl_post
				? $smartcrawl_post->get_meta_title()
				: '';
		}

		$this->state = $this->has_focus( $subject );

		return ! ! $this->state;
	}

	/**
	 * Apply title focus check.
	 *
	 * @return bool
	 */
	public function apply_html() {
		$titles = Smartcrawl_Html::find_content( 'title', $this->get_markup() );
		if ( empty( $titles ) ) {
			$this->state = - 1;

			return false;
		}

		$title = reset( $titles );
		if ( empty( $title ) ) {
			$this->state = - 1;

			return false;
		}

		$this->state = $this->has_focus( $title );

		return ! ! $this->state;
	}

	/**
	 * Get the recommendation texts.
	 *
	 * @return string
	 */
	public function get_recommendation() {
		if ( $this->state ) {
			return __( "You've got your focus keyword(s) in the SEO title meaning it has the best chance of matching what users are searching for first up - nice work.", 'smartcrawl-seo' );
		} else {
			return __( "The focus keyword(s) for this article doesn't appear in the SEO title which means it has less of a chance of matching what your visitors will search for.", 'smartcrawl-seo' );
		}
	}

	/**
	 * Get more info text.
	 *
	 * @return string
	 */
	public function get_more_info() {
		return __( "It's considered good practice to try to include your focus keyword(s) in the SEO title of a page because this is what people looking for the article are likely searching for. The higher chance of a keyword match, the greater the chance that your article will be found higher up in search results. Whilst it's recommended to try and get these words in, don't sacrifice readability and the quality of the SEO title just to rank higher - people may not want to click on it if it doesn't read well.", 'smartcrawl-seo' );
	}
}
