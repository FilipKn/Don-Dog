(function () {
	var heroes = document.querySelectorAll('[data-dondog-animate="hero"]');
	var textSelector = [
		'.dondog-hero__eyebrow',
		'.dondog-hero__title',
		'.dondog-hero__text',
		'.dondog-hero__actions',
		'.dondog-hero__features',
	].join(',');
	var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	if (!heroes.length) {
		return;
	}

	function getTextParts(hero) {
		return Array.prototype.slice.call(hero.querySelectorAll(textSelector));
	}

	function prepareText(hero) {
		getTextParts(hero).forEach(function (part) {
			if (reduceMotion) {
				finishTextPart(part);
				return;
			}

			part.style.opacity = '0';
			part.style.filter = 'blur(14px)';
			part.style.transform = 'translate3d(-115vw, 0, 0)';
			part.style.willChange = 'opacity, filter, transform';
		});
	}

	function finishTextPart(part) {
		part.style.opacity = '1';
		part.style.filter = 'none';
		part.style.transform = 'translate3d(0, 0, 0)';
		part.style.willChange = 'auto';
	}

	function animateText(hero) {
		var parts = getTextParts(hero);
		var supportsWebAnimations = typeof Element !== 'undefined' && Element.prototype.animate;

		if (reduceMotion) {
			parts.forEach(finishTextPart);
			return;
		}

		parts.forEach(function (part, index) {
			var delay = 120 + index * 150;
			var duration = index < 2 ? 1050 : 900;

			if (!supportsWebAnimations) {
				window.setTimeout(function () {
					part.style.transition = 'opacity 850ms cubic-bezier(0.16, 1, 0.3, 1), filter 850ms cubic-bezier(0.16, 1, 0.3, 1), transform 850ms cubic-bezier(0.16, 1, 0.3, 1)';
					finishTextPart(part);
				}, delay);
				return;
			}

			var animation = part.animate(
				[
					{
						opacity: 0,
						filter: 'blur(14px)',
						transform: 'translate3d(-115vw, 0, 0)',
					},
					{
						opacity: 1,
						filter: 'blur(0)',
						transform: 'translate3d(14px, 0, 0)',
						offset: 0.78,
					},
					{
						opacity: 1,
						filter: 'blur(0)',
						transform: 'translate3d(0, 0, 0)',
					},
				],
				{
					delay: delay,
					duration: duration,
					easing: 'cubic-bezier(0.16, 1, 0.3, 1)',
					fill: 'forwards',
				}
			);

			animation.onfinish = function () {
				finishTextPart(part);
				animation.cancel();
			};
		});
	}

	function revealHero(hero) {
		window.requestAnimationFrame(function () {
			window.requestAnimationFrame(function () {
				hero.classList.add('is-visible');
				animateText(hero);
			});
		});
	}

	if (!('IntersectionObserver' in window)) {
		heroes.forEach(function (hero) {
			hero.classList.add('is-ready');
			prepareText(hero);
			revealHero(hero);
		});
		return;
	}

	var observer = new IntersectionObserver(
		function (entries) {
			entries.forEach(function (entry) {
				if (!entry.isIntersecting) {
					return;
				}

				revealHero(entry.target);
				observer.unobserve(entry.target);
			});
		},
		{
			rootMargin: '0px 0px -12% 0px',
			threshold: 0.25,
		}
	);

	heroes.forEach(function (hero) {
		hero.classList.add('is-ready');
		prepareText(hero);
		observer.observe(hero);
	});
})();
