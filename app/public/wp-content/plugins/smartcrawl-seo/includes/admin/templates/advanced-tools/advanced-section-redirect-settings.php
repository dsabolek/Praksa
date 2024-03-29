<?php
$option_name              = empty( $_view['option_name'] ) ? '' : $_view['option_name'];
$plugin_settings          = Smartcrawl_Settings::get_specific_options( 'wds_settings_options' );
$current_redirection_code = Smartcrawl_Redirect_Utils::get()->get_default_type();
$redirection_types        = array(
	301 => __( 'Permanent (301)', 'smartcrawl-seo' ),
	302 => __( 'Temporary (302)', 'smartcrawl-seo' ),
);
?>
<input type="hidden" value="1" name="<?php echo esc_attr( $option_name ); ?>[save_redirects]"/>
<div class="sui-box-settings-row">
	<div class="sui-box-settings-col-1">
		<label class="sui-settings-label">
			<?php esc_html_e( 'Redirect attachments', 'smartcrawl-seo' ); ?>
		</label>
		<span class="sui-description">
			<?php esc_html_e( 'Redirect attachments to their respective file, preventing them from appearing in the SERPs.', 'smartcrawl-seo' ); ?>
		</span>
	</div>

	<div class="sui-box-settings-col-2">
		<?php
		$this->render_view(
			'toggle-item',
			array(
				'field_name'                 => "{$option_name}[redirect-attachments]",
				'field_id'                   => "{$option_name}[redirect-attachments]",
				'checked'                    => ! empty( $_view['options']['redirect-attachments'] ),
				'item_label'                 => esc_html__( 'Redirect attachments', 'smartcrawl-seo' ),
				'sub_settings_template'      => 'advanced-tools/advanced-redirect-image-attachments',
				'sub_settings_template_args' => array(),
			)
		);
		?>
	</div>
</div>

<div class="sui-box-settings-row">
	<div class="sui-box-settings-col-1">
		<label class="sui-settings-label" for="wds-default-redirection-type">
			<?php esc_html_e( 'Default Redirection Type', 'smartcrawl-seo' ); ?>
		</label>
		<p class="sui-description">
			<?php esc_html_e( 'Select the redirection type that you would like to be used as default.', 'smartcrawl-seo' ); ?>
		</p>
	</div>

	<div class="sui-box-settings-col-2">
		<select
			id="wds-default-redirection-type"
			name="<?php echo esc_attr( $option_name ); ?>[redirections-code]"
			autocomplete="off"
			data-minimum-results-for-search="-1"
			class="sui-select">
			<?php foreach ( $redirection_types as $redirection_type => $redirection_type_label ) : ?>
				<option value="<?php echo esc_attr( $redirection_type ); ?>"
					<?php echo selected( $redirection_type, $current_redirection_code, false ); ?>>
					<?php echo esc_html( $redirection_type_label ); ?>
				</option>
			<?php endforeach; ?>
		</select>
	</div>
</div>
