<?php
/**
 * Analysis (SEO and Readability) wiring hub.
 *
 * @package wpmu-dev-seo
 */

/**
 * Analysis controller
 *
 * At the same time, some rendering duties.
 */
class Smartcrawl_Controller_Analysis extends Smartcrawl_Base_Controller {

	use Smartcrawl_Singleton;

	/**
	 * Bind listening actions
	 *
	 * @return bool
	 */
	protected function init() {
		// Fetch analysis data via AJAX POST request.
		add_action( 'wp_ajax_wds-analysis-get-data', array( $this, 'json_get_post_analysis_data' ) );
		add_action( 'wp_ajax_wds-analysis-get-markup', array( $this, 'json_get_post_analysis_markup' ) );
		add_action( 'wp_ajax_wds-analysis-recheck', array( $this, 'json_get_post_analysis_recheck' ) );
		add_action( 'wp_ajax_wds-analysis-get-editor-analysis', array( $this, 'json_get_post_editor_analysis' ) );
		add_action( 'wp_ajax_wds-analysis-add-keyword', array( $this, 'add_post_keyword' ) );
		add_action( 'wp_ajax_wds-analysis-remove-keyword', array( $this, 'remove_post_keyword' ) );

		add_action( 'wp_ajax_wds-analysis-ignore-check', array( $this, 'json_set_ignore_check' ) );
		add_action( 'wp_ajax_wds-analysis-unignore-check', array( $this, 'json_unset_ignore_check' ) );

		// Set up CPT columns filtering.
		add_action( 'admin_init', array( $this, 'set_up_post_columns' ) );

		add_action( 'parse_query', array( $this, 'apply_analysis_post_list_filter' ) );

		add_action( 'post_submitbox_misc_actions', array( $this, 'add_postbox_fields' ) );
		add_action( 'wds-editor-metabox-seo-analysis', array( $this, 'add_seo_analysis_metabox_content' ) );
		add_action(
			'wds-editor-metabox-readability-analysis',
			array(
				$this,
				'add_readability_analysis_metabox_content',
			)
		);

		add_action( 'admin_enqueue_scripts', array( $this, 'inject_script_dependencies' ) );

		return true;
	}

	/**
	 * Unbinds listening actions
	 *
	 * @return bool
	 */
	protected function terminate() {
		remove_action( 'wp_ajax_wds-analysis-get-data', array( $this, 'json_get_post_analysis_data' ) );
		remove_action( 'wp_ajax_wds-analysis-get-markup', array( $this, 'json_get_post_analysis_markup' ) );
		remove_action( 'wp_ajax_wds-analysis-recheck', array( $this, 'json_get_post_analysis_recheck' ) );
		remove_action( 'wp_ajax_wds-analysis-get-editor-analysis', array( $this, 'json_get_post_editor_analysis' ) );
		remove_action( 'wp_ajax_wds-analysis-add-keyword', array( $this, 'add_post_keyword' ) );
		remove_action( 'wp_ajax_wds-analysis-remove-keyword', array( $this, 'remove_post_keyword' ) );
		remove_action( 'wp_ajax_wds-analysis-ignore-check', array( $this, 'json_set_ignore_check' ) );
		remove_action( 'wp_ajax_wds-analysis-unignore-check', array( $this, 'json_unset_ignore_check' ) );
		remove_action( 'admin_init', array( $this, 'set_up_post_columns' ) );
		remove_action( 'admin_enqueue_scripts', array( $this, 'inject_script_dependencies' ) );
		remove_action( 'post_submitbox_misc_actions', array( $this, 'add_postbox_fields' ) );
		remove_action( 'wds-editor-metabox-seo-analysis', array( $this, 'add_seo_analysis_metabox_content' ) );
		remove_action(
			'wds-editor-metabox-readability-analysis',
			array(
				$this,
				'add_readability_analysis_metabox_content',
			)
		);

		return true;
	}

