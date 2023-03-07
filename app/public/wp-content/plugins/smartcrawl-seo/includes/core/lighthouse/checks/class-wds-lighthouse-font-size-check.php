<?php

class Smartcrawl_Lighthouse_Font_Size_Check extends Smartcrawl_Lighthouse_Check {
	const ID = 'font-size';

	public function prepare() {
		$this->set_success_title( esc_html__( 'Document uses legible font sizes', 'smartcrawl-seo' ) );
		$this->set_failure_title( esc_html__( "Document doesn't use legible font sizes", 'smartcrawl-seo' ) );
		$this->set_success_description( $this->format_success_description() );
		$this->set_failure_description( $this->format_failure_description() );
		$this->set_copy_description( $this->format_copy_description() );
	}

	private function print_common_description() {
		?>
		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'Overview', 'smartcrawl-seo' ); ?></strong>
			<p><?php esc_html_e( 'Many search engines rank pages based on how mobile-friendly they are. Font sizes smaller than 12px are often difficult to read on mobile devices and may require users to zoom in to display text at a comfortable reading size.', 'smartcrawl-seo' ); ?></p>
		</div>
		<?php
	}

	private function format_success_description() {
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
					'message' => esc_html__( 'Document uses legible font sizes, nice work!', 'smartcrawl-seo' ),
				)
			);
			?>
		</div>
		<?php
		return ob_get_clean();
	}

	private function format_failure_description() {
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
					'message' => esc_html__( "Document doesn't use legible font sizes.", 'smartcrawl-seo' ),
				)
			);
			?>
		</div>

		<div class="wds-lh-section wds-lh-font-sizes-table">
			<p><?php esc_html_e( 'Lighthouse flags pages on which 60% or more of the text has a font size smaller than 12px.', 'smartcrawl-seo' ); ?></p>
			<?php $this->print_details_table(); ?>
		</div>

		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'How to fix illegible fonts', 'smartcrawl-seo' ); ?></strong>
			<p><?php esc_html_e( 'If Lighthouse reports Text is illegible because of a missing viewport config, add a <meta name="viewport" content="width=device-width, initial-scale=1"> tag to the <head> of your document.', 'smartcrawl-seo' ); ?></p>
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
				esc_html__( 'Selector', 'smartcrawl-seo' ),
				esc_html__( 'Font Size', 'smartcrawl-seo' ),
				esc_html__( '% of Page Text', 'smartcrawl-seo' ),
			),
			$this->get_report()
		);

		$items = smartcrawl_get_array_value( $raw_details, 'items' );
		foreach ( $items as $item ) {
			$table->add_row(
				array(
					smartcrawl_get_array_value( $item, array( 'selector', 'snippet' ) ),
					smartcrawl_get_array_value( $item, 'fontSize' ),
					smartcrawl_get_array_value( $item, 'coverage' ),
				)
			);
		}

		return $table;
	}

	/**
	 * @return string
	 */
	private function format_copy_description() {
		$parts = array(
			__( 'Tested Device: ', 'smartcrawl-seo' ) . $this->get_device_label(),
			__( 'Audit Type: Responsive audits', 'smartcrawl-seo' ),
			'',
			__( "Failing Audit: Document doesn't use legible font sizes", 'smartcrawl-seo' ),
			'',
			__( "Status: Document doesn't use legible font sizes.", 'smartcrawl-seo' ),
			__( 'Lighthouse flags pages on which 60% or more of the text has a font size smaller than 12px.', 'smartcrawl-seo' ),
			'',
			__( 'Overview:', 'smartcrawl-seo' ),
			__( 'Many search engines rank pages based on how mobile-friendly they are. Font sizes smaller than 12px are often difficult to read on mobile devices and may require users to zoom in to display text at a comfortable reading size.', 'smartcrawl-seo' ),
			'',
			__( 'For more information please check the SEO Audits section in SmartCrawl plugin.', 'smartcrawl-seo' ),
		);
		return implode( "\n", $parts );
	}
}
