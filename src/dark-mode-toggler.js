function astraToggleDarkMode() { // jshint ignore:line
	var toggler = document.getElementById( 'dark-mode-toggler' ),
		siteIcon = document.querySelector( '.site-logo-img img' );

	if ( 'false' === toggler.getAttribute( 'aria-pressed' ) ) {
		toggler.setAttribute( 'aria-pressed', 'true' );
		document.documentElement.classList.add( 'is-dark-theme' );
		document.body.classList.add( 'is-dark-theme' );
		window.localStorage.setItem( 'astraDarkMode', 'yes' );
		if ( siteIcon.dataset.darkModeSrc ) {
			siteIcon.dataset.lightModeSrc = siteIcon.src;
			siteIcon.dataset.lightModeSrcset = siteIcon.srcset;
			siteIcon.dataset.lightModeSizes = siteIcon.srcset;
			siteIcon.src = siteIcon.dataset.darkModeSrc;
			siteIcon.srcset = siteIcon.dataset.darkModeSrcset;
			siteIcon.sizes = siteIcon.dataset.darkModeSizes;
		}
	} else {
		toggler.setAttribute( 'aria-pressed', 'false' );
		document.documentElement.classList.remove( 'is-dark-theme' );
		document.body.classList.remove( 'is-dark-theme' );
		window.localStorage.setItem( 'astraDarkMode', 'no' );
		if ( siteIcon.dataset.lightModeSrc ) {
			siteIcon.dataset.darkModeSrc = siteIcon.src;
			siteIcon.dataset.darkModeSrcset = siteIcon.srcset;
			siteIcon.dataset.darkModeSizes = siteIcon.srcset;
			siteIcon.src = siteIcon.dataset.lightModeSrc;
			siteIcon.srcset = siteIcon.dataset.lightModeSrcset;
			siteIcon.sizes = siteIcon.dataset.lightModeSizes;
		}
	}
}

function astraIsDarkMode() {
	var isDarkMode = window.matchMedia( '(prefers-color-scheme: dark)' ).matches;

	if ( 'yes' === window.localStorage.getItem( 'astraDarkMode' ) ) {
		isDarkMode = true;
	} else if ( 'no' === window.localStorage.getItem( 'astraDarkMode' ) ) {
		isDarkMode = false;
	}

	return isDarkMode;
}

function darkModeInitialLoad() {
	var toggler = document.getElementById( 'dark-mode-toggler' ),
		isDarkMode = astraIsDarkMode();

	if ( isDarkMode && ! document.documentElement.classList.contains('is-dark-theme') ) {
		astraToggleDarkMode();
	}

	if ( 'fixed' === window.getComputedStyle( toggler ).position ) {
		darkModeRepositionTogglerOnScroll();
	}

	toggler.addEventListener( 'click', astraToggleDarkMode );
}

function darkModeRepositionTogglerOnScroll() {
	var toggler = document.getElementById( 'dark-mode-toggler' ),
		prevScroll = window.scrollY || document.documentElement.scrollTop,
		currentScroll,

		checkScroll = function () {
			currentScroll = window.scrollY || document.documentElement.scrollTop;
			if (
				currentScroll + ( window.innerHeight * 1.5 ) > document.body.clientHeight ||
				currentScroll < prevScroll
			) {
				toggler.classList.remove( 'hide' );
			} else if ( currentScroll > prevScroll && 250 < currentScroll ) {
				toggler.classList.add( 'hide' );
			}
			prevScroll = currentScroll;
		};

	if ( toggler ) {
		window.addEventListener( 'scroll', checkScroll );
	}
}

darkModeInitialLoad();

// This is needed additionally to the Twenty Twenty-One, since the function `astraToggleDarkMode` is in a closure.
window.astraToggleDarkMode = astraToggleDarkMode;
