<?php
$accordion_item_classes = empty( $accordion_item_classes ) ? '' : $accordion_item_classes;
$readability_ignored    = empty( $readability_ignored ) ? false : $readability_ignored;
$icon_class             = empty( $icon_class ) ? '' : $icon_class;
$tag_class              = empty( $tag_class ) ? '' : $tag_class;
$readability_level      = empty( $readability_level ) ? '' : $readability_level;
$readability_levels_map = empty( $readability_levels_map ) ? array() : $readability_levels_map;
?>

<div class="wds-report-inner">
	<div class="wds-accordion sui-accordion">
		<div class="wds-check-item sui-accordion-item <?php echo esc_attr( $accordion_item_classes ); ?>">
			<div class="<?php echo $readability_ignored ? 'wds-ignored-item-header' : 'sui-accordion-item-header'; ?>">
				<div class="sui-accordion-item-title sui-accordion-col-8">
					<span aria-hidden="true" class="<?php echo esc_attr( $icon_class ); ?>"></span>
					<?php esc_html_e( 'Flesch-Kincaid Test', 'smartcrawl-seo' ); ?>
				</div>

				<?php if ( $readability_ignored ) : ?>
					<div class="sui-accordion-col-4">
						<button
							type="button"
							class="wds-unignore wds-disabled-during-request sui-button sui-button-ghost"
							data-check_id="readability"
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
						<span class="sui-tag <?php echo esc_attr( $tag_class ); ?>">
							<?php echo esc_html( $readability_level ); ?>
						</span>

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
			<div class="sui-accordion-item-body">
				<div class="sui-box">
					<div class="sui-box-body">

						<small><strong><?php esc_html_e( 'Overview', 'smartcrawl-seo' ); ?></strong></small>
						<p>
							<small><?php esc_html_e( 'The Flesch-Kincaid readability tests are readability tests designed to indicate how difficult a passage is to understand. Here are the benchmarks.', 'smartcrawl-seo' ); ?></small>
						</p>
						<table class="sui-table">
							<thead>
							<tr>
								<th><?php esc_html_e( 'Score', 'smartcrawl-seo' ); ?></th>
								<th><?php esc_html_e( 'Description', 'smartcrawl-seo' ); ?></th>
							</tr>
							</thead>

							<tbody>
							<?php foreach ( $readability_levels_map as $label => $level ) : ?>
								<tr>
									<?php
									if ( ! is_array( $level ) || ! isset( $level['max'] ) || ! isset( $level['min'] ) ) {
										continue;
									}
									?>
									<td><?php echo esc_html( (int) ceil( $level['min'] ) ); ?>
										- <?php echo esc_html( (int) ceil( $level['max'] ) ); ?></td>
									<td><?php echo esc_html( $label ); ?></td>
								</tr>
							<?php endforeach; ?>
							</tbody>
						</table>

						<small><strong><?php esc_html_e( 'How to fix', 'smartcrawl-seo' ); ?></strong></small>
						<p>
							<small><?php esc_html_e( 'Try to use shorter sentences, with less difficult words to improve readability.', 'smartcrawl-seo' ); ?></small>
						</p>

						<div class="wds-ignore-container">
							<button
								type="button"
								class="wds-ignore wds-disabled-during-request sui-button sui-button-ghost"
								data-check_id="readability"
							>
								<span class="sui-loading-text">
									<span class="sui-icon-eye-hide" aria-hidden="true"></span>

									<?php esc_html_e( 'Ignore', 'smartcrawl-seo' ); ?>
								</span>

								<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
							</button>

							<span><small><?php esc_html_e( 'This will ignore warnings for this particular post.', 'smartcrawl-seo' ); ?></small></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