	/**
	 * Applies filtering by analysis threshold
	 *
	 * Used on post list table pages
	 *
	 * @param object $query Query to augment.
	 *
	 * @return bool
	 */
	public function apply_analysis_post_list_filter( $query ) {
		$data = $_GET; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		if (
			! isset( $data['wds_analysis_threshold'] )
			&&
			! isset( $data['wds_readability_threshold'] )
		) {
			return false;
		}
		if ( ! is_admin() ) {
			return false;
		}
		if ( ! function_exists( 'get_current_screen' ) ) {
			return false;
		}

		$screen = get_current_screen();
		if ( ! is_object( $screen ) || empty( $screen->base ) ) {
			return false;
		}
		if ( 'edit' !== $screen->base ) {
			return false;
		}

		$pt = ! empty( $screen->post_type ) ? $screen->post_type : false;
		if ( $query->get( 'post_type' ) !== $pt ) {
			return false;
		}

		$meta_queries = $query->get( 'meta_query', array() );

		// Set SEO analysis threshold meta query.
		if ( isset( $data['wds_analysis_threshold'] ) ) {
			$raw = ! empty( $data['wds_analysis_threshold'] ) && is_numeric( $data['wds_analysis_threshold'] )
				? (int) $data['wds_analysis_threshold'] . ''
				: '69';
			if ( empty( $raw ) ) {
				return false;
			}

			$rx = '';
			foreach ( str_split( $raw ) as $char ) {
				$rx .= ! empty( $char )
					? '[0-' . (int) $char . ']?'
					: '0?';
			}
			$rx             = substr( $rx, 0, strlen( $rx ) - 1 ); // Strip last question mark.
			$meta_queries[] = array(
				'key'     => Smartcrawl_Model_Analysis::META_KEY_ANALYSIS,
				'value'   => '[[:punct:]]percentage[[:punct:]];i:' . $rx . ';',
				'compare' => 'REGEXP',
			);
		}

		// Set readability meta query.
		if ( isset( $data['wds_readability_threshold'] ) ) {
			// Filter by just readable/not readable.
			$readable       = ! empty( $data['wds_readability_threshold'] ) ? 1 : 0;
			$meta_queries[] = array(
				'key'     => Smartcrawl_Model_Analysis::META_KEY_READABILITY,
				'value'   => '[[:punct:]]is_readable[[:punct:]];b:' . $readable . ';',
				'compare' => 'REGEXP',
			);
		}

		if ( ! empty( $meta_queries ) ) {
			$query->set( 'meta_query', $meta_queries );
		}

		return true;
	}

	/**
	 * Enqueues admin scripts
	 *
	 * @param string $hook Page hook.
	 *
	 * @return bool Status
	 */
	public function inject_script_dependencies( $hook ) {
		if ( 'edit.php' !== $hook ) {
			return false;
		}

		wp_enqueue_script( Smartcrawl_Controller_Assets::WP_POST_LIST_TABLE_JS );
		wp_enqueue_style( Smartcrawl_Controller_Assets::WP_POST_LIST_TABLE_CSS );

		return true;
	}

	/**
	 * Sets up column filtering actions
	 *
	 * @return void
	 */
	public function set_up_post_columns() {
		if ( ! Smartcrawl_Settings::get_setting( 'disable-analysis-on-list' ) ) {
			// Set up column filtering.
			foreach ( smartcrawl_frontend_post_types() as $type ) {
				add_filter( "manage_{$type}_posts_columns", array( $this, 'add_analysis_columns' ) );
				add_action( "manage_{$type}_posts_custom_column", array( $this, 'add_analysis_column_data' ), 10, 2 );
			}
			// Add quick edit for focus keyword.
			add_action( 'quick_edit_custom_box', array( $this, 'add_quick_edit_focus_keyword_field' ), 10, 1 );
		}
	}

	/**
	 * Add Focus Keyword section.
	 *
	 * @param string $column Column name.
	 *
	 * @return void
	 */
	public function add_quick_edit_focus_keyword_field( $column ) {
		if ( 'seo' === $column ) {
			Smartcrawl_Simple_Renderer::render( 'post-list/quick-edit-seo-analysis', array() );
		}
	}

