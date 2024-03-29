<?php
$tax_meta = empty( $tax_meta ) ? array() : $tax_meta;
$term     = empty( $term ) ? null : $term; // phpcs:ignore
if ( ! $term ) {
	return;
}
$smartcrawl_term = Smartcrawl_Term_Cache::get()->get_term( $term->term_id );
if ( ! $smartcrawl_term ) {
	return;
}

$meta_title        = smartcrawl_get_array_value( $tax_meta, 'wds_title' );
$title_placeholder = $smartcrawl_term->get_meta_title();

$meta_desc        = smartcrawl_get_array_value( $tax_meta, 'wds_desc' );
$desc_placeholder = $smartcrawl_term->get_meta_description();
?>
<div class="wds-edit-meta">
	<a class="sui-button sui-button-ghost">
		<span class="sui-icon-pencil" aria-hidden="true"></span>

		<?php esc_html_e( 'Edit Meta', 'smartcrawl-seo' ); ?>
	</a>

	<div class="sui-border-frame" style="display: none;">
		<div class="sui-form-field">
			<label class="sui-label" for="wds_title">
				<?php esc_html_e( 'SEO Title', 'smartcrawl-seo' ); ?>
			</label>
			<input
				type="text"
				id="wds_title"
				name="wds_title"
				placeholder="<?php echo esc_attr( $title_placeholder ); ?>"
				value="<?php echo esc_attr( $meta_title ); ?>"
				class="sui-form-control wds-meta-field"
			/>
			<p class="sui-description">
				<?php esc_html_e( 'The SEO title is used on the archive page for this term.', 'smartcrawl-seo' ); ?>
			</p>
		</div>

		<div class="sui-form-field">
			<label class="sui-label" for="wds_metadesc">
				<?php esc_html_e( 'Description', 'smartcrawl-seo' ); ?>
			</label>
			<textarea
				name="wds_desc"
				id="wds_metadesc"
				placeholder="<?php echo esc_attr( $desc_placeholder ); ?>"
				class="sui-form-control wds-meta-field"
			><?php echo esc_textarea( $meta_desc ); ?></textarea>
			<p class="sui-description">
				<?php esc_html_e( 'The SEO description is used for the meta description on the archive page for this term.', 'smartcrawl-seo' ); ?>
			</p>
		</div>
	</div>
</div>
