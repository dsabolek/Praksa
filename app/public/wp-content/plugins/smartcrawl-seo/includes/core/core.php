<?php
/**
 * Core helper functions
 *
 * Procedures smartcrawl_get_value(), smartcrawl_replace_vars(), smartcrawl_get_term_meta()
 * inspired by WordPress SEO by Joost de Valk (http://yoast.com/wordpress/seo/).
 *
 * @package wpmu-dev-seo
 */

/**
 * Gets post meta value
 *
 * @param string $val     Key root to check.
 * @param int    $post_id Optional post ID.
 *
 * @return mixed
 */
function smartcrawl_get_value( $val, $post_id = false ) {
	if ( ! $post_id ) {
		global $post;
		$post_id = isset( $post ) ? $post->ID : false;
	}
	if ( ! $post_id ) {
		return false;
	}

	$meta_value = get_post_meta( $post_id, '_wds_' . $val, true );

	return empty( $meta_value ) ? false : $meta_value;
}

/**
 * Sets post meta value
 *
 * @param string $meta    Key root to check.
 * @param mixed  $val     Value to set.
 * @param int    $post_id Optional post ID.
 *
 * @return void
 */
function smartcrawl_set_value( $meta, $val, $post_id ) {
	update_post_meta( $post_id, "_wds_{$meta}", $val );
}

/**
 * Gets separator, or separators list
 *
 * @param string $key Optional separator key.
 *
 * @return string|array
 */
function smartcrawl_get_separators( $key = null ) {
	$separators = array(
		'dot'           => '·',
		'dot-l'         => '•',
		'dash'          => '-',
		'dash-l'        => '—',
		'pipe'          => '|',
		'forward-slash' => '/',
		'back-slash'    => '\\',
		'tilde'         => '~',
		'greater-than'  => '>',
		'less-than'     => '<',
		'caret-right'   => '›',
		'caret-left'    => '‹',
		'arrow-right'   => '→',
		'arrow-left'    => '←',
	);

	if ( null === $key || empty( $separators[ $key ] ) ) {
		return $separators;
	} else {
		return $separators[ $key ];
	}
}

/**
 * Returns the number as an anglicized string
 *
 * Adapted from original code by Hugh Bothwell (hugh_bothwell@hotmail.com)
 *
 * @param int $num Number to convert.
 *
 * @return string
 */
function smartcrawl_spell_number( $num ) {
	$num = (int) $num;    // make sure it's an integer.

	if ( $num < 0 ) {
		return 'negative' . _wds_hb_convert_tri( - $num, 0 );
	}
	if ( 0 === $num ) {
		return 'zero';
	}

	return _wds_hb_convert_tri( $num, 0 );
}

/**
 * Recursive fn, converts three digits per pass
 *
 * Adapted from original code by Hugh Bothwell (hugh_bothwell@hotmail.com)
 *
 * @param int $num Number to convert.
 * @param int $tri Triplet to check.
 *
 * @return string
 */
function _wds_hb_convert_tri( $num, $tri ) {
	$ones = array(
		'',
		' one',
		' two',
		' three',
		' four',
		' five',
		' six',
		' seven',
		' eight',
		' nine',
		' ten',
		' eleven',
		' twelve',
		' thirteen',
		' fourteen',
		' fifteen',
		' sixteen',
		' seventeen',
		' eighteen',
		' nineteen',
	);

	$tens = array(
		'',
		'',
		' twenty',
		' thirty',
		' forty',
		' fifty',
		' sixty',
		' seventy',
		' eighty',
		' ninety',
	);

	$triplets = array(
		'',
		' thousand',
		' million',
		' billion',
		' trillion',
		' quadrillion',
		' quintillion',
		' sextillion',
		' septillion',
		' octillion',
		' nonillion',
	);

	// chunk the number, ...rxyy.
	$r = (int) ( $num / 1000 );
	$x = ( $num / 100 ) % 10;
	$y = $num % 100;

	// init the output string.
	$str = '';

	// do hundreds.
	if ( $x > 0 ) {
		$str = $ones[ $x ] . ' hundred';
	}

	// do ones and tens.
	if ( $y < 20 ) {
		$str .= $ones[ $y ];
	} else {
		$str .= $tens[ (int) ( $y / 10 ) ] . $ones[ $y % 10 ];
	}

	// add triplet modifier only if there is some output to be modified...
	if ( '' !== $str ) {
		$str .= $triplets[ $tri ];
	}

	// continue recursing?.
	if ( $r > 0 ) {
		return _wds_hb_convert_tri( $r, $tri + 1 ) . $str;
	} else {
		return $str;
	}
}

