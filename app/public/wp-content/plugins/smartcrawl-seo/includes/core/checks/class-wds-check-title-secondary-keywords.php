<?php
/**
 * Title secondary keyword check.
 *
 * This is a duplication of main check, but created to show as less important
 * check for secondary keywords.
 *
 * @since   3.4.0
 * @package Smartcrawl
 */

/**
 * Class Smartcrawl_Check_Title_Secondary_Keywords
 */
class Smartcrawl_Check_Title_Secondary_Keywords extends Smartcrawl_Check_Title_Keywords {

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
			? __( 'You didn\'t use this secondary keyword in the title.', 'smartcrawl-seo' )
			: __( 'You have used this secondary keyword in the title.', 'smartcrawl-seo' );
	}

	/**
	 * Get the recommendation texts.
	 *
	 * @return string
	 */
	public function get_recommendation() {
		return __( 'It\'s recommended to use your secondary keywords in the title of your page if possible. However, it has a minor impact on improving SEO.', 'smartcrawl-seo' );
	}

	/**
	 * Get more info text.
	 *
	 * @return string
	 */
	public function get_more_info() {
		return __( 'Having secondary keywords that express the same idea as your primary keyword in your title helps searchers find your content easier. When possible, you should consider adding the secondary keyword in the title.', 'smartcrawl-seo' );
	}
}
