/**
 * @since 0.1
 * @ingroup ValueView
 *
 * @licence GNU GPL v2+
 * @author Daniel Werner < daniel.werner@wikimedia.de >
 */
 ( function( $, QUnit, valueview, StringParser ) {

	'use strict';

	var testExpert = valueview.tests.testExpert;

	QUnit.module( 'jquery.valueview.experts.StringValue' );

	testExpert( {
		expertConstructor: valueview.experts.StringValue,
		rawValues: {
			valid: [ 'foo bar', '42', '*(&#$@#*$' ],
			unknown: testExpert.basicTestDefinition.rawValues.unknown.concat( [ 42 ] )
		},
		relatedValueParser: StringParser
	} );

}( jQuery, QUnit, jQuery.valueview, valueParsers.StringParser ) );
