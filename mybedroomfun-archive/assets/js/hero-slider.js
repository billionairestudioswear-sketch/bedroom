/**
 * Minimal vanilla JS for the homepage hero slider. Only enqueued when
 * 2+ slides exist (see inc/enqueue.php) -- a single-slide hero has no
 * controls in the markup at all, so this script has nothing to do and
 * isn't loaded.
 *
 * Autoplay is intentionally simple and accessible: it never starts at
 * all when the visitor has requested prefers-reduced-motion, and it
 * pauses on hover or keyboard focus so it can always be read/stopped
 * (WCAG 2.2.2 Pause, Stop, Hide).
 */
( function () {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {
		var hero = document.querySelector( '[data-mbf-hero]' );

		if ( ! hero ) {
			return;
		}

		var slides = hero.querySelectorAll( '.mbf-hero__slide' );
		var dots = hero.querySelectorAll( '.mbf-hero__dot' );
		var prevBtn = hero.querySelector( '[data-hero-prev]' );
		var nextBtn = hero.querySelector( '[data-hero-next]' );

		if ( slides.length < 2 ) {
			return;
		}

		var current = 0;
		var AUTOPLAY_MS = 6000;
		var timer = null;
		var reducedMotion = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

		function goTo( index ) {
			var next = ( index + slides.length ) % slides.length;

			if ( next === current ) {
				return;
			}

			slides[ current ].classList.remove( 'is-active' );
			slides[ current ].setAttribute( 'aria-hidden', 'true' );
			var prevLink = slides[ current ].querySelector( '.mbf-hero__link' );
			if ( prevLink ) {
				prevLink.setAttribute( 'tabindex', '-1' );
			}

			slides[ next ].classList.add( 'is-active' );
			slides[ next ].removeAttribute( 'aria-hidden' );
			var nextLink = slides[ next ].querySelector( '.mbf-hero__link' );
			if ( nextLink ) {
				nextLink.removeAttribute( 'tabindex' );
			}

			if ( dots[ current ] ) {
				dots[ current ].classList.remove( 'is-active' );
				dots[ current ].removeAttribute( 'aria-current' );
			}
			if ( dots[ next ] ) {
				dots[ next ].classList.add( 'is-active' );
				dots[ next ].setAttribute( 'aria-current', 'true' );
			}

			current = next;
		}

		function stopAutoplay() {
			if ( timer ) {
				window.clearInterval( timer );
				timer = null;
			}
		}

		function startAutoplay() {
			if ( reducedMotion || timer ) {
				return;
			}
			timer = window.setInterval( function () {
				goTo( current + 1 );
			}, AUTOPLAY_MS );
		}

		if ( prevBtn ) {
			prevBtn.addEventListener( 'click', function () {
				goTo( current - 1 );
			} );
		}

		if ( nextBtn ) {
			nextBtn.addEventListener( 'click', function () {
				goTo( current + 1 );
			} );
		}

		dots.forEach( function ( dot, index ) {
			dot.addEventListener( 'click', function () {
				goTo( index );
			} );
		} );

		hero.addEventListener( 'mouseenter', stopAutoplay );
		hero.addEventListener( 'mouseleave', startAutoplay );
		hero.addEventListener( 'focusin', stopAutoplay );
		hero.addEventListener( 'focusout', startAutoplay );

		startAutoplay();
	} );
} )();
