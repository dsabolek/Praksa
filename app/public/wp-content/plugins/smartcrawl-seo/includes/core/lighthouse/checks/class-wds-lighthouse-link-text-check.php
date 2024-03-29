<?php

class Smartcrawl_Lighthouse_Link_Text_Check extends Smartcrawl_Lighthouse_Check {
	const ID = 'link-text';

	/**
	 * @return void
	 */
	public function prepare() {
		$this->set_success_title( esc_html__( 'Links have descriptive text', 'smartcrawl-seo' ) );
		$this->set_failure_title( esc_html__( 'Links do not have descriptive text', 'smartcrawl-seo' ) );
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
		<p><?php esc_html_e( 'Link text is the clickable word or phrase in a hyperlink. When link text clearly conveys a hyperlink\'s target, both users and search engines can more easily understand your content and how it relates to other pages.', 'smartcrawl-seo' ); ?></p>
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
					'message' => esc_html__( 'All your links have descriptive text, nice work.', 'smartcrawl-seo' ),
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
		<div class="wds-lh-section cf">
			<?php $this->print_common_description(); ?>

			<p><?php esc_html_e( 'Lighthouse flags the following generic link text:', 'smartcrawl-seo' ); ?></p>
			<ul style="float: left; width: 30%; margin-bottom: 0;">
				<li><?php esc_html_e( 'click here', 'smartcrawl-seo' ); ?></li>
				<li><?php esc_html_e( 'click this', 'smartcrawl-seo' ); ?></li>
				<li><?php esc_html_e( 'go', 'smartcrawl-seo' ); ?></li>
			</ul>
			<ul style="float: left; width: 30%; margin-bottom: 0;">
				<li><?php esc_html_e( 'here', 'smartcrawl-seo' ); ?></li>
				<li><?php esc_html_e( 'this', 'smartcrawl-seo' ); ?></li>
				<li><?php esc_html_e( 'start', 'smartcrawl-seo' ); ?></li>
			</ul>
			<ul style="float: left; width: 30%; margin-bottom: 0;">
				<li><?php esc_html_e( 'right here', 'smartcrawl-seo' ); ?></li>
				<li><?php esc_html_e( 'more', 'smartcrawl-seo' ); ?></li>
				<li><?php esc_html_e( 'learn more', 'smartcrawl-seo' ); ?></li>
			</ul>
		</div>

		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'Status', 'smartcrawl-seo' ); ?></strong>
			<?php
			Smartcrawl_Simple_Renderer::render(
				'notice',
				array(
					'class'   => 'sui-notice-warning',
					'message' => esc_html__( 'Some links are empty and without helpful descriptive text.', 'smartcrawl-seo' ),
				)
			);
			?>

