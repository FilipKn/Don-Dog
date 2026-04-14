(function () {
	var heroes = document.querySelectorAll('[data-dondog-animate="hero"]');

	if (!heroes.length) {
		return;
	}

	if (!('IntersectionObserver' in window)) {
		heroes.forEach(function (hero) {
			hero.classList.add('is-ready');
			hero.classList.add('is-visible');
		});
		return;
	}

	var observer = new IntersectionObserver(
		function (entries) {
			entries.forEach(function (entry) {
				if (!entry.isIntersecting) {
					return;
				}

				entry.target.classList.add('is-visible');
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
