import React from 'react';
import classnames from 'classnames';

export default class Notice extends React.Component {
	static defaultProps = {
		type: 'warning',
		message: '',
		icon: '',
	};

	render() {
		const icon = this.props.icon
			? this.props.icon
			: this.getIcon(this.props.type);

		return (
			<div
				className={classnames(
					'sui-notice',
					'sui-notice-' + this.props.type,
					this.props.className
				)}
			>
				<div className="sui-notice-content">
					<div className="sui-notice-message">
						{icon && (
							<span
								className={classnames(
									'sui-notice-icon sui-md',
									icon
								)}
								aria-hidden="true"
							/>
						)}
						<p>{this.props.message}</p>
					</div>
				</div>
			</div>
		);
	}

	getIcon(type) {
		const icons = {
			warning: 'sui-icon-warning-alert',
			error: 'sui-icon-warning-alert',
			info: 'sui-icon-info',
			success: 'sui-icon-check-tick',
			purple: 'sui-icon-info',
			'': 'sui-icon-info',
		};

		return icons[type];
	}
}
