function astraToggleDarkMode() { // jshint ignore:line
	const toggler = document.getElementById('dark-mode-toggler');
	const siteIcon = document.querySelector('.site-logo-img img');

	if ('false' === toggler.getAttribute('aria-pressed')) {
		toggler.setAttribute('aria-pressed', 'true');
		document.documentElement.classList.add('is-dark-theme');
		document.body.classList.add('is-dark-theme');
		window.localStorage.setItem('astraDarkMode', 'yes');
		if (siteIcon.dataset.darkModeSrc) {
			siteIcon.dataset.lightModeSrc = siteIcon.src;
			if (siteIcon.srcset) {
				siteIcon.dataset.lightModeSrcset = siteIcon.srcset;
			}
			if (siteIcon.sizes) {
				siteIcon.dataset.lightModeSizes = siteIcon.sizes;
			}

			siteIcon.src = siteIcon.dataset.darkModeSrc;
			if (siteIcon.dataset.darkModeSrcset) {
				siteIcon.srcset = siteIcon.dataset.darkModeSrcset;
			}
			if (siteIcon.dataset.darkModeSizes) {
				siteIcon.sizes = siteIcon.dataset.darkModeSizes;
			}
		}
	} else {
		toggler.setAttribute('aria-pressed', 'false');
		document.documentElement.classList.remove('is-dark-theme');
		document.body.classList.remove('is-dark-theme');
		window.localStorage.setItem('astraDarkMode', 'no');
		if (siteIcon.dataset.lightModeSrc) {
			siteIcon.dataset.darkModeSrc = siteIcon.src;
			if (siteIcon.srcset) {
				siteIcon.dataset.darkModeSrcset = siteIcon.srcset;
			}
			if (siteIcon.sizes) {
				siteIcon.dataset.darkModeSizes = siteIcon.sizes;
			}

			siteIcon.src = siteIcon.dataset.lightModeSrc;
			if (siteIcon.dataset.lightModeSrcset) {
				siteIcon.srcset = siteIcon.dataset.lightModeSrcset;
			}
			if (siteIcon.dataset.lightModeSizes) {
				siteIcon.sizes = siteIcon.dataset.lightModeSizes;
			}
		}
	}
}

function astraIsDarkMode() {
	let isDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;

	if ('yes' === window.localStorage.getItem('astraDarkMode')) {
		isDarkMode = true;
	} else if ('no' === window.localStorage.getItem('astraDarkMode')) {
		isDarkMode = false;
	}

	return isDarkMode;
}

function darkModeInitialLoad() {
	const toggler = document.getElementById('dark-mode-toggler');
	const isDarkMode = astraIsDarkMode();

	if (isDarkMode && !document.documentElement.classList.contains('is-dark-theme')) {
		astraToggleDarkMode();
	}

	if ('fixed' === window.getComputedStyle(toggler).position) {
		darkModeRepositionTogglerOnScroll();
	}

	toggler.addEventListener('click', astraToggleDarkMode);
}

function darkModeRepositionTogglerOnScroll() {
	const toggler = document.getElementById('dark-mode-toggler');
	let prevScroll = window.scrollY || document.documentElement.scrollTop;
	let currentScroll;

	const checkScroll = function () {
		currentScroll = window.scrollY || document.documentElement.scrollTop;
		if (
			currentScroll + (window.innerHeight * 1.5) > document.body.clientHeight ||
			currentScroll < prevScroll
		) {
			toggler.classList.remove('hide');
		} else if (currentScroll > prevScroll && 250 < currentScroll) {
			toggler.classList.add('hide');
		}
		prevScroll = currentScroll;
	};

	if (toggler) {
		window.addEventListener('scroll', checkScroll);
	}
}

darkModeInitialLoad();

// This is needed additionally to the Twenty Twenty-One, since the function `astraToggleDarkMode` is in a closure.
window.astraToggleDarkMode = astraToggleDarkMode;
