<?php
if ( ! Smartcrawl_Settings_Admin::is_tab_allowed( Smartcrawl_Settings::TAB_AUTOLINKS ) ) {
	return;
}

$page_url = Smartcrawl_Settings_Admin::admin_url( Smartcrawl_Settings::TAB_AUTOLINKS );

$redirection_table = Smartcrawl_Redirects_Database_Table::get();
$redirection_count = $redirection_table->get_count();

$option_name         = Smartcrawl_Settings::TAB_SETTINGS . '_options';
$options             = $_view['options'];
$autolinking_enabled = Smartcrawl_Settings::get_setting( 'autolinks' );
$service             = Smartcrawl_Service::get( Smartcrawl_Service::SERVICE_SITE );
$is_member           = $service->is_member();
$moz_connected       = Smartcrawl_Settings::get_setting( 'access-id' ) && Smartcrawl_Settings::get_setting( 'secret-key' );
$footer_class        = $is_member ? 'sui-box-footer' : 'sui-box-body'; // Because the mascot message needs to be inside box body.

$robots_file_exists = Smartcrawl_Controller_Robots::get()->file_exists();
$is_rootdir_install = Smartcrawl_Controller_Robots::get()->is_rootdir_install();
$robots_enabled     = (bool) Smartcrawl_Settings::get_setting( 'robots-txt' ) && ! $robots_file_exists && $is_rootdir_install;
?>

<section
	id="<?php echo esc_attr( Smartcrawl_Settings_Dashboard::BOX_ADVANCED_TOOLS ); ?>"
	class="sui-box wds-dashboard-widget"
