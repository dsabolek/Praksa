import React from 'react';
import { __ } from '@wordpress/i18n';
import SettingsRow from '../../../components/settings-row';
import Button from '../../../components/button';

export default class Deactivate extends React.Component {
	render() {
		return (
			<SettingsRow
				label={__('Deactivate', 'smartcrawl-seo')}
				description={__(
					'No longer need keyword linking? This will deactivate this feature but won’t remove existing links.',
					'smartcrawl-seo'
				)}
			>
				<Button
					type="submit"
					name="deactivate-autolinks-component"
					color="ghost"
					icon="sui-icon-power-on-off"
					text={__('Deactivate', 'smartcrawl-seo')}
				></Button>
			</SettingsRow>
		);
	}
}
