<?php

class Smartcrawl_Check_Metadesc_Length extends Smartcrawl_Check_Post_Abstract {

	/**
	 * Holds metadesc length
	 *
	 * @var int
	 */
	private $length;

	/**
	 * Holds check state
	 *
	 * @var int
	 */
	private $state;

	public function get_status_msg() {
		if ( ! is_numeric( $this->state ) ) {
			return __( 'Your meta description is a good length', 'smartcrawl-seo' );
		}

		return 0 === $this->state
			? __( "You haven't specified a meta description yet", 'smartcrawl-seo' )
			: ( $this->state > 0
				/* translators: %d: Maximum length of characters */
				? sprintf( __( 'Your meta description is greater than %d characters', 'smartcrawl-seo' ), $this->get_max() )
				/* translators: %d: Minimum length of characters */
				: sprintf( __( 'Your meta description is less than %d characters', 'smartcrawl-seo' ), $this->get_min() )
			);
	}

	public function get_max() {
		return smartcrawl_metadesc_max_length();
	}

	public function get_min() {
		return smartcrawl_metadesc_min_length();
	}

	public function apply() {
		$post = $this->get_subject();

		if ( ! is_object( $post ) || empty( $post->ID ) ) {
			$subject = $this->get_markup();
		} else {
			$smartcrawl_post = Smartcrawl_Post_Cache::get()->get_post( $post->ID );
			$subject         = $smartcrawl_post
				? $smartcrawl_post->get_meta_description()
				: '';
		}

		$this->state  = $this->is_within_char_length( $subject, $this->get_min(), $this->get_max() );
		$this->length = Smartcrawl_String_Utils::len( $subject );

		return ! is_numeric( $this->state );
	}

	public function apply_html() {
		$subjects = Smartcrawl_Html::find_attributes( 'meta[name="description"]', 'content', $this->get_markup() );
		if ( empty( $subjects ) ) {
			$this->length = 0;
			$this->state  = 0;

			return false;
		}

		$subject      = reset( $subjects );
		$this->state  = $this->is_within_char_length( $subject, $this->get_min(), $this->get_max() );
		$this->length = Smartcrawl_String_Utils::len( $subject );

		return ! is_numeric( $this->state );
	}

	public function get_recommendation() {
		if ( ! is_numeric( $this->state ) ) {
			return __( 'Your SEO description is a good length. Having an SEO description that is either too long or too short can harm your chances of ranking highly for this article.', 'smartcrawl-seo' );
		}

		return 0 === $this->state
			? __( "Because you haven't specified a meta description (or excerpt), search engines will automatically generate one using your content. While this is OK, you should create your own meta description making sure it contains your focus keywords.", 'smartcrawl-seo' )
			: ( $this->state > 0
				? __( "Your SEO description (or excerpt) is currently too long. Search engines generally don't like long descriptions and after a certain length the value of extra keywords drops significantly.", 'smartcrawl-seo' )
				: __( 'Your SEO description (or excerpt) is currently too short which means it has less of a chance ranking for your chosen focus keywords.', 'smartcrawl-seo' )
			);
	}

	public function get_more_info() {
		return sprintf(
			/* translators: 1,2: Recommended range of characters */
			__( 'We recommend keeping your meta descriptions between %1$d and %2$d characters (including spaces). Doing so achieves a nice balance between populating your description with keywords to rank highly in search engines, and also keeping it to a readable length that won\'t be cut off in search engine results. Unfortunately there isn\'t a rule book for SEO meta descriptions, just remember to make your description great for SEO, but also (most importantly) readable and enticing for potential visitors to click on.', 'smartcrawl-seo' ),
			$this->get_min(),
			$this->get_max()
		);
	}
}
