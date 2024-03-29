import $ from 'jQuery';
import ConfigValues from './config-values';
import { EventTarget } from 'event-target-shim';

class MetaboxAnalysisHelper {
	static get_focus_keyword_el() {
		return $('#wds_focus');
	}

	static get_focus_keyword_input_el() {
		return $('#wds_focus_input');
	}

	static get_focus_keyword() {
		return this.get_focus_keyword_el().val();
	}

	static get_title() {
		return $('#wds_title').val();
	}

	static get_description() {
		return $('#wds_metadesc').val();
	}

	static get_metabox_el() {
		return $('#wds-wds-meta-box');
	}

	static get_seo_report_el() {
		return $('.wds-seo-analysis', this.get_seo_analysis_el());
	}

	static get_readability_report_el() {
		return $('.wds-readability-report', this.get_readability_analysis_el());
	}

	static get_postbox_fields_el() {
		return $('.wds-post-box-fields');
	}

	static replace_seo_report(new_report) {
		this.get_seo_report_el().replaceWith(new_report);
	}

	static replace_readability_report(new_report) {
		this.get_readability_report_el().replaceWith(new_report);
	}

	static replace_post_fields(new_fields) {
		this.get_postbox_fields_el().replaceWith(new_fields);
	}

	static get_refresh_button_el() {
		return $('.wds-refresh-analysis', this.get_metabox_el());
	}

	static update_refresh_button(enable) {
		this.get_refresh_button_el().prop('disabled', !enable);
	}

	static get_seo_error_count() {
		return this.get_seo_report_el().data('errors');
	}

	static get_readability_state() {
		return this.get_readability_report_el().data('readabilityState');
	}

	static get_seo_analysis_el() {
		return $('.wds-seo-analysis-container', this.get_metabox_el());
	}

	static get_readability_analysis_el() {
		return $('.wds-readability-analysis-container', this.get_metabox_el());
	}

	static block_ui($el = false) {
		let $container = this.get_analysis_containers();

		if ($el) {
			$el.addClass(
				$el.is('button') ? 'sui-button-onload' : 'wds-item-loading'
			);
		} else {
			$('.wds-report-inner', $container).hide();
			$('.wds-seo-analysis', $container).hide();
			$('.wds-analysis-working', $container).show();
		}

		$('.wds-disabled-during-request', $container).prop('disabled', true);
		$('#wds_add_keyword').prop('disabled', true);
		$('#wds_add_keyword').addClass('sui-button-onload-text');
		$('#wds-added-keywords-tags').hide();
	}

	static unblock_ui() {
		let $container = this.get_analysis_containers();

		$('.wds-item-loading', $container).removeClass('wds-item-loading');
		$('.sui-button-onload', $container).removeClass('sui-button-onload');
		$('.wds-report-inner', $container).show();
		$('.wds-seo-analysis', $container).show();
		$('.wds-analysis-working', $container).hide();
		$('.wds-disabled-during-request', $container).prop('disabled', false);
		$('#wds_add_keyword')
			.prop('disabled', false)
			.removeClass('sui-button-onload-text');
		$('#wds-added-keywords-tags').show();
	}

	static get_analysis_containers() {
		return $(
			'.wds-seo-analysis-container, .wds-readability-analysis-container',
			this.get_metabox_el()
		);
	}

	static update_focus_field_state(focusValid) {
		this.get_focus_container_el()
			.removeClass('wds-focus-keyword-loaded wds-focus-keyword-invalid')
			.addClass(
				focusValid
					? 'wds-focus-keyword-loaded'
					: 'wds-focus-keyword-invalid'
			);
	}

	static get_focus_container_el() {
		return $('.wds-focus-keyword');
	}
}

class MetaboxAnalysis extends EventTarget {
	/**
	 * @param postEditor {ClassicEditor|GutenbergEditor}
	 * @param metaboxOnpage {MetaboxOnpage|false}
	 */
	constructor(postEditor, metaboxOnpage) {
		super();

		this.editor = postEditor;
		this.metaboxOnpage = metaboxOnpage;
		this.init();
	}

