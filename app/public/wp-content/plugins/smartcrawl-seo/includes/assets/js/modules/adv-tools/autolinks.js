import React from 'react';
import { __, _x, sprintf } from '@wordpress/i18n';
import { createInterpolateElement } from '@wordpress/element';
import VerticalTab from '../../components/vertical-tab';
import UrlUtil from '../../utils/url-util';
import Notice from '../../components/notices/notice';
import AutolinkTypes from './autolinks/autolink-types';
import CustomKeywordPairs from './autolinks/custom-keyword-pairs';
import ExcludedPosts from './autolinks/excluded-posts';
import Settings from './autolinks/settings';
import Deactivate from './autolinks/deactivate';
import DisabledComponent from '../../components/disabled-component';
import Button from '../../components/button';
import ConfigValues from '../../es6/config-values';
import Tabs from '../../components/tabs';
import SettingsRow from '../../components/settings-row';

export default class Autolinks extends React.Component {
	constructor(props) {
		super(props);

		this.state = {
			selectedTab: !!UrlUtil.getQueryParam('sub')
				? UrlUtil.getQueryParam('sub')
				: 'post_types',
		};
	}

	render() {
		const isActive =
			!UrlUtil.getQueryParam('tab') ||
			UrlUtil.getQueryParam('tab') === 'tab_automatic_linking';

		const enabled = ConfigValues.get('enabled', 'autolinks');

		return (
			<VerticalTab
				id="tab_automatic_linking"
				title={__('Automatic Linking', 'smartcrawl-seo')}
				children={enabled ? this.getSettings() : this.getDeactivated()}
				buttonText={enabled && __('Save Settings', 'smartcrawl-seo')}
				isActive={isActive}
			></VerticalTab>
		);
	}

	getSettings() {
		return [
			<p key={0}>
				{__(
					'SmartCrawl will look for keywords that match posts/pages around your website and automatically link them. Specify what post types you want to include in this tool, and what post types you want those to automatically link to.',
					'smartcrawl-seo'
				)}
			</p>,

			<Notice
				key={1}
				type=""
				message={createInterpolateElement(
					__(
						'Certain page builders and themes can interfere with the auto linking feature causing issues on your site. Enable the "<strong>Prevent caching on auto-linked content</strong>" option in the Configuration tab section to fix the issues.',
						'smartcrawl-seo'
					),
					{
						strong: <strong />,
					}
				)}
			></Notice>,
			<SettingsRow key={2} direction="column">
				<Tabs
					tabs={{
						post_types: {
							label: __('Post Types', 'smartcrawl-seo'),
							component: <AutolinkTypes />,
						},
						custom_links: {
							label: __('Custom Links', 'smartcrawl-seo'),
							component: <CustomKeywordPairs />,
						},
						exclusions: {
							label: __('Exclusions', 'smartcrawl-seo'),
							component: <ExcludedPosts />,
						},
						settings: {
							label: __('Settings', 'smartcrawl-seo'),
							component: <Settings />,
						},
					}}
					value={this.state.selectedTab}
					onChange={(tab) => this.handleTabChange(tab)}
				></Tabs>
			</SettingsRow>,
			<Deactivate key={3}></Deactivate>,
		];
	}

	handleTabChange(tab) {
		const urlParts = location.href.split('&sub=');

		history.replaceState({}, '', urlParts[0] + '&sub=' + tab);

		event.preventDefault();
		event.stopPropagation();

		this.setState({
			selectedTab: tab,
		});
	}

	getDeactivated() {
		return (
			<DisabledComponent
				imagePath={ConfigValues.get('image', 'autolinks')}
				message={createInterpolateElement(
					sprintf(
						'%1$s<br/>%2$s<br/>%3$s',
						_x(
							'Configure SmartCrawl to automatically link certain key words to a page on your blog or even',
							'part of a larger text',
							'smartcrawl-seo'
						),
						_x(
							'a whole new site all together. Internal linking can help boost SEO by giving search engines',
							'part of a larger text',
							'smartcrawl-seo'
						),
						_x(
							'ample ways to index your site.',
							'part of a larger text',
							'smartcrawl-seo'
						)
					),
					{
						br: <br />,
					}
				)}
				component="autolinks"
				premium={true}
				member={ConfigValues.get('is_member', 'autolinks')}
				upgradeTag="smartcrawl_autolinking_upgrade_button"
				nonce={ConfigValues.get('settings_nonce', 'autolinks')}
				referer={ConfigValues.get('referer', 'autolinks')}
				button={
					<Button
						name="submit"
						type="submit"
						color="blue"
						text={__('Activate', 'smartcrawl-seo')}
					/>
				}
				inner
			/>
		);
	}
}
