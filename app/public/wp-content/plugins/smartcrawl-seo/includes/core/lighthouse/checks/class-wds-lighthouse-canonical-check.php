<?php

class Smartcrawl_Lighthouse_Canonical_Check extends Smartcrawl_Lighthouse_Check {
	const ID = 'canonical';

	/**
	 * @return void
	 */
	public function prepare() {
		$this->set_success_title( esc_html__( 'Document has a valid rel=canonical', 'smartcrawl-seo' ) );
		$this->set_failure_title( esc_html__( 'Document does not have a valid rel=canonical', 'smartcrawl-seo' ) );
		$this->set_success_description( $this->format_success_description() );
		$this->set_failure_description( $this->format_failure_description() );
		$this->set_copy_description( $this->format_copy_description() );
	}

	/**
	 * @return void
	 */
	private function print_common_description() {
		?>
		<strong><?php esc_html_e( 'Overview', 'smartcrawl-seo' ); ?></strong>
		<p><?php esc_html_e( 'When multiple pages have similar content, search engines consider them duplicate versions of the same page. For example, desktop and mobile versions of a product page are often considered duplicates.', 'smartcrawl-seo' ); ?></p>
		<p><?php esc_html_e( 'Search engines select one of the pages as the canonical, or primary, version and crawl that one more. Valid canonical links let you tell search engines which version of a page to crawl and display to users in search results.', 'smartcrawl-seo' ); ?></p>
		<?php
	}

	/**
	 * @return string
	 */
	private function format_success_description() {
		ob_start();
		?>
		<div class="wds-lh-section">
			<?php $this->print_common_description(); ?>
		</div>

		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'Status', 'smartcrawl-seo' ); ?></strong>
			<?php
			Smartcrawl_Simple_Renderer::render(
				'notice',
				array(
					'class'   => 'sui-notice-success',
					'message' => esc_html__( 'We found a valid canonical meta tag.', 'smartcrawl-seo' ),
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
		?>
		<div class="wds-lh-section">
			<?php $this->print_common_description(); ?>

			<p><?php esc_html_e( 'Using canonical links has many advantages:', 'smartcrawl-seo' ); ?></p>
			<ul>
				<li><?php esc_html_e( 'It helps search engines consolidate multiple URLs into a single, preferred URL. For example, if other sites put query parameters on the ends of links to your page, search engines consolidate those URLs to your preferred version.', 'smartcrawl-seo' ); ?></li>
				<li><?php esc_html_e( 'It simplifies tracking methods. Tracking one URL is easier than tracking many.', 'smartcrawl-seo' ); ?></li>
				<li><?php esc_html_e( 'It improves the page ranking of syndicated content by consolidating the syndicated links to your original content back to your preferred URL.', 'smartcrawl-seo' ); ?></li>
			</ul>
		</div>

		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'Status', 'smartcrawl-seo' ); ?></strong>
			<?php
			Smartcrawl_Simple_Renderer::render(
				'notice',
				array(
					'class'   => 'sui-notice-warning',
					'message' => esc_html__( 'We couldn’t detect a valid canonical meta tag.', 'smartcrawl-seo' ),
				)
			);
			?>

			<p><?php esc_html_e( 'It’s highly recommended to always set a single canonical URL for every webpage to ensure search engines never get confused and always have the original source of truth content.', 'smartcrawl-seo' ); ?></p>
		</div>

		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'How to add canonical links to your pages', 'smartcrawl-seo' ); ?></strong>
			<p><?php esc_html_e( 'For your homepage, set the canonical URL using the Titles & Meta settings area. For individual pages we automatically generate a canonical URL based off your base site URL, but you can override that on a per post basis using the Post Editor SEO widget.', 'smartcrawl-seo' ); ?></p>
			<p>
				<?php
				echo smartcrawl_format_link(
					/* translators: %s: Link to blog post about SEO effort */
					esc_html__( 'To help ensure your SEO efforts are up to snuff, see our blog post, %s, for an easy setup guide to get canonicals right.', 'smartcrawl-seo' ),
					'https://wpmudev.com/blog/wordpress-canonicalization-guide/',
					esc_html__( 'WordPress Canonicalization Made Simple With SmartCrawl', 'smartcrawl-seo' ),
					'_blank'
				);
				?>
			</p>
		</div>

