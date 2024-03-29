<?php

class Smartcrawl_Check_Links_Count extends Smartcrawl_Check_Abstract {

	/**
	 * Holds check state
	 *
	 * @var int
	 */
	private $state;

	private $link_count = null;

	private $internal_link_count = null;

	public function get_status_msg() {
		return $this->choose_message(
			/* translators: 1, 2: Number of internal/external links */
			__( 'You have %1$d internal and %2$d external links in your content', 'smartcrawl-seo' ),
			__( "You haven't added any internal or external links in your content", 'smartcrawl-seo' ),
			/* translators: 2: Number of external links */
			__( 'You have 0 internal and %2$d external links in your content', 'smartcrawl-seo' )
		);
	}

	private function choose_message( $okay_message, $no_links, $no_internal ) {
		$total_count    = (int) $this->link_count;
		$internal_count = (int) $this->internal_link_count;
		$external_count = $total_count - $internal_count;

		if ( $this->state ) {
			$message = $okay_message;
		} elseif ( ! $total_count ) {
			$message = $no_links;
		} elseif ( ! $internal_count ) {
			$message = $no_internal;
		}

		return sprintf( $message, (int) $internal_count, (int) $external_count, (int) $total_count );
	}

	public function apply() {
		$selector_links          = 'a[href]';
		$selector_internal_links = sprintf(
			'a[href^="%s"],a[href^="/"],a[href^="#"]',
			site_url()
		);

		$links            = Smartcrawl_Html::find( $selector_links, $this->get_markup() );
		$link_count       = count( $links );
		$this->link_count = $link_count;

		$internal_links            = Smartcrawl_Html::find( $selector_internal_links, $this->get_markup() );
		$internal_link_count       = count( $internal_links );
		$this->internal_link_count = $internal_link_count;

		$this->state = ! empty( $internal_link_count );

		return ! ! $this->state;
	}

	public function get_recommendation() {
		return $this->choose_message(
			/* translators: 3: Number of internal links */
			__( 'Internal links help search engines crawl your website, effectively pointing them to more pages to index on your website. You\'ve already added %3$d links, nice work!', 'smartcrawl-seo' ),
			__( 'Internal links help search engines crawl your website, effectively pointing them to more pages to index on your website. You should consider adding at least one internal link to another related article.', 'smartcrawl-seo' ),
			__( 'Internal links help search engines crawl your website, effectively pointing them to more pages to index on your website. You should consider adding at least one internal link to another related article.', 'smartcrawl-seo' )
		);
	}

	public function get_more_info() {
		ob_start();
		?>
		<?php esc_html_e( "Internal links are important for linking together related content. Search engines will 'crawl' through your website, indexing pages and posts as they go. To help them discover all the juicy content your website has to offer, it's wise to make sure your content has internal links built in for bots to follow and index.", 'smartcrawl-seo' ); ?>
		<br/><br/>

		<?php
		printf(
			"%s <a href='https://moz.com/learn/seo/internal-link' target='_blank'>https://moz.com/learn/seo/internal-link</a>",
			esc_html__( "External links don't benefit your SEO by having them in your own content, but you'll want to try and get as many other websites linking to your articles and pages as possible. Search engines treat links to your website as a 'third party vote' in favour of your website - like a vote of confidence. Since they're the hardest form of 'validation' to get (another website has to endorse you!) search engines weight them heavily when considering page rank. For more info:", 'smartcrawl-seo' )
		);
		?>
		<br/><br/>

		<?php esc_html_e( "Note: This check is only looking at the content your page is outputting and doesn't include your main navigation. Blogs with lots of posts will benefit the most from this method, as it aids Google in finding and indexing all of your content, not just the latest articles.", 'smartcrawl-seo' ); ?>
		<?php
		return ob_get_clean();
	}
}
