<?php
$smartcrawl_options = Smartcrawl_Settings::get_options();
$access_id          = Smartcrawl_Settings::get_setting( 'access-id' );
$secret_key         = Smartcrawl_Settings::get_setting( 'secret-key' );

$image_url = sprintf( '%s/assets/images/graphic-moz-disabled.svg', SMARTCRAWL_PLUGIN_URL );
$image_url = Smartcrawl_White_Label::get()->get_wpmudev_hero_image( $image_url );
?>

<?php if ( empty( $access_id ) || empty( $secret_key ) ) : ?>
	<div class="wds-disabled-component">
		<?php if ( $image_url ) : ?>
			<p>
				<img
					src="<?php echo esc_attr( $image_url ); ?>"
					alt="<?php esc_attr_e( 'MOZ Disabled', 'smartcrawl-seo' ); ?>"
					class="wds-disabled-image"
				/>
			</p>
		<?php endif; ?>
		<p>
			<?php esc_html_e( 'Moz provides reports that tell you how your site stacks up against the competition with all of', 'smartcrawl-seo' ); ?>
			<br/><?php esc_html_e( 'the important SEO measurement tools - ranking, links, and much more.', 'smartcrawl-seo' ); ?>
		</p>
	</div>
	<div class="wds-moz-api-credentials">
		<form method="POST" class="wds-form">
			<div class="wds-moz-fields">
				<div class="wds-moz-fields-inner">
					<p class="sui-p-small">
						<?php
						printf(
							/* translators: %s: Url to get the Moz account API credentials */
							esc_html__( 'Connect your Moz account. You can get the API credentials %s.', 'smartcrawl-seo' ),
							sprintf( '<a href="https://moz.com/products/mozscape/access" target="_blank">%s</a>', esc_html__( 'here', 'smartcrawl-seo' ) )
						);
						?>
					</p>

					<div class="sui-form-field">
						<label
							class="sui-label"
							for="wds-moz-access-id"><?php esc_html_e( 'Access ID', 'smartcrawl-seo' ); ?></label>
						<input
							type="text"
							id="wds-moz-access-id"
							name="wds-moz-access-id"
							class="sui-form-control"
							placeholder="<?php esc_attr_e( 'Enter your Moz Access ID', 'smartcrawl-seo' ); ?>"
							value="<?php echo esc_attr( $access_id ); ?>"/>
						<span class="sui-error-message"><?php esc_html_e( 'Please enter a valid Moz Access ID', 'smartcrawl-seo' ); ?></span>
					</div>

					<div class="sui-form-field">
						<label
							class="sui-label"
							for="wds-moz-secret-key"><?php esc_html_e( 'Secret Key', 'smartcrawl-seo' ); ?></label>
						<input
							type="text"
							id="wds-moz-secret-key"
							name="wds-moz-secret-key"
							class="sui-form-control"
							placeholder="<?php esc_attr_e( 'Enter your Moz Secret Key', 'smartcrawl-seo' ); ?>"
							value="<?php echo esc_attr( $secret_key ); ?>"/>
						<span class="sui-error-message"><?php esc_html_e( 'Please enter a valid Moz Secret Key', 'smartcrawl-seo' ); ?></span>
					</div>
					<button
						type="submit"
						class="sui-button sui-button-blue">

						<span class="sui-loading-text"><?php esc_html_e( 'Connect', 'smartcrawl-seo' ); ?></span>
						<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
					</button>
					<?php wp_nonce_field( 'wds-settings-nonce', '_wds_nonce' ); ?>
				</div>
			</div>

			<p class="wds-moz-signup-notice">
				<small>
					<?php
					printf(
						/* translators: %s: Url to signup Moz account */
						esc_html__( "Don't have an account yet? %s.", 'smartcrawl-seo' ),
						sprintf( '<a href="https://moz.com/community/join" target="_blank">%s</a>', esc_html__( 'Sign up free', 'smartcrawl-seo' ) )
					);
					?>
				</small>
			</p>
		</form>
	</div>
<?php else : ?>
	<p><?php esc_html_e( 'Here’s how your site stacks up against the competition as defined by Moz. You can also see individual stats per post in the post editor under the Moz module.', 'smartcrawl-seo' ); ?></p>

	<button
		type="submit" class="sui-button"
		name="reset-moz-credentials"
		value="1"><?php esc_html_e( 'Reset API Credentials', 'smartcrawl-seo' ); ?></button>
	<?php wp_nonce_field( 'wds-autolinks-nonce', '_wds_nonce' ); ?>
	<?php Smartcrawl_Moz_Dashboard_Widget::widget(); ?>
<?php endif; ?>