/**
 * Gets excerpt trimmed to length
 *
 * @param string $excerpt  Optional excerpt.
 * @param string $contents Contents.
 *
 * @return string
 */
function smartcrawl_get_trimmed_excerpt( $excerpt, $contents ) {
	$string = $excerpt ? $excerpt : $contents;

	// Check if we have the excerpt cached.
	$cache_key = 'wds-' . md5( $string );
	$cached    = wp_cache_get( $cache_key, 'smartcrawl' );
	if ( is_string( $cached ) ) {
		return $cached;
	}

	// Remove shortcodes but keep the content.
	$string = smartcrawl_remove_shortcodes( $string );
	// Strip all HTML tags.
	$string = wp_strip_all_tags( $string );
	// Encode any HTML entities like > and <.
	$string = esc_attr( $string );
	// Normalize whitespace.
	$string = smartcrawl_normalize_whitespace( $string );
	// Truncate length.
	$string = smartcrawl_truncate_meta_description( $string );

	wp_cache_set( $cache_key, (string) $string, 'smartcrawl', 60 );

	return $string;
}

function smartcrawl_normalize_whitespace( $string ) {
	// Replace whitespace characters with simple spaces.
	$string = str_replace( array( "\r", "\n", "\t" ), ' ', $string );
	// Replace each set of multiple consecutive spaces with a single space.
	$string = preg_replace( '/[ ]+/', ' ', $string );

	return trim( $string );
}

/**
 * Removes the shortcode tags but keeps the content within them. Will convert [shortcode attr="val"]Some text![/shortcode] to: Some text!.
 *
 * @see get_shortcode_regex()
 *
 * @param string $content Content.
 *
 */
function smartcrawl_remove_shortcodes( $content ) {
	if ( strpos( $content, '[' ) === false ) {
		return $content;
	}

	preg_match_all( '/\[([a-zA-Z0-9_-]+)/', $content, $shortcode_matches );
	if ( empty( $shortcode_matches[1] ) ) {
		return $content;
	}
	$shortcode_tags = array_values( array_unique( $shortcode_matches[1] ) );

	$pattern = get_shortcode_regex( $shortcode_tags );

	return preg_replace_callback( "/$pattern/s", 'smartcrawl_extract_shortcode_contents', $content );
}

/**
 * Our callback function for making get_shortcode_regex() replacements.
 *
 * @see get_shortcode_regex()
 *
 * @param array $matches This array contains data in the following format:
 *                       array(
 *                       0 => full matched string
 *                       1 => the character [ for escaped shortcodes e.g. [[foo]]
 *                       2 => the actual shortcode tag
 *                       3 => shortcode attributes
 *                       4 => ?
 *                       5 => The content nested inside the opening and closing shortcode tags
 *                       6 => the character ] for escaped shortcodes
 *                       );.
 *
 * @return bool|mixed|string
 */
function smartcrawl_extract_shortcode_contents( $matches ) {
	if ( empty( $matches ) || count( $matches ) < 7 ) {
		// Not the expected regex for some reason. Try returning the full match or fall back to empty string.
		return isset( $matches[0] ) ? $matches[0] : '';
	}

	// Allow [[foo]] syntax for escaping a tag.
	if ( '[' === $matches[1] && ']' === $matches[6] ) {
		// Return the whole matched string without the surrounding square brackets that were there for escaping.
		return substr( $matches[0], 1, - 1 );
	}

	$omitted = apply_filters( 'wds-omitted-shortcodes', array() ); // phpcs:ignore
	if (
		! empty( $matches[5] )
		&& ! in_array( $matches[2], $omitted, true )
	) {
		// Call the removal method on the content nested in the current shortcode.
		// This will continue recursively until we have removed all shortcodes.
		return smartcrawl_remove_shortcodes( trim( $matches[5] ) . ' ' );
	}

	// Just remove the content-less, non-escaped shortcodes.
	return '';
}

function smartcrawl_truncate_meta_title( $string ) {
	return smartcrawl_truncare_meta( $string, smartcrawl_title_max_length() );
}

function smartcrawl_truncate_meta_description( $string ) {
	return smartcrawl_truncare_meta( $string, smartcrawl_metadesc_max_length() );
}

function smartcrawl_truncare_meta( $string, $limit ) {
	$pattern             = sprintf( '/.{%d,}/um', $limit + 1 );
	$replacement_pattern = sprintf( '/(.{0,%d}).*/um', $limit - 4 ); // -4 for 1 space plus ...

	return ( preg_match( $pattern, $string ) )
		? preg_replace( $replacement_pattern, '$1', $string ) . ' ...'
		: $string;
}

