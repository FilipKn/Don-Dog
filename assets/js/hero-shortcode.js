(function () {
	var heroes = document.querySelectorAll('[data-dondog-animate="hero"]');

	if (!heroes.length) {
		return;
	}

	function revealText(hero) {
		hero.classList.add('is-text-ready');

		window.requestAnimationFrame(function () {
			window.setTimeout(function () {
				hero.classList.add('is-text-visible');
			}, 1000);
		});
	}

	function revealHero(hero) {
		window.requestAnimationFrame(function () {
			window.requestAnimationFrame(function () {
				hero.classList.add('is-visible');
				revealText(hero);
			});
		});
	}

	if (!('IntersectionObserver' in window)) {
		heroes.forEach(function (hero) {
			hero.classList.add('is-ready');
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
		observer.observe(hero);
	});
})();
