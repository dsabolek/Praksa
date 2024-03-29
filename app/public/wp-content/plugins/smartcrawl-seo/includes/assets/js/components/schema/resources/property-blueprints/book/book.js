import { __ } from '@wordpress/i18n';
import uniqueId from 'lodash-es/uniqueId';
import BookPerson from './book-person';
import BookEdition from './book-edition';
import AggregateRating from '../aggregate-rating';
import Review from '../review/review';

const id = uniqueId;
const Book = {
	'@id': {
		id: id(),
		label: __('@id', 'smartcrawl-seo'),
		type: 'URL',
		source: 'custom_text',
		value: '',
		required: true,
		description: __(
			"A globally unique ID for the book in URL format. It must be unique to your organization. The ID must be stable and not change over time. URL format is suggested though not required. It doesn't have to be a working link. The domain used for the @id value must be owned by your organization.",
			'smartcrawl-seo'
		),
	},
	name: {
		id: id(),
		label: __('Name', 'smartcrawl-seo'),
		type: 'TextFull',
		source: 'post_data',
		value: 'post_title',
		required: true,
		description: __('The title of the book.', 'smartcrawl-seo'),
	},
	url: {
		id: id(),
		label: __('URL', 'smartcrawl-seo'),
		type: 'URL',
		source: 'post_data',
		value: 'post_permalink',
		required: true,
		description: __(
			'The URL on your website where the book is introduced or described.',
			'smartcrawl-seo'
		),
	},
	author: {
		id: id(),
		label: __('Authors', 'smartcrawl-seo'),
		labelSingle: __('Author', 'smartcrawl-seo'),
		required: true,
		description: __('The authors of the book.', 'smartcrawl-seo'),
		properties: {
			0: {
				id: id(),
				type: 'Person',
				properties: BookPerson,
			},
		},
	},
	contributor: {
		id: id(),
		label: __('Contributors', 'smartcrawl-seo'),
		labelSingle: __('Contributor', 'smartcrawl-seo'),
		optional: true,
		description: __(
			'People who have made contributions to the book.',
			'smartcrawl-seo'
		),
		properties: {
			0: {
				id: id(),
				type: 'Person',
				properties: BookPerson,
			},
		},
	},
	sameAs: {
		id: id(),
		label: __('Same As', 'smartcrawl-seo'),
		labelSingle: __('URL', 'smartcrawl-seo'),
		description: __(
			'The URL of a reference page that identifies the work. For example, a Wikipedia, Wikidata, VIAF, or Library of Congress page for the book.',
			'smartcrawl-seo'
		),
		properties: {
			0: {
				id: id(),
				label: __('URL', 'smartcrawl-seo'),
				type: 'URL',
				source: 'custom_text',
				value: '',
			},
		},
	},
	editor: {
		id: id(),
		label: __('Editors', 'smartcrawl-seo'),
		labelSingle: __('Editor', 'smartcrawl-seo'),
		optional: true,
		description: __('People who have edited the book.', 'smartcrawl-seo'),
		properties: {
			0: {
				id: id(),
				type: 'Person',
				properties: BookPerson,
			},
		},
	},
	workExample: {
		id: id(),
		label: __('Editions', 'smartcrawl-seo'),
		labelSingle: __('Edition', 'smartcrawl-seo'),
		description: __('The editions of the work.', 'smartcrawl-seo'),
		properties: {
			0: {
				id: id(),
				type: 'Book',
				properties: BookEdition,
			},
		},
	},
	aggregateRating: {
		id: id(),
		label: __('Aggregate Rating', 'smartcrawl-seo'),
		type: 'AggregateRating',
		properties: AggregateRating,
		description: __(
			'A nested aggregateRating of the book.',
			'smartcrawl-seo'
		),
		optional: true,
	},
	review: {
		id: id(),
		label: __('Reviews', 'smartcrawl-seo'),
		labelSingle: __('Review', 'smartcrawl-seo'),
		properties: {
			0: {
				id: id(),
				type: 'Review',
				properties: Review,
			},
		},
		description: __('Reviews of the book.', 'smartcrawl-seo'),
		optional: true,
	},
};
export default Book;
