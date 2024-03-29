<?php
// All values passed to this template are expected to be escaped already so phpcs is disabled
// phpcs:ignoreFile
$source        = empty( $source ) ? '' : $source;
$destination   = empty( $destination ) ? '' : $destination;
$selected_type = empty( $selected_type ) ? '' : $selected_type;
$index         = empty( $index ) ? 0 : $index;

$option_name = 'wds_autolinks_options';
?>

<div
	data-index="<?php echo esc_attr( $index ); ?>"
	class="sui-builder-field wds-redirect-item">

	<label class="sui-checkbox">
		<input
			type="checkbox"
			name="<?php echo esc_attr( $option_name ); ?>[bulk][]"
			value="<?php echo esc_attr( $index ); ?>"/>
		<span aria-hidden="true"></span>
	</label>

	<div class="sui-builder-field-label">
		<span><?php echo esc_html( $source ); ?></span>
	</div>

	<small><?php echo esc_html( $destination ); ?></small>
	<span class="wds-redirect-type-label wds-redirect-type-label-<?php echo esc_attr( $selected_type ); ?>">
        <small><?php esc_html_e( 'Permanent', 'smartcrawl-seo' ); ?></small>
        <small><?php esc_html_e( 'Temporary', 'smartcrawl-seo' ); ?></small>
    </span>

	<?php $this->render_view( 'links-dropdown', array(
		'label' => esc_html__( 'Options', 'smartcrawl-seo' ),
		'links' => array(
			'#edit'   => '<span class="sui-icon-pencil" aria-hidden="true"></span> ' . esc_html__( 'Edit', 'smartcrawl-seo' ),
			'#remove' => '<span class="sui-icon-trash" aria-hidden="true"></span> ' . esc_html__( 'Remove', 'smartcrawl-seo' ),
		),
	) ); ?>

	<input
		value="<?php echo esc_attr( $source ); ?>"
		type="hidden"
		class="wds-source-url"
		name="<?php echo esc_attr( $option_name ); ?>[urls][<?php echo esc_attr( $index ); ?>][source]"/>

	<input
		value="<?php echo esc_attr( $destination ); ?>"
		type="hidden"
		class="wds-destination-url"
		name="<?php echo esc_attr( $option_name ); ?>[urls][<?php echo esc_attr( $index ); ?>][destination]"/>

	<input
		value="<?php echo esc_attr( $selected_type ); ?>"
		type="hidden"
		class="wds-redirect-type"
		name="<?php echo esc_attr( $option_name ); ?>[urls][<?php echo esc_attr( $index ); ?>][type]"/>
</div>
