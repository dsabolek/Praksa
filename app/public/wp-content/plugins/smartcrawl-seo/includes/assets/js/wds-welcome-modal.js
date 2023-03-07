(function ($, undefined) {
	window.Wds = window.Wds || {};

	$(init);

	/**
	 * Initialze modal functionality.
	 */
	function init() {
		if ($('#wds-welcome-modal').length) {
			Wds.open_dialog('wds-welcome-modal');
		}

		// On close.
		$(document).on('click', '#wds-welcome-modal-close-button, #wds-welcome-modal-get-started', closeModal);
	}

	/**
	 * Close the modal using ajax.
	 *
	 * @param {object} e Event.
	 */
	function closeModal(e) {
		e.preventDefault();
		e.stopPropagation();

		$.post(ajaxurl, {
			action: 'wds-close-welcome-modal',
			_wds_nonce: _wds_welcome.nonce,
		}, function (response) {
			if (response.success) {
				Wds.close_dialog();
			}
		});
	}
})(jQuery);