/**
 * Gets taxonomy term meta value
 *
 * @param object $term     Term object.
 * @param string $taxonomy Taxonomy the term belongs to.
 * @param string $meta_key Meta key to check.
 *
 * @return mixed
 */
function smartcrawl_get_term_meta( $term, $taxonomy, $meta_key ) {
	if ( is_numeric( $term ) || is_string( $term ) ) {
		$term = get_term_by(
			is_numeric( $term ) ? 'id' : 'slug',
			$term,
			$taxonomy
		);
		if ( ! $term || is_wp_error( $term ) ) {
			return false;
		}
	}

	$term_id    = $term->term_id;
	$tax_meta   = get_option( 'wds_taxonomy_meta' );
	$meta_value = smartcrawl_get_array_value( $tax_meta, array( $taxonomy, $term_id, $meta_key ) );

	return apply_filters( "wds-taxonomy-meta-{$meta_key}", $meta_value, $term_id, $taxonomy ); // phpcs:ignore
}

/**
 * Blog template settings handler
 *
 * @param string $and Query gathered this far.
 *
 * @return string
 */
function smartcrawl_blog_template_settings( $and ) {
	// phpcs:ignore
	// $and .= " AND `option_name` != 'wds_sitemaps_options'"; // Removed plural
	$and .= " AND `option_name` != 'wds_sitemap_options'"; // Added singular.

	return $and;
}

add_filter( 'blog_template_exclude_settings', 'smartcrawl_blog_template_settings' );


/**
 * Checks user persmission level against minumum requirement
 * for displaying SEO metabox.
 *
 * @return bool
 */
function user_can_see_seo_metabox() {
	$smartcrawl_options = Smartcrawl_Settings::get_options();
	$capability         = ( defined( 'SMARTCRAWL_SEO_METABOX_ROLE' ) && SMARTCRAWL_SEO_METABOX_ROLE )
		? SMARTCRAWL_SEO_METABOX_ROLE
		: ( ! empty( $smartcrawl_options['seo_metabox_permission_level'] ) ? $smartcrawl_options['seo_metabox_permission_level'] : false );
	$capability         = apply_filters( 'wds-capabilities-seo_metabox', $capability ); // phpcs:ignore
	$able               = false;

	if ( is_array( $capability ) ) {
		foreach ( $capability as $cap ) {
			$able = current_user_can( $cap );
			if ( $able ) {
				break;
			}
		}
	} else {
		$able = current_user_can( $capability );
	}

	return $able;
}

/**
 * Checks user persmission level against minumum requirement
 * for displaying Moz urlmetrics metabox.
 *
 * @return bool
 */
function user_can_see_urlmetrics_metabox() {
	$smartcrawl_options = Smartcrawl_Settings::get_options();
	$capability         = ( defined( 'SMARTCRAWL_URLMETRICS_METABOX_ROLE' ) && SMARTCRAWL_URLMETRICS_METABOX_ROLE )
		? SMARTCRAWL_URLMETRICS_METABOX_ROLE
		: ( ! empty( $smartcrawl_options['urlmetrics_metabox_permission_level'] ) ? $smartcrawl_options['urlmetrics_metabox_permission_level'] : false );
	$capability         = apply_filters( 'wds-capabilities-urlmetrics_metabox', $capability ); // phpcs:ignore
	$able               = false;

	if ( is_array( $capability ) ) {
		foreach ( $capability as $cap ) {
			$able = current_user_can( $cap );
			if ( $able ) {
				break;
			}
		}
	} else {
		$able = current_user_can( $capability );
	}

	return $able;
}

/**
 * Checks user persmission level against minumum requirement
 * for displaying the 301 redirection field within SEO metabox.
 *
 * @return bool
 */
function user_can_see_seo_metabox_301_redirect() {
	$smartcrawl_options = Smartcrawl_Settings::get_options();
	$capability         = ( defined( 'SMARTCRAWL_SEO_METABOX_301_ROLE' ) && SMARTCRAWL_SEO_METABOX_301_ROLE )
		? SMARTCRAWL_SEO_METABOX_301_ROLE
		: ( ! empty( $smartcrawl_options['seo_metabox_301_permission_level'] ) ? $smartcrawl_options['seo_metabox_301_permission_level'] : false );
	$capability         = apply_filters( 'wds-capabilities-seo_metabox_301_redirect', $capability ); // phpcs:ignore
	$able               = false;

	if ( is_array( $capability ) ) {
		foreach ( $capability as $cap ) {
			$able = current_user_can( $cap );
			if ( $able ) {
				break;
			}
		}
	} else {
		$able = current_user_can( $capability );
	}

	return $able;
}

