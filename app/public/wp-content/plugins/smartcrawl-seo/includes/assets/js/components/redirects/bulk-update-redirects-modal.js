import React from 'react';
import Modal from '../modal';
import { __ } from '@wordpress/i18n';
import fieldWithValidation from '../field-with-validation';
import TextInputField from '../text-input-field';
import {
	isNonEmpty,
	isUrlValid,
	isValuePlainText,
	Validator,
} from '../../utils/validators';
import SelectField from '../select-field';
import Button from '../button';
import { getDefaultRedirectType } from './redirect-commons';

export default class BulkUpdateRedirectsModal extends React.Component {
	static defaultProps = {
		homeUrl: '',
		inProgress: false,
		onSave: () => false,
		onClose: () => false,
	};

	constructor(props) {
		super(props);

		this.state = {
			destination: '',
			isDestinationValid: false,
			type: getDefaultRedirectType(),
		};

		this.destinationField = fieldWithValidation(TextInputField, [
			isNonEmpty,
			isValuePlainText,
			this.urlValidator(),
		]);
	}

	handleDestinationChange(destination, isValid) {
		this.setState({
			destination,
			isDestinationValid: isValid,
		});
	}

	urlValidator() {
		return new Validator(
			isUrlValid,
			__(
				'You need to use an absolute URL like https://domain.com/new-url or start with a slash /new-url.',
				'smartcrawl-seo'
			)
		);
	}

	render() {
		const { onSave, onClose, inProgress } = this.props;
		const { type, destination, isDestinationValid } = this.state;
		const onSubmit = () => onSave(destination.trim(), type);
		const submissionDisabled = !isDestinationValid || inProgress;
		const DestinationField = this.destinationField;

		return (
			<Modal
				id="wds-bulk-update-redirects"
				title={__('Bulk Update', 'smartcrawl-seo')}
				description={__(
					'Choose which bulk update actions you wish to apply. This will override the existing values for the selected items.',
					'smartcrawl-seo'
				)}
				onEnter={onSubmit}
				onClose={onClose}
				disableCloseButton={inProgress}
				enterDisabled={submissionDisabled}
				focusAfterOpen="wds-destination-field"
				focusAfterClose="wds-add-redirect-dashed-button"
				small={true}
			>
				<DestinationField
					id="wds-destination-field"
					label={__('New URL', 'smartcrawl-seo')}
					value={destination}
					placeholder={__('E.g. /cats-new', 'smartcrawl-seo')}
					onChange={(dest, isValid) =>
						this.handleDestinationChange(dest, isValid)
					}
					disabled={inProgress}
				/>

				<SelectField
					label={__('Redirect Type', 'smartcrawl-seo')}
					description={__(
						'This tells search engines whether to keep indexing the old page, or replace it with the new page.',
						'smartcrawl-seo'
					)}
					options={{
						302: __('Temporary', 'smartcrawl-seo'),
						301: __('Permanent', 'smartcrawl-seo'),
					}}
					selectedValue={type}
					onSelect={(tp) => this.setState({ type: tp })}
					disabled={inProgress}
				/>

				<div
					style={{ display: 'flex', justifyContent: 'space-between' }}
				>
					<Button
						text={__('Cancel', 'smartcrawl-seo')}
						ghost={true}
						onClick={onClose}
						disabled={inProgress}
					/>

					<Button
						id="wds-redirects-save-bulk-changes"
						text={__('Save', 'smartcrawl-seo')}
						color="blue"
						onClick={onSubmit}
						icon="sui-icon-save"
						disabled={submissionDisabled}
						loading={inProgress}
					/>
				</div>
			</Modal>
		);
	}
}
