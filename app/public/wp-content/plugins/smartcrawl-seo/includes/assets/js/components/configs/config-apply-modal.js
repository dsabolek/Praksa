import React from 'react';
import Modal from '../modal';
import { __, sprintf } from '@wordpress/i18n';
import Button from '../button';
import { createInterpolateElement } from '@wordpress/element';

export default class ConfigApplyModal extends React.Component {
	static defaultProps = {
		configName: '',
		inProgress: false,
		onClose: () => false,
		onApply: () => false,
	};

	render() {
		return (
			<Modal
				id="wds-apply-config-modal"
				title={__('Apply config', 'smartcrawl-seo')}
				description={this.getDescription()}
				small={true}
				onClose={() => this.props.onClose()}
				focusAfterOpen="wds-cancel-config-apply"
				disableCloseButton={this.props.inProgress}
			>
				<Button
					id="wds-cancel-config-apply"
					ghost={true}
					text={__('Cancel', 'smartcrawl-seo')}
					disabled={this.props.inProgress}
					onClick={() => this.props.onClose()}
				/>

				<Button
					color="blue"
					loading={this.props.inProgress}
					icon="sui-icon-check"
					text={__('Apply', 'smartcrawl-seo')}
					onClick={() => this.props.onApply()}
				/>
			</Modal>
		);
	}

	getDescription() {
		return createInterpolateElement(
			sprintf(
				// translators: %s: Name of configuration.
				__(
					'Are you sure you want to apply the <strong>%s</strong> settings config? We recommend you have a backup available as your <strong>existing settings configuration will be overridden</strong>.',
					'smartcrawl-seo'
				),
				this.props.configName
			),
			{ strong: <strong /> }
		);
	}
}
