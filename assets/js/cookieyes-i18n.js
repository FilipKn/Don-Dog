(function () {
	'use strict';

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
	var observedTargets = typeof WeakSet !== 'undefined' ? new WeakSet() : null;
	var persistentTimer = null;
	var persistentRuns = 0;
	var cookieYesGlobals = [
		'ckySettings',
		'ckyConfig',
		'_ckyConfig',
		'cookieyes',
		'CookieYes',
		'Cookieyes',
		'CookieConsent',
		'cli_cookiebar_settings',
		'CLI',
		'cookieLawInfo',
		'ckyConsent'
	];

	function hasTranslations() {
		return Object.keys(getTranslations()).length > 0;
	}

	function getTranslations() {
		return window.dondogCookieYesI18n || {};
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

		var translations = getTranslations();
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

		Object.keys(translations).forEach(function (source) {
			var normalizedTranslated = normalize(translated);

			if (source.length < 12 || normalizedTranslated.indexOf(source) === -1) {
				return;
			}

			translated = normalizedTranslated.split(source).join(translations[source]);
		});

		return translated;
	}

	function markObserved(target) {
		if (observedTargets) {
			observedTargets.add(target);
			return;
		}

		target.dondogCookieYesI18nObserved = true;
	}

	function isObserved(target) {
		return observedTargets ? observedTargets.has(target) : Boolean(target.dondogCookieYesI18nObserved);
	}

	function markSeenObject(value, seen) {
		if (seen.add) {
			seen.add(value);
			return;
		}

		seen.push(value);
	}

	function hasSeenObject(value, seen) {
		if (seen.has) {
			return seen.has(value);
		}

		return seen.indexOf(value) !== -1;
	}

	function translateObjectStrings(value, seen, depth) {
		if (!value || depth > 5 || value === window || value === document || value.nodeType) {
			return;
		}

		if (typeof value !== 'object' && typeof value !== 'function') {
			return;
		}

		if (hasSeenObject(value, seen)) {
			return;
		}

		markSeenObject(value, seen);

		Object.keys(value).forEach(function (key) {
			var current;
			var translated;

			try {
				current = value[key];
			} catch (error) {
				return;
			}

			if (typeof current === 'string') {
				translated = translateString(current);

				if (translated !== current) {
					try {
						value[key] = translated;
					} catch (error) {}
				}

				return;
			}

			translateObjectStrings(current, seen, depth + 1);
		});
	}

	function translateCookieYesGlobals() {
		cookieYesGlobals.forEach(function (key) {
			var value;
			var seen = typeof WeakSet !== 'undefined' ? new WeakSet() : [];

			try {
				value = window[key];
			} catch (error) {
				return;
			}

			translateObjectStrings(value, seen, 0);
		});
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
			translateCookieYesGlobals();
			translateCookieYesDom();
			getSearchRoots().forEach(translateKnownCookieText);
		}
	}

	function handleMutations(mutations) {
		mutations.forEach(function (mutation) {
			Array.prototype.forEach.call(mutation.addedNodes, function (node) {
				if (node.nodeType === 1) {
					if (isCookieYesElement(node)) {
						translateElement(node);
					}

					if (node.querySelectorAll) {
						Array.prototype.forEach.call(node.querySelectorAll(cookieYesSelector), translateElement);
					}

					translateKnownCookieText(node);

					if (node.shadowRoot) {
						translateKnownCookieText(node.shadowRoot);
						observeChanges();
					}
				}

				if (node.nodeType === 3) {
					translateTextNode(node);
				}
			});

			if (mutation.type === 'characterData') {
				translateTextNode(mutation.target);
			}
		});
	}

	function observeTarget(target) {
		var observer;

		if (!window.MutationObserver || !target || isObserved(target)) {
			return;
		}

		observer = new MutationObserver(handleMutations);
		observer.observe(target, {
			childList: true,
			characterData: true,
			subtree: true
		});

		markObserved(target);
	}

	function observeChanges() {
		if (!window.MutationObserver) {
			return;
		}

		getSearchRoots().forEach(function (root) {
			observeTarget(root.nodeType === 9 ? root.body || root.documentElement : root);
		});
	}

	function translateSoon() {
		[0, 80, 250, 700].forEach(function (delay) {
			window.setTimeout(function () {
				translateAll();
				observeChanges();
			}, delay);
		});
	}

	function startPersistentTranslation() {
		if (persistentTimer) {
			return;
		}

		persistentTimer = window.setInterval(function () {
			persistentRuns += 1;
			translateAll();
			observeChanges();

			if (persistentRuns >= 120) {
				window.clearInterval(persistentTimer);
				persistentTimer = null;
			}
		}, 250);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function () {
			translateAll();
			observeChanges();
			startPersistentTranslation();
		});
	} else {
		translateAll();
		observeChanges();
		startPersistentTranslation();
	}

	window.addEventListener('load', translateSoon);
	document.addEventListener('click', translateSoon, true);

	[100, 300, 700, 1200, 2000, 3500, 5000, 8000, 12000].forEach(function (delay) {
		window.setTimeout(function () {
			translateAll();
			observeChanges();
		}, delay);
	});
}());
