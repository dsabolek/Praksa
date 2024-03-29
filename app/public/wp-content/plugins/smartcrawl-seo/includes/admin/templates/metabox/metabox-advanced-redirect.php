<?php
$redirect_url   = empty( $redirect_url ) ? '' : $redirect_url;
$has_permission = ! empty( $has_permission ) && $has_permission;
?>

<?php if ( apply_filters( 'wds-metabox-visible_parts-redirect_area', true ) && $has_permission ) : // phpcs:ignore ?>
	<div class="sui-box-settings-row">
		<div class="sui-box-settings-col-1">
			<label
				for="wds_redirect"
				class="sui-settings-label"
			><?php esc_html_e( '301 Redirect', 'smartcrawl-seo' ); ?></label>
			<p class="sui-description">
				<?php esc_html_e( 'Send visitors to this URL to another page.', 'smartcrawl-seo' ); ?>
			</p>
		</div>
		<div class="sui-box-settings-col-2">
			<input
				type='text'
				id='wds_redirect'
				name='wds_redirect'
				value='<?php echo esc_attr( $redirect_url ); ?>'
				class='wds sui-form-control'
			/>
			<span class="sui-description">
					<?php esc_html_e( 'Enter the URL to send traffic to including http:// or https://', 'smartcrawl-seo' ); ?>
				</span>
		</div>
	</div>
<?php endif; ?>
