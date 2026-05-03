(function () {
	'use strict';

	var translations = window.dondogCookieYesI18n || {};
	var cookieYesSelector = [
		'.cky-consent-container',
		'.cky-modal',
		'.cky-preference-center',
		'.cky-revisit-bottom-left',
		'.cky-revisit-bottom-right',
		'.cky-btn-revisit-wrapper',
		'#cookie-law-info-bar',
		'#cookie-law-info-again',
		'.cli-bar-container',
		'.cli-modal',
		'.cli_settings_button',
		'.cli_action_button',
		'[class*="cky-"]',
		'[class*="cli-"]',
		'[class*="cookieyes"]',
		'[id^="cky"]'
	].join(',');
	var textAttributes = ['aria-label', 'title', 'alt', 'value'];
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

	function normalize(value) {
		return value.replace(/\s+/g, ' ').trim();
	}

	function getSearchRoots() {
		var roots = [];

		function addRoot(root) {
			if (!root || roots.indexOf(root) !== -1) {
				return;
			}

			roots.push(root);

			if (!root.querySelectorAll) {
				return;
			}

			Array.prototype.forEach.call(root.querySelectorAll('*'), function (element) {
				if (element.shadowRoot) {
					addRoot(element.shadowRoot);
				}
			});
		}

		addRoot(document);

		return roots;
	}

	function translateString(value) {
		if (typeof value !== 'string' || value === '') {
			return value;
		}

		var trimmed = normalize(value);

		if (Object.prototype.hasOwnProperty.call(translations, trimmed)) {
			return value.replace(value.trim(), translations[trimmed]);
		}

		var translated = value;

		Object.keys(translations).forEach(function (source) {
			if (source.length < 12 || translated.indexOf(source) === -1) {
				return;
			}

			translated = translated.split(source).join(translations[source]);
		});

		return translated;
	}

	function isCookieYesElement(element) {
		if (!element || element.nodeType !== 1) {
			return false;
		}

		if (element.matches(cookieYesSelector)) {
			return true;
		}

		return Boolean(element.closest(cookieYesSelector));
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

		if (!parent || skippedTags[parent.tagName]) {
			return;
		}

		var translated = translateString(node.nodeValue);

		if (translated !== node.nodeValue) {
			node.nodeValue = translated;
		}
	}

	function translateElement(element) {
		if (!isCookieYesElement(element)) {
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

				return isCookieYesElement(parent) ? NodeFilter.FILTER_ACCEPT : NodeFilter.FILTER_REJECT;
			}
		});
		var textNode = walker.nextNode();

		while (textNode) {
			translateTextNode(textNode);
			textNode = walker.nextNode();
		}
	}

	function translateCookieYesDom() {
		getSearchRoots().forEach(function (root) {
			if (!root.querySelectorAll) {
				return;
			}

			Array.prototype.forEach.call(root.querySelectorAll(cookieYesSelector), translateElement);
		});
	}

	function translateKnownCookieText(root) {
		var target = root;

		if (root.nodeType === 9) {
			target = root.body || root.documentElement;
		}

		if (!target || !target.ownerDocument) {
			return;
		}

		var walker = target.ownerDocument.createTreeWalker(target, NodeFilter.SHOW_TEXT, {
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
			translateTextNode(textNode);
			textNode = walker.nextNode();
		}
	}

	function translateAll() {
		if (hasTranslations()) {
			translateCookieYesDom();
			getSearchRoots().forEach(translateKnownCookieText);
		}
	}

	function observeChanges() {
		if (!window.MutationObserver || !document.body) {
			return;
		}

		var observer = new MutationObserver(function (mutations) {
			mutations.forEach(function (mutation) {
				Array.prototype.forEach.call(mutation.addedNodes, function (node) {
					if (node.nodeType === 1) {
						if (isCookieYesElement(node)) {
							translateElement(node);
							return;
						}

						Array.prototype.forEach.call(node.querySelectorAll(cookieYesSelector), translateElement);
						translateKnownCookieText(node);
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

	[100, 300, 700, 1200, 2000].forEach(function (delay) {
		window.setTimeout(translateAll, delay);
	});
}());
