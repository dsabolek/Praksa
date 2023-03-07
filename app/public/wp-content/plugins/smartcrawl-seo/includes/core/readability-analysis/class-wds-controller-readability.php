<?php
/**
 * Controller for readability analysis.
 *
 * @package SmartCrawl
 */

/**
 * Class Smartcrawl_Controller_Readability
 */
class Smartcrawl_Controller_Readability extends Smartcrawl_Base_Controller {

	use Smartcrawl_Singleton;

	/**
	 * Initialize the class.
	 *
	 * @return void
	 */
	protected function init() {

	}

	/**
	 * Check if a language is supported for readability analysis.
	 *
	 * @since 3.4.0
	 *
	 * @param string $lang Language to check (By default current language).
	 *
	 * @return bool
	 */
	public function is_language_supported( $lang = '' ) {
		$analysis_model = new Smartcrawl_Model_Analysis();

		return $analysis_model->is_readability_lang_supported( $lang );
	}
}
