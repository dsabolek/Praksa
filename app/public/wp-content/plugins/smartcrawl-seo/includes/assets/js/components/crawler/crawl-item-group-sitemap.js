import React from 'react';
import { __ } from '@wordpress/i18n';
import CrawlItemGroup from './crawl-item-group';

export default class CrawlItemGroupSitemap extends React.Component {
	render() {
		return (
			<CrawlItemGroup
				{...this.props}
				singularTitle={
					// translators: %s: Number of active items.
					__('%s URL is missing from the sitemap', 'smartcrawl-seo')
				}
				pluralTitle={
					// translators: %s: Number of active items.
					__('%s URLs are missing from the sitemap', 'smartcrawl-seo')
				}
				description={__(
					'SmartCrawl couldn’t find these URLs in your Sitemap. You can choose to add them to your Sitemap, or ignore the warning if you don’t want them included.',
					'smartcrawl-seo'
				)}
				warningClass="sui-default"
			/>
		);
	}
}
