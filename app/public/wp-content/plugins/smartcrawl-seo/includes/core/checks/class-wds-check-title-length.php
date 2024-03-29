<?php

class Smartcrawl_Check_Title_Length extends Smartcrawl_Check_Post_Abstract {

	/**
	 * Holds check state
	 *
	 * @var int
	 */
	private $state;

	private $length = null;

	public function get_status_msg() {
		if ( ! is_numeric( $this->state ) ) {
			return __( 'Your SEO title is a good length', 'smartcrawl-seo' );
		}

		return 0 === $this->state
			? __( "You haven't added an SEO title yet", 'smartcrawl-seo' )
			: ( $this->state > 0
				? sprintf( __( 'Your SEO title is too long', 'smartcrawl-seo' ), $this->get_max() )
				: sprintf( __( 'Your SEO title is too short', 'smartcrawl-seo' ), $this->get_min() )
			);
	}

	public function get_max() {
		return smartcrawl_title_max_length();
	}

	public function get_min() {
		return smartcrawl_title_min_length();
	}

	public function apply() {
		$post       = $this->get_subject();
		$post_cache = Smartcrawl_Post_Cache::get();

		if ( ! is_object( $post ) || empty( $post->ID ) ) {
			$subject = $this->get_markup();
		} elseif ( wp_is_post_revision( $post->ID ) && ! empty( $post->post_title ) ) {
			$parent_post_id = wp_is_post_revision( $post->ID );
			$parent_post    = $post_cache->get_post( $parent_post_id );
			$parent_title   = $parent_post
				? $parent_post->get_title()
				: '';
			$parent_subject = $parent_post
				? $parent_post->get_meta_title()
				: '';
			$subject        = preg_replace( '/' . preg_quote( $parent_title, '/' ) . '/', $post->post_title, $parent_subject );
		} else {
			$smartcrawl_post = $post_cache->get_post( $post->ID );
			$subject         = $smartcrawl_post
				? $smartcrawl_post->get_meta_title()
				: '';
		}

		$this->state  = $this->is_within_char_length( $subject, $this->get_min(), $this->get_max() );
		$this->length = Smartcrawl_String_Utils::len( $subject );

		return ! is_numeric( $this->state );
	}

	public function apply_html() {
		$titles = Smartcrawl_Html::find_content( 'title', $this->get_markup() );
		if ( empty( $titles ) ) {
			$this->state = 0;

			return false;
		}

		$title        = reset( $titles );
		$this->state  = $this->is_within_char_length( $title, $this->get_min(), $this->get_max() );
		$this->length = Smartcrawl_String_Utils::len( $title );

		return ! is_numeric( $this->state );
	}

	public function get_recommendation() {
		$message = '';
		if ( ! is_numeric( $this->length ) ) {
			return $message;
		}

		$title_length = $this->length;

		if (
			$title_length >= $this->get_min()
			&& $title_length <= $this->get_max()
		) {
			/* translators: 1: Current characters length, 2,3: Recommend length range */
			$message = __( 'Your SEO title is %1$d characters which is between the recommended best practice of %2$d-%3$d characters.', 'smartcrawl-seo' );
		} elseif ( $title_length > $this->get_max() ) {
			/* translators: 1: Current characters length, 2,3: Recommend length range */
			$message = __( 'Your SEO title is %1$d characters which is greater than the recommended %3$d characters. Best practice is between %2$d and %3$d characters, with 60 being the sweet spot.', 'smartcrawl-seo' );
		} elseif ( $title_length < $this->get_min() ) {
			/* translators: 1: Current characters length, 2,3: Recommend length range */
			$message = __( 'Your SEO title is %1$d characters which is less than the recommended %2$d characters. Best practice is between %2$d and %3$d characters, with 60 being the sweet spot.', 'smartcrawl-seo' );
		} elseif ( 0 === intval( $title_length ) ) {
			/* translators: 1: Current characters length, 2,3: Recommend length range */
			$message = __( 'You have NOT written an SEO specific title for this article. We recommend an SEO specific title between %2$d and %3$d characters, optimized with your focus keywords.', 'smartcrawl-seo' );
		}

		return sprintf(
			$message,
			$title_length,
			$this->get_min(),
			$this->get_max()
		);
	}

	public function get_more_info() {
		$message = '';
		if ( ! is_numeric( $this->length ) ) {
			return $message;
		}

		$title_length = $this->length;

		return sprintf(
		/* translators: 1: Current characters length, 2,3: Recommend length range */
			__( 'Your SEO title is the most important element because it is what users will see in search engine results. You\'ll want to make sure that you have your focus keywords in there, that it\'s a nice length, and that people will want to click on it. Best practices suggest keeping your titles between %2$d and %3$d characters including spaces, though in some cases 60 is the sweetspot. The length is important both for SEO ranking but also how your title will show up in search engines - long titles will be cut off visually and look bad. Unfortunately there isn\'t a rule book for SEO titles, just remember to make your title great for SEO but also (most importantly) readable and enticing for potential visitors to click on.', 'smartcrawl-seo' ),
			$title_length,
			$this->get_min(),
			$this->get_max()
		);
	}
}
