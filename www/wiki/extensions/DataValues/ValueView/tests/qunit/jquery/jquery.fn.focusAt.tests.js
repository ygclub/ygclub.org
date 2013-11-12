/**
 * QUnit tests for 'focusAt' jQuery plugin
 *
 * @since 0.1
 * @file
 * @ingroup DataTypes
 *
 * @licence GNU GPL v2+
 * @author Daniel Werner
 */
( function( $, QUnit ) {
	'use strict';

	var BROWSER_FOCUS_NOTE = '(An error at this stage might also occur if you removed the focus ' +
		'from the browser window.)';

	/**
	 * Returns a DOM object within a HTML page
	 * @returns {jQuery}
	 * @throws {Error} If the test runs in a non-browser environment or on a unsuitable HTML page.
	 */
	function getDomInsertionTestViewport() {
		var body = $( 'body' );

		if( !body.length ) {
			throw new Error( 'Can only run this test on a HTML page with "body" tag in the browser.' );
		}
		return body;
	}

	QUnit.module( 'jquery.fn.focusAt' );

	QUnit.test( 'plugin initialization', function( assert ) {
		assert.ok(
			$.isFunction( $.fn.focusAt ),
			'"jQuery.fn.focusAt" is available'
		);
	} );

	var elemsCasesData = [
		{
			title: 'div',
			elem: $( '<div/>' ),
			focusable: false
		}, {
			title: 'input',
			elem: $( '<input/>', { text: 'foo 123' } ),
			focusable: true
		}, {
			title: 'textarea',
			elem: $( '<textarea/>', { text: 'bar 123' } ),
			focusable: true
		}, {
			title: 'span(+tabindex)',
			elem: $( '<span tabindex="0">foo</span>' ),
			focusable: true
		}, {
			title: 'span',
			elem: $( '<span/>' ),
			focusable: false
		}
	];

	var elemsCases = QUnit.cases( elemsCasesData );

	elemsCases.test( 'Focusing with valid parameter', function( params, assert ) {
		var positions = [ 0, 1, 4, 9, 9999, 'start', 'end', -1, -3, -9999 ];

		$.each( positions, function( i, pos ) {
			assert.ok(
				params.elem.focusAt( pos ),
				'focusAt takes "' + pos + '" as a valid position for the element'
			);
		} );
	} );

	elemsCases.test( 'Focusing with invalid parameter', function( params, assert ) {
		var positions = [ null, undefined, 'foo', [], {} ];

		$.each( positions, function( i, pos ) {
			assert.throws(
				function() {
					params.elem.focusAt( pos );
				},
				'focusAt does not take "' + pos + '" as a valid position for the element'
			);
		} );
	} );

	elemsCases.test( 'Focusing element, not in DOM yet', function( params, assert ) {
		var $dom = getDomInsertionTestViewport(),
			elem = params.elem;

		if( !$dom.length ) {
			throw new Error( 'Can only run this test on a HTML page with "body" tag in the browser.' );
		}

		assert.ok(
			elem.focusAt( 0 ),
			'Can call focusAt on element not in DOM yet'
		);

		$( ':focus' ).blur();
		elem.appendTo( $dom );

		assert.equal(
			$( ':focus' ).length,
			0,
			'After inserting focused element into DOM, the element is not focused since there is' +
				'no state tracking focus for those elements not in the DOM.'
		);
		elem.remove();
	} );

	// TODO: Skip test if the browser is not focused.
	//  See http://stackoverflow.com/questions/13748129/skipping-a-test-in-qunit
	elemsCases.test( 'Focusing element in DOM', function( params, assert ) {
		var $dom = getDomInsertionTestViewport(),
			elem = params.elem;

		if( !$dom.length ) {
			throw new Error( 'Can only run this test on a HTML page with "body" tag in the browser.' );
		}

		$( ':focus' ).blur();
		elem.appendTo( $dom );

		assert.ok(
			elem.focusAt( 0 ),
			'Can call focusAt on element in DOM'
		);

		if( !params.focusable ) {
			assert.equal(
				$( ':focus' ).length,
				0,
				'Element is a non-focusable element and no focus is active'
			);
		} else {
			assert.ok(
				$( ':focus' ).filter( elem ).length,
				'Focused element has focus set. ' + BROWSER_FOCUS_NOTE
			);
		}
		elem.remove();
	} );

}( jQuery, QUnit ) );