	init() {
		this.editor.addEventListener('autosave', () => {
			this.refresh_analysis(true);
		});

		$(document)
			.on('click', '.wds-refresh-analysis', (e) =>
				this.handle_refresh_click(e)
			)
			.on('click', '.wds-seo-analysis-container .wds-ignore', (e) =>
				this.handle_ignore_click(e)
			)
			.on('click', '.wds-seo-analysis-container .wds-unignore', (e) =>
				this.handle_unignore_click(e)
			)
			.on(
				'click',
				'.wds-readability-analysis-container .wds-ignore',
				(e) => this.handle_ignore_click(e)
			)
			.on(
				'click',
				'.wds-readability-analysis-container .wds-unignore',
				(e) => this.handle_unignore_click(e)
			)
			.on('click', '.wds-seo-analysis-container #wds_add_keyword', (e) =>
				this.handle_focus_keywords_add(e)
			)
			.on(
				'click',
				'.wds-seo-analysis-container .wds-remove-keyword',
				(e) => this.handle_focus_keywords_remove(e)
			);

		$(window)
			.on('load', () => this.hook_meta_change_listener()) // Hook meta change listener as late as possible
			.on('load', () => this.handle_page_load());
	}

	refresh_analysis(forceDirty = false) {
		let focusKeyword = MetaboxAnalysisHelper.get_focus_keyword();

		return this.post('wds-analysis-get-editor-analysis', {
			post_id: this.editor.get_data().get_id(),
			is_dirty: forceDirty || this.editor.is_post_dirty() ? 1 : 0,
			wds_title: MetaboxAnalysisHelper.get_title(),
			wds_description: MetaboxAnalysisHelper.get_description(),
			wds_focus_keywords: focusKeyword,
		})
			.done((response) => {
				if (!(response || {}).success) {
					return false;
				}

				let data = (response || {}).data,
					seo_report = (data || {}).seo || '',
					readability_report = (data || {}).readability || '',
					post_fields = (data || {}).postbox_fields || '',
					keyword_tags = (data || {}).keyword_tags || '',
					focus_keywords = (data || {}).focus_keywords || '';

				// In case if it's a wrong string.
				if (focus_keywords === 'false') {
					focus_keywords = '';
				}

				MetaboxAnalysisHelper.replace_seo_report(seo_report);
				MetaboxAnalysisHelper.replace_readability_report(
					readability_report
				);
				MetaboxAnalysisHelper.replace_post_fields(post_fields);
				$('#wds-added-keywords-tags').html(keyword_tags);
				// Set keywords to hidden input.
				MetaboxAnalysisHelper.get_focus_keyword_el().val(
					focus_keywords
				);

				let seo_errors = MetaboxAnalysisHelper.get_seo_error_count(),
					readability_state =
						MetaboxAnalysisHelper.get_readability_state();

				this.dispatch_seo_update_event(seo_errors);
				this.dispatch_readability_update_event(readability_state);
			})
			.always(() => {
				MetaboxAnalysisHelper.unblock_ui();

				let focusValid = !!(focusKeyword && focusKeyword.length);
				MetaboxAnalysisHelper.update_focus_field_state(focusValid);
				MetaboxAnalysisHelper.update_refresh_button(true);
				// Toggle button state.
				this.toggle_keyword_button();
			});
	}

	/**
	 * Add new keyword for the post.
	 *
	 * @since 3.4.0
	 *
	 * @returns {*}
	 */
	add_keyword() {
		let keyword = MetaboxAnalysisHelper.get_focus_keyword_input_el().val();

		return this.post('wds-analysis-add-keyword', {
			post_id: this.editor.get_data().get_id(),
			keyword: keyword,
		})
			.done((response) => {
				if (!(response || {}).success) {
					MetaboxAnalysisHelper.unblock_ui();
					return false;
				}
				// Set keywords to hidden input.
				MetaboxAnalysisHelper.get_focus_keyword_el().val(response.data);
			})
			.always(() => {
				// Clear input.
				MetaboxAnalysisHelper.get_focus_keyword_input_el().val('');
				// Now refresh the analysis.
				this.refresh_analysis();
			});
	}

