import ErrorBoundary from './components/error-boundry';
import domReady from '@wordpress/dom-ready';
import { render } from 'react-dom';
import React from 'react';
import ConfigValues from './es6/config-values';
import Redirects from './components/redirects/redirects';
import WooSettingsTab from './components/woocommerce/woo-settings-tab';
import Autolinks from './modules/adv-tools/autolinks';

domReady(() => {
	const placeholder = document.getElementById('wds-autolinks');
	if (placeholder) {
		render(
			<ErrorBoundary>
				<Autolinks />
			</ErrorBoundary>,
			placeholder
		);
	}

	const redirectsContainer = document.getElementById(
		'wds-redirects-container'
	);
	if (redirectsContainer) {
		const redirects = ConfigValues.get('redirects', 'redirects') || {};
		const nonce = ConfigValues.get('nonce', 'redirects') || {};
		const homeUrl = ConfigValues.get('home_url', 'redirects') || {};
		const csvTypes =
			ConfigValues.get('accepted-csv-types', 'redirects') || [];
		render(
			<ErrorBoundary>
				<Redirects
					redirects={redirects}
					homeUrl={homeUrl}
					nonce={nonce}
					csvTypes={csvTypes}
				/>
			</ErrorBoundary>,
			redirectsContainer
		);
	}

	const wooTab = document.getElementById('wds-woo-settings-tab');
	if (wooTab) {
		const options = ConfigValues.get('options', 'woo') || {};
		const permalinkSettings = ConfigValues.get('permalink_settings', 'woo');
		render(
			<ErrorBoundary>
				<WooSettingsTab
					{...options}
					permalinkSettingsUrl={permalinkSettings}
					disabledImagePath={ConfigValues.get('image_path', 'woo')}
					nonce={ConfigValues.get('nonce', 'woo')}
				/>
			</ErrorBoundary>,
			wooTab
		);
	}
});

(function ($) {
	function submit_dialog_form_on_enter(e) {
		const $button = $(this).find('.wds-action-button'),
			key = e.which;

		if ($button.length && 13 === key) {
			e.preventDefault();
			e.stopPropagation();

			$button.trigger('click');
		}
	}

	function validate_moz_form(e) {
		let is_valid = true,
			$form = $(this),
			$submit_button = $('button[type="submit"]', $form);

		$('.sui-form-field', $form).each(function () {
			const $form_field = $(this),
				$input = $('input', $form_field);

			if (!$input.val().trim()) {
				is_valid = false;
				$form_field.addClass('sui-form-field-error');

				$input.on('focus keydown', function () {
					$(this)
						.closest('.sui-form-field-error')
						.removeClass('sui-form-field-error');
				});
			}
		});

		if (is_valid) {
			$submit_button.addClass('sui-button-onload');
		} else {
			$submit_button.removeClass('sui-button-onload');
			e.preventDefault();
		}
	}

	function adjust_robots_field_height() {
		let scrollHeight = this.scrollHeight;
		if (!scrollHeight && this.value.includes('\n')) {
			scrollHeight = (this.value.split('\n').length + 1) * 22;
		}
		this.style.height = '1px';
		this.style.height = scrollHeight + 'px';
	}

	function open_add_redirect_form() {
		const query = new URLSearchParams(window.location.search);
		if (
			query.get('tab') === 'tab_url_redirection' &&
			query.get('add_redirect')
		) {
			$('button.wds-add-redirect').trigger('click');
		}
	}

	$(function () {
		$('.wds-vertical-tabs').on(
			'wds_vertical_tabs:tab_change',
			function (event, active_tab) {
				$(active_tab)
					.find('.wds-vertical-tab-section')
					.removeClass('hidden');
			}
		);

		$(document)
			.on('submit', '.wds-moz-form', validate_moz_form)
			.on(
				'input propertychange',
				'.tab_robots_editor textarea',
				adjust_robots_field_height
			)
			.on('keydown', '.sui-modal', submit_dialog_form_on_enter);

		$('.tab_robots_editor textarea').each(function () {
			adjust_robots_field_height.apply(this);
		});
		window.Wds.link_dropdown();
		window.Wds.accordion();
		window.Wds.vertical_tabs();
		window.Wds.hook_toggleables();

		open_add_redirect_form();
	});
})(jQuery);
