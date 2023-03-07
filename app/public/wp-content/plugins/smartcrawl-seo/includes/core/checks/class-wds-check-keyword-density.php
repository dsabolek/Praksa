<?php
/**
 * Keyword density check.
 *
 * @package Smartcrawl
 */

/**
 * Class Smartcrawl_Check_Keyword_Density
 */
class Smartcrawl_Check_Keyword_Density extends Smartcrawl_Check_Abstract {

	/**
	 * Holds check state
	 *
	 * @var int
	 */
	private $state;

	/**
	 * Holds keyword density value.
	 *
	 * @var null|int
	 */
	private $density = null;

	/**
	 * Get the message for the check.
	 *
	 * @return string
	 */
	public function get_status_msg() {
		return $this->choose_status_message(
			__( "You haven't used any keywords yet", 'smartcrawl-seo' ),
			// translators: %d low, %d high.
			__( 'Your %4$s density is between %1$d%% and %2$d%%', 'smartcrawl-seo' ),
			// translators: %d low.
			__( 'Your %4$s density is less than %1$d%%', 'smartcrawl-seo' ),
			// translators: %d high.
			__( 'Your %4$s density is greater than %2$d%%', 'smartcrawl-seo' )
		);
	}

	/**
	 * Select status message based on density.
	 *
	 * @param string $no_keywords     No keywords message.
	 * @param string $correct_density Correct density keyword.
	 * @param string $low_density     Low density keyword.
	 * @param string $high_density    High density keyword.
	 *
	 * @return string
	 */
	private function choose_status_message( $no_keywords, $correct_density, $low_density, $high_density ) {
		$keyword_density = $this->density ? round( $this->density, 2 ) : 0;

		if ( 0 === $keyword_density ) {
			$message = $no_keywords;
		} elseif ( $this->state ) {
			$message = $correct_density;
		} else {
			if ( $keyword_density < $this->get_min() ) {
				$message = $low_density;
			} else {
				$message = $high_density;
			}
		}

		return sprintf( $message, $this->get_min(), $this->get_max(), $keyword_density, $this->get_keyword_label() );
	}

	/**
	 * Get minimum recommended density.
	 *
	 * @return int
	 */
	public function get_min() {
		return 1;
	}

	/**
	 * Get maximum recommended density.
	 *
	 * @return int
	 */
	public function get_max() {
		return 3;
	}

	/**
	 * Apply check to the subject.
	 *
	 * @return bool
	 */
	public function apply() {
		$markup = $this->get_markup();
		if ( empty( $markup ) ) {
			$this->state = false;

			return false;
		}

		$kws = $this->get_focus();
		if ( empty( $kws ) ) {
			$this->state = true;

			return true; // Can't determine kw density.
		}
		$text      = Smartcrawl_Html::plaintext( $markup );
		$string    = Smartcrawl_String_Cache::get()->get_string( $text, $this->get_language() );
		$words     = $string->get_words();
		$freq      = array_count_values( $words );
		$densities = array();
		if ( ! empty( $words ) ) {
			foreach ( $kws as $kw ) {
				$dns              = isset( $freq[ $kw ] ) ? $freq[ $kw ] : 0;
				$densities[ $kw ] = ( $dns / count( $words ) ) * 100;
			}
		}
		$density       = ! empty( $densities )
			? array_sum( array_values( $densities ) ) / count( $densities )
			: 0;
		$this->density = $density;

		$this->state = $density >= $this->get_min() && $density <= $this->get_max();

		return ! ! $this->state;
	}

	/**
	 * Get the recommendation texts.
	 *
	 * @return string
	 */
	public function get_recommendation() {
		return $this->choose_status_message(
		/* translators: 1, 2: Recommended density range */
			__( 'Currently you haven\'t used any keywords in your content. The recommended density is %1$d-%2$d%%. A low keyword density means your content has less chance of ranking highly for your chosen focus keywords.', 'smartcrawl-seo' ),
			/* translators: 1, 2: Recommended density range, 3: Current density, 5: label for keyword */
			__( 'Your %4$s density is %3$s%% which is within the recommended %1$d-%2$d%%, nice work! This means your content has a better chance of ranking highly for your chosen focus keywords, without appearing as spam.', 'smartcrawl-seo' ),
			/* translators: 1, 2: Recommended density range, 3: Current density, 5: label for keyword */
			__( 'Currently your %4$s density is %3$s%% which is below the recommended %1$d-%2$d%%. A low keyword density means your content has less chance of ranking highly for your chosen focus keywords.', 'smartcrawl-seo' ),
			/* translators: 1, 2: Recommended density range, 3: Current density, 5: label for keyword */
			__( 'Currently your %4$s density is %3$s%% which is greater than the recommended %1$d-%2$d%%. If your content is littered with too many focus keywords, search engines can penalize your content and mark it as spam.', 'smartcrawl-seo' )
		);
	}

	/**
	 * Get more info text.
	 *
	 * @return string
	 */
	public function get_more_info() {
		/* translators: 1, 2: Recommended density range */
		$message = __( 'Keyword density is all about making sure your content is populated with enough keywords to give it a better chance of appearing higher in search results. One way of making sure people will be able to find our content is using particular focus keywords, and using them as much as naturally possible in our content. In doing this we are trying to match up the keywords that people are likely to use when searching for this article or page, so try to get into your visitors mind and picture them typing a search into Google. While we recommend aiming for %1$d-%2$d%% density, remember content is king and you don\'t want your article to end up sounding like a robot. Get creative and utilize the page title, image caption, and subheadings.', 'smartcrawl-seo' );

		return sprintf(
			$message,
			$this->get_min(),
			$this->get_max()
		);
	}
}