	/**
	 * Remove a keyword from the post.
	 *
	 * @since 3.4.0
	 *
	 * @returns {*}
	 */
	remove_keyword(keyword) {
		return this.post('wds-analysis-remove-keyword', {
			post_id: this.editor.get_data().get_id(),
			keyword: keyword,
		})
			.done((response) => {
				if (!(response || {}).success) {
					MetaboxAnalysisHelper.unblock_ui();
					return false;
				}
				// Set keywords to hidden input.
				MetaboxAnalysisHelper.get_focus_keyword_el().val(response.data);
			})
			.always(() => {
				// Now refresh the analysis.
				this.refresh_analysis();
			});
	}

	handle_refresh_click(e) {
		this.prevent_default(e);
		this.dispatch_event('before-analysis-refresh');
		MetaboxAnalysisHelper.block_ui();
		this.editor.autosave();
	}

	handle_ignore_click(e) {
		this.prevent_default(e);

		let $button = $(e.target).closest('button'),
			check_id = $button.attr('data-check_id');

		MetaboxAnalysisHelper.block_ui($button);
		return this.change_ignore_status(check_id, true).done(() =>
			this.refresh_analysis()
		);
	}

	handle_unignore_click(e) {
		this.prevent_default(e);

		let $button = $(e.target).closest('button'),
			check_id = $button.attr('data-check_id');

		MetaboxAnalysisHelper.block_ui($button);
		return this.change_ignore_status(check_id, false).done(() =>
			this.refresh_analysis()
		);
	}

	handle_focus_keywords_add() {
		this.dispatch_event('before-focus-keyword-add');
		MetaboxAnalysisHelper.block_ui(false);
		this.add_keyword();
	}

	handle_focus_keywords_remove(ev) {
		let keyword = $(ev.target).attr('data-keyword');
		if (keyword.length > 0) {
			this.dispatch_event('before-focus-keyword-remove');
			MetaboxAnalysisHelper.block_ui();
			this.remove_keyword(keyword);
		}
	}

	hook_meta_change_listener() {
		let metaboxOnpage = this.metaboxOnpage;
		if (metaboxOnpage) {
			metaboxOnpage.addEventListener('meta-change-deferred', () =>
				this.refresh_analysis()
			);
		}
	}

	handle_page_load() {
		this.dispatch_event('before-analysis-refresh');
		MetaboxAnalysisHelper.block_ui();
		this.refresh_analysis();
	}

	/**
	 * Hide or show keyword add button when required.
	 *
	 * If there are 3 keywords, disable keyword add button.
	 * If there are less than 3, enable button.
	 *
	 * @since 3.4.0
	 */
	toggle_keyword_button() {
		let keywords = MetaboxAnalysisHelper.get_focus_keyword_el().val();
		if (keywords.length > 0 && keywords.split(',').length > 2) {
			$('#wds_add_keyword').prop('disabled', true);
		} else {
			$('#wds_add_keyword').prop('disabled', false);
		}
	}

	post(action, data) {
		data = $.extend(
			{
				action: action,
				_wds_nonce: ConfigValues.get('nonce', 'metabox'),
			},
			data
		);

		return $.post(ajaxurl, data);
	}

	change_ignore_status(check_id, ignore) {
		this.dispatch_event('before-ignore-status-change');

		let action = !!ignore
			? 'wds-analysis-ignore-check'
			: 'wds-analysis-unignore-check';
		return this.post(action, {
			post_id: this.editor.get_data().get_id(),
			check_id: check_id,
		});
	}

	prevent_default(event) {
		if (event && event.preventDefault && event.stopPropagation) {
			event.preventDefault();
			event.stopPropagation();
		}
	}

	dispatch_event(event) {
		this.dispatchEvent(new Event(event));
	}

	dispatch_seo_update_event(error_count) {
		let event = new CustomEvent('after-seo-analysis-update', {
			detail: { errors: error_count },
		});
		this.dispatchEvent(event);
	}

	dispatch_readability_update_event(state) {
		let event = new CustomEvent('after-readability-analysis-update', {
			detail: { state: state },
		});
		this.dispatchEvent(event);
	}
}

export default MetaboxAnalysis;
