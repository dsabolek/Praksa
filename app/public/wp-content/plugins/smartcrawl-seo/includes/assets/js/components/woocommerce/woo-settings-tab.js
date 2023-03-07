import React from 'react';
import { __ } from '@wordpress/i18n';
import SettingsRow from '../settings-row';
import Toggle from '../toggle';
import { createInterpolateElement } from '@wordpress/element';
import DisabledComponent from '../disabled-component';
import Button from '../button';
import RequestUtil from '../../utils/request-util';
import SelectField from '../select-field';
import Notice from '../notices/notice';

export default class WooSettingsTab extends React.Component {
	static defaultProps = {
		woocommerceEnabled: false,
		removeGeneratorTag: false,
		enableOpenGraph: false,
		addRobots: false,
		enableShopPageSchema: false,
		noindexHiddenProducts: false,
		brandOptions: {},
		brand: '',
		globalIdentifier: '',
		disabledImagePath: '',
		permalinkSettingsUrl: '',
		nonce: '',
		opengraphEnabled: false,
		socialAllowed: false,
		socialUrl: '',
		schemaEnabled: false,
		schemaAllowed: false,
		schemaUrl: '',
	};

	constructor(props) {
		super(props);

		this.state = Object.assign({}, this.props, {
			requestInProgress: false,
		});
	}

