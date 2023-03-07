import React from 'react';
import classnames from 'classnames';

export default class TextInput extends React.Component {
	static defaultProps = {
		id: '',
		name: '',
		value: '',
		placeholder: '',
		disabled: false,
		className: '',
	};

	constructor(props) {
		super(props);

		this.state = { value: this.props.value };
	}

	handleChange(e) {
		if (this.props.beforeChange) {
			e.target.value = this.props.beforeChange(e.target.value);
		}

		if (this.props.onChange) {
			this.props.onChange(e.target.value);
		}

		this.setState({ value: e.target.value });
	}

	render() {
		const { id, name, placeholder, disabled, className } = this.props;
		const { value } = this.state;

		const props = { name, placeholder, disabled, value };

		if (id) {
			props.id = id;
		}

		return (
			<input
				{...props}
				type="text"
				className={classnames('sui-form-control', className)}
				onChange={(e) => this.handleChange(e)}
			/>
		);
	}
}