/**
 * Attempt to hide metaboxes by default by adding them to "hidden" array.
 *
 * Metaboxes are still added to "Screen Options".
 * If user chooses to show/hide them, respect her decision.
 *
 * @param array $arg Whatever's been already hidden.
 *
 * @return array
 * @deprecated as of version 1.0.9
 */
function smartcrawl_process_default_hidden_meta_boxes( $arg ) {
	$smartcrawl_options = Smartcrawl_Settings::get_options();
	$arg[]              = 'wds-wds-meta-box';
	$arg[]              = 'wds_seomoz_urlmetrics';

	return $arg;
}

/**
 * Hide ALL wds metaboxes.
 *
 * Respect wishes for other metaboxes.
 * Still accessble from "Screen Options".
 *
 * @param array $arg Whatever's been already hidden.
 *
 * @return array
 */
function smartcrawl_hide_metaboxes( $arg ) {
	// Hide WP defaults, if nothing else.
	if ( empty( $arg ) ) {
		$arg = array(
			'slugdiv',
			'trackbacksdiv',
			'postcustom',
			'postexcerpt',
			'commentstatusdiv',
			'commentsdiv',
			'authordiv',
			'revisionsdiv',
		);
	}
	$arg[] = 'wds-wds-meta-box';
	$arg[] = 'wds_seomoz_urlmetrics';

	return $arg;
}

/**
 * Register metabox hiding for other boxes.
 *
 * @deprecated
 */
function smartcrawl_register_metabox_hiding() {
	$post_types = get_post_types();
	foreach ( $post_types as $type ) {
		add_filter( 'get_user_option_metaboxhidden_' . $type, 'smartcrawl_hide_metaboxes' );
	}

}

/**
 * Forces metaboxes to start collapsed.
 *
 * It properly merges the WDS boxes with the rest of the users collapsed boxes.
 * For info on registering, see `register_metabox_collapsed_state`.
 *
 * @param array $closed Whatever's been closed this far.
 *
 * @return array
 */
function force_metabox_collapsed_state( $closed ) {
	$closed = is_array( $closed ) ? $closed : array();

	return array_merge(
		$closed,
		array(
			'wds-wds-meta-box',
			'wds_seomoz_urlmetrics',
		)
	);
}

/**
 * Registers WDS boxes state.
 * Collapsed state is tracked per post type.
 * This is why we have this separate hook to register state change processing.
 */
function register_metabox_collapsed_state() {
	global $post;
	if ( $post && $post->post_type ) {
		add_filter( 'get_user_option_closedpostboxes_' . $post->post_type, 'force_metabox_collapsed_state' );
	}
}

add_filter( 'post_edit_form_tag', 'register_metabox_collapsed_state' );

/**
 * Checks if transient is stuck
 *
 * Stuck transient has no expiry time.
 * If so found, removes it.
 *
 * @param string $key Transient key.
 *
 * @return bool
 */
function smartcrawl_kill_stuck_transient( $key ) {
	global $_wp_using_ext_object_cache;
	if ( $_wp_using_ext_object_cache ) {
		return true;
	} // In object cache, nothing to do.

	$key        = "_transient_{$key}";
	$alloptions = wp_load_alloptions();
	// If option is in alloptions, it is autoloaded and thus has no timeout - kill it.
	if ( isset( $alloptions[ $key ] ) ) {
		return delete_option( $key );
	}

	return true;
}

/**
 * Check for boolean define switches and their values.
 *
 * @param string $switch Define name to check.
 *
 * @return bool
 */
function smartcrawl_is_switch_active( $switch ) {
	$result = defined( $switch ) ? constant( $switch ) : false;

	/**
	 * Checks if a define switch is toggled on
	 *
	 * Used in tests.
	 *
	 * @param bool   $result Whether the switch is turned on.
	 * @param string $switch Switch name.
	 *
	 * @return bool
	 */
	return (bool) apply_filters( 'smartcrawl_switch_active', $result, $switch );
}

/**
 * Check if we're on main BuddyPress site - BuddyPress root blog check.
 *
 * @return bool Are we on the main BuddyPress site.
 */
function smartcrawl_is_main_bp_site() {
	if ( is_multisite() && defined( 'BP_VERSION' ) && ( defined( 'BP_ROOT_BLOG' ) && BP_ROOT_BLOG ) ) {
		global $blog_id;

		return intval( BP_ROOT_BLOG ) === intval( $blog_id );
	}

	return is_main_site();
}

