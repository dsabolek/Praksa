<?php

class Smartcrawl_Lighthouse_Hreflang_Check extends Smartcrawl_Lighthouse_Check {
	const ID = 'hreflang';

	/**
	 * @return void
	 */
	public function prepare() {
		$this->set_success_title( esc_html__( 'Document has a valid hreflang', 'smartcrawl-seo' ) );
		$this->set_failure_title( esc_html__( "Document doesn't have a valid hreflang", 'smartcrawl-seo' ) );
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
			<p><?php esc_html_e( "Many sites provide different versions of a page based on a user's language or region. hreflang links tell search engines the URLs for all the versions of a page so that they can display the correct version for each language or region.", 'smartcrawl-seo' ); ?></p>
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
					'message' => sprintf(
						/* translators: %s: hreflang */
						esc_html__( 'Document has a valid %s, nice work.', 'smartcrawl-seo' ),
						'<strong>' . esc_html__( 'hreflang', 'smartcrawl-seo' ) . '</strong>'
					),
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
					'message' => esc_html__( "Document doesn't have a valid hreflang.", 'smartcrawl-seo' ),
				)
			);
			?>

			<?php $this->print_details_table(); ?>
		</div>

		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'How to define an hreflang link for each version of a page', 'smartcrawl-seo' ); ?></strong>
			<ul>
				<li style="margin-bottom: 25px;">
					<strong><?php esc_html_e( 'Method 1: Add hreflang Tag in WordPress Using a Multilingual Plugin.', 'smartcrawl-seo' ); ?></strong><br/>
					<?php
					printf(
						/* translators: 1,2: Example links */
						esc_html__( 'The best approach to building a multilingual WordPress site is by using a multilingual plugin. A multilingual WordPress plugin allows you to easily create and manage content in multiple languages using the same WordPress core software. Some examples: %1$s or %2$s.', 'smartcrawl-seo' ),
						sprintf(
							'<a target="%s" href="%s">%s</a>',
							'_blank',
							esc_url_raw( 'https://polylang.pro/' ),
							esc_html__( 'Polylang', 'smartcrawl-seo' )
						),
						sprintf(
							'<a target="%s" href="%s">%s</a>',
							'_blank',
							esc_url_raw( 'https://wpml.org/' ),
							esc_html__( 'WPML', 'smartcrawl-seo' )
						)
					);
					?>
				</li>

				<li>
					<strong><?php esc_html_e( 'Method 2: Add hreflang Tags in WordPress Without Using a Multilingual Plugin', 'smartcrawl-seo' ); ?></strong><br/>
					<?php
					echo smartcrawl_format_link(
						/* translators: %s: Plugin installation url */
						esc_html__( 'This method is for users who are not using a multilingual plugin to manage translations on their websites. First thing you need to do is install and activate the %s. Next, you need to edit the post or page where you want to add the hreflang tag. On the post edit screen, you will notice a new metabox labeled hreflang tags.', 'smartcrawl-seo' ),
						'https://wordpress.org/plugins/hreflang-tags-by-dcgws/',
						esc_html__( 'hreflang Tags Lite plugin', 'smartcrawl-seo' ),
						'_blank'
					);
					?>
				</li>
			</ul>
		</div>
		<div class="wds-lh-toggle-container">
			<a class="wds-lh-toggle" href="#">
				<?php esc_html_e( 'Read More - Guidelines' ); ?>
			</a>

			<div class="wds-lh-section">
				<strong><?php esc_html_e( 'Guidelines for hreflang values', 'smartcrawl-seo' ); ?></strong>
				<ul>
					<li><?php esc_html_e( 'The hreflang value must always specify a language code.', 'smartcrawl-seo' ); ?></li>
					<li>
					<?php
					echo smartcrawl_format_link(
						/* translators: %s: Language code format */
						esc_html__( 'The language code must follow %s.', 'smartcrawl-seo' ),
						'https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes',
						esc_html__( 'ISO 639-1 format', 'smartcrawl-seo' ),
						'_blank'
					);
					?>
					</li>
					<li><?php esc_html_e( 'The hreflang value can also include an optional regional code. For example, es-mx is for Spanish speakers in Mexico, while es-cl is for Spanish speakers in Chile.', 'smartcrawl-seo' ); ?></li>
					<li>
					<?php
					echo smartcrawl_format_link(
						/* translators: %s: Region code format */
						esc_html__( 'The region code must follow the %s.', 'smartcrawl-seo' ),
						'https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2',
						esc_html__( 'ISO 3166-1 alpha-2 format', 'smartcrawl-seo' ),
						'_blank'
					);
					?>
					</li>
				</ul>

				<p>
					<?php
					echo smartcrawl_format_link(
						/* translators: %s: Link to documentation */
						esc_html__( "For more information, see Google's %s.", 'smartcrawl-seo' ),
						'https://developers.google.com/search/docs/advanced/crawling/localized-versions',
						esc_html__( 'Tell Google about localized versions of your page', 'smartcrawl-seo' ),
						'_blank'
					)
					?>
				</p>
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
				esc_html__( 'Source', 'smartcrawl-seo' ),
			),
			$this->get_report()
		);

		$items = smartcrawl_get_array_value( $raw_details, 'items' );
		foreach ( $items as $item ) {
			$table->add_row(
				array(
					smartcrawl_get_array_value( $item, array( 'source', 'snippet' ) ),
				)
			);
		}

		return $table;
	}

	/**
	 * @return false|string
	 */
	public function get_action_button() {
		$url = false;
		if ( is_multisite() && is_super_admin() ) {
			$url = network_admin_url( 'plugin-install.php?s=hreflang&tab=search&type=term' );
		} elseif ( current_user_can( 'install_plugins' ) ) {
			$url = admin_url( 'plugin-install.php?s=hreflang&tab=search&type=term' );
		}

		if ( ! $url ) {
			return '';
		}

		return $this->button_markup(
			esc_html__( 'HREFLANG Plugins', 'smartcrawl-seo' ),
			$url,
			'sui-icon-magnifying-glass-search'
		);
	}

	/**
	 * @return string
	 */
	private function format_copy_description() {
		$parts = array_merge(
			array(
				__( 'Tested Device: ', 'smartcrawl-seo' ) . $this->get_device_label(),
				__( 'Audit Type: Content audits', 'smartcrawl-seo' ),
				'',
				__( "Failing Audit: Document doesn't have a valid hreflang", 'smartcrawl-seo' ),
				'',
				__( "Status: Document doesn't have a valid hreflang.", 'smartcrawl-seo' ),
				'',
			),
			$this->get_flattened_details(),
			array(
				'',
				__( 'Overview:', 'smartcrawl-seo' ),
				__( "Many sites provide different versions of a page based on a user's language or region. hreflang links tell search engines the URLs for all the versions of a page so that they can display the correct version for each language or region.", 'smartcrawl-seo' ),
				'',
				__( 'For more information please check the SEO Audits section in SmartCrawl plugin.', 'smartcrawl-seo' ),
			)
		);

		return implode( "\n", $parts );
	}
}
