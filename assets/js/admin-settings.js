( function () {
	'use strict';

	/**
	 * Keep every slider and matching number input in a control row in sync.
	 *
	 * @param {HTMLElement} row Control row containing range and number inputs.
	 */
	function bindSyncedRow( row ) {
		var slider = row.querySelector( 'input[type="range"]' );
		var input = row.querySelector( 'input[type="number"]' );

		if ( ! slider || ! input ) {
			return;
		}

		var min = parseInt( input.min, 10 );
		var max = parseInt( input.max, 10 );

		if ( Number.isNaN( min ) ) {
			min = parseInt( slider.min, 10 ) || 1;
		}
		if ( Number.isNaN( max ) ) {
			max = parseInt( slider.max, 10 ) || 100;
		}

		function clamp( value ) {
			var n = parseInt( value, 10 );
			if ( Number.isNaN( n ) ) {
				n = min;
			}
			return Math.min( max, Math.max( min, n ) );
		}

		function syncFromSlider() {
			input.value = String( clamp( slider.value ) );
		}

		function syncFromInput( force ) {
			if ( ! force && '' === input.value ) {
				return;
			}
			var value = clamp( input.value );
			input.value = String( value );
			slider.value = String( value );
		}

		slider.addEventListener( 'input', syncFromSlider );
		slider.addEventListener( 'change', syncFromSlider );
		input.addEventListener( 'input', function () {
			syncFromInput( false );
		} );
		input.addEventListener( 'change', function () {
			syncFromInput( true );
		} );
		input.addEventListener( 'blur', function () {
			syncFromInput( true );
		} );

		syncFromInput( true );
	}

	function init() {
		document.querySelectorAll( '.sikora-iq-control-row' ).forEach( bindSyncedRow );
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
} )();
