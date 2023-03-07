<?php
$option_name          = empty( $_view['option_name'] ) ? '' : $_view['option_name'];
$strong               = '<strong>%s</strong>';
$analysis_strategy    = Smartcrawl_Controller_Analysis_Content::get()->get_analysis_strategy();
$is_strategy_strict   = Smartcrawl_Controller_Analysis_Content::STRATEGY_STRICT === $analysis_strategy;
$is_strategy_moderate = Smartcrawl_Controller_Analysis_Content::STRATEGY_MODERATE === $analysis_strategy;
$is_strategy_manual   = Smartcrawl_Controller_Analysis_Content::STRATEGY_MANUAL === $analysis_strategy;
$is_strategy_loose    = Smartcrawl_Controller_Analysis_Content::STRATEGY_LOOSE === $analysis_strategy;
// Check if current language is supported for readability analysis.
$lang_supported = Smartcrawl_Controller_Readability::get()->is_language_supported();
?>

<div class="sui-box-settings-row">
	<div class="sui-box-settings-col-1">
		<label class="sui-settings-label"><?php esc_html_e( 'In-Post Analysis', 'smartcrawl-seo' ); ?></label>
		<p class="sui-description">
			<?php esc_html_e( 'These modules appear inside the WordPress Post Editor and provide per-page SEO and Readability analysis to fine tune each post to focus keywords.', 'smartcrawl-seo' ); ?>
		</p>
	</div>

	<div class="sui-box-settings-col-2">
		<label class="sui-settings-label"><?php esc_html_e( 'Visibility', 'smartcrawl-seo' ); ?></label>
		<p class="sui-description"><?php esc_html_e( 'SEO Analysis benchmarks your content against recommend SEO practice and gives recommendations for improvement to make sure content is as optimized as possible. Readibility Analysis uses the Flesch-Kincaid test to determine how easy your content is to read.', 'smartcrawl-seo' ); ?></p>
		<?php
		$this->render_view(
			'toggle-item',
			array(
				'item_value' => 'analysis-seo',
				'field_name' => "{$option_name}[analysis-seo]",
				'field_id'   => 'analysis-seo',
				'checked'    => ! empty( $_view['options']['analysis-seo'] ),
				'item_label' => esc_html__( 'Page Analysis', 'smartcrawl-seo' ),
			)
		);
		$this->render_view(
			'toggle-item',
			array(
				'item_value' => 'analysis-readability',
				'field_name' => "{$option_name}[analysis-readability]",
				'field_id'   => 'analysis-readability',
				'checked'    => ! empty( $_view['options']['analysis-readability'] ),
				'item_label' => esc_html__( 'Readability Analysis', 'smartcrawl-seo' ),
			)
		);
		if ( ! $lang_supported ) {
			$this->render_view(
				'notice',
				array(
					'class'   => 'sui-notice-yellow',
					'message' => sprintf(
					// translators: %s link to documentation.
						__( 'This feature may not work as expected as our SEO analysis engine doesn\'t support your current site language. For better results, change the language in WordPress settings to one of the <a href="%s" target="_blank">supported languages</a>.', 'smartcrawl-seo' ),
						'https://wpmudev.com/docs/wpmu-dev-plugins/smartcrawl/#in-post-analysis'
					),
				)
			);
		}
		?>

		<label class="sui-settings-label"><?php esc_html_e( 'Engine', 'smartcrawl-seo' ); ?></label>
		<p class="sui-description"><?php esc_html_e( 'Choose how you want SmartCrawl to analyze your content.', 'smartcrawl-seo' ); ?></p>
		<p class="sui-description">
			<?php
			printf(
			/* translators: %s: "Content" within <strong> tag */
				esc_html__( '%s is recommended for most websites as it only reviews the_content() output.', 'smartcrawl-seo' ),
				sprintf( $strong, esc_html__( 'Content', 'smartcrawl-seo' ) )
			);
			?>
		</p>
		<p class="sui-description">
			<?php
			printf(
			/* translators: %s: "Wide" within <strong> tag */
				esc_html__( '%s includes everything, except for your header, nav, footer and sidebars. This can be helpful for page builders and themes with custom output.', 'smartcrawl-seo' ),
				sprintf( $strong, esc_html__( 'Wide', 'smartcrawl-seo' ) ) // phpcs:ignore
			);
			?>
		</p>
		<p class="sui-description">
			<?php
			printf(
			/* translators: %s: "All" within <strong> tag */
				esc_html__( '%s checks your entire page’s content including elements like nav and footer. Due to analysing everything you might miss key analysis of your real content so we don’t recommend this approach.', 'smartcrawl-seo' ),
				sprintf( $strong, esc_html__( 'All', 'smartcrawl-seo' ) ) // phpcs:ignore
			);
			?>
		</p>
		<p class="sui-description">
			<?php
			printf(
			/* translators: %s: "None" within <strong> tag */
				esc_html__( '%s only analyzes content you tell it to programmatically. If you have a fully custom setup, this is the option for you. Read the documentation.', 'smartcrawl-seo' ),
				sprintf( $strong, esc_html__( 'None', 'smartcrawl-seo' ) ) // phpcs:ignore
			);
			?>
		</p>

		<div class="wds-analysis-strategy-tabs sui-side-tabs sui-tabs">
			<div class="sui-tabs-menu">
				<label class="wds-strategy-strict sui-tab-item <?php echo $is_strategy_strict ? 'active' : ''; ?>">
					<?php esc_html_e( 'Content', 'smartcrawl-seo' ); ?>
					<input
						name="<?php echo esc_attr( $option_name ); ?>[analysis_strategy]"
						value="<?php echo esc_attr( Smartcrawl_Controller_Analysis_Content::STRATEGY_STRICT ); ?>"
						type="radio" <?php checked( $is_strategy_strict ); ?>
						class="hidden"
					/>
				</label>
				<label class="wds-strategy-moderate sui-tab-item <?php echo $is_strategy_moderate ? 'active' : ''; ?>">
					<?php esc_html_e( 'Wide', 'smartcrawl-seo' ); ?>
					<input
						name="<?php echo esc_attr( $option_name ); ?>[analysis_strategy]"
						value="<?php echo esc_attr( Smartcrawl_Controller_Analysis_Content::STRATEGY_MODERATE ); ?>"
						type="radio" <?php checked( $is_strategy_moderate ); ?>
						class="hidden"
					/>
				</label>
				<label class="wds-strategy-loose sui-tab-item <?php echo $is_strategy_loose ? 'active' : ''; ?>">
					<?php esc_html_e( 'All', 'smartcrawl-seo' ); ?>
					<input
						name="<?php echo esc_attr( $option_name ); ?>[analysis_strategy]"
						value="<?php echo esc_attr( Smartcrawl_Controller_Analysis_Content::STRATEGY_LOOSE ); ?>"
						type="radio" <?php checked( $is_strategy_loose ); ?>
						class="hidden"
					/>
				</label>
				<label class="wds-strategy-manual sui-tab-item <?php echo $is_strategy_manual ? 'active' : ''; ?>">
					<?php esc_html_e( 'None', 'smartcrawl-seo' ); ?>
					<input
						name="<?php echo esc_attr( $option_name ); ?>[analysis_strategy]"
						value="<?php echo esc_attr( Smartcrawl_Controller_Analysis_Content::STRATEGY_MANUAL ); ?>"
						type="radio" <?php checked( $is_strategy_manual ); ?>
						class="hidden"
					/>
				</label>
			</div>
		</div>

		<?php
		$this->render_view(
			'notice',
			array(
				'message' => sprintf(
				/* translators: 1: "None" within <strong> tag, 2: Class selector */
					esc_html__( 'Custom setup? Choose the %1$s method and add the class %2$s to container elements you want to include in the SEO and Readability Analysis.', 'smartcrawl-seo' ),
					'<strong>' . esc_html__( 'None', 'smartcrawl-seo' ) . '</strong>',
					'<strong>' . esc_html( '.smartcrawl-checkup-included' ) . '</strong>'
				),
				'class'   => 'grey',
			)
		);
		?>

		<?php
		$this->render_view(
			'toggle-item',
			array(
				'item_value'       => 'disable-analysis-on-list',
				'field_name'       => "{$option_name}[disable-analysis-on-list]",
				'field_id'         => 'disable-analysis-on-list',
				'checked'          => ! empty( $_view['options']['disable-analysis-on-list'] ),
				'item_label'       => __( 'Disable Page Analysis Check on Pages/Posts Screen', 'smartcrawl-seo' ),
				'item_description' => __( 'By default, posts and pages are analyzed one at a time to avoid excessive server load. You can use this option to disable these checks on the pages and posts screens.', 'smartcrawl-seo' ),
			)
		);
		?>
	</div>
</div>