/**
 * Converts an argument map to HTML attributes string.
 *
 * @param array $args A hash of arguments.
 *
 * @return string Constructed attributes string
 */
function smartcrawl_autolinks_construct_attributes( $args = array() ) {
	$ret = array();
	if ( empty( $args ) ) {
		return '';
	}
	foreach ( $args as $key => $value ) {
		if ( empty( $key ) || empty( $value ) ) {
			continue; // Only accept properly formatted members.
		}
		$ret[] = esc_html( $key ) . '="' . esc_attr( $value ) . '"';
	}

	return apply_filters( 'wds_autolinks_attributes', trim( join( ' ', $ret ) ) );
}

/**
 * Get a value from an array. If nothing is found for the provided keys, returns null by default.
 *
 * @param array        $array The array to search (haystack).
 * @param array|string $key   The key to use for the search.
 *
 * @return null|mixed The array value found or null if nothing found.
 */
function smartcrawl_get_array_value( $array, $key, $default = null ) {
	if ( ! is_array( $key ) ) {
		$key = array( $key );
	}

	if ( ! is_array( $array ) ) {
		return null;
	}

	$value = $array;
	foreach ( $key as $key_part ) {
		$value = isset( $value[ $key_part ] ) ? $value[ $key_part ] : $default;
	}

	return $value;
}

/**
 * Inserts a value in the given array.
 *
 * @param mixed        $value The value to insert.
 * @param array        $array The array in which the value is to be inserted. Passed by reference.
 * @param array|string $keys  Key specifying the place where the new value is to be inserted.
 *
 * @return void
 */
function smartcrawl_put_array_value( $value, &$array, $keys ) {
	if ( ! is_array( $keys ) ) {
		$keys = array( $keys );
	}

	$pointer = &$array;
	foreach ( $keys as $key ) {
		if ( ! isset( $pointer[ $key ] ) ) {
			$pointer[ $key ] = array();
		}
		$pointer = &$pointer[ $key ];
	}
	$pointer = $value;
}

/**
 * Sanitizes a string into relative URL
 *
 * @param string $raw Raw string to process.
 *
 * @return string Root-relative string
 */
function smartcrawl_sanitize_relative_url( $raw ) {
	$raw = preg_match( '/^https?:\/\//', $raw ) || preg_match( '/^\//', $raw )
		? esc_url( $raw )
		: esc_url( "/{$raw}" );

	$parsed = wp_parse_url( $raw );
	$domain = preg_replace( '/^https?:\/\//', '', home_url() );

	if ( strpos( $raw, $domain ) !== false || empty( $parsed ) ) {
		$raw    = preg_replace( '/^https?:\/\//', '', $raw );
		$result = preg_replace( '/^' . preg_quote( $domain, '/' ) . '/', '', $raw );
	} else {
		$result = ! empty( $parsed['path'] ) ? $parsed['path'] : '/';
		if ( ! empty( $parsed['query'] ) ) {
			$result .= '?' . $parsed['query'];
		}
	}

	return '/' . ltrim( $result, '/' );
}

/**
 * Gets regex for matching against a list of relative URLs
 *
 * @param array $urls A list of relative URLs.
 *
 * @return string
 */
function smartcrawl_get_relative_urls_regex( $urls ) {
	$regex = '';
	if ( ! is_array( $urls ) ) {
		return $regex;
	}

	$processed = array();
	foreach ( $urls as $url ) {
		if ( empty( $url ) ) {
			continue;
		}
		$processed[] = preg_quote( $url, '/' );
	}
	$regex = '/https?:\/\/.*?(' . join( '|', $processed ) . ')\/?$/';

	return $regex;
}

function smartcrawl_get_attachment_id_by_url( $url ) {
	global $wpdb;

	return $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE guid=%s", $url ) ); // phpcs:ignore
}

function smartcrawl_get_attachment_by_url( $url ) {
	$attachment_id = smartcrawl_get_attachment_id_by_url( $url );
	if ( $attachment_id ) {
		$attachment_image_src = wp_get_attachment_image_src( $attachment_id, 'full' );

		return $attachment_image_src
			? array(
				'url'    => $attachment_image_src[0],
				'width'  => $attachment_image_src[1],
				'height' => $attachment_image_src[2],
			)
			: null;
	}

	return null;
}

function smartcrawl_get_archive_post_types() {
	return array_keys( smartcrawl_get_archive_post_type_labels() );
}

