/**
 * Minimal vanilla JS: toggles the "Shopping Categories" mega menu and
 * the mobile primary-nav collapse. No jQuery, no build step. Loaded
 * on every page (small, deferred).
 */
( function () {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {
		var navToggle = document.querySelector( '[data-nav-toggle]' );
		var navPanel = document.getElementById( 'mbf-nav-inner' );

		/**
		 * The panel is visible by default in the markup/CSS so the menu
		 * still works with no JS at all -- this only collapses it once
		 * JS (and therefore the toggle button) is actually available.
		 * The CSS class only hides anything under the mobile breakpoint,
		 * so applying it unconditionally is harmless at desktop widths.
		 */
		if ( navToggle && navPanel ) {
			navPanel.classList.add( 'is-collapsed' );

			navToggle.addEventListener( 'click', function () {
				var isCollapsed = navPanel.classList.toggle( 'is-collapsed' );
				navToggle.setAttribute( 'aria-expanded', isCollapsed ? 'false' : 'true' );
			} );
		}

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
