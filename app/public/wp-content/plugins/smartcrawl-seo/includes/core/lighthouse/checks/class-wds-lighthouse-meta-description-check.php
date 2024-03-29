<?php

class Smartcrawl_Lighthouse_Meta_Description_Check extends Smartcrawl_Lighthouse_Check {
	const ID = 'meta-description';

	/**
	 * @return void
	 */
	public function prepare() {
		$this->set_success_title( esc_html__( 'Document has a meta description', 'smartcrawl-seo' ) );
		$this->set_failure_title( esc_html__( 'Document does not have a meta description', 'smartcrawl-seo' ) );
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
		<p>
			<?php
			printf(
				/* translators: %s: Meta description tag within <strong/> */
				esc_html__( "The %s element provides a summary of a page's content that search engines include in search results. A high-quality, unique meta description makes your page appear more relevant and can increase your search traffic.", 'smartcrawl-seo' ),
				'<strong>' . esc_html( '<meta name="description">' ) . '</strong>'
			);
			?>
		</p>
		<?php
	}

	/**
	 * @return false|string
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
					'message' => esc_html__( 'Your homepage has a meta description, well done!', 'smartcrawl-seo' ),
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
			<p>
				<?php esc_html_e( 'The audit fails if:', 'smartcrawl-seo' ); ?>
			</p>

			<ul>
				<li>
					<?php
					printf(
						/* translators: %s: Meta description tag within <strong/> */
						esc_html__( "If your page doesn't have a %s element.", 'smartcrawl-seo' ),
						'<strong>' . esc_html( '<meta name="description">' ) . '</strong>'
					);
					?>
				</li>
				<li>
					<?php
					printf(
						/* translators: %s: Meta description tag within <strong/> */
						esc_html__( 'The %1$s attribute of the %2$s element is empty.', 'smartcrawl-seo' ),
						'<strong>' . esc_html__( 'content', 'smartcrawl-seo' ) . '</strong>',
						'<strong>' . esc_html( '<meta name="description">' ) . '</strong>'
					);
					?>
				</li>
			</ul>
		</div>

		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'Status', 'smartcrawl-seo' ); ?></strong>
			<?php
			Smartcrawl_Simple_Renderer::render(
				'notice',
				array(
					'class'   => 'sui-notice-warning',
					'message' => esc_html__( "We couldn't find a meta description tag on your homepage.", 'smartcrawl-seo' ),
				)
			);
			?>
		</div>

		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'How to add a meta description', 'smartcrawl-seo' ); ?></strong>
			<p>
				<?php
				printf(
					/* translators: 1,2: Opening/closing tag for <strong/> */
					esc_html__( 'Open the %1$sTitles & Meta%2$s editor and add a meta description (and title) for your homepage. While you\'re there, set up your default format for all other post types to ensure you always have a good quality <meta name=description> output.', 'smartcrawl-seo' ),
					'<strong>',
					'</strong>'
				);
				?>
			</p>
		</div>

		<div class="wds-lh-toggle-container">
			<a class="wds-lh-toggle" href="#">
				<?php esc_html_e( 'Read More - Best practices' ); ?>
			</a>

			<div class="wds-lh-section">
				<strong><?php esc_html_e( 'Meta description best practices', 'smartcrawl-seo' ); ?></strong>
				<ul>
					<li><?php esc_html_e( 'Use a unique description for each page.', 'smartcrawl-seo' ); ?></li>
					<li><?php esc_html_e( 'Make descriptions relevant and concise. Avoid vague descriptions like "Home page”.', 'smartcrawl-seo' ); ?></li>
					<li>
						<?php
						echo smartcrawl_format_link(
							/* translators: %s: Link to documentation */
							esc_html__( "Avoid %s. It doesn't help users, and search engines may mark the page as spam.", 'smartcrawl-seo' ),
							'https://developers.google.com/search/docs/advanced/guidelines/irrelevant-keywords',
							esc_html__( 'keyword stuffing', 'smartcrawl-seo' ),
							'_blank'
						);
						?>
					</li>
					<li><?php esc_html_e( "Descriptions don't have to be complete sentences; they can contain structured data.", 'smartcrawl-seo' ); ?></li>
				</ul>

				<div class="wds-lh-highlight-container">
					<p>
						<strong class="wds-lh-red-word"><?php esc_html_e( 'Don’t. ' ); ?></strong>
						<?php esc_html_e( 'Too vague.' ); ?>
					</p>
					<div class="wds-lh-highlight wds-lh-highlight-error">
						<?php
						echo join(
							'',
							array(
								$this->tag( '<meta ' ),
								$this->attr( 'name="' ),
								'description',
								$this->attr( '" ' ),
								$this->attr( 'content="' ),
								esc_html__( 'Donut recipe', 'smartcrawl-seo' ),
								$this->attr( '"' ),
								$this->tag( '/>' ),
							)
						);
						?>
					</div>

					<p>
						<strong class="wds-lh-green-word"><?php esc_html_e( 'Do. ' ); ?></strong>
						<?php esc_html_e( 'Descriptive yet concise.' ); ?>
					</p>
					<div class="wds-lh-highlight wds-lh-highlight-success">
						<?php
						echo join(
							'',
							array(
								$this->tag( '<meta ' ),
								$this->attr( 'name="' ),
								'description',
								$this->attr( '" ' ),
								$this->attr( 'content="' ),
								esc_html__( "Mary's simple recipe for maple bacon donuts makes a sticky, sweet treat with just a hint of salt that you'll keep coming back for.", 'smartcrawl-seo' ),
								$this->attr( '"' ),
								$this->tag( '/>' ),
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
	 * @return false|string
	 */
	public function get_action_button() {
		if ( ! Smartcrawl_Settings_Admin::is_tab_allowed( Smartcrawl_Settings::TAB_ONPAGE ) ) {
			return '';
		}

		return $this->button_markup(
			esc_html__( 'Add Description', 'smartcrawl-seo' ),
			Smartcrawl_Settings_Admin::admin_url( Smartcrawl_Settings::TAB_ONPAGE ),
			'sui-icon-plus'
		);
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
			__( 'Failing Audit: Document does not have a meta description', 'smartcrawl-seo' ),
			'',
			__( "Status: We couldn't find a meta description tag on your homepage.", 'smartcrawl-seo' ),
			'',
			__( 'Overview:', 'smartcrawl-seo' ),
			__( 'The <meta name="description"> element provides a summary of a page\'s content that search engines include in search results. A high-quality, unique meta description makes your page appear more relevant and can increase your search traffic.', 'smartcrawl-seo' ),
			__( 'The audit fails if:', 'smartcrawl-seo' ),
			__( '- If your page doesn\'t have a <meta name="description"> element.', 'smartcrawl-seo' ),
			__( '- The content attribute of the <meta name="description"> element is empty.', 'smartcrawl-seo' ),
			'',
			__( 'For more information please check the SEO Audits section in SmartCrawl plugin.', 'smartcrawl-seo' ),
		);

		return implode( "\n", $parts );
	}
}
