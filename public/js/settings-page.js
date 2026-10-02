(function () {
	'use strict';

	var settings = window.GridElementTrash || {};

	document.addEventListener('click', function (e) {
		var item = e.target;
		if (!item.classList || !item.classList.contains('trash-check')) {
			return;
		}

		var body = new URLSearchParams();
		body.set('action', settings.action);
		body.set('_wpnonce', settings.nonce);
		body.set('element', item.getAttribute('data-element'));
		body.set('type', item.getAttribute('name'));
		body.set('value', item.checked ? 0 : 1);

		fetch(settings.ajaxUrl, {
			method: 'POST',
			credentials: 'same-origin',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
			body: body.toString()
		});
	});
})();
