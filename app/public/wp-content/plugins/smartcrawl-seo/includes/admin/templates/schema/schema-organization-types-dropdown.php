<?php
$option_name       = empty( $_view['option_name'] ) ? '' : $_view['option_name'];
$organization_type = empty( $organization_type ) ? '' : $organization_type;
?>

<div class="sui-form-field">
	<label for="organization_type" class="sui-label">
		<?php esc_html_e( 'Organization type', 'smartcrawl-seo' ); ?>
	</label>

	<select
		id="organization_type"
		name="<?php echo esc_attr( $option_name ); ?>[organization_type]"
		data-minimum-results-for-search="-1"
		class="sui-select"
	>
		<option value=""><?php esc_html_e( 'Select (Optional)', 'smartcrawl-seo' ); ?></option>
		<?php
		foreach (
			array(
				Smartcrawl_Schema_Type_Constants::ORGANIZATION_AIRLINE          => 'Airline',
				Smartcrawl_Schema_Type_Constants::ORGANIZATION_CONSORTIUM       => 'Consortium',
				Smartcrawl_Schema_Type_Constants::ORGANIZATION_CORPORATION      => 'Corporation',
				Smartcrawl_Schema_Type_Constants::ORGANIZATION_EDUCATIONAL      => 'Educational',
				Smartcrawl_Schema_Type_Constants::ORGANIZATION_FUNDING_SCHEME   => 'Funding Scheme',
				Smartcrawl_Schema_Type_Constants::ORGANIZATION_GOVERNMENT       => 'Government',
				Smartcrawl_Schema_Type_Constants::ORGANIZATION_LIBRARY_SYSTEM   => 'Library System',
				Smartcrawl_Schema_Type_Constants::ORGANIZATION_MEDICAL          => 'Medical',
				Smartcrawl_Schema_Type_Constants::ORGANIZATION_NGO              => 'NGO',
				Smartcrawl_Schema_Type_Constants::ORGANIZATION_NEWS_MEDIA       => 'News Media',
				Smartcrawl_Schema_Type_Constants::ORGANIZATION_PERFORMING_GROUP => 'Performing Group',
				Smartcrawl_Schema_Type_Constants::ORGANIZATION_PROJECT          => 'Project',
				Smartcrawl_Schema_Type_Constants::ORGANIZATION_SPORTS           => 'Sports',
				Smartcrawl_Schema_Type_Constants::ORGANIZATION_WORKERS_UNION    => 'Workers Union',
			) as $org_type_value => $org_type_label
		) :
			?>
			<option
				<?php selected( $org_type_value, $organization_type ); ?>
				value="<?php echo esc_attr( $org_type_value ); ?>"
			>
				<?php echo esc_html( $org_type_label ); ?>
			</option>
		<?php endforeach; ?>
	</select>

	<p class="sui-description" style="margin-bottom: 7px;">
		<?php esc_html_e( 'Choose the type that best describes your organization website.', 'smartcrawl-seo' ); ?>
	</p>
	<p class="sui-description">
		<?php
		echo smartcrawl_format_link(
			/* translators: %s: Link to Schema types section */
			esc_html__( 'Note: If you want to add Local Business markup, you can do it by adding a “Local Business” type in the %s.', 'smartcrawl-seo' ),
			Smartcrawl_Settings_Admin::admin_url( Smartcrawl_Settings::TAB_SCHEMA ) . '&tab=tab_types',
			esc_html__( 'Types Builder', 'smartcrawl-seo' )
		);
		?>
	</p>
</div>
