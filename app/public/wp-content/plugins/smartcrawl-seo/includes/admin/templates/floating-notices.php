<?php
$keys            = empty( $keys ) ? array() : $keys;
$settings_errors = empty( $this->option_name ) ? array() : get_settings_errors( $this->option_name );
$errors          = array(); // phpcs:ignore
foreach ( $settings_errors as $settings_error ) {
	$code = smartcrawl_get_array_value( $settings_error, 'code' );
	if ( $code ) {
		$errors[ $code ] = smartcrawl_get_array_value( $settings_error, 'message' ); // phpcs:ignore
	}
}
$message = empty( $message ) ? esc_html__( 'Settings updated', 'smartcrawl-seo' ) : $message;
?>
<div class="sui-floating-notices">
	<?php
	if ( isset( $_GET['settings-updated'] ) ) { // phpcs:ignore
		$this->render_view(
			'floating-notice',
			array(
				'code'      => 'wds-success-message',
				'type'      => 'success',
				'message'   => $message,
				'autoclose' => true,
			)
		);
	}

	foreach ( $errors as $code => $message ) {
		$this->render_view(
			'floating-notice',
			array(
				'code'      => $code,
				'type'      => 'error',
				'message'   => $message,
				'autoclose' => false,
			)
		);
	}

	foreach ( $keys as $key ) :
		?>
		<div
			role="alert"
			id="<?php echo esc_attr( $key ); ?>"
			class="sui-notice"
			aria-live="assertive"
		>
		</div>
	<?php endforeach; ?>
</div>
