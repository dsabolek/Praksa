import React from 'react';
import { __ } from '@wordpress/i18n';
import CrawlItemGroup from './crawl-item-group';

export default class CrawlItemGroup3xx extends React.Component {
	render() {
		return (
			<CrawlItemGroup
				{...this.props}
				singularTitle={
					// translators: %s: Number of active issues.
					__('%s URL has multiple redirections', 'smartcrawl-seo')
				}
				pluralTitle={
					// translators: %s: Number of active issues.
					__('%s URLs have multiple redirections', 'smartcrawl-seo')
				}
				description={__(
					'Some of your URLs have multiple redirections. In the options menu you can List occurrences to see where these links can be found, and also set up and 301 redirects to a newer version of these pages.',
					'smartcrawl-seo'
				)}
			/>
		);
	}
}