	render() {
		if (!this.state.woocommerceEnabled) {
			const button = (
				<Button
					color="blue"
					text={__('Activate', 'smartcrawl-seo')}
					loading={this.state.requestInProgress}
					onClick={() => this.changeModuleStatus(true)}
				/>
			);

			return (
				<DisabledComponent
					imagePath={this.props.disabledImagePath}
					message={__(
						'Activate WooCommerce SEO to add the required metadata and Product Schema to your WooCommerce site, helping you stand out in search results.',
						'smartcrawl-seo'
					)}
					button={button}
				/>
			);
		}

		return (
			<div className="sui-box">
				<div className="sui-box-header">
					<h2 className="sui-box-title">
						{__('WooCommerce SEO', 'smartcrawl-seo')}
					</h2>
				</div>

				<div className="sui-box-body">
					<p>
						{__(
							'Use the WooCommerce SEO configurations below to add recommended Woo metadata and Product Schema to your WooCommerce site, helping you stand out in search results pages.',
							'smartcrawl-seo'
						)}
					</p>

					<SettingsRow
						label={__('Improve Woo Schema', 'smartcrawl-seo')}
						description={__(
							"Improve your site's WooCommerce Schema.",
							'smartcrawl-seo'
						)}
					>
						{this.props.schemaAllowed && !this.props.schemaEnabled && (
							<Notice
								message={createInterpolateElement(
									__(
										'For these settings to be applied, the <a>Schema module</a> must first be enabled.',
										'smartcrawl-seo'
									),
									{
										a: (
											<a
												target="_blank"
												href={this.props.schemaUrl}
												rel="noreferrer"
											/>
										),
									}
								)}
							/>
						)}

						<SelectField
							label={__('Brand', 'smartcrawl-seo')}
							description={__(
								'Select the product taxonomy to use as Brand in Schema & OpenGraph markup.',
								'smartcrawl-seo'
							)}
							options={this.props.brandOptions}
							selectedValue={this.state.brand}
							onSelect={(brand) => this.setState({ brand })}
						/>

						<SelectField
							label={__('Global Identifier', 'smartcrawl-seo')}
							description={createInterpolateElement(
								__(
									'Global Identifier key to use in the Product Schema. You can add a Global Identifier value for each product in the <strong>Inventory</strong> section of the <strong>Product Metabox</strong>',
									'smartcrawl-seo'
								),
								{
									strong: <strong />,
								}
							)}
							options={{
								'': __('None', 'smartcrawl-seo'),
								gtin8: 'GTIN-8',
								gtin12: 'GTIN-12',
								gtin13: 'GTIN-13',
								gtin14: 'GTIN-14',
								isbn: 'ISBN',
								mpn: 'MPN',
							}}
							selectedValue={this.state.globalIdentifier}
							onSelect={(globalIdentifier) =>
								this.setState({
									globalIdentifier,
								})
							}
						/>

						{this.props.schemaAllowed && (
							<Toggle
								id="wds-enable-shop-schema"
								label={__(
									'Enable Shop Schema',
									'smartcrawl-seo'
								)}
								description={__(
									'Add schema data on the shop page.',
									'smartcrawl-seo'
								)}
								disabled={!this.props.schemaEnabled}
								checked={this.state.enableShopPageSchema}
								wrapped={true}
								wrapperClass="sui-form-field"
								onChange={(checked) =>
									this.setState({
										enableShopPageSchema: checked,
									})
								}
							/>
						)}
					</SettingsRow>

					<SettingsRow
						label={__('Improve Woo Meta', 'smartcrawl-seo')}
						description={__(
							"Improve your site's default WooCommerce Meta.",
							'smartcrawl-seo'
						)}
					>
						{this.props.socialAllowed &&
							!this.props.opengraphEnabled && (
								<Notice
									message={createInterpolateElement(
										__(
											'For these settings to be applied, OpenGraph Support must first be <a>enabled</a>.',
											'smartcrawl-seo'
										),
										{
											a: (
												<a
													target="_blank"
													href={this.props.socialUrl}
													rel="noreferrer"
												/>
											),
										}
									)}
								/>
							)}

						{this.props.socialAllowed && (
							<Toggle
								id="wds-enable-opengraph"
								label={__(
									'Enable Product Open Graph',
									'smartcrawl-seo'
								)}
								description={__(
									'If enabled, WooCommerce product data will be added to Open Graph.',
									'smartcrawl-seo'
								)}
								disabled={!this.props.opengraphEnabled}
								checked={this.state.enableOpenGraph}
								wrapped={true}
								wrapperClass="sui-form-field"
								onChange={(checked) =>
									this.setState({ enableOpenGraph: checked })
								}
							/>
						)}

						<Toggle
							id="wds-remove-generator-tag"
							label={__('Remove Generator Tag', 'smartcrawl-seo')}
							description={createInterpolateElement(
								__(
									'If enabled, the WooCommerce generator tag <strong><meta name="generator" content="WooCommerce x.x.x" /></strong> will be removed.',
									'smartcrawl-seo'
								),
								{ strong: <strong /> }
							)}
							checked={this.state.removeGeneratorTag}
							wrapped={true}
							wrapperClass="sui-form-field"
							onChange={(checked) =>
								this.setState({ removeGeneratorTag: checked })
							}
						/>
					</SettingsRow>

					<SettingsRow
						label={__('Restrict Search Engines', 'smartcrawl-seo')}
						description={__(
							'Use these options to restrict Indexing or crawling of specific pages on the site.',
							'smartcrawl-seo'
						)}
					>
						<Toggle
							id="wds-noindex-hidden-products"
							label={__(
								'Noindex Hidden Products',
								'smartcrawl-seo'
							)}
							description={__(
								'Set Product Pages to noindex when WooCommerce Catalog visibility is set to hidden.',
								'smartcrawl-seo'
							)}
							checked={this.state.noindexHiddenProducts}
							wrapped={true}
							wrapperClass="sui-form-field"
							onChange={(checked) =>
								this.setState({
									noindexHiddenProducts: checked,
								})
							}
						/>

						<Toggle
							id="wds-add-robots"
							label={__(
								'Disallow Crawling of Cart, Checkout & My Account Pages',
								'smartcrawl-seo'
							)}
							description={createInterpolateElement(
								__(
									'If enabled, the following will be added to your Robots.txt file:<br/><strong>Disallow: /*add-to-cart=*<br/>Disallow: /cart/<br/>Disallow: /checkout/<br/>Disallow: /my-account/</strong>',
									'smartcrawl-seo'
								),
								{ strong: <strong />, br: <br /> }
							)}
							checked={this.state.addRobots}
							wrapped={true}
							wrapperClass="sui-form-field"
							onChange={(checked) =>
								this.setState({ addRobots: checked })
							}
						/>
					</SettingsRow>
				</div>

				<div className="sui-box-footer">
					<input
						type="hidden"
						name="wds_autolinks_options[woo-settings]"
						value={JSON.stringify(this.state)}
					/>

					<Button
						text={__('Deactivate', 'smartcrawl-seo')}
						loading={this.state.requestInProgress}
						icon="sui-icon-power-on-off"
						ghost={true}
						onClick={() => this.changeModuleStatus(false)}
					/>

					<button
						name="submit"
						type="submit"
						className="sui-button sui-button-blue"
					>
						<span className="sui-icon-save" aria-hidden="true" />
						{__('Save Settings', 'smartcrawl-seo')}
					</button>
				</div>
			</div>
		);
	}

	changeModuleStatus(enable) {
		this.setState({ requestInProgress: true });

		RequestUtil.post('wds_change_woo_status', this.props.nonce, {
			enable: enable ? 1 : 0,
		}).then(() => {
			this.setState({
				woocommerceEnabled: enable,
				requestInProgress: false,
			});
		});
	}
}
