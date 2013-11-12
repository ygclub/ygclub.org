/**
 * @since 0.1
 * @ingroup ValueView
 * @licence GNU GPL v2+
 *
 * @author H. Snater < mediawiki@snater.com >
 */
( function( $, QUnit, valueview, GlobeCoordinate, GlobeCoordinateParser ) {
	'use strict';

	var testExpert = valueview.tests.testExpert;

	QUnit.module( 'jquery.valueview.experts.GlobeCoordinateInput' );

	testExpert( {
		expertConstructor: valueview.experts.GlobeCoordinateInput,
		rawValues: {
			valid: [
				'-1.5, -1.25',
				'30, 30',
				'foo' // Might not be a valid coordinate, but that's for the parser to decide, the expert shouldn't care.
			],
			unknown: testExpert.basicTestDefinition.rawValues.unknown.concat( [
				42
			] )
		},
		relatedValueParser: GlobeCoordinateParser
	} );

}( jQuery, QUnit, jQuery.valueview, globeCoordinate.GlobeCoordinate, valueParsers.GlobeCoordinateParser ) );
