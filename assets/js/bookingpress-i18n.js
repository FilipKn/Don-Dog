(function () {
	'use strict';

	var translations = window.dondogBookingPressI18n || {};
	var config = window.dondogBookingPressI18nConfig || {};
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

	function isGermanPage() {
		return config.language === 'de';
	}

	function getGermanThankYouUrl() {
		return typeof config.thankYouUrl === 'string' ? config.thankYouUrl : '';
	}

	function isGermanThankYouPage() {
		return isGermanPage() && /\/vielen-dank\/?$/i.test(window.location.pathname);
	}

	function rewriteThankYouUrl(value) {
		var target = getGermanThankYouUrl();

		if (!isGermanPage() || !target || typeof value !== 'string' || value === '') {
			return value;
		}

		if (value.indexOf('/vielen-dank') !== -1) {
			return value;
		}

		var trimmed = value.trim();
		var parsed;

		try {
			parsed = new URL(trimmed, window.location.origin);
		} catch (error) {
			return value;
		}

		var normalizedPath = parsed.pathname.replace(/\/+$/, '') + '/';
		var thankYouPaths = [
			'/thank-you/',
			'/sl/thank-you/',
			'/thankyou/',
			'/sl/thankyou/',
			'/hvala/',
			'/sl/hvala/',
			'/zahvala/',
			'/sl/zahvala/',
			'/hvala-za-rezervacijo/',
			'/sl/hvala-za-rezervacijo/'
		];

		if (thankYouPaths.indexOf(normalizedPath) === -1) {
			return value;
		}

		var replacement = target;

		if (parsed.search) {
			replacement += parsed.search;
		}

		if (parsed.hash) {
			replacement += parsed.hash;
		}

		return value.replace(trimmed, replacement);
	}

	function translateString(value) {
		if (typeof value !== 'string' || value === '') {
			return value;
		}

		var trimmed = value.trim();
		var translatedTimeRange = trimmed.replace(/^(\d{1,2}:\d{2})\s+to\s+(\d{1,2}:\d{2})$/i, '$1 - $2');

		if (translatedTimeRange !== trimmed) {
			return value.replace(trimmed, translatedTimeRange);
		}

		if (!Object.prototype.hasOwnProperty.call(translations, trimmed)) {
			return rewriteThankYouUrl(value);
		}

		return rewriteThankYouUrl(value.replace(trimmed, translations[trimmed]));
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

	function translateThankYouPageDom() {
		if (!isGermanThankYouPage() || !document.body) {
			return;
		}

		var walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT, {
			acceptNode: function (node) {
				var parent = node.parentElement;

				if (!parent || skippedTags[parent.tagName]) {
					return NodeFilter.FILTER_REJECT;
				}

				return translateString(node.nodeValue) !== node.nodeValue ? NodeFilter.FILTER_ACCEPT : NodeFilter.FILTER_REJECT;
			}
		});
		var textNode = walker.nextNode();

		while (textNode) {
			textNode.nodeValue = translateString(textNode.nodeValue);
			textNode = walker.nextNode();
		}
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

	function refreshGlobalsFromBookingPressInteraction(event) {
		var target = event.target;

		if (target && target.nodeType === 1 && isBookingPressElement(target)) {
			translateBookingPressGlobals();
		}
	}

	function bindBookingPressInteractionHandlers() {
		['click', 'change', 'submit'].forEach(function (eventName) {
			document.addEventListener(eventName, refreshGlobalsFromBookingPressInteraction, true);
		});
	}

	function translateAll() {
		if (!hasTranslations()) {
			return;
		}

		translateBookingPressGlobals();
		translateBookingPressDom();
		translateThankYouPageDom();
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
			bindBookingPressInteractionHandlers();
		});
	} else {
		translateAll();
		observeChanges();
		bindBookingPressInteractionHandlers();
	}

	[100, 500, 1000, 2000].forEach(function (delay) {
		window.setTimeout(translateAll, delay);
	});
}());
