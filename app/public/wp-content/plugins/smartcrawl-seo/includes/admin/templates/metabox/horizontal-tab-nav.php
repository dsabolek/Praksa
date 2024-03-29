<?php
$tab_items    = array();
$seo_sections = empty( $seo_sections ) ? array() : $seo_sections;
if ( $seo_sections ) {
	$tab_items['wds_seo'] = esc_html__( 'SEO', 'smartcrawl-seo' );
}

$readability_sections = empty( $readability_sections ) ? array() : $readability_sections;
if ( $readability_sections ) {
	$tab_items['wds_readability'] = esc_html__( 'Readability', 'smartcrawl-seo' );
}

$social_sections = empty( $social_sections ) ? array() : $social_sections;
if ( $social_sections ) {
	$tab_items['wds_social'] = esc_html__( 'Social', 'smartcrawl-seo' );
}

$advanced_sections = empty( $advanced_sections ) ? array() : $advanced_sections;
if ( $advanced_sections ) {
	$tab_items['wds_advanced'] = esc_html__( 'Advanced', 'smartcrawl-seo' );
}
$first_tab = true;
?>
<div data-tabs>
	<?php foreach ( $tab_items as $tab_id => $tab_name ) : ?>
		<div class="<?php echo $first_tab ? 'active' : ''; ?> <?php echo esc_attr( $tab_id ); ?>-tab">
			<?php echo wp_kses_post( apply_filters( 'wds-metabox-nav-item', $tab_name, $tab_id ) ); // phpcs:ignore ?>
		</div>
		<?php $first_tab = false; ?>
	<?php endforeach; ?>
</div>
