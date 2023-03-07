<?php
$post                    = empty( $post ) ? null : $post; // phpcs:ignore
$refresh_button_disabled = 'auto-draft' === get_post_status() ? 'disabled' : '';
if ( $post ) {
	$post_id = $post->ID; // phpcs:ignore
} else {
	return;
}

$disable_add = ! empty( $focus_keywords ) && count( $focus_keywords ) > 2;

?>

<div class="wds-metabox-section">
	<div class="wds-seo-analysis-container">
		<div class="wds-seo-analysis-label">
			<strong><?php esc_html_e( 'SEO Analysis', 'smartcrawl-seo' ); ?></strong>

			<button <?php esc_attr( $refresh_button_disabled ); ?>
				class="sui-button sui-button-ghost wds-refresh-analysis wds-analysis-seo wds-disabled-during-request"
				type="button">
			<span class="sui-loading-text">
				<span class="sui-icon-update" aria-hidden="true"></span>

				<?php esc_html_e( 'Refresh', 'smartcrawl-seo' ); ?>
			</span>

				<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
			</button>
		</div>

		<div class="sui-box-body">
			<?php
			$this->render_view(
				'mascot-message',
				array(
					'key'     => 'metabox-seo-analysis',
					'message' => esc_html__( 'This tool helps you optimize your content to give it the best chance of being found in search engines when people are looking for it. Start by choosing a few focus keywords that best describe your article, then SmartCrawl will give you recommendations to make sure your content is highly optimized.', 'smartcrawl-seo' ),
				)
			);
			?>
		</div>

		<?php if ( apply_filters( 'wds-metabox-visible_parts-focus_area', true ) ) : // phpcs:ignore ?>
			<div class="wds-focus-keyword sui-border-frame sui-form-field">
				<label class="sui-label wds-label" for='wds_focus_input'>
					<?php esc_html_e( 'Focus keyword', 'smartcrawl-seo' ); ?>
				</label>
				<span class="sui-description wds-description">
					<?php _e( 'You can analyze the post content for up to 3 focus keywords. The SEO recommendations for each keyword will be displayed in separate tabs below. Enter each keyword you want to analyze and click the <strong>Add Keyword</strong> button, or enter multiple keywords separated by commas and click the <strong>Add Keyword</strong> button only once.', 'smartcrawl-seo' ); ?>
				</span>
				<div class="sui-with-button sui-with-button-inside">
					<input
						type="text"
						id="wds_focus_input"
						name="wds_focus_input"
						value=""
						class="wds-disabled-during-request sui-form-control"
						placeholder="<?php esc_html_e( 'E.g. broken iphone screen', 'smartcrawl-seo' ); ?>"
					/>
					<input
						type="hidden"
						id="wds_focus"
						name="wds_focus"
						readonly="readonly"
						value="<?php echo esc_html( smartcrawl_get_value( 'focus-keywords', $post_id ) ); ?>"
					/>
					<button
						type="button"
						class="sui-button"
						aria-live="polite"
						id="wds_add_keyword"
						<?php disabled( $disable_add ); ?>
					>
						<span class="sui-button-text-default"><?php esc_html_e( 'Add Keyword(s)', 'smartcrawl-seo' ); ?></span>
						<span class="sui-button-text-onload">
							<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
							<?php esc_html_e( 'Add Keyword(s)', 'smartcrawl-seo' ); ?>
						</span>
					</button>
				</div>

				<div class="wds-added-keywords-tags" id="wds-added-keywords-tags"></div>
				<?php
				$this->render_view(
					'notice',
					array(
						'message' => esc_html__( 'Analyzing content. Please wait a few moments.', 'smartcrawl-seo' ),
						'class'   => 'wds-analysis-working',
						'loading' => true,
					)
				);
				?>
			</div>
		<?php endif; ?>

		<?php do_action( 'wds-editor-metabox-seo-analysis', $post ); // phpcs:ignore ?>
	</div>
</div>
