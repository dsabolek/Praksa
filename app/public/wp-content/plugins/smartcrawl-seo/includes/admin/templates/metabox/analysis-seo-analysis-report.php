<?php
/**
 * Template for SEO Analysis
 *
 * @var array  $checks                  Checks.
 * @var int    $error_count             Error count.
 * @var array  $errors                  Errors (only for secondary keyword).
 * @var string $recommendations_message Message.
 *
 * @package SmartCrawl
 */

?>
<div class="wds-report" data-errors="<?php echo esc_attr( $error_count ); ?>">
	<div class="wds-report-inner">
		<?php
		$this->render_view(
			'notice',
			array(
				'message' => $error_count > 0
					? sprintf( $recommendations_message, $error_count )
					: esc_html__( 'All SEO recommendations are met. Your content is as optimized as possible - nice work!', 'smartcrawl-seo' ),
				'class'   => $error_count > 0 ? 'sui-notice-warning' : 'sui-notice-success',
			)
		);
		?>
		<div class="wds-accordion sui-accordion">
			<?php foreach ( $checks as $check_id => $result ) : ?>
				<?php
				$passed         = $result['status'];
				$ignored        = $result['ignored'];
				$recommendation = $result['recommendation'];
				$more_info      = $result['more_info'];
				$status_msg     = $result['status_msg'];

				$classes_array = array();
				if ( $ignored ) {
					$classes_array[] = 'wds-check-invalid disabled';
					$icon_class      = 'sui-icon-info';
				} else {
					$state_class     = $passed ? 'sui-success' : 'sui-warning';
					$icon_class      = $passed
						? $state_class . ' sui-icon-check-tick'
						: $state_class . ' sui-icon-info';
					$classes_array[] = $state_class;
					$classes_array[] = $passed ? 'wds-check-success' : 'wds-check-warning';
					// Special case for some.
					if ( 'title_secondary_keywords' === $check_id && ! $passed ) {
						$classes_array = array();
						$icon_class    = 'sui-icon-info';
					}
				}
				$classes = implode( ' ', $classes_array );
				?>
				<div
					id="wds-check-<?php echo esc_attr( $check_id ); ?>"
					class="wds-check-item sui-accordion-item <?php echo esc_attr( $classes ); ?>"
				>
					<div class="<?php echo $ignored ? 'wds-ignored-item-header' : 'sui-accordion-item-header'; ?>">
						<div class="sui-accordion-item-title sui-accordion-col-8">
							<span aria-hidden="true" class="<?php echo esc_attr( $icon_class ); ?>"></span>
							<?php echo wp_kses_post( $status_msg ); ?>
						</div>
						<?php if ( $ignored ) : ?>
							<div class="sui-accordion-col-4">
								<button
									type="button"
									id="wds-unignore-check-<?php echo esc_attr( $check_id ); ?>"
									class="wds-unignore wds-disabled-during-request sui-button sui-button-ghost"
									data-check_id="<?php echo esc_attr( $check_id ); ?>"
								>
									<span class="sui-loading-text">
										<span class="sui-icon-undo" aria-hidden="true"></span>
										<?php esc_html_e( 'Restore', 'smartcrawl-seo' ); ?>
									</span>
									<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
								</button>
							</div>
						<?php else : ?>
							<div class="sui-accordion-col-4">
								<button
									class="sui-button-icon sui-accordion-open-indicator"
									type="button"
									aria-label="<?php esc_html_e( 'Open item', 'smartcrawl-seo' ); ?>"
								>
									<span class="sui-icon-chevron-down" aria-hidden="true"></span>
								</button>
							</div>
						<?php endif; ?>
					</div>
					<div class="sui-accordion-item-body wds-check-item-content">
						<div class="sui-box">
							<div class="sui-box-body">

								<?php if ( $recommendation ) : ?>
									<div class="wds-recommendation">
										<div>
											<strong><?php esc_html_e( 'Recommendation', 'smartcrawl-seo' ); ?></strong>
										</div>
										<p><?php echo wp_kses_post( $recommendation ); ?></p>
									</div>
								<?php endif; ?>

								<?php if ( $more_info ) : ?>
									<div class="wds-more-info">
										<div>
											<strong><?php esc_html_e( 'More Info', 'smartcrawl-seo' ); ?></strong>
										</div>
										<p><?php echo wp_kses_post( $more_info ); ?></p>
									</div>
								<?php endif; ?>

								<?php if ( ! $ignored ) : ?>
									<div class="wds-ignore-container">
										<button
											type="button"
											id="wds-ignore-check-<?php echo esc_attr( $check_id ); ?>"
											class="wds-ignore wds-disabled-during-request sui-button sui-button-ghost"
											data-check_id="<?php echo esc_attr( $check_id ); ?>"
										>
											<span class="sui-loading-text">
												<span class="sui-icon-eye-hide" aria-hidden="true"></span>
												<?php esc_html_e( 'Ignore', 'smartcrawl-seo' ); ?>
											</span>
											<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
										</button>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<div class="cf"></div>
	</div>
</div>
