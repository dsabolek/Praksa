<?php

class Smartcrawl_Lighthouse_Tap_Targets_Check extends Smartcrawl_Lighthouse_Check {
	const ID = 'tap-targets';

	/**
	 * @return void
	 */
	public function prepare() {
		$this->set_success_title( esc_html__( 'Tap targets are sized appropriately', 'smartcrawl-seo' ) );
		$this->set_failure_title( esc_html__( 'Tap targets are not sized appropriately', 'smartcrawl-seo' ) );
		$this->set_success_description( $this->format_success_description() );
		$this->set_failure_description( $this->format_failure_description() );
		$this->set_copy_description( $this->format_copy_description() );
	}

	/**
	 * @return string
	 */
	public function get_id() {
		return self::ID;
	}

	/**
	 * @return void
	 */
	private function print_common_description() {
		?>
		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'Overview', 'smartcrawl-seo' ); ?></strong>
			<p>
				<?php
				printf(
					/* translators: 1: Button size, 2: Space around */
					esc_html__( 'Interactive elements like buttons and links should be large enough (%1$s), and have enough space around them (%2$s), to be easy enough to tap without overlapping onto other elements.', 'smartcrawl-seo' ),
					'<strong>48x48px</strong>',
					'<strong>8px</strong>'
				);
				?>
			</p>
			<p><?php esc_html_e( 'Many search engines rank pages based on how mobile-friendly they are. Making sure tap targets are big enough and far enough apart from each other makes your page more mobile-friendly and accessible.', 'smartcrawl-seo' ); ?></p>
		</div>
		<?php
	}

	/**
	 * @return false|string
	 */
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
					'message' => esc_html__( 'Tap targets are sized appropriately.', 'smartcrawl-seo' ),
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
					'message' => esc_html__( 'Tap targets are not sized appropriately.', 'smartcrawl-seo' ),
				)
			);
			?>
		</div>

		<div class="wds-lh-section">
			<p><?php esc_html_e( 'Targets that are smaller than 48 px by 48 px or closer than 8 px apart fail the audit.', 'smartcrawl-seo' ); ?></p>
			<?php $this->print_details_table(); ?>
		</div>

		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'How to fix your tap targets', 'smartcrawl-seo' ); ?></strong>
			<ul>
				<li>
				<?php
				printf(
					/* translators: 1: Step, 2: Size */
					esc_html__( "%1\$s: Increase the size of tap targets that are too small. Tap targets that are %2\$s never fail the audit. If you have elements that shouldn't appear any bigger (for example, icons), try increasing the padding property.", 'smartcrawl-seo' ),
					'<strong>' . esc_html__( 'Step 1', 'smartcrawl-seo' ) . '</strong>',
					'<strong>' . esc_html__( '48 px by 48 px', 'smartcrawl-seo' ) . '</strong>'
				);
				?>
				</li>
				<li>
				<?php
				printf(
					/* translators: 1: Step 2, 2: Margin around */
					esc_html__( '%1$s: Increase the spacing between tap targets that are too close together using properties like margin. There should be at least %2$s between tap targets.', 'smartcrawl-seo' ),
					'<strong>' . esc_html__( 'Step 2', 'smartcrawl-seo' ) . '</strong>',
					'<strong>' . esc_html__( '8px', 'smartcrawl-seo' ) . '</strong>'
				);
				?>
				</li>
			</ul>
		</div>

		<?php
		return ob_get_clean();
	}

	/**
	 * @param $raw_details
	 *
	 * @return Smartcrawl_Lighthouse_Tap_Targets_Table
	 */
	public function parse_details( $raw_details ) {
		$table = new Smartcrawl_Lighthouse_Tap_Targets_Table(
			array(
				esc_html__( 'Tap Target', 'smartcrawl-seo' ),
				esc_html__( 'Size', 'smartcrawl-seo' ),
				esc_html__( 'Overlapping Target', 'smartcrawl-seo' ),
			),
			$this->get_report()
		);

		$items = smartcrawl_get_array_value( $raw_details, 'items' );
		foreach ( $items as $item ) {
			$tap_target_node_id  = smartcrawl_get_array_value( $item, array( 'tapTarget', 'lhId' ) );
			$overlapping_node_id = smartcrawl_get_array_value( $item, array( 'overlappingTarget', 'lhId' ) );

			$table->add_row(
				array(
					smartcrawl_get_array_value( $item, array( 'tapTarget', 'snippet' ) ),
					smartcrawl_get_array_value( $item, 'size' ),
					smartcrawl_get_array_value( $item, array( 'overlappingTarget', 'snippet' ) ),
				),
				$tap_target_node_id,
				$overlapping_node_id
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
			__( 'Failing Audit: Tap targets are not sized appropriately', 'smartcrawl-seo' ),
			'',
			__( 'Status: Tap targets are not sized appropriately.', 'smartcrawl-seo' ),
			__( 'Targets that are smaller than 48 px by 48 px or closer than 8 px apart fail the audit.', 'smartcrawl-seo' ),
			'',
			__( 'Overview:', 'smartcrawl-seo' ),
			__( 'Interactive elements like buttons and links should be large enough (48x48px), and have enough space around them (8px), to be easy enough to tap without overlapping onto other elements.', 'smartcrawl-seo' ),
			__( 'Many search engines rank pages based on how mobile-friendly they are. Making sure tap targets are big enough and far enough apart from each other makes your page more mobile-friendly and accessible.', 'smartcrawl-seo' ),
			'',
			__( 'For more information please check the SEO Audits section in SmartCrawl plugin.', 'smartcrawl-seo' ),
		);
		return implode( "\n", $parts );
	}
}