	/**
	 * Injects custom columns for analysis
	 *
	 * @param array $columns Columns hash.
	 *
	 * @return array
	 */
	public function add_analysis_columns( $columns ) {
		if ( Smartcrawl_Settings::get_setting( 'analysis-seo' ) ) {
			$columns['seo'] = __( 'SEO', 'smartcrawl-seo' );
		}
		if ( Smartcrawl_Settings::get_setting( 'analysis-readability' ) ) {
			$columns['readability'] = __( 'Readability', 'smartcrawl-seo' );
		}

		return $columns;
	}

	/**
	 * Adds custom columns analysis data
	 *
	 * @param string $cid     Column ID.
	 * @param int    $post_id Post ID.
	 *
	 * @return bool
	 */
	public function add_analysis_column_data( $cid, $post_id ) {
		if ( ! in_array( $cid, array( 'seo', 'readability' ), true ) ) {
			return false;
		}

		$result = $this->get_post_analysis_result_markup( $post_id );

		if ( 'seo' === $cid ) {
			echo wp_kses_post( $result['seo'] );
		}

		if ( 'readability' === $cid ) {
			echo wp_kses_post( $result['readability'] );
		}

		return true;
	}

	/**
	 * Gets post analysis results markup for posts column
	 *
	 * @param int $post_id ID of the post.
	 *
	 * @return array List of column markups
	 */
	public function get_post_analysis_result_markup( $post_id ) {
		$model  = new Smartcrawl_Model_Analysis( $post_id );
		$result = array();
		$na     = '<div class="wds-analysis wds-status-invalid"><span>' . esc_html( __( 'N/A', 'smartcrawl-seo' ) ) . '</span></div>';

		if ( ! $model->has_post_data( Smartcrawl_Model_Analysis::DATA_ANALYSIS ) ) {
			$result['seo'] = $na;
		} else {
			$data                     = $model->get_post_data( Smartcrawl_Model_Analysis::DATA_ANALYSIS );
			$focus_keywords           = smartcrawl_get_value( 'focus-keywords', $post_id );
			$focus_keywords_available = ! empty( $focus_keywords );
			if ( ! $focus_keywords_available ) {
				$result['seo'] = Smartcrawl_Simple_Renderer::load(
					'post-list/post-seo-analysis-errors',
					array(
						'focus_missing' => true,
						'status_class'  => 'wds-status-invalid',
						'errors'        => array(
							'focus-keyword-missing' => esc_html__( 'You need to add focus keywords to see recommendations for this article.', 'smartcrawl-seo' ),
						),
					)
				);
			} elseif ( empty( $data['errors'] ) ) {
				$result['seo'] = Smartcrawl_Simple_Renderer::load( 'post-list/post-seo-analysis-good' );
			} else {
				$result['seo'] = Smartcrawl_Simple_Renderer::load(
					'post-list/post-seo-analysis-errors',
					array(
						'errors' => $data['errors'],
					)
				);
			}
		}

		if ( ! $model->has_post_data( Smartcrawl_Model_Analysis::DATA_READABILITY ) ) {
			$result['readability'] = $na;
		} else {
			$data                  = $model->get_post_data( Smartcrawl_Model_Analysis::DATA_READABILITY );
			$readability_score     = intval( ceil( smartcrawl_get_array_value( $data, 'score' ) ) );
			$readability_ignored   = Smartcrawl_Checks::is_readability_ignored( $post_id );
			$readability_state     = $model->get_kincaid_readability_state( $readability_score, $readability_ignored );
			$readability_class     = sprintf(
				'wds-status-%s',
				$readability_state
			);
			$readability_level     = $model->get_readability_level( false );
			$tag                   = smartcrawl_get_array_value(
				$model->get_readability_levels_map(),
				array( $readability_level, 'tag' )
			);
			$tag                   = empty( $tag )
				? esc_html__( 'N/A', 'smartcrawl-seo' )
				: $tag;
			$result['readability'] = '<div class="wds-analysis ' . $readability_class . '" title="' . $readability_score . '">' . esc_html( $tag ) . '</div>';
			if ( empty( $readability_score ) ) {
				$result['readability'] .= '<div class="wds-analysis-details">' . $readability_level . '</div>';
			}
		}

		return $result;
	}

