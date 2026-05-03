(function () {
	'use strict';

	var translations = window.dondogBookingPressI18n || {};
	var bookingPressSelector = [
		'[id^="bookingpress_booking_form"]',
		'.bookingpress',
		'.bookingpress_front',
		'.bookingpress_front_vue',
		'[class*="bookingpress"]',
		'[class*="bpa-front"]'
	].join(',');
	var textAttributes = ['placeholder', 'aria-label', 'title', 'value'];
	var skippedTags = {
		SCRIPT: true,
		STYLE: true,
		NOSCRIPT: true,
		TEMPLATE: true,
		CODE: true,
		PRE: true
	};

	function hasTranslations() {
		return Object.keys(translations).length > 0;
	}

	function translateString(value) {
		if (typeof value !== 'string' || value === '') {
			return value;
		}

		var trimmed = value.trim();

		if (!Object.prototype.hasOwnProperty.call(translations, trimmed)) {
			return value;
		}

		return value.replace(trimmed, translations[trimmed]);
	}

	function isBookingPressElement(element) {
		if (!element || element.nodeType !== 1) {
			return false;
		}

		if (element.matches(bookingPressSelector)) {
			return true;
		}

		return Boolean(element.closest(bookingPressSelector));
	}

	function translateAttributes(element) {
		textAttributes.forEach(function (attribute) {
			if (!element.hasAttribute(attribute)) {
				return;
			}

			var current = element.getAttribute(attribute);
			var translated = translateString(current);

			if (translated !== current) {
				element.setAttribute(attribute, translated);
			}
		});
	}

	function translateTextNode(node) {
		var parent = node.parentElement;

		if (!parent || skippedTags[parent.tagName] || !isBookingPressElement(parent)) {
			return;
		}

		var translated = translateString(node.nodeValue);

		if (translated !== node.nodeValue) {
			node.nodeValue = translated;
		}
	}

	function translateElement(element) {
		if (!isBookingPressElement(element)) {
			return;
		}

		translateAttributes(element);

		Array.prototype.forEach.call(element.querySelectorAll('*'), function (child) {
			if (!skippedTags[child.tagName]) {
				translateAttributes(child);
			}
		});

		var walker = document.createTreeWalker(element, NodeFilter.SHOW_TEXT, {
			acceptNode: function (node) {
				var parent = node.parentElement;

				if (!parent || skippedTags[parent.tagName]) {
					return NodeFilter.FILTER_REJECT;
				}

				return isBookingPressElement(parent) ? NodeFilter.FILTER_ACCEPT : NodeFilter.FILTER_REJECT;
			}
		});
		var textNode = walker.nextNode();

		while (textNode) {
			translateTextNode(textNode);
			textNode = walker.nextNode();
		}
	}

	function translateBookingPressDom() {
		Array.prototype.forEach.call(document.querySelectorAll(bookingPressSelector), translateElement);
	}

	function translateObjectStrings(value, seen) {
		if (!value || typeof value !== 'object') {
			return;
		}

		if (seen.has(value)) {
			return;
		}

		seen.add(value);

		Object.keys(value).forEach(function (key) {
			if (typeof value[key] === 'string') {
				value[key] = translateString(value[key]);
				return;
			}

			if (value[key] && typeof value[key] === 'object') {
				translateObjectStrings(value[key], seen);
			}
		});
	}

	function translateBookingPressGlobals() {
		[
			'bookingpress_return_data',
			'bookingpress_front_vue_data',
			'bookingpress_front_vue_data_fields',
			'bookingpress_vue_data',
			'bookingpress_booking_form_data'
		].forEach(function (key) {
			if (window[key]) {
				translateObjectStrings(window[key], new WeakSet());
			}
		});
	}

	function translateAll() {
		if (!hasTranslations()) {
			return;
		}

		translateBookingPressGlobals();
		translateBookingPressDom();
	}

	function observeChanges() {
		if (!window.MutationObserver || !document.body) {
			return;
		}

		var observer = new MutationObserver(function (mutations) {
			mutations.forEach(function (mutation) {
				Array.prototype.forEach.call(mutation.addedNodes, function (node) {
					if (node.nodeType === 1) {
						if (isBookingPressElement(node)) {
							translateElement(node);
							return;
						}

						Array.prototype.forEach.call(node.querySelectorAll(bookingPressSelector), translateElement);
					}

					if (node.nodeType === 3) {
						translateTextNode(node);
					}
				});

				if (mutation.type === 'characterData') {
					translateTextNode(mutation.target);
				}
			});
		});

		observer.observe(document.body, {
			childList: true,
			characterData: true,
			subtree: true
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function () {
			translateAll();
			observeChanges();
		});
	} else {
		translateAll();
		observeChanges();
	}

	[100, 500, 1000, 2000].forEach(function (delay) {
		window.setTimeout(translateAll, delay);
	});
}());
