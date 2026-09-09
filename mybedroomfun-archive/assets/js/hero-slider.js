/**
 * Minimal vanilla JS for the homepage hero slider. Only enqueued when
 * 2+ slides exist (see inc/enqueue.php) -- a single-slide hero has no
 * controls in the markup at all, so this script has nothing to do and
 * isn't loaded. No autoplay: slides only change on explicit user
 * action, which is the simplest way to respect
 * prefers-reduced-motion (there is no motion unless the user asks for
 * it) -- the CSS crossfade itself is also disabled under that media
 * query (see assets/css/homepage.css).
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
	} );
} )();
