<?php
if ( ! Smartcrawl_Settings_Admin::is_tab_allowed( Smartcrawl_Settings::TAB_SOCIAL ) ) {
	return;
}

$options                       = $_view['options'];
$og_enabled                    = smartcrawl_get_array_value( $options, 'og-enable' );
$twitter_card_enabled          = smartcrawl_get_array_value( $options, 'twitter-card-enable' );
$twitter_card_type             = smartcrawl_get_array_value( $options, 'twitter-card-type' );
$twitter_card_status_text      = Smartcrawl_Twitter_Printer::CARD_IMAGE === $twitter_card_type ? esc_html__( 'Summary Card with Large Image', 'smartcrawl-seo' ) : esc_html__( 'Summary Card', 'smartcrawl-seo' );
$pinterest_verification_status = smartcrawl_get_array_value( $options, 'pinterest-verification-status' );
$pinterest_tag                 = smartcrawl_get_array_value( $options, 'pinterest-verify' );

$social_page_url      = Smartcrawl_Settings_Admin::admin_url( Smartcrawl_Settings::TAB_SOCIAL );
$social_option_name   = Smartcrawl_Settings::TAB_SOCIAL . '_options';
$settings_option_name = Smartcrawl_Settings::TAB_SETTINGS . '_options';
$social_enabled       = Smartcrawl_Settings::get_setting( 'social' );
?>
<section id="<?php echo esc_attr( Smartcrawl_Settings_Dashboard::BOX_SOCIAL ); ?>" class="sui-box wds-dashboard-widget">
	<div class="sui-box-header">
		<h2 class="sui-box-title">
			<span class="sui-icon-social-twitter" aria-hidden="true"></span> <?php esc_html_e( 'Social', 'smartcrawl-seo' ); ?>
		</h2>
	</div>

	<div class="sui-box-body">
		<p><?php esc_html_e( 'Control and optimize how your website appears when shared on social platforms like Facebook and Twitter.', 'smartcrawl-seo' ); ?></p>

		<?php if ( $social_enabled ) : ?>
			<div class="wds-separator-top wds-draw-left-padded">
				<small><strong><?php esc_html_e( 'OpenGraph', 'smartcrawl-seo' ); ?></strong></small>
				<?php if ( ! $og_enabled ) : ?>
					<p>
						<small><?php esc_html_e( 'Add meta data to your pages to make them look great when shared platforms such as Facebook and other popular social networks.', 'smartcrawl-seo' ); ?></small>
					</p>
					<button
						type="button"
						data-option-id="<?php echo esc_attr( $social_option_name ); ?>"
						data-flag="<?php echo 'og-enable'; ?>"
						aria-label="<?php esc_html_e( 'Activate OpenGraph', 'smartcrawl-seo' ); ?>"
						class="wds-activate-component wds-disabled-during-request sui-button sui-button-blue">
						<span class="sui-loading-text"><?php esc_html_e( 'Activate', 'smartcrawl-seo' ); ?></span>
						<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
					</button>
				<?php else : ?>
					<div class="wds-right">
						<small><?php esc_html_e( 'Active', 'smartcrawl-seo' ); ?></small>
					</div>
				<?php endif; ?>
			</div>

			<div class="wds-separator-top wds-draw-left-padded">
				<small><strong><?php esc_html_e( 'Twitter Cards', 'smartcrawl-seo' ); ?></strong></small>
				<?php if ( ! $twitter_card_enabled ) : ?>
					<p>
						<small><?php esc_attr_e( 'With Twitter Cards, you can attach rich photos, videos and media experiences to Tweets, helping to drive traffic to your website.', 'smartcrawl-seo' ); ?></small>
					</p>
					<button
						type="button"
						data-option-id="<?php echo esc_attr( $social_option_name ); ?>"
						data-flag="<?php echo 'twitter-card-enable'; ?>"
						aria-label="<?php esc_html_e( 'Activate twitter cards', 'smartcrawl-seo' ); ?>"
						class="wds-activate-component wds-disabled-during-request sui-button sui-button-blue"
					>
						<span class="sui-loading-text"><?php esc_html_e( 'Activate', 'smartcrawl-seo' ); ?></span>
						<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
					</button>
				<?php else : ?>
					<div class="wds-right">
						<small><?php echo esc_html( $twitter_card_status_text ); ?></small>
					</div>
				<?php endif; ?>
			</div>

			<div class="wds-separator-top wds-draw-left-padded">
				<small>
					<strong><?php esc_html_e( 'Pinterest Verification', 'smartcrawl-seo' ); ?></strong>
				</small>
				<?php if ( ! $pinterest_tag || 'fail' === $pinterest_verification_status ) : ?>
					<p>
						<small><?php esc_html_e( 'Verify your website with Pinterest to attribute your website when your website content is pinned to the platform.', 'smartcrawl-seo' ); ?></small>
					</p>
					<a
						href="<?php echo esc_attr( $social_page_url ); ?>&tab=tab_pinterest_verification"
						aria-label="<?php esc_html_e( 'Connect to pinterest', 'smartcrawl-seo' ); ?>"
						class="sui-button sui-button-blue"
					>
						<span class="sui-icon-plus" aria-hidden="true"></span>

						<?php esc_html_e( 'Connect', 'smartcrawl-seo' ); ?>
					</a>
				<?php else : ?>
					<div class="wds-right">
						<small><?php esc_html_e( 'Verification tag added' ); ?></small>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>

	<div class="sui-box-footer">
		<?php if ( $social_enabled ) : ?>
			<a
				href="<?php echo esc_attr( $social_page_url ); ?>"
				aria-label="<?php esc_html_e( 'Configure social component', 'smartcrawl-seo' ); ?>"
				class="sui-button sui-button-ghost"
			>
				<span
					class="sui-icon-wrench-tool"
					aria-hidden="true"></span> <?php esc_html_e( 'Configure', 'smartcrawl-seo' ); ?>
			</a>
		<?php else : ?>
			<button
				type="button"
				data-option-id="<?php echo esc_attr( $settings_option_name ); ?>"
				data-flag="<?php echo esc_attr( 'social' ); ?>"
				aria-label="<?php esc_html_e( 'Activate social component', 'smartcrawl-seo' ); ?>"
				class="wds-activate-component wds-disabled-during-request sui-button sui-button-blue"
			>
				<span class="sui-loading-text"><?php esc_html_e( 'Activate', 'smartcrawl-seo' ); ?></span>
				<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
			</button>
		<?php endif; ?>
	</div>
</section>
