<?php
/**
 * @var $model Smartcrawl_Model_Analysis Analysis model.
 *
 * @package SmartCrawl
 */

$model               = empty( $model ) ? null : $model;
$readability_data    = empty( $readability_data ) ? array() : $readability_data;
$readability_ignored = empty( $readability_ignored ) ? false : $readability_ignored;
$readability_score   = smartcrawl_get_array_value( $readability_data, 'score' );

if ( null === $model || null === $readability_score ) {
	return;
}

$readability_score      = intval( ceil( $readability_score ) );
$readability_level      = $model->get_readability_level();
$readability_levels_map = $model->get_readability_levels_map();

if ( $readability_ignored ) {
	$accordion_item_classes_array[] = 'disabled';
}

$total_possible_score           = '100';
$readability_level_description  = $model->get_readability_level_description( $readability_level );
$readability_state              = $model->get_kincaid_readability_state( $readability_score, $readability_ignored );
$state_class                    = sprintf(
	'sui-%s',
	$readability_state
);
$accordion_item_classes_array[] = $state_class;
$accordion_item_classes_array[] = sprintf(
	'wds-check-%s',
	$readability_state
);
$accordion_item_classes         = implode( ' ', $accordion_item_classes_array );
$refresh_analysis_disabled      = 'auto-draft' === get_post_status() ? 'disabled' : '';
$icon_class                     = 'success' === $readability_state
	? $state_class . ' sui-icon-check-tick'
	: $state_class . ' sui-icon-info';
$tag_class                      = sprintf(
	'sui-tag-%s',
	$readability_state
);
$whitelabel_class               = Smartcrawl_White_Label::get()->summary_class();
?>

<div
	class="wds-readability-report wds-report"
	data-readability-state="<?php echo esc_attr( $readability_state ); ?>"
>
	<div id="wds-readability-stats" class="sui-summary sui-summary-sm <?php echo esc_attr( $whitelabel_class ); ?>">
		<div class="sui-summary-image-space"></div>

		<div class="sui-summary-segment">
			<div class="sui-summary-details">
				<span class="sui-summary-large"><?php echo esc_html( $readability_score ); ?></span>
				<span class="<?php echo esc_attr( $icon_class ); ?>"></span>
				<span class="sui-summary-percent">/<?php echo esc_html( $total_possible_score ); ?></span>
				<span class="sui-summary-sub"><?php esc_html_e( 'Readability score', 'smartcrawl-seo' ); ?></span>
			</div>
		</div>

		<div class="sui-summary-segment">
			<?php if ( $readability_level_description ) : ?>
				<small><?php echo wp_kses( $readability_level_description, array( 'strong' => array() ) ); ?></small>
				<br/>
			<?php endif; ?>

			<button
				class="sui-button sui-button-ghost wds-refresh-analysis wds-analysis-readability wds-disabled-during-request"
				type="button" <?php echo esc_attr( $refresh_analysis_disabled ); ?>
			>
				<span class="sui-loading-text">
					<span class="sui-icon-update" aria-hidden="true"></span>

					<?php esc_html_e( 'Refresh', 'smartcrawl-seo' ); ?>
				</span>

				<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
			</button>
		</div>
	</div>

	<p class="wds-interstitial-text">
		<small>
			<strong><?php esc_html_e( 'Difficult', 'smartcrawl-seo' ); ?></strong> <?php esc_html_e( '= Less than 60', 'smartcrawl-seo' ); ?>
		</small>
		<small>
			<strong><?php esc_html_e( 'OK', 'smartcrawl-seo' ); ?></strong> <?php esc_html_e( '= 60 to 70', 'smartcrawl-seo' ); ?>
		</small>
		<small>
			<strong><?php esc_html_e( 'Easy', 'smartcrawl-seo' ); ?></strong> <?php esc_html_e( '= 70+', 'smartcrawl-seo' ); ?>
		</small>
	</p>

	<?php
	$this->render_view(
		'notice',
		array(
			'class'   => 'wds-analysis-working',
			'message' => esc_html__( 'Analyzing content, please wait a few moments', 'smartcrawl-seo' ),
		)
	);
	?>

	<?php
	$this->render_view(
		'metabox/metabox-readability-report-inner',
		array(
			'accordion_item_classes' => $accordion_item_classes,
			'readability_ignored'    => $readability_ignored,
			'icon_class'             => $icon_class,
			'tag_class'              => $tag_class,
			'readability_level'      => $readability_level,
			'readability_levels_map' => $readability_levels_map,
		)
	);
	?>

	<p class="wds-interstitial-text">
		<small><?php esc_html_e( 'More advanced readability tests coming soon.', 'smartcrawl-seo' ); ?></small>
	</p>
</div>
