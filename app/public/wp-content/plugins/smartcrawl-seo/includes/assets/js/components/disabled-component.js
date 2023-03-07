import React from 'react';
import { __ } from '@wordpress/i18n';
import classnames from 'classnames';
import Notice from './notices/notice';
import Button from './button';

export default class DisabledComponent extends React.Component {
	static defaultProps = {
		imagePath: false,
		message: '',
		notice: '',
		component: '',
		button: false,
		inner: false,
		nonce: '',
		referer: '',
		premium: false,
		member: false,
		upgradeTag: '',
	};

	render() {
		const {
			imagePath,
			message,
			notice,
			component,
			button,
			inner,
			nonce,
			referer,
			premium,
			member,
			upgradeTag,
		} = this.props;

		return (
			<div
				className={classnames(
					'sui-message',
					'sui-message-lg',
					!!inner || 'sui-box'
				)}
			>
				<img
					src={imagePath}
					aria-hidden="true"
					alt={__('Disabled component', 'smartcrawl-seo')}
				/>

				<div className="sui-message-content">
					<p>{message}</p>

					{!!notice && <Notice message={notice}></Notice>}

					{component && (
						<input
							type="hidden"
							name="wds-activate-component"
							value={component}
						/>
					)}
					{nonce && (
						<input
							type="hidden"
							id="_wds_nonce"
							name="_wds_nonce"
							value={nonce}
						/>
					)}
					{referer && (
						<input
							type="hidden"
							name="_wp_http_referer"
							value={referer}
						/>
					)}

					{premium && !member && (
						<Button
							color="purple"
							target="_blank"
							href={
								'https://wpmudev.com/project/smartcrawl-wordpress-seo/?utm_source=smartcrawl&utm_medium=plugin&utm_campaign=' +
								upgradeTag
							}
							text={__('Upgrade to Pro', 'smartcrawl-seo')}
						></Button>
					)}

					{(!premium || member) && button}
				</div>
			</div>
		);
	}
}
