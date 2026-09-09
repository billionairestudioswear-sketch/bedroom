/**
 * Minimal vanilla JS: toggles the "Shopping Categories" mega menu.
 * No jQuery, no build step. Loaded on every page (small, deferred).
 */
( function () {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {
		var trigger = document.querySelector( '.mbf-mega__trigger' );
		var panel = document.getElementById( 'mbf-mega-panel' );

		if ( ! trigger || ! panel ) {
			return;
		}

		function closePanel() {
			panel.hidden = true;
			trigger.setAttribute( 'aria-expanded', 'false' );
		}

		function openPanel() {
			panel.hidden = false;
			trigger.setAttribute( 'aria-expanded', 'true' );
		}

		trigger.addEventListener( 'click', function ( event ) {
			event.stopPropagation();
			if ( panel.hidden ) {
				openPanel();
			} else {
				closePanel();
			}
		} );

		document.addEventListener( 'click', function ( event ) {
			if ( ! panel.hidden && ! panel.contains( event.target ) && event.target !== trigger ) {
				closePanel();
			}
		} );

		document.addEventListener( 'keydown', function ( event ) {
			if ( 'Escape' === event.key && ! panel.hidden ) {
				closePanel();
				trigger.focus();
			}
		} );
	} );
} )();
