import React from 'react';
import FormField from './form-field';
import TextareaInput from './textarea-input';

export default class TextareaInputField extends React.Component {
	render() {
		return <FormField {...this.props} formControl={TextareaInput} />;
	}
}