	/**
	 * Handles check ignoring front-end requests
	 *
	 * @return void
	 */
	public function json_set_ignore_check() {
		$data = $this->get_request_data();
		if ( empty( $data['post_id'] ) || ! is_numeric( $data['post_id'] ) ) {
			wp_send_json_error();

			return;
		}
		if ( empty( $data['check_id'] ) ) {
			wp_send_json_error();

			return;
		}

		Smartcrawl_Checks::add_ignored_check( (int) $data['post_id'], sanitize_text_field( $data['check_id'] ) );

		$model = new Smartcrawl_Model_Analysis( (int) $data['post_id'] );
		$model->clear_cached_data();

		wp_send_json_success();
	}

	/**
	 * Handles check de-ignoring front-end requests
	 *
	 * @return void
	 */
	public function json_unset_ignore_check() {
		$data = $this->get_request_data();
		if ( empty( $data['post_id'] ) || ! is_numeric( $data['post_id'] ) ) {
			wp_send_json_error();

			return;
		}
		if ( empty( $data['check_id'] ) ) {
			wp_send_json_error();

			return;
		}

		Smartcrawl_Checks::remove_ignored_check( (int) $data['post_id'], sanitize_text_field( $data['check_id'] ) );

		$model = new Smartcrawl_Model_Analysis( (int) $data['post_id'] );
		$model->clear_cached_data();

		wp_send_json_success();
	}

	/**
	 * Sends postbox editor JSON response with detailed post analysis
	 *
	 * @return void
	 */
	public function json_get_post_editor_analysis() {
		$data = $this->get_request_data();
		if ( empty( $data['post_id'] ) || ! is_numeric( $data['post_id'] ) ) {
			wp_send_json_error();

			return;
		}

		$is_dirty = (bool) smartcrawl_get_array_value( $data, 'is_dirty' );
		$post_id  = (int) smartcrawl_get_array_value( $data, 'post_id' );
		$post     = get_post( $post_id );
		/**
		 * If there is_dirty flag is set i.e. are unsaved changes in the editor then we
		 * will fetch the latest post revision and analyze that.
		 */
		$post_to_analyze = $is_dirty
			? smartcrawl_get_latest_post_version( $post_id )
			: $post;
		$this->analyze_post( $post_to_analyze->ID );

		$out = array();
		ob_start();
		$this->add_seo_analysis_metabox_content( $post );
		$out['seo'] = ob_get_clean();

		ob_start();
		$this->add_readability_analysis_metabox_content( $post );
		$out['readability'] = ob_get_clean();

		ob_start();
		$this->add_postbox_fields( $post );
		$out['postbox_fields'] = ob_get_clean();

		ob_start();
		$this->get_keyword_tags_content( $post );
		$out['keyword_tags'] = ob_get_clean();
		// Set the keywords too.
		$keywords              = smartcrawl_get_value( 'focus-keywords', $post->ID );
		$out['focus_keywords'] = empty( $keywords ) ? '' : $keywords;

		wp_send_json_success( $out );
	}

	/**
	 * Add a new keyword to the post.
	 *
	 * @since 3.4.0
	 *
	 * @return void
	 */
	public function add_post_keyword() {
		$data = $this->get_request_data();
		if ( empty( $data['post_id'] ) || ! is_numeric( $data['post_id'] ) || empty( $data['keyword'] ) ) {
			wp_send_json_error();

			return;
		}

		// Get required data.
		$keyword = (string) smartcrawl_get_array_value( $data, 'keyword' );
		$post_id = (int) smartcrawl_get_array_value( $data, 'post_id' );

		// Permission check.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			wp_send_json_error();

			return;
		}