function smartcrawl_get_archive_post_type_labels() {
	$archive_post_types = array();
	$post_type_args     = array(
		'public'      => true,
		'has_archive' => true,
	);
	foreach ( get_post_types( $post_type_args ) as $post_type ) {
		if ( in_array( $post_type, array( 'revision', 'nav_menu_item' ), true ) ) {
			continue;
		}

		$post_type_object                                 = get_post_type_object( $post_type );
		$archive_post_types[ 'pt-archive-' . $post_type ] = $post_type_object->labels->name;
	}

	return $archive_post_types;
}

/**
 * Fetch sitemap URL in an uniform fashion.
 *
 * TODO see what's going on in this function and get rid of everything we don't need
 *
 * @return string Sitemap URL
 */
function smartcrawl_get_sitemap_url() {
	return apply_filters( 'wds-sitemaps-sitemap_url', home_url( 'sitemap.xml' ) ); // phpcs:ignore
}

/**
 * @return string|void
 */
function smartcrawl_get_plain_sitemap_url( $type = 'index' ) {
	return home_url( "?wds_sitemap=1&wds_sitemap_type=$type" );
}

function smartcrawl_get_news_sitemap_url() {
	return home_url( 'news-sitemap.xml' );
}

/**
 * @return string|void
 */
function smartcrawl_get_plain_news_sitemap_url() {
	return home_url( '?wds_news_sitemap=1&wds_news_sitemap_type=index' );
}

function smartcrawl_get_robots_url() {
	return home_url( '/robots.txt' );
}

function smartcrawl_get_allowed_html_for_forms() {
	return array(
		'form'  => array(
			'class'   => array(),
			'id'      => array(),
			'action'  => array(),
			'method'  => array(),
			'enctype' => array(),
		),
		'input' => array(
			'class' => array(),
			'id'    => array(),
			'type'  => array(),
			'name'  => array(),
			'value' => array(),
		),
	);
}

function smartcrawl_file_get_contents( $file ) {
	$pre = apply_filters( 'wds_pre_file_get_contents', null, $file );

	return ! is_null( $pre )
		? $pre
		: file_get_contents( $file ); // phpcs:ignore -- WP_Filesystem doesn't work properly.
}

function smartcrawl_file_put_contents( $file, $contents, $flags = 0 ) {
	$pre = apply_filters( 'wds_pre_file_put_contents', null, $file, $contents, $flags );

	return ! is_null( $pre )
		? $pre
		: ! ! @file_put_contents( $file, $contents, $flags ); // phpcs:ignore -- WP_Filesystem doesn't work properly.
}

/**
 * Gets whatever's latest of a post
 *
 * @param int $post_id Post ID.
 *
 * @return WP_Post
 */
function smartcrawl_get_latest_post_version( $post_id ) {
	$post           = get_post( $post_id );
	$post_revisions = wp_get_post_revisions(
		$post_id,
		array(
			'orderby'       => 'modified',
			'order'         => 'DESC',
			'check_enabled' => false,
		)
	);
	if ( count( $post_revisions ) ) {
		$revision = array_shift( $post_revisions );
		if ( strtotime( $revision->post_modified ) > strtotime( $post->post_modified ) ) {
			return $revision;
		}
	}

	return $post;
}

/**
 * Checks whether the supplied string is a valid meta tag
 *
 * @param string $string String to check.
 *
 * @return bool
 */
function smartcrawl_is_valid_meta_tag( $string ) {
	$string = trim( $string );
	if ( ! preg_match( '/^\<meta/i', $string ) ) {
		return false;
	}
	if ( ! preg_match( '/\>$/', $string ) ) {
		return false;
	}

	return true;
}

function smartcrawl_get_dash_profile_data( $default = null ) {
	if (
		class_exists( 'WPMUDEV_Dashboard' )
		&& isset( WPMUDEV_Dashboard::$site )
		&& is_callable( array( WPMUDEV_Dashboard::$site, 'get_option' ) )
	) {
		$profile_data = WPMUDEV_Dashboard::$site->get_option( 'profile_data' );
		$name         = empty( $profile_data['profile']['name'] )
			? ''
			: $profile_data['profile']['name'];
		$email        = empty( $profile_data['profile']['user_name'] )
			? ''
			: sanitize_email( $profile_data['profile']['user_name'] );

		if ( $name && $email ) {
			return (object) array(
				'user_login' => $name,
				'user_email' => $email,
			);
		}
	}

	return $default;
}

function smartcrawl_sui_class() {
	$classes[] = defined( 'SMARTCRAWL_SUI_VERSION' ) && SMARTCRAWL_SUI_VERSION
		? 'sui-' . str_replace( '.', '-', SMARTCRAWL_SUI_VERSION )
		: '';

	$hide_branding = Smartcrawl_White_Label::get()->is_hide_wpmudev_branding();
	if ( $hide_branding ) {
		$classes[] = 'wds-no-branding';
	}

	return implode( ' ', $classes );
}

