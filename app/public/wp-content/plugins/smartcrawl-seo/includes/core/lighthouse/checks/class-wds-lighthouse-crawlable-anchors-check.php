<?php

class Smartcrawl_Lighthouse_Crawlable_Anchors_Check extends Smartcrawl_Lighthouse_Check {
	const ID = 'crawlable-anchors';

	/**
	 * @return void
	 */
	public function prepare() {
		$this->set_success_title( esc_html__( 'Links are crawlable', 'smartcrawl-seo' ) );
		$this->set_failure_title( esc_html__( 'Links are not crawlable', 'smartcrawl-seo' ) );
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
			<p>
				<?php
				printf(
					/* translators: %s: Tag for anchor with href attribute */
					esc_html__( "Google can follow links only if they are an %s. Links that use other formats won't be followed by Google's crawlers. Google cannot follow links without an href, or links created by script events.", 'smartcrawl-seo' ),
					'<strong>' . esc_html__( '<a> tag with an href attribute', 'smartcrawl-seo' ) . '</strong>'
				);
				?>
			</p>
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
					'message' => esc_html__( 'Way to go! It appears all your links are crawlable!', 'smartcrawl-seo' ),
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
					'message' => esc_html__( "We've detected some of your links are not crawlable.", 'smartcrawl-seo' ),
				)
			);
			?>

			<?php $this->print_details_table(); ?>
		</div>

		<div class="wds-lh-section">
			<p><?php esc_html_e( "Here are examples of links that Google can and can't follow:", 'smartcrawl-seo' ); ?></p>

			<div class="wds-lh-highlight-container">
				<p>
					<strong
						class="wds-lh-green-word"><?php esc_html_e( 'Can follow:' ); ?></strong>
				</p>
				<div class="wds-lh-highlight wds-lh-highlight-success">
					<?php
					echo join(
						'',
						array(
							$this->tag( '<a ' ),
							$this->attr( 'href=' ),
							'"https://example.com"',
							$this->tag( '>' ),
						)
					);
					?>
					<br/>

					<?php
					echo join(
						'',
						array(
							$this->tag( '<a ' ),
							$this->attr( 'href=' ),
							'"/relative/path/file"',
							$this->tag( '>' ),
						)
					);
					?>
				</div>

				<p>
					<strong
						class="wds-lh-red-word"><?php esc_html_e( "Can't follow:" ); ?></strong>
				</p>
				<div class="wds-lh-highlight wds-lh-highlight-error">
					<?php echo $this->tag( '<a>' ); ?><br/>
					<?php
					echo join(
						'',
						array(
							$this->tag( '<a ' ),
							$this->attr( 'routerLink=' ),
							'"some/path"',
							$this->tag( '>' ),
						)
					)
					?>
					<br/>
					<?php
					echo join(
						'',
						array(
							$this->tag( '<span ' ),
							$this->attr( 'href=' ),
							'"https://example.com"',
							$this->tag( '>' ),
						)
					)
					?>
					<br/>
					<?php
					echo join(
						'',
						array(
							$this->tag( '<span ' ),
							$this->attr( 'onclick=' ),
							'"goto(\'https://example.com\')"',
							$this->tag( '>' ),
						)
					)
					?>
					<br/>
				</div>
			</div>
		</div>

		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'Link to resolvable URLs', 'smartcrawl-seo' ); ?></strong>
			<p><?php esc_html_e( 'Ensure that the URL linked to by your <a> tag is an actual web address that Googlebot can send requests to, for example:', 'smartcrawl-seo' ); ?></p>

			<div class="wds-lh-highlight-container">
				<p>
					<strong
						class="wds-lh-green-word"><?php esc_html_e( 'Can resolve:' ); ?></strong>
				</p>
				<div class="wds-lh-highlight wds-lh-highlight-success">
					<?php echo $this->tag( 'https://example.com/stuff' ); ?>
					<br/>
					<?php echo $this->tag( '/products' ); ?><br/>
					<?php echo $this->tag( '/products.php?id=123' ); ?>
				</div>

				<p>
					<strong
						class="wds-lh-red-word"><?php esc_html_e( "Can't resolve:" ); ?></strong>
				</p>
				<div class="wds-lh-highlight wds-lh-highlight-error">
					<?php echo $this->tag( "javascript:goTo('products')" ); ?>
					<br/>
					<?php echo $this->tag( "javascript:window.location.href='/products'" ); ?>
					<br/>
					<?php echo $this->tag( '#' ); ?>
				</div>
			</div>
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
				esc_html__( 'Failing links', 'smartcrawl-seo' ),
				esc_html__( 'Link text', 'smartcrawl-seo' ) . $this->get_link_text_tooltip(),
			),
			$this->get_report()
		);

		$items = smartcrawl_get_array_value( $raw_details, 'items' );
		foreach ( $items as $item ) {
			$screenshot_node_id = smartcrawl_get_array_value(
				$item,
				array(
					'node',
					'lhId',
				)
			);

			$table->add_row(
				array(
					smartcrawl_get_array_value(
						$item,
						array(
							'node',
							'snippet',
						)
					),
					smartcrawl_get_array_value(
						$item,
						array(
							'node',
							'nodeLabel',
						)
					),
				),
				$screenshot_node_id
			);
		}

		return $table;
	}

	/**
	 * @return false|string
	 */
	private function get_link_text_tooltip() {
		ob_start();
		?>
		<span class="sui-tooltip sui-tooltip-constrained"
			data-tooltip="<?php esc_html_e( 'To locate the Link text on your homepage, use the Find tool of your browser.', 'smartcrawl-seo' ); ?>">
			<span class="sui-notice-icon sui-icon-info sui-sm"
				aria-hidden="true"></span>
		</span>
		<?php
		return ob_get_clean();
	}

	/**
	 * @return false|string
	 */
	public function get_action_button() {
		return $this->edit_homepage_button();
	}

	/**
	 * @return string
	 */
	private function format_copy_description() {
		$parts = array_merge(
			array(
				__( 'Tested Device: ', 'smartcrawl-seo' ) . $this->get_device_label(),
				__( 'Audit Type: Indexing audits', 'smartcrawl-seo' ),
				'',
				__( 'Failing Audit: Links are not crawlable', 'smartcrawl-seo' ),
				'',
				__( "Status: We've detected some of your links are not crawlable.", 'smartcrawl-seo' ),
				'',
			),
			$this->get_flattened_details(),
			array(
				'',
				__( 'Overview:', 'smartcrawl-seo' ),
				__( "Google can follow links only if they are an <a> tag with an href attribute. Links that use other formats won't be followed by Google's crawlers. Google cannot follow links without an href, or links created by script events.", 'smartcrawl-seo' ),
				'',
				__( 'For more information please check the SEO Audits section in SmartCrawl plugin.', 'smartcrawl-seo' ),
			)
		);

		return implode( "\n", $parts );
	}
}
