<?php

class Smartcrawl_Lighthouse_Robots_Txt_Check extends Smartcrawl_Lighthouse_Check {
	const ID = 'robots-txt';

	/**
	 * @return mixed|void
	 */
	public function prepare() {
		$this->set_success_title( esc_html__( 'robots.txt is valid', 'smartcrawl-seo' ) );
		$this->set_failure_title( esc_html__( 'robots.txt is not valid', 'smartcrawl-seo' ) );
		$this->set_success_description( $this->format_success_description() );
		$this->set_failure_description( $this->format_failure_description() );
		$this->set_copy_description( $this->format_copy_description() );
	}

	/**
	 * @return void
	 */
	private function print_common_description() {
		?>
		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'Overview', 'smartcrawl-seo' ); ?></strong>
			<p><?php esc_html_e( "The robots.txt file tells search engines which of your site's pages they can crawl. An invalid robots.txt configuration can cause two types of problems:", 'smartcrawl-seo' ); ?></p>
			<ul>
				<li><?php esc_html_e( 'It can keep search engines from crawling public pages, causing your content to show up less often in search results.', 'smartcrawl-seo' ); ?></li>
				<li><?php esc_html_e( 'It can cause search engines to crawl pages you may not want shown in search results.', 'smartcrawl-seo' ); ?></li>
			</ul>
		</div>
		<?php
	}

	/**
	 * @return false|string
	 */
	public function format_success_description() {
		ob_start();
		$this->print_common_description();
		?>
		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'Status', 'smartcrawl-seo' ); ?></strong>
			<?php
			Smartcrawl_Simple_Renderer::render(
				'notice',
				array(
					'class'   => 'sui-notice-success',
					'message' => esc_html__( "We've detected a robots.txt file, nice work.", 'smartcrawl-seo' ),
				)
			);
			?>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * @return false|string
	 */
	public function format_failure_description() {
		ob_start();
		$this->print_common_description();
		?>
		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'Status', 'smartcrawl-seo' ); ?></strong>
			<?php
			Smartcrawl_Simple_Renderer::render(
				'notice',
				array(
					'class'   => 'sui-notice-warning',
					'message' => esc_html__( 'The robots.txt file is not valid.', 'smartcrawl-seo' ),
				)
			);
			?>

			<p>
				<?php esc_html_e( 'If your robots.txt file is malformed, crawlers may not be able to understand how you want your website to be crawled or indexed.', 'smartcrawl-seo' ); ?>
			</p>

			<?php $this->print_details_table(); ?>
		</div>

		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'How to fix problems with robots.txt', 'smartcrawl-seo' ); ?></strong>
			<p>
				<?php
				printf(
					/* translators: 1,2: Opening/closing tags for <strong/> */
					esc_html__( 'SmartCrawl can automatically add a robots.txt file for you, and link to your sitemap. Jump to %1$sAdvanced Tools / Robots.txt Editor%2$s and fix the issues in your robots.txt file.', 'smartcrawl-seo' ),
					'<strong>',
					'</strong>'
				);
				?>
			</p>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * @return string
	 */
	public function get_id() {
		return self::ID;
	}

	/**
	 * @param $raw_details
	 *
	 * @return Smartcrawl_Lighthouse_Table
	 */
	public function parse_details( $raw_details ) {
		$table = new Smartcrawl_Lighthouse_Table(
			array(
				esc_html__( 'Line Number', 'smartcrawl-seo' ),
				esc_html__( 'Content', 'smartcrawl-seo' ),
				esc_html__( 'Error', 'smartcrawl-seo' ),
			),
			$this->get_report()
		);

		$items = smartcrawl_get_array_value( $raw_details, 'items' );
		foreach ( $items as $item ) {
			$table->add_row(
				array(
					smartcrawl_get_array_value( $item, 'index' ),
					smartcrawl_get_array_value( $item, 'line' ),
					smartcrawl_get_array_value( $item, 'message' ),
				)
			);
		}

		return $table;
	}

	/**
	 * @return false|string
	 */
	public function get_action_button() {
		if ( ! Smartcrawl_Settings_Admin::is_tab_allowed( Smartcrawl_Settings::TAB_AUTOLINKS ) ) {
			return '';
		}

		$url = Smartcrawl_Settings_Admin::admin_url( Smartcrawl_Settings::TAB_AUTOLINKS ) . '&tab=tab_robots_editor';
		return $this->button_markup(
			esc_html__( 'Edit Robots.txt', 'smartcrawl-seo' ),
			$url,
			'sui-icon-wrench-tool'
		);
	}

	/**
	 * @return string
	 */
	private function format_copy_description() {
		$parts = array_merge(
			array(
				esc_html__( 'Tested Device: ', 'smartcrawl-seo' ) . $this->get_device_label(),
				esc_html__( 'Audit Type: Indexing audits', 'smartcrawl-seo' ),
				'',
				esc_html__( 'Failing Audit: robots.txt is not valid', 'smartcrawl-seo' ),
				'',
				esc_html__( 'Status: The robots.txt file is not valid.', 'smartcrawl-seo' ),
				esc_html__( 'If your robots.txt file is malformed, crawlers may not be able to understand how you want your website to be crawled or indexed.', 'smartcrawl-seo' ),
				'',
			),
			$this->get_flattened_details(),
			array(
				'',
				esc_html__( 'Overview:', 'smartcrawl-seo' ),
				esc_html__( "The robots.txt file tells search engines which of your site's pages they can crawl. An invalid robots.txt configuration can cause two types of problems:", 'smartcrawl-seo' ),
				'',
				esc_html__( '- It can keep search engines from crawling public pages, causing your content to show up less often in search results.', 'smartcrawl-seo' ),
				esc_html__( '- It can cause search engines to crawl pages you may not want shown in search results.', 'smartcrawl-seo' ),
				'',
				esc_html__( 'For more information please check the SEO Audits section in SmartCrawl plugin.', 'smartcrawl-seo' ),
			)
		);

		return implode( "\n", $parts );
	}
}