function smartcrawl_wrap_class( $page_class = '' ) {
	$classes = 'sui-wrap wrap wrap-wds wds-page';

	if ( $page_class ) {
		$classes .= " $page_class";
	}

	$options = Smartcrawl_Settings::get_options();
	if ( ! empty( $options['high-contrast'] ) ) {
		$classes .= ' sui-color-accessible';
	}

	echo esc_attr( $classes );
}

function smartcrawl_format_link( $text, $url, $anchor = '', $target = '' ) {
	if ( empty( $anchor ) ) {
		$anchor = esc_html__( 'click here', 'smartcrawl-seo' );
	}

	return sprintf(
		$text,
		sprintf(
			'<a target="%s" href="%s">%s</a>',
			$target,
			esc_url_raw( $url ),
			$anchor
		)
	);
}

function smartcrawl_sanitize_preserve_macros( $str ) {
	if ( empty( $str ) ) {
		return $str;
	}

	$rpl = '__SMARTCRAWL_MACRO_QUOTES_REPLACEMENT__';
	$str = preg_replace( '/%%/', $rpl, $str );

	$str = sanitize_text_field( $str );

	$str = preg_replace( '/' . preg_quote( $rpl, '/' ) . '/', '%%', $str );

	return $str;
}

function smartcrawl_uploads_dir() {
	$dir  = wp_upload_dir();
	$path = trailingslashit( $dir['basedir'] );

	return "{$path}smartcrawl/";
}

function smartcrawl_title_min_length() {
	$options      = Smartcrawl_Settings::get_options();
	$custom_limit = (int) smartcrawl_get_array_value( $options, 'custom_title_min_length' );
	if ( empty( $options['custom_title_char_lengths'] ) || $custom_limit <= 0 ) {
		return SMARTCRAWL_TITLE_DEFAULT_MIN_LENGTH;
	}

	return $custom_limit;
}

function smartcrawl_title_max_length() {
	$options      = Smartcrawl_Settings::get_options();
	$custom_limit = (int) smartcrawl_get_array_value( $options, 'custom_title_max_length' );
	if ( empty( $options['custom_title_char_lengths'] ) || $custom_limit <= 0 ) {
		return SMARTCRAWL_TITLE_DEFAULT_MAX_LENGTH;
	}

	return $custom_limit;
}

function smartcrawl_metadesc_min_length() {
	$options      = Smartcrawl_Settings::get_options();
	$custom_limit = (int) smartcrawl_get_array_value( $options, 'custom_metadesc_min_length' );
	if ( empty( $options['custom_metadesc_char_lengths'] ) || $custom_limit <= 0 ) {
		return SMARTCRAWL_METADESC_DEFAULT_MIN_LENGTH;
	}

	return $custom_limit;
}

function smartcrawl_metadesc_max_length() {
	$options      = Smartcrawl_Settings::get_options();
	$custom_limit = (int) smartcrawl_get_array_value( $options, 'custom_metadesc_max_length' );
	if ( empty( $options['custom_metadesc_char_lengths'] ) || $custom_limit <= 0 ) {
		return SMARTCRAWL_METADESC_DEFAULT_MAX_LENGTH;
	}

	return $custom_limit;
}

function smartcrawl_is_build_type_full() {
	return defined( 'SMARTCRAWL_BUILD_TYPE' ) && SMARTCRAWL_BUILD_TYPE === 'full';
}

function smartcrawl_frontend_post_types() {
	$types['post']       = 'post';
	$types['page']       = 'page';
	$types['attachment'] = 'attachment';
	foreach (
		get_post_types(
			array(
				'public'             => true,
				'publicly_queryable' => true,
				'_builtin'           => false,
			)
		) as $type
	) {
		$types[ $type ] = $type;
	}

	return $types;
}

function smartcrawl_frontend_taxonomies( $output = 'objects' ) {
	$taxonomies = get_taxonomies(
		array(
			'public'             => true,
			'publicly_queryable' => true,
		),
		$output
	);

	unset( $taxonomies['post_format'] );

	return $taxonomies;
}

function smartcrawl_sanitize_recipients( $email_recipients ) {
	if ( empty( $email_recipients ) ) {
		return array();
	}

	$sanitized_recipients = array();
	foreach ( $email_recipients as $recipient ) {
		$recipient_name  = smartcrawl_get_array_value( $recipient, 'name' );
		$recipient_email = smartcrawl_get_array_value( $recipient, 'email' );

		$sanitized_emails = array_column( $sanitized_recipients, 'email' );
		$recipient_exists = in_array( $recipient_email, $sanitized_emails, true );
		if (
			$recipient_name && $recipient_email
			&& sanitize_text_field( $recipient_name ) === $recipient_name
			&& sanitize_email( $recipient_email ) === $recipient_email
			&& ! $recipient_exists
		) {
			$sanitized_recipients[] = $recipient;
		}
	}

	return $sanitized_recipients;
}

