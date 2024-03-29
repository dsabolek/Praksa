<?php
if ( empty( $readability_sections ) ) {
	return false;
}
?>
<div class="wds-metabox-section">
	<p>
		<small><?php esc_html_e( 'We’ve analyzed your content to see how readable it is for the average person. Suggestions are based on best practice, but only you can decide what works for you and your readers.', 'smartcrawl-seo' ); ?></small>
	</p>

	<?php
	foreach ( $readability_sections as $template => $args ) {
		$this->render_view( $template, $args );
	}
	?>
</div>