		<div class="wds-lh-toggle-container">
			<a class="wds-lh-toggle" href="#">
				<?php esc_html_e( 'Read More - Guidelines' ); ?>
			</a>

			<div class="wds-lh-section">
				<strong><?php esc_html_e( 'General guidelines', 'smartcrawl-seo' ); ?></strong>
				<ul>
					<li><?php esc_html_e( 'Make sure that the canonical URL is valid.', 'smartcrawl-seo' ); ?></li>
					<li>
					<?php
					echo smartcrawl_format_link(
						/* translators: %s: Link to Https protocol documentation */
						esc_html__( 'Use secure %s canonical URLs rather than HTTP whenever possible.', 'smartcrawl-seo' ),
						'https://developers.google.com/search/docs/advanced/security/https',
						'HTTPS',
						'_blank'
					);
					?>
					</li>
					<li>
						<?php
						echo smartcrawl_format_link(
							/* translators: %s: Link to documentation */
							esc_html__( 'If you use %s to serve different versions of a page depending on a user\'s language or country, make sure that the canonical URL points to the proper page for that respective language or country.', 'smartcrawl-seo' ),
							'https://developers.google.com/search/docs/advanced/crawling/localized-versions?hl=en#expandable-1',
							esc_html__( 'hreflang links', 'smartcrawl-seo' ),
							'_blank'
						);
						?>
					</li>
					<li><?php esc_html_e( "Don't point the canonical URL to a different domain. Yahoo and Bing don't allow this.", 'smartcrawl-seo' ); ?></li>
					<li><?php esc_html_e( "Don't point lower-level pages to the site's root page unless their content is the same.", 'smartcrawl-seo' ); ?></li>
				</ul>

				<p>
				<?php
				echo smartcrawl_format_link(
					'See %s page.',
					'https://developers.google.com/search/docs/advanced/crawling/consolidate-duplicate-urls',
					esc_html__( "Google's Consolidate duplicate URLs", 'smartcrawl-seo' ),
					'_blank'
				);
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
	 * @return false|string
	 */
	public function get_action_button() {
		return $this->edit_homepage_button();
	}

	/**
	 * @return string
	 */
	private function format_copy_description() {
		$parts = array(
			__( 'Tested Device: ', 'smartcrawl-seo' ) . $this->get_device_label(),
			__( 'Audit Type: Content audits', 'smartcrawl-seo' ),
			'',
			__( 'Failing Audit: Document does not have a valid rel=canonical', 'smartcrawl-seo' ),
			'',
			__( 'Status: We couldn’t detect a valid canonical meta tag.', 'smartcrawl-seo' ),
			__( 'It’s highly recommended to always set a single canonical URL for every webpage to ensure search engines never get confused and always have the original source of truth content.', 'smartcrawl-seo' ),
			'',
			__( 'Overview:', 'smartcrawl-seo' ),
			__( 'When multiple pages have similar content, search engines consider them duplicate versions of the same page. For example, desktop and mobile versions of a product page are often considered duplicates.', 'smartcrawl-seo' ),
			__( 'Search engines select one of the pages as the canonical, or primary, version and crawl that one more. Valid canonical links let you tell search engines which version of a page to crawl and display to users in search results.', 'smartcrawl-seo' ),
			'',
			__( 'Using canonical links has many advantages:', 'smartcrawl-seo' ),
			__( '- It helps search engines consolidate multiple URLs into a single, preferred URL. For example, if other sites put query parameters on the ends of links to your page, search engines consolidate those URLs to your preferred version.', 'smartcrawl-seo' ),
			__( '- It simplifies tracking methods. Tracking one URL is easier than tracking many.', 'smartcrawl-seo' ),
			__( '- It improves the page ranking of syndicated content by consolidating the syndicated links to your original content back to your preferred URL.', 'smartcrawl-seo' ),
			'',
			__( 'For more information please check the SEO Audits section in SmartCrawl plugin.', 'smartcrawl-seo' ),
		);

		return implode( "\n", $parts );
	}
}