function smartcrawl_subsite_manager_role() {
	$manager_role = get_site_option( 'wds_subsite_manager_role', false );

	return empty( $manager_role )
		? 'admin'
		: $manager_role;
}

function smartcrawl_activate_all_blog_tabs() {
	update_site_option(
		'wds_blog_tabs',
		array(
			Smartcrawl_Settings::TAB_ONPAGE    => true,
			Smartcrawl_Settings::TAB_SCHEMA    => true,
			Smartcrawl_Settings::TAB_SOCIAL    => true,
			Smartcrawl_Settings::TAB_SITEMAP   => true,
			Smartcrawl_Settings::TAB_AUTOLINKS => true,
			Smartcrawl_Settings::TAB_SETTINGS  => true,
		)
	);
}

function smartcrawl_camel_to_snake( $string ) {
	return strtolower( preg_replace( '/(?<!^)[A-Z]/', '_$0', $string ) );
}

function smartcrawl_snake_to_camel( $string ) {
	$string    = str_replace( ' ', '', ucwords( str_replace( '_', ' ', $string ) ) );
	$string[0] = strtolower( $string[0] );

	return $string;
}

function smartcrawl_woocommerce_active() {
	return class_exists( 'woocommerce' );
}

function smartcrawl_clean( $value ) {
	if ( is_array( $value ) ) {
		return array_map( 'smartcrawl_clean', $value );
	} else {
		return is_scalar( $value ) ? sanitize_text_field( $value ) : $value;
	}
}

function smartcrawl_array_hash( $array, $keys = array() ) {
	$hash = 0;
	if ( is_array( $array ) ) {
		foreach ( $array as $key => $value ) {
			if ( is_array( $value ) ) {
				$value_hash = smartcrawl_array_hash(
					$value,
					array_merge( $keys, array( $key ) )
				);
			} else {
				$prefix     = join( '~', $keys );
				$value_hash = crc32( $prefix . $value );
			}

			$hash += $value_hash;
		}
	}

	return $hash;
}

function smartcrawl_arrays_same( $array1, $array2 ) {
	if (
		! is_array( $array1 )
		|| ! is_array( $array2 )
		|| count( $array1 ) !== count( $array2 )
	) {
		return false;
	}

	return smartcrawl_array_hash( $array1 ) === smartcrawl_array_hash( $array2 );
}

function smartcrawl_csv_mime_types() {
	return array(
		'text/plain',
		'text/x-csv',
		'text/plain',
		'application/vnd.ms-excel',
		'text/x-csv',
		'application/csv',
		'application/x-csv',
		'text/csv',
		'text/comma-separated-values',
		'text/x-comma-separated-values',
		'text/tab-separated-values',
	);
}

function smartcrawl_append_archive_page_number( $url, $page_number ) {
	/**
	 * WP Rewrite.
	 *
	 * @var $wp_rewrite WP_Rewrite
	 */
	global $wp_rewrite;
	if ( $page_number > 1 ) {
		if ( $wp_rewrite->using_permalinks() ) {
			$url = trailingslashit( $url ) . sprintf( $wp_rewrite->pagination_base . '/%d/', $page_number );
		} else {
			$url = esc_url_raw( add_query_arg( 'paged', $page_number, $url ) );
		}
	}

	return $url;
}

function smartcrawl_print_admin_notice( $key, $title, $message, $action_url, $button_text ) {
	?>
	<div
		class="notice-info notice is-dismissible wds-native-dismissible-notice"
		data-message-key="<?php echo esc_attr( $key ); ?>"
	>
		<?php if ( $title ) : ?>
			<p><strong><?php echo esc_html( $title ); ?></strong></p>
		<?php endif; ?>

		<p style="margin-bottom:15px;">
			<?php echo esc_html( $message ); ?>
		</p>
		<a
			href="<?php echo esc_attr( $action_url ); ?>"
			class="button button-primary"
		>
			<?php echo esc_html( $button_text ); ?>
		</a>
		<a
			href="#"
			class="wds-native-dismiss"
		>
			<?php esc_html_e( 'Not now', 'smartcrawl-seo' ); ?>
		</a>
		<p></p>
	</div>
	<?php
}
