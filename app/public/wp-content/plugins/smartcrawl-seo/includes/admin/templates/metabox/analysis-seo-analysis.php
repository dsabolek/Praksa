<?php
/**
 * Template for SEO Analysis
 *
 * @var int    $post_id                  Post ID.
 * @var array  $primary_checks           Checks.
 * @var array  $extra_checks             Extra keyword checks.
 * @var int    $primary_error_count      Error count.
 * @var string $primary_keyword          Primary keyword.
 * @var array  $extra_keywords           Extra keywords.
 * @var bool   $focus_keywords_available Is focus keyword available.
 *
 * @package wpmu-dev-seo
 */

$primary_checks           = empty( $primary_checks ) ? array() : $primary_checks;
$primary_error_count      = empty( $primary_error_count ) ? 0 : $primary_error_count;
$focus_keywords_available = empty( $focus_keywords_available ) ? false : $focus_keywords_available;
/* translators: %s: Error count */
$pending_recommendations_message = _n(
	'You have %d SEO recommendation. We recommend you satisfy as many improvements as possible to ensure your content gets found.',
	'You have %d SEO recommendations. We recommend you satisfy as many improvements as possible to ensure your content gets found.',
	$primary_error_count,
	'smartcrawl-seo'
);

?>

<?php if ( $focus_keywords_available ) : ?>
	<div class="sui-tabs wds-seo-analysis">
		<div role="tablist" class="sui-tabs-menu">
			<button
				type="button"
				role="tab"
				id="wds-keywords-tab-<?php echo sanitize_html_class( $primary_keyword ); ?>"
				class="sui-tab-item active wds-seo-analysis-keyword-tab-item"
				aria-controls="wds-keywords-content-<?php echo sanitize_html_class( $primary_keyword ); ?>"
				aria-selected="true"
			>
				<?php if ( $primary_error_count > 0 ) : ?>
					<span aria-hidden="true" class="sui-warning sui-icon-info" style="pointer-events: none;"></span>
				<?php else : ?>
					<span aria-hidden="true" class="sui-success sui-icon-check-tick"></span>
				<?php endif; ?>
				<?php echo esc_html( $primary_keyword ); ?>
				<span class="sui-tag sui-tag-green" style="pointer-events: none;"><?php esc_attr_e( 'Primary', 'smartcrawl-seo' ); ?></span>
			</button>
			<?php foreach ( $extra_keywords as $keyword ) : ?>
				<?php $keyword_key = sanitize_html_class( $keyword ); ?>
				<?php if ( isset( $extra_checks[ $keyword_key ] ) ) : ?>
					<?php $errors = smartcrawl_get_array_value( $extra_checks[ $keyword_key ], 'errors' ); ?>
					<button
						type="button"
						role="tab"
						id="wds-keywords-tab-<?php echo sanitize_html_class( $keyword ); ?>"
						class="sui-tab-item wds-seo-analysis-keyword-tab-item"
						aria-controls="wds-keywords-content-<?php echo sanitize_html_class( $keyword ); ?>"
						aria-selected="false"
						tabindex="-1"
					>
						<?php if ( ! empty( $errors ) && count( $errors ) > 0 ) : ?>
							<span aria-hidden="true" class="sui-warning sui-icon-info" style="pointer-events: none;"></span>
						<?php else : ?>
							<span aria-hidden="true" class="sui-success sui-icon-check-tick" style="pointer-events: none;"></span>
						<?php endif; ?>
						<?php echo esc_html( $keyword ); ?>
					</button>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>

		<div class="sui-tabs-content">
			<div
				role="tabpanel"
				tabindex="0"
				id="wds-keywords-content-<?php echo sanitize_html_class( $primary_keyword ); ?>"
				class="sui-tab-content active"
				aria-labelledby="wds-keywords-tab-<?php echo sanitize_html_class( $primary_keyword ); ?>"
			>
				<?php
				$this->render_view(
					'metabox/analysis-seo-analysis-report',
					array(
						'errors'                  => array(),
						'checks'                  => $primary_checks,
						'error_count'             => $primary_error_count,
						'recommendations_message' => $pending_recommendations_message,
					)
				);
				?>
			</div>
			<?php foreach ( $extra_keywords as $keyword ) : ?>
				<?php $keyword_key = sanitize_html_class( $keyword ); ?>
				<?php if ( isset( $extra_checks[ $keyword_key ] ) ) : ?>
					<?php
					$errors = smartcrawl_get_array_value( $extra_checks[ $keyword_key ], 'errors' );
					$checks = smartcrawl_get_array_value( $extra_checks[ $keyword_key ], 'checks' );
					?>
					<div
						role="tabpanel"
						tabindex="0"
						id="wds-keywords-content-<?php echo $keyword_key; // phpcs:ignore ?>"
						class="sui-tab-content"
						aria-labelledby="wds-keywords-tab-<?php echo $keyword_key; // phpcs:ignore ?>"
						hidden
					>
						<?php
						$this->render_view(
							'metabox/analysis-seo-analysis-report',
							array(
								'checks'                  => $checks,
								'error_count'             => count( $errors ),
								'errors'                  => $errors,
								'recommendations_message' => $pending_recommendations_message,
							)
						);
						?>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	</div>
<?php else : ?>
	<div class="wds-seo-analysis wds-no-focus-keywords" data-errors="-1"></div>
<?php endif; ?>