			<?php $this->print_details_table(); ?>
		</div>

		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'How to add descriptive link text', 'smartcrawl-seo' ); ?></strong>
			<p>
				<?php esc_html_e( 'Replace generic phrases like "click here" and "learn more" with specific descriptions. In general, write link text that clearly indicates what type of content users will get if they follow the hyperlink.', 'smartcrawl-seo' ); ?>
			</p>
		</div>

		<div class="wds-lh-toggle-container">
			<a class="wds-lh-toggle" href="#">
				<?php esc_html_e( 'Read More - Best practices' ); ?>
			</a>

			<div class="wds-lh-section">
				<strong><?php esc_html_e( 'Link text best practices', 'smartcrawl-seo' ); ?></strong>
				<ul>
					<li><?php esc_html_e( "Stay on topic. Don't use link text that has no relation to the page's content.", 'smartcrawl-seo' ); ?></li>
					<li><?php esc_html_e( "Don't use the page's URL as the link description unless you have a good reason to do so, such as referencing a site's new address.", 'smartcrawl-seo' ); ?></li>
					<li><?php esc_html_e( 'Keep descriptions concise. Aim for a few words or a short phrase.', 'smartcrawl-seo' ); ?></li>
					<li><?php esc_html_e( 'Pay attention to your internal links too. Improving the quality of internal links can help both users and search engines navigate your site more easily.', 'smartcrawl-seo' ); ?></li>
				</ul>

				<div class="wds-lh-highlight-container">
					<p>
						<strong
							class="wds-lh-red-word"><?php esc_html_e( 'Don’t. ' ); ?></strong>
						<?php esc_html_e( '"Click here" doesn\'t convey where the hyperlink will take users.', 'smartcrawl-seo' ); ?>
					</p>
					<div class="wds-lh-highlight wds-lh-highlight-error">
						<?php
						echo join(
							'',
							array(
								$this->tag( '<p>' ),
								esc_html__( 'To see all of our basketball videos, ', 'smartcrawl-seo' ),
								$this->tag( '<a ' ),
								$this->attr( 'href=' ),
								$this->tag( '"videos.html">' ),
								esc_html__( 'click here', 'smartcrawl-seo' ),
								$this->tag( '</a>.</p>' ),
							)
						);
						?>
					</div>

					<p>
						<strong
							class="wds-lh-green-word"><?php esc_html_e( 'Do. ' ); ?></strong>
						<?php esc_html_e( '"Basketball videos" clearly conveys that the hyperlink will take users to a page of videos.' ); ?>
					</p>
					<div class="wds-lh-highlight wds-lh-highlight-success">
						<?php
						echo join(
							'',
							array(
								$this->tag( '<p>' ),
								esc_html__( 'Check out all of our ', 'smartcrawl-seo' ),
								$this->tag( '<a ' ),
								$this->attr( 'href=' ),
								$this->tag( '"videos.html">' ),
								esc_html__( 'basketball videos', 'smartcrawl-seo' ),
								$this->tag( '</a>.</p>' ),
							)
						);
						?>
					</div>
				</div>

				<?php
				printf(
					/* translators: 1, 2: Links to documentation */
					esc_html__( 'See the %1$s section of %2$s for more tips.', 'smartcrawl-seo' ),
					sprintf(
						'<a target="%s" href="%s">%s</a>',
						'_blank',
						esc_url_raw( 'https://developers.google.com/search/docs/beginner/seo-starter-guide#use-links-wisely' ),
						esc_html__( 'Use links wisely', 'smartcrawl-seo' )
					),
					sprintf(
						'<a target="%s" href="%s">%s</a>',
						'_blank',
						esc_url_raw( 'https://developers.google.com/search/docs/beginner/seo-starter-guide' ),
						esc_html__( "Google's Search Engine Optimization (SEO) Starter Guide", 'smartcrawl-seo' )
					)
				);
				?>
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
	 * @param $raw_details
	 *
	 * @return Smartcrawl_Lighthouse_Table
	 */
	public function parse_details( $raw_details ) {
		$table = new Smartcrawl_Lighthouse_Table(
			array(
				esc_html__( 'Link Text', 'smartcrawl-seo' ) . $this->get_link_text_tooltip(),
				esc_html__( 'Link Destination', 'smartcrawl-seo' ),
			),
			$this->get_report()
		);

		$items = smartcrawl_get_array_value( $raw_details, 'items' );
		foreach ( $items as $item ) {
			$table->add_row(
				array(
					smartcrawl_get_array_value( $item, 'text' ),
					smartcrawl_get_array_value( $item, 'href' ),
				)
			);
		}

		return $table;
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
				__( 'Audit Type: Content audits', 'smartcrawl-seo' ),
				'',
				__( 'Failing Audit: Links do not have descriptive text', 'smartcrawl-seo' ),
				'',
				__( 'Status: Some links are empty and without helpful descriptive text.', 'smartcrawl-seo' ),
				'',
			),
			$this->get_flattened_details(),
			array(
				'',
				__( 'Overview:', 'smartcrawl-seo' ),
				__( "Link text is the clickable word or phrase in a hyperlink. When link text clearly conveys a hyperlink's target, both users and search engines can more easily understand your content and how it relates to other pages.", 'smartcrawl-seo' ),
				__( 'Lighthouse flags the following generic link text: click here, click this, go,here,this,start,right here,more and learn more', 'smartcrawl-seo' ),
				'',
				__( 'For more information please check the SEO Audits section in SmartCrawl plugin.', 'smartcrawl-seo' ),
			)
		);

		return implode( "\n", $parts );
	}
}