		$smartcrawl_post = Smartcrawl_Post_Cache::get()->get_post( $post_id );
		if ( empty( $smartcrawl_post ) ) {
			wp_send_json_error();
		} else {
			$smartcrawl_post->add_focus_keyword( $keyword );
			$keywords = smartcrawl_get_value( 'focus-keywords', $post_id );
			wp_send_json_success( empty( $keywords ) ? '' : $keywords );
		}
	}

	/**
	 * Remove an existing keyword from the post.
	 *
	 * @since 3.4.0
	 *
	 * @return void
	 */
	public function remove_post_keyword() {
		$data = $this->get_request_data();
		if ( empty( $data['post_id'] ) || ! is_numeric( $data['post_id'] ) || empty( $data['keyword'] ) ) {
			wp_send_json_error();

			return;
		}

		// Get required data.
		$keyword = (string) smartcrawl_get_array_value( $data, 'keyword' );
		$post_id = (int) smartcrawl_get_array_value( $data, 'post_id' );

		// Permission check.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			wp_send_json_error();

			return;
		}

		$smartcrawl_post = Smartcrawl_Post_Cache::get()->get_post( $post_id );
		if ( empty( $smartcrawl_post ) ) {
			wp_send_json_error();
		} else {
			$smartcrawl_post->remove_focus_keyword( $keyword );
			$keywords = smartcrawl_get_value( 'focus-keywords', $post_id );
			wp_send_json_success( empty( $keywords ) ? '' : $keywords );
		}
	}

	/**
	 * Injects SEO analysis metabox content
	 *
	 * @param WP_Post $post Post instance.
	 *
	 * @return bool
	 */
	public function add_seo_analysis_metabox_content( $post ) {
		if ( ! Smartcrawl_Settings::get_setting( 'analysis-seo' ) ) {
			return false;
		}

		// If no analysis data is available, run analysis first.
		$this->maybe_analyze_post( $post->ID );

		$model          = new Smartcrawl_Model_Analysis( $post->ID );
		$seo_data       = $model->get_post_data( Smartcrawl_Model_Analysis::DATA_ANALYSIS );
		$extra_checks   = $model->get_post_data( Smartcrawl_Model_Analysis::DATA_ANALYSIS_EXTRA );
		$errors         = smartcrawl_get_array_value( $seo_data, 'errors' );
		$primary_checks = smartcrawl_get_array_value( $seo_data, 'checks' );
		if ( empty( $checks ) ) {
			// For older versions that didn't cache checks.
			$result         = Smartcrawl_Checks::apply( $post->ID );
			$primary_checks = $result->get_applied_checks();
		}
		$smartcrawl_post = Smartcrawl_Post_Cache::get()->get_post( $post->ID );
		$primary_keyword = $smartcrawl_post ? $smartcrawl_post->get_primary_keyword() : '';
		$extra_keywords  = $smartcrawl_post ? $smartcrawl_post->get_extra_keywords() : array();

		Smartcrawl_Simple_Renderer::render(
			'metabox/analysis-seo-analysis',
			array(
				'post_id'                  => $post->ID,
				'primary_keyword'          => $primary_keyword,
				'extra_keywords'           => $extra_keywords,
				'primary_checks'           => $primary_checks,
				'extra_checks'             => $extra_checks,
				'primary_error_count'      => ! empty( $errors ) ? count( $errors ) : 0,
				'focus_keywords_available' => ! empty( $primary_keyword ),
			)
		);

		return true;
	}

	/**
	 * Injects readability analysis metabox content.
	 *
	 * @param WP_Post $post Post instance.
	 *
	 * @return bool
	 */
	public function add_readability_analysis_metabox_content( $post ) {
		if ( ! Smartcrawl_Settings::get_setting( 'analysis-readability' ) ) {
			return false;
		}

		// If no analysis data is available, run analysis first.
		$this->maybe_analyze_post( $post->ID );

		$model               = new Smartcrawl_Model_Analysis( $post->ID );
		$readability_data    = $model->get_post_data( Smartcrawl_Model_Analysis::DATA_READABILITY );
		$readability_ignored = Smartcrawl_Checks::is_readability_ignored( $post->ID );

		Smartcrawl_Simple_Renderer::render(
			'metabox/analysis-readability',
			array(
				'model'               => $model,
				'readability_data'    => $readability_data,
				'readability_ignored' => $readability_ignored,
			)
		);

		return true;
	}

	/**
	 * Determine whether current post type requires analysis or not.
	 *
	 * @param int $post_id Post ID.
	 *
	 * @return bool
	 */
	private function post_type_requires_analysis( $post_id ) {
		$post_type = get_post_type_object( get_post_type( $post_id ) );

		return 'revision' === $post_type->name || $post_type->public;
	}

	/**
	 * Return SEO analysis keyword tags content.
	 *
	 * @param WP_Post $post Post instance.
	 *
	 * @return bool
	 */
	private function get_keyword_tags_content( $post ) {
		if ( ! Smartcrawl_Settings::get_setting( 'analysis-seo' ) ) {
			return false;
		}

		$smartcrawl_post = Smartcrawl_Post_Cache::get()->get_post( $post->ID );
		$focus_keywords  = $smartcrawl_post ? $smartcrawl_post->get_focus_keywords() : array();

		Smartcrawl_Simple_Renderer::render(
			'metabox/analysis-seo-keyword-tags',
			array(
				'post_id'  => $post->ID,
				'keywords' => $focus_keywords,
			)
		);

		return true;
	}

	/**
	 * Update post analysis data
	 *
	 * Forcefully updates the post analysis data,
	 * no questions asked
	 *
	 * @param int $post_id Post ID.
	 *
	 * @return bool Status
	 */
	public function analyze_post( $post_id ) {
		if (
			empty( $post_id )
			|| ! is_numeric( $post_id )
			|| ! $this->post_type_requires_analysis( $post_id )
		) {
			return false;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return false;
		}

		$model = new Smartcrawl_Model_Analysis( $post_id );
		$model->update_analysis_data();
		$model->update_readability_data();

		return true;
	}

	/**
	 * Update post analysis data only if there's no such data
	 *
	 * @param int $post_id Post ID.
	 *
	 * @return bool Whether we updated the analysis data or not
	 */
	public function maybe_analyze_post( $post_id ) {
		$analyzed = false;
		if ( empty( $post_id ) || ! is_numeric( $post_id ) ) {
			return $analyzed;
		}

		$model = new Smartcrawl_Model_Analysis( $post_id );

		if ( ! $model->has_post_data( Smartcrawl_Model_Analysis::DATA_ANALYSIS ) ) {
			if ( current_user_can( 'edit_post', $post_id ) ) {
				$model->update_analysis_data();
			}
			$analyzed = true;
		}

		if ( ! $model->has_post_data( Smartcrawl_Model_Analysis::DATA_READABILITY ) ) {
			if ( current_user_can( 'edit_post', $post_id ) ) {
				$model->update_readability_data();
			}
			$analyzed = true;
		}

		return $analyzed;
	}

	/**
	 * Injects postbox publish editor content
	 *
	 * @param WP_Post $post Post instance.
	 *
	 * @return void
	 */
	public function add_postbox_fields( $post ) {
		$model                    = new Smartcrawl_Model_Analysis( $post->ID );
		$smartcrawl_post          = Smartcrawl_Post_Cache::get()->get_post( $post->ID );
		$focus_keywords           = $smartcrawl_post
			? $smartcrawl_post->get_focus_keywords()
			: array();
		$focus_keywords_available = ! empty( $focus_keywords );

		if ( in_array( get_post_status( $post ), array( 'draft', 'auto-draft' ), true ) ) {
			$result     = Smartcrawl_Checks::apply( $post->ID );
			$checks     = $result->get_applied_checks();
			$has_errors = false;
			foreach ( $checks as $title => $chk ) {
				if ( empty( $chk['status'] ) && empty( $chk['ignored'] ) ) {
					$has_errors = true;
					break;
				}
			}
		} else {
			$seo_data   = $model->get_post_data( Smartcrawl_Model_Analysis::DATA_ANALYSIS );
			$has_errors = ! empty( $seo_data['errors'] );
		}

		if ( ! $focus_keywords_available ) {
			$seo_class = 'wds-status-invalid';
			$seo_text  = __( 'No Focus Keyword', 'smartcrawl-seo' );
		} elseif ( $has_errors ) {
			$seo_class = 'wds-status-warning';
			$seo_text  = __( 'Needs Improvement', 'smartcrawl-seo' );
		} else {
			$seo_class = 'wds-status-success';
			$seo_text  = __( 'Good', 'smartcrawl-seo' );
		}

		$readability_data    = $model->get_post_data( Smartcrawl_Model_Analysis::DATA_READABILITY );
		$readability_score   = smartcrawl_get_array_value( $readability_data, 'score' );
		$readability_score   = intval( ceil( $readability_score ) );
		$readability_ignored = Smartcrawl_Checks::is_readability_ignored( $post->ID );
		$readability_state   = $model->get_kincaid_readability_state( $readability_score, $readability_ignored );
		$readability_class   = sprintf(
			'wds-status-%s',
			$readability_state
		);
		$readability_text    = $model->get_readability_level();

		?>
		<div class="wds-post-box-fields">
			<?php if ( Smartcrawl_Settings::get_setting( 'analysis-seo' ) ) : ?>
				<div class="misc-pub-section seo-analysis <?php echo esc_attr( $seo_class ); ?>">
					<i class="wds-icon-magnifying-glass-search"></i>
					<?php esc_html_e( 'SEO:', 'smartcrawl-seo' ); ?> <b><?php echo esc_html( $seo_text ); ?></b>
				</div>
			<?php endif; ?>

			<?php if ( Smartcrawl_Settings::get_setting( 'analysis-readability' ) ) : ?>
				<div class="misc-pub-section readability-analysis <?php echo esc_attr( $readability_class ); ?>">
					<i class="wds-icon-monitor"></i>
					<?php esc_html_e( 'Readability:', 'smartcrawl-seo' ); ?> <b><?php echo esc_html( $readability_text ); ?></b>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Sends JSON response with post analysis data
	 *
	 * As a side-effect, updates the analysis if needed
	 *
	 * @return void
	 */
	public function json_get_post_analysis_data() {
		$data = $this->get_request_data();
		if ( empty( $data['post_id'] ) || ! is_numeric( $data['post_id'] ) ) {
			wp_send_json_error();

			return;
		}

		$this->maybe_analyze_post( (int) $data['post_id'] );
		$model = new Smartcrawl_Model_Analysis( (int) $data['post_id'] );

		wp_send_json_success(
			array(
				'analysis'              => $model->get_post_data( Smartcrawl_Model_Analysis::DATA_ANALYSIS ),
				'readability'           => $model->get_post_data( Smartcrawl_Model_Analysis::DATA_READABILITY ),
				'readability_threshold' => $model->get_readability_threshold(),
				'readable'              => $model->is_readable(),
			)
		);
	}

	/**
	 * Sends JSON response with post analysis markup
	 *
	 * As a side-effect, updates the analysis if needed
	 *
	 * @return void
	 */
	public function json_get_post_analysis_markup() {
		$data = $this->get_request_data();
		if ( empty( $data['post_id'] ) || ! is_numeric( $data['post_id'] ) ) {
			wp_send_json_error();

			return;
		}

		$this->maybe_analyze_post( (int) $data['post_id'] );
		$result = $this->get_post_analysis_result_markup( (int) $data['post_id'] );

		wp_send_json_success( $result );
	}

	/**
	 * Force analysis recheck and respond with column markup data
	 *
	 * @return void
	 */
	public function json_get_post_analysis_recheck() {
		$data = $this->get_request_data();
		if ( empty( $data['post_id'] ) || ! is_numeric( $data['post_id'] ) ) {
			wp_send_json_error();

			return;
		}

		$this->analyze_post( (int) $data['post_id'] );
		$result = $this->get_post_analysis_result_markup( (int) $data['post_id'] );
		wp_send_json_success( $result );
	}

	/**
	 * Verify nonce for request.
	 *
	 * @return array|mixed
	 */
	private function get_request_data() {
		return isset( $_POST['_wds_nonce'] ) && wp_verify_nonce( wp_unslash( $_POST['_wds_nonce'] ), 'wds-metabox-nonce' ) ? stripslashes_deep( $_POST ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	}
}