>
	<div class="sui-box-header">
		<h2 class="sui-box-title">
			<span class="sui-icon-wand-magic" aria-hidden="true"></span> <?php esc_html_e( 'Advanced Tools', 'smartcrawl-seo' ); ?>
		</h2>
	</div>

	<div class="sui-box-body">
		<p><?php esc_html_e( 'Advanced tools focus on the finer details of SEO including internal linking, redirections and Moz analysis.', 'smartcrawl-seo' ); ?></p>

		<div class="wds-separator-top wds-draw-left-padded">
			<small><strong><?php esc_html_e( 'URL Redirects', 'smartcrawl-seo' ); ?></strong></small>
			<?php if ( empty( $redirection_count ) ) : ?>
				<p>
					<small><?php esc_html_e( 'Automatically redirect traffic from one URL to another.', 'smartcrawl-seo' ); ?></small>
				</p>
				<a
					href="<?php echo esc_attr( $page_url ); ?>&tab=tab_url_redirection&add_redirect=1"
					class="sui-button sui-button-blue"
				>
					<?php esc_html_e( 'Add Redirect', 'smartcrawl-seo' ); ?>
				</a>
			<?php else : ?>
				<span class="wds-right"><small><?php echo esc_html( $redirection_count ); ?></small></span>
			<?php endif; ?>
		</div>

		<div class="wds-separator-top wds-draw-left-padded <?php echo $moz_connected ? 'wds-space-between' : ''; ?>">
			<small><strong><?php esc_html_e( 'Moz Integration', 'smartcrawl-seo' ); ?></strong></small>

			<?php if ( $moz_connected ) : ?>
				<a
					href="<?php echo esc_attr( $page_url ); ?>&tab=tab_moz"
					class="sui-button sui-button-ghost"
				>
					<span class="sui-icon-eye" aria-hidden="true"></span> <?php esc_html_e( 'View Report', 'smartcrawl-seo' ); ?>
				</a>
			<?php else : ?>
				<p>
					<small><?php esc_html_e( 'Moz provides reports that tell you how your site stacks up against the competition with all of the important SEO measurement tools.', 'smartcrawl-seo' ); ?></small>
				</p>
				<a
					href="<?php echo esc_attr( $page_url ); ?>&tab=tab_moz"
					aria-label="<?php esc_html_e( 'Connect your Moz account', 'smartcrawl-seo' ); ?>"
					class="sui-button sui-button-blue"
				>
					<?php esc_html_e( 'Connect', 'smartcrawl-seo' ); ?>
				</a>
			<?php endif; ?>
		</div>

		<div class="wds-separator-top wds-draw-left-padded <?php echo $robots_enabled ? 'wds-space-between' : ''; ?>">
			<small>
				<strong><?php esc_html_e( 'Robots.txt', 'smartcrawl-seo' ); ?></strong>
			</small>

			<?php if ( $robots_enabled ) : ?>
				<span class="wds-right">
					<small><?php esc_html_e( 'Active robots.txt file', 'smartcrawl-seo' ); ?></small>
				</span>
			<?php else : ?>
				<p>
					<small><?php esc_html_e( 'Add a robots.txt file to tell search engines what they can and can’t index, and where things are.', 'smartcrawl-seo' ); ?></small>
				</p>
				<a
					href="<?php echo esc_attr( $page_url ); ?>&tab=tab_robots_editor"
					aria-label="<?php esc_html_e( 'Activate Robots.txt file', 'smartcrawl-seo' ); ?>"
					class="sui-button sui-button-blue">
					<?php esc_html_e( 'Activate', 'smartcrawl-seo' ); ?>
				</a>
			<?php endif; ?>
		</div>

		<div class="wds-separator-top <?php echo ! $is_member ? 'wds-box-blocked-area wds-draw-down wds-draw-left' : 'wds-draw-left-padded'; ?>">
			<small><strong><?php esc_html_e( 'Automatic Linking', 'smartcrawl-seo' ); ?></strong></small>
			<?php if ( ! $is_member ) : ?>
				<a
					href="https://wpmudev.com/project/smartcrawl-wordpress-seo/?utm_source=smartcrawl&utm_medium=plugin&utm_campaign=smartcrawl_dash_autolinking_pro_tag"
					target="_blank"
				>
					<span
						class="sui-tag sui-tag-pro sui-tooltip"
						data-tooltip="<?php esc_attr_e( 'Try SmartCrawl Pro Free', 'smartcrawl-seo' ); ?>"
					>
						<?php esc_html_e( 'Pro', 'smartcrawl-seo' ); ?>
					</span>
				</a>
			<?php endif; ?>
			<?php if ( $autolinking_enabled && $is_member ) : ?>
				<div class="wds-right">
					<small><?php esc_html_e( 'Active', 'smartcrawl-seo' ); ?></small>
				</div>
			<?php else : ?>
				<p>
					<small><?php esc_html_e( 'Configure SmartCrawl to automatically link certain key words to a page on your blog or even a whole new site all together.', 'smartcrawl-seo' ); ?></small>
				</p>
				<button
					type="button"
					data-option-id="<?php echo esc_attr( $option_name ); ?>"
					data-flag="<?php echo 'autolinks'; ?>"
					aria-label="<?php esc_html_e( 'Activate autolinks component', 'smartcrawl-seo' ); ?>"
					class="wds-activate-component wds-disabled-during-request sui-button sui-button-blue">

					<span class="sui-loading-text"><?php esc_html_e( 'Activate', 'smartcrawl-seo' ); ?></span>
					<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
				</button>
			<?php endif; ?>
		</div>
	</div>

	<div class="<?php echo esc_attr( $footer_class ); ?>">
		<div>
			<a
				href="<?php echo esc_attr( $page_url ); ?>"
				aria-label="<?php esc_html_e( 'Configure advanced tools', 'smartcrawl-seo' ); ?>"
				class="sui-button sui-button-ghost"
			>
				<span
					class="sui-icon-wrench-tool"
					aria-hidden="true"></span> <?php esc_html_e( 'Configure', 'smartcrawl-seo' ); ?>
			</a>
		</div>

		<?php
		if ( ! $is_member ) {

			$this->render_view(
				'mascot-message',
				array(
					'key'         => 'seo-checkup-upsell',
					'dismissible' => false,
					'image_name'  => 'mascot-message-advanced-tools',
					'message'     => sprintf(
						'%s <a target="_blank" class="sui-button sui-button-purple" href="https://wpmudev.com/project/smartcrawl-wordpress-seo/?utm_source=smartcrawl&utm_medium=plugin&utm_campaign=smartcrawl_dash_reports_upsell_notice">%s</a>',
						esc_html__( 'Upgrade to Pro and automatically link your articles both internally and externally with automatic linking - a favourite among SEO pros.', 'smartcrawl-seo' ),
						esc_html__( 'Try it for FREE today', 'smartcrawl-seo' )
					),
				)
			);
		}
		?>
	</div>
</section>
