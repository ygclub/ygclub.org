/**
 * @since 0.1
 * @ingroup ValueView
 *
 * @licence GNU GPL v2+
 * @author H. Snater < mediawiki@snater.com >
 */

( function( $, QUnit ) {
	'use strict';

	/**
	 * Factory for creating an listrotator widget suitable for testing.
	 */
	var newTestListrotator = function( options ) {
		options = $.extend( {
			values: [
				{ value: 1, label: 'one' },
				{ value: 2, label: 'two' },
				{ value: 3, label: 'three' }
			]
		}, options || {} );

		var $div = $( '<div/>' )
		.addClass( 'test_listrotator' )
		.appendTo( $( 'body' ) )
		.listrotator( options );

		return $div.data( 'listrotator' );
	};

	QUnit.module( 'jquery.ui.listrotator', {
		teardown: function() {
			$( '.test_listrotator' ).each( function( i, node ) {
				var $node = $( node ),
					listRotator = $node.data( 'listrotator' );
				if( listRotator ) {
					listRotator.destroy();
				}
				$node.remove();
			} );
		}
	} );

	QUnit.test( 'Initialize and destroy', 4, function( assert ) {
		var listrotator = newTestListrotator(),
			widgetBaseClass = listrotator.widgetBaseClass;

		assert.equal(
			$( '.test_listrotator' ).data( 'listrotator' ),
			listrotator,
			'Initialized widget.'
		);

		assert.equal(
			$( '.' + widgetBaseClass + '-menu' ).length,
			1,
			'Appended menu element to DOM.'
		);

		listrotator.destroy();

		assert.ok(
			$( '.test_listrotator' ).data( 'listrotator' ) === undefined,
			'Destroyed widget.'
		);

		assert.equal(
			$( '.' + widgetBaseClass + '-menu' ).length,
			0,
			'Remove menu element from DOM.'
		);
	} );

	QUnit.test( 'value()', 3, function( assert ) {
		var listrotator = newTestListrotator();

		assert.strictEqual(
			listrotator.value(),
			1,
			'Listrotator\'s value is set to first value on initialization.'
		);

		assert.strictEqual(
			listrotator.value( 3 ),
			3,
			'Set listrotator\'s value.'
		);

		assert.strictEqual(
			listrotator.value(),
			3,
			'Confirmed listrotator\'s value.'
		);
	} );

	QUnit.test( 'autoActive()', function( assert ) {
		assert.ok(
			newTestListrotator().autoActive(),
			'Listrotator uses "auto" initially'
		);

		// TODO: Implement autoActive( true ) and check for autoActive() again or simulate click
		//  which would also result into autoActive() === false.
	} );

}( jQuery, QUnit ) );
