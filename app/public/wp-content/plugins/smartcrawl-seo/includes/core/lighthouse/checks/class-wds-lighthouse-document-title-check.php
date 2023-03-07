<?php

class Smartcrawl_Lighthouse_Document_Title_Check extends Smartcrawl_Lighthouse_Check {
	const ID = 'document-title';

	/**
	 * @return void
	 */
	public function prepare() {
		$this->set_success_title( esc_html__( 'Document has a <title> element', 'smartcrawl-seo' ) );
		$this->set_failure_title( esc_html__( "Document doesn't have a <title> element", 'smartcrawl-seo' ) );
		$this->set_success_description( $this->format_success_description() );
		$this->set_failure_description( $this->format_failure_description() );
		$this->set_copy_description( $this->format_copy_description() );
	}

	/**
	 * @return false|string
	 */
	public function get_action_button() {
		if ( ! Smartcrawl_Settings_Admin::is_tab_allowed( Smartcrawl_Settings::TAB_ONPAGE ) ) {
			return '';
		}

		return $this->button_markup(
			esc_html__( 'Add Title', 'smartcrawl-seo' ),
			Smartcrawl_Settings_Admin::admin_url( Smartcrawl_Settings::TAB_ONPAGE ),
			'sui-icon-plus'
		);
	}

	/**
	 * @return void
	 */
	private function print_common_description() {
		?>
		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'Overview', 'smartcrawl-seo' ); ?></strong>
			<p><?php esc_html_e( 'Having a <title> element on every page helps all your users:', 'smartcrawl-seo' ); ?></p>
			<ul>
				<li><?php esc_html_e( 'Search engine users rely on the title to determine whether a page is relevant to their search.', 'smartcrawl-seo' ); ?></li>
				<li><?php esc_html_e( 'The title also gives users of screen readers and other assistive technologies an overview of the page. The title is the first text that an assistive technology announces.', 'smartcrawl-seo' ); ?></li>
			</ul>
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
					'message' => esc_html__( 'Your homepage has a <title> element, well done!', 'smartcrawl-seo' ),
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
					'message' => esc_html__( "We couldn't find a <title> tag on your homepage.", 'smartcrawl-seo' ),
				)
			);
			?>
		</div>

		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'How to add a title', 'smartcrawl-seo' ); ?></strong>
			<p>
				<?php
				printf(
					/* translators: %s: Link to Titles & Meta page */
					esc_html__( 'Open the %s editor and add a meta title (and description) for your homepage. While you’re there, set up your default format for all other post types to ensure you always have a good quality <title> output.', 'smartcrawl-seo' ),
					'<strong>' . esc_html__( 'Titles & Meta', 'smartcrawl-seo' ) . '</strong>'
				);
				?>
			</p>
		</div>

		<div class="wds-lh-toggle-container">
			<a class="wds-lh-toggle" href="#">
				<?php esc_html_e( 'Read More - Best practices' ); ?>
			</a>

			<div class="wds-lh-section">
				<strong><?php esc_html_e( 'Tips for creating great titles', 'smartcrawl-seo' ); ?></strong>
				<p><?php esc_html_e( 'Having a <title> element on every page helps all your users:' ); ?></p>
				<ul>
					<li><?php esc_html_e( 'Use a unique title for each page.', 'smartcrawl-seo' ); ?></li>
					<li><?php esc_html_e( 'Make titles descriptive and concise. Avoid vague titles like "Home."', 'smartcrawl-seo' ); ?></li>
					<li><?php esc_html_e( "Avoid keyword stuffing. It doesn't help users, and search engines may mark the page as spam.", 'smartcrawl-seo' ); ?></li>
					<li><?php esc_html_e( "It's OK to brand your titles, but do so concisely.", 'smartcrawl-seo' ); ?></li>
				</ul>

				<div class="wds-lh-highlight-container">
					<p>
						<strong
							class="wds-lh-red-word"><?php esc_html_e( 'Don’t. ' ); ?></strong>
						<?php esc_html_e( 'Too vague.' ); ?>
					</p>
					<div class="wds-lh-highlight wds-lh-highlight-error">
						<?php
						echo join(
							'',
							array(
								$this->tag( '<title>' ),
								esc_html__( 'Donut recipe', 'smartcrawl-seo' ),
								$this->tag( '</title>' ),
							)
						);
						?>
					</div>

					<p>
						<strong
							class="wds-lh-green-word"><?php esc_html_e( 'Do. ' ); ?></strong>
						<?php esc_html_e( 'Descriptive yet concise.' ); ?>
					</p>
					<div class="wds-lh-highlight wds-lh-highlight-success">
						<?php
						echo join(
							'',
							array(
								$this->tag( '<title>' ),
								esc_html__( "Mary's quick maple bacon donut recipe", 'smartcrawl-seo' ),
								$this->tag( '</title>' ),
							)
						);
						?>
					</div>
				</div>

				<p>
					<?php
					echo smartcrawl_format_link(
						/* translators: %s: Link to documentation */
						esc_html__( "See Google's %s page for more details about these tips.", 'smartcrawl-seo' ),
						'https://developers.google.com/search/docs/advanced/appearance/good-titles-snippets',
						esc_html__( 'Create good titles and snippets in Search Results', 'smartcrawl-seo' ),
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
	 * @return string
	 */
	private function format_copy_description() {
		$parts = array(
			__( 'Tested Device: ', 'smartcrawl-seo' ) . $this->get_device_label(),
			__( 'Audit Type: Content audits', 'smartcrawl-seo' ),
			'',
			__( "Failing Audit: Document doesn't have a <title> element", 'smartcrawl-seo' ),
			'',
			__( "Status: We couldn't find a <title> tag on your homepage.", 'smartcrawl-seo' ),
			'',
			__( 'Overview:', 'smartcrawl-seo' ),
			__( 'Having a <title> element on every page helps all your users:', 'smartcrawl-seo' ),
			__( '- Search engine users rely on the title to determine whether a page is relevant to their search.', 'smartcrawl-seo' ),
			__( '- The title also gives users of screen readers and other assistive technologies an overview of the page. The title is the first text that an assistive technology announces.', 'smartcrawl-seo' ),
			'',
			__( 'For more information please check the SEO Audits section in SmartCrawl plugin.', 'smartcrawl-seo' ),
		);

		return implode( "\n", $parts );
	}
}
