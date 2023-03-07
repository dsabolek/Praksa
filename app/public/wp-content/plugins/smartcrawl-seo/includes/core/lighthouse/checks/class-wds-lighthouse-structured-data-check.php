<?php

class Smartcrawl_Lighthouse_Structured_Data_Check extends Smartcrawl_Lighthouse_Check {
	const ID = 'structured-data';

	/**
	 * @return void
	 */
	public function prepare() {
		$this->set_success_title( esc_html__( 'Structured data is valid', 'smartcrawl-seo' ) );
		$this->set_failure_title( esc_html__( 'Structured data is invalid', 'smartcrawl-seo' ) );
		$this->set_success_description( $this->format_description() );
		$this->set_failure_description( $this->format_description() );
	}

	/**
	 * @return false|string
	 */
	private function format_description() {
		$schema_builder_url = Smartcrawl_Settings_Admin::admin_url( Smartcrawl_Settings::TAB_SCHEMA ) . '&tab=tab_types';
		$testing_tool       = sprintf( 'https://search.google.com/test/rich-results?url=%s&user_agent=2', urlencode( home_url() ) );

		ob_start();
		?>
		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'Overview', 'smartcrawl-seo' ); ?></strong>
			<p><?php esc_html_e( 'Search engines use structured data to understand what kind of content is on your page. For example, you can tell search engines that your page is an article, a job posting, or an FAQ.', 'smartcrawl-seo' ); ?></p>
			<p><?php esc_html_e( 'Marking up your content with structured data makes it more likely that it will be included in rich search results. For example, content marked up as an article might appear in a list of top stories relevant to something the user searched for.', 'smartcrawl-seo' ); ?></p>
		</div>

		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'Status', 'smartcrawl-seo' ); ?></strong>
			<?php
			Smartcrawl_Simple_Renderer::render(
				'notice',
				array(
					'class'   => 'sui-notice-grey',
					'message' => esc_html__( 'The Lighthouse structured data audit is manual, so it does not affect your Lighthouse SEO score.', 'smartcrawl-seo' ),
				)
			);
			?>
		</div>

		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'How to mark up your content', 'smartcrawl-seo' ); ?></strong>
			<ol>
				<li><?php esc_html_e( 'Identify the content type that represents your content.', 'smartcrawl-seo' ); ?></li>
				<li>
					<?php
					echo smartcrawl_format_link(
						/* translators: %s: Link ti Schema Types builder */
						esc_html__( "Create the structured data markup using SmartCrawl's %s, and ensure location rules are configured for the content types you want to make available to search engines.", 'smartcrawl-seo' ),
						$schema_builder_url,
						esc_html__( 'Schema Types Builder', 'smartcrawl-seo' )
					);
					?>
				</li>
				<li>
					<?php
					echo smartcrawl_format_link(
						/* translators: %s: Link to documentation */
						esc_html__( 'Run the %s to validate your structured data.', 'smartcrawl-seo' ),
						'//linter.structured-data.org/',
						esc_html__( 'Structured Data Linter', 'smartcrawl-seo' ),
						'_blank'
					);
					?>
				</li>
				<li><?php esc_html_e( 'Test how the markup works in Google Search:', 'smartcrawl-seo' ); ?>
					<br/><br/>
					<a href="<?php echo esc_attr( $testing_tool ); ?>"
						target="_blank"
						class="sui-button sui-button-ghost">
						<span class="sui-icon-target" aria-hidden="true"></span>
						<?php esc_html_e( 'Structured Data Testing Tool', 'smartcrawl-seo' ); ?>
					</a>
				</li>
			</ol>

			<p>
				<?php
				echo smartcrawl_format_link(
					/* translators: %s: Link to documentation */
					esc_html__( "See Google's %s page for more information.", 'smartcrawl-seo' ),
					'https://developers.google.com/search/docs/guides/mark-up-content',
					esc_html__( 'Mark Up Your Content Items', 'smartcrawl-seo' )
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
}
