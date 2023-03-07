<?php
/**
 * Bolded keyword check.
 *
 * @since   3.4.0
 * @package Smartcrawl
 */

/**
 * Class Smartcrawl_Check_Bolded_Keyword
 */
class Smartcrawl_Check_Bolded_Keyword extends Smartcrawl_Check_Abstract {

	/**
	 * Holds check state
	 *
	 * @since 3.4.0
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
			// translators: %s keyword label.
			? sprintf( __( 'You haven\'t bolded this %s in your content.', 'smartcrawl-seo' ), $this->get_keyword_label() )
			// translators: %s keyword label.
			: sprintf( __( 'The %s is bolded in your content.', 'smartcrawl-seo' ), $this->get_keyword_label() );
	}

	/**
	 * Apply check to the subject.
	 *
	 * @since 3.4.0
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

		$subjects_b      = Smartcrawl_Html::find_content( 'b', $raw );
		$subjects_strong = Smartcrawl_Html::find_content( 'strong', $raw );
		if ( ! empty( $subjects_b ) || ! empty( $subjects_strong ) ) {
			$subjects = array_merge( $subjects_strong, $subjects_b );
			foreach ( $subjects as $subject ) {
				if ( $this->has_focus( $subject ) ) {
					$this->state = true;
					return true;
				}
			}
		}

		$this->state = false;

		return false;
	}

	/**
	 * Get the recommendation texts.
	 *
	 * @since 3.4.0
	 *
	 * @return string
	 */
	public function get_recommendation() {
		if ( $this->state ) {
			/* translators: %s keyword type label */
			return sprintf( __( 'It’s best practice to bold your secondary keyword at least once throughout your content.', 'smartcrawl-seo' ), $this->get_keyword_label() );
		} else {
			/* translators: %s keyword type label */
			return sprintf( __( 'You bolded your %s at least once in your content. Good work!', 'smartcrawl-seo' ), $this->get_keyword_label() );
		}
	}

	/**
	 * Get more info text.
	 *
	 * @since 3.4.0
	 *
	 * @return string
	 */
	public function get_more_info() {
		/* translators: %s keyword type label */
		return sprintf( __( 'Bold keywords can help visitors and Google identify what is important on the page. You should consider bolding this %s at least once in your content.', 'smartcrawl-seo' ), $this->get_keyword_label() );
	}
}
