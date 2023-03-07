<?php
$is_member         = empty( $_view['is_member'] ) ? false : true;
$autolinks_enabled = Smartcrawl_Settings::get_setting( 'autolinks' ) && $is_member;
$form_action       = $autolinks_enabled ? $_view['action_url'] : '';
$already_exists    = empty( $already_exists ) ? false : true;
$rootdir_install   = empty( $rootdir_install ) ? false : true;
?>

<?php $this->render_view( 'before-page-container' ); ?>
<div id="container" class="<?php smartcrawl_wrap_class( 'wds-page-autolinks' ); ?>">

	<?php
	$this->render_view(
		'page-header',
		array(
			'title'                 => esc_html__( 'Advanced Tools', 'smartcrawl-seo' ),
			'documentation_chapter' => 'advanced-tools',
			'utm_campaign'          => 'smartcrawl_advanced-tools_docs',
		)
	);
	?>

	<?php
	$this->render_view(
		'floating-notices',
		array(
			'keys' => array(
				'wds-redirect-notice',
			),
		)
	);
	?>

	<div class="wds-vertical-tabs-container sui-row-with-sidenav">
		<?php
		$this->render_view(
			'advanced-tools/advanced-side-nav',
			array(
				'active_tab' => $active_tab,
			)
		);
		?>

		<form action='<?php echo esc_attr( $form_action ); ?>' method='post' class="wds-form">
			<?php if ( $autolinks_enabled ) : ?>
				<?php $this->settings_fields( $_view['option_name'] ); ?>

				<input
					type="hidden"
					name='<?php echo esc_attr( $_view['option_name'] ); ?>[<?php echo esc_attr( $_view['slug'] ); ?>-setup]'
					value="1">
			<?php endif; ?>

			<div id="wds-autolinks"></div>

		</form>

		<form
			action='<?php echo esc_attr( $_view['action_url'] ); ?>'
			method='post'
			class="wds-form">
			<?php $this->settings_fields( $_view['option_name'] ); ?>

			<?php
			if ( smartcrawl_woocommerce_active() ) {
				$this->render_view(
					'advanced-tools/advanced-section-woo-settings',
					array(
						'is_active' => 'tab_woo' === $active_tab,
					)
				);
			}
			?>
		</form>

		<form
			action='<?php echo esc_attr( $_view['action_url'] ); ?>'
			method='post'
			class="wds-form">
			<?php $this->settings_fields( $_view['option_name'] ); ?>

			<div
				id="tab_url_redirection"
				class="wds-vertical-tab-section"
			>
				<?php
				$this->render_view(
					'advanced-tools/advanced-section-redirects',
					array(
						'is_active' => 'tab_url_redirection' === $active_tab,
					)
				);

				$this->render_view(
					'vertical-tab',
					array(
						'tab_id'       => 'tab_url_redirection_settings',
						'tab_name'     => esc_html__( 'Settings', 'smartcrawl-seo' ),
						'is_active'    => 'tab_url_redirection' === $active_tab,
						'tab_sections' => array(
							array(
								'section_template' => 'advanced-tools/advanced-section-redirect-settings',
							),
						),
					)
				);
				?>
			</div>
		</form>

		<form method="post" class="wds-moz-form wds-form">
			<?php
			$this->render_view(
				'vertical-tab',
				array(
					'tab_id'       => 'tab_moz',
					'tab_name'     => __( 'Moz', 'smartcrawl-seo' ),
					'is_active'    => 'tab_moz' === $active_tab,
					'button_text'  => false,
					'tab_sections' => array(
						array(
							'section_template' => 'advanced-tools/advanced-section-moz',
							'section_args'     => array(),
						),
					),
				)
			);
			?>
		</form>

		<?php
		$this->render_view(
			'advanced-tools/advanced-tab-robots',
			array(
				'active_tab'      => $active_tab,
				'already_exists'  => $already_exists,
				'rootdir_install' => $rootdir_install,
			)
		);
		?>
	</div>
	<?php $this->render_view( 'footer' ); ?>
	<?php $this->render_view( 'upsell-modal' ); ?>

</div><!-- end wds-page-autolinks -->
