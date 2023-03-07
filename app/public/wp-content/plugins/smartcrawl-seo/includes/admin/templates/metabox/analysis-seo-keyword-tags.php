<?php
/**
 * Template for SEO Analysis keyword tags.
 *
 * @var array $keywords Focus keywords.
 *
 * @package SmartCrawl
 */

?>

<?php if ( ! empty( $keywords ) ) : ?>
	<label class="sui-label"><?php esc_attr_e( 'Added keywords', 'smartcrawl-seo' ); ?></label>
	<div class="sui-pagination-active-filters">
		<?php foreach ( $keywords as $keyword ) : ?>
			<span class="sui-active-filter">
				<?php echo esc_html( $keyword ); ?>
				<span
					role="button"
					class="sui-active-filter-remove wds-remove-keyword"
					data-keyword="<?php echo esc_html( $keyword ); ?>"
				></span>
			</span>
		<?php endforeach; ?>
	</div>
<?php else : ?>
	<?php
	$this->render_view(
		'notice',
		array(
			'message' => esc_html__( 'You need to add focus keywords to see recommendations for this article.', 'smartcrawl-seo' ),
			'class'   => 'sui-notice-inactive',
		)
	);
	?>
<?php endif; ?>
