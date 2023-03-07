<?php
/**
 * Check to find out the keywords in other articles.
 *
 * @package Smartcrawl
 */

/**
 * Class to find out the keywords in other articles.
 */
class Smartcrawl_Check_Keywords_Used extends Smartcrawl_Check_Post_Abstract {

	/**
	 * Hold check state
	 *
	 * @var bool
	 */
	private $state;

	/**
	 * Hold a list of other post ids which include primary keyword.
	 *
	 * @var array
	 */
	private $used_ids;

	/**
	 * Get the message for the check.
	 *
	 * @return string
	 */
	public function get_status_msg() {
		return ! $this->state
			? __( 'Primary focus keyword is already used on another post/page', 'smartcrawl-seo' )
			: __( 'Primary focus keyword isn’t used on another post/page', 'smartcrawl-seo' );
	}

	/**
	 * Apply check to the subject.
	 *
	 * @return bool
	 */
	public function apply() {
		$kws = $this->get_focus();
		if ( empty( $kws ) ) {
			return true;
		}

		global $wpdb;
		$wild        = '%';
		$likes_array = array();
		foreach ( $kws as $kw_id => $kw ) {
			$likes_array[] = 'meta_value LIKE %s';
			$kws[ $kw_id ] = $wild . $wpdb->esc_like( $kw ) . $wild;
		}

		$subject    = $this->get_subject();
		$subject_id = $this->get_subject_post_id( $subject );

		$likes     = join( ' AND ', $likes_array );
		$query     = "SELECT post_id, meta_value FROM $wpdb->postmeta WHERE meta_key = '_wds_focus-keywords' AND post_id != $subject_id AND $likes ORDER BY post_id DESC";
		$meta_rows = $wpdb->get_results( $wpdb->prepare( $query, ...$kws ), ARRAY_A ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.NotPrepared

		$meta_rows = empty( $meta_rows ) ? array() : $meta_rows;
		$post_ids  = $this->filter_out_supersets( $meta_rows );

		$this->state    = ! count( $post_ids );
		$this->used_ids = $post_ids;

		return $this->state;
	}

	/**
	 * "iphone access france" is a superset of "iphone access" and it should be ignored
	 *
	 * @param array $meta_rows Meta rows.
	 *
	 * @return array
	 */
	private function filter_out_supersets( $meta_rows ) {
		$filtered = array();
		foreach ( $meta_rows as $meta_row ) {
			$post_id   = (int) smartcrawl_get_array_value( $meta_row, 'post_id' );
			$raw_focus = smartcrawl_get_array_value( $meta_row, 'meta_value' );
			$focus     = $this->prepare_focus( array( $raw_focus ) );

			if ( count( $this->get_focus() ) === count( $focus ) ) {
				$filtered[] = $post_id;
			}
		}

		return $filtered;
	}

	/**
	 * Get post id if subject is post.
	 *
	 * @return int|WP_Post
	 */
	public function get_post_id() {
		$subject = $this->get_subject();

		return is_object( $subject ) ? $subject->ID : $subject;
	}

	/**
	 * Gets the recommendation texts.
	 *
	 * @return string
	 */
	public function get_recommendation() {
		if ( $this->state ) {
			$message = __( 'Your primary focused keyword isn’t used on other pages on your site. Excellent!', 'smartcrawl-seo' );
		} else {
			$message = __( 'Your primary focus keyword is used on the following pages:', 'smartcrawl-seo' );

			ob_start();
			if ( is_array( $this->used_ids ) && count( $this->used_ids ) > 0 ) {
				?>
				<table class="sui-table wds-keywords-used-table">
					<thead>
					<tr>
						<th colspan="2">
							<?php esc_html_e( 'Posts and Pages with the same primary focus keyword', 'smartcrawl-seo' ); ?>
							<span class="sui-description"><?php esc_attr_e( 'Please note that the list below displays a maximum of 10 posts and pages. There might be other posts and pages using the same keyword.', 'smartcrawl-seo' ); ?></span>
						</th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ( array_slice( $this->used_ids, 0, 10, true ) as $post_id ) : ?>
						<tr>
							<td>
								<div class="wds-keywords-used-post-title">
									<strong><?php echo esc_html( get_the_title( $post_id ) ); ?></strong>
									<span class="sui-tag"><?php echo esc_html( get_post_type_object( get_post_type( $post_id ) )->labels->singular_name ); ?></span>
								</div>
								<a class="wds-keywords-used-post-link" href="<?php echo esc_html( get_permalink( $post_id ) ); ?>">
									<span class="sui-icon-link" aria-hidden="true"></span>
									<?php echo esc_html( get_permalink( $post_id ) ); ?>
								</a>
							</td>
							<td>
								<a class="sui-button sui-button-ghost" href="<?php echo esc_attr( get_edit_post_link( $post_id ) ); ?>" target="_blank">
									<span class="sui-icon-pencil" aria-hidden="true"></span>
									<?php esc_html_e( 'Edit', 'smartcrawl-seo' ); ?>
								</a>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
				<?php
			}
			$message .= ob_get_clean();
		}

		return $message;
	}

	/**
	 * Get more info text.
	 *
	 * @return string
	 */
	public function get_more_info() {
		return __( 'Using the same focus keywords on multiple pages or posts can affect your page\'s SEO ranking. Therefore, it\'s recommended to only use one primary focus keyword per page/post on your site to improve its SEO ranking.', 'smartcrawl-seo' );
	}

	/**
	 * Get subject post id.
	 *
	 * @param string $subject Subject.
	 *
	 * @return int
	 */
	private function get_subject_post_id( $subject ) {
		if ( is_a( $subject, 'WP_Post' ) ) {
			$post_parent = wp_is_post_revision( $subject->ID );
			if ( $post_parent ) {
				$subject_id = $post_parent;
			} else {
				$subject_id = $subject->ID;
			}
		} else {
			$subject_id = - 1;
		}

		return $subject_id;
	}

}
