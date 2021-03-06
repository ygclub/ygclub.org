/**
 * @file
 * @ingroup ValueParsers
 *
 * @licence GNU GPL v2+
 *
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
( function( vp, dv, $, undefined ) {
	'use strict';

	var PARENT = vp.ApiBasedValueParser;

	/**
	 * Constructor for string to integer parsers.
	 *
	 * @constructor
	 * @extends vp.ApiBasedValueParser
	 * @since 0.1
	 */
	vp.IntParser = dv.util.inherit( PARENT, {
		/**
		 * @see ApiBasedValueParser.API_VALUE_PARSER_ID
		 */
		API_VALUE_PARSER_ID: 'int'
	} );

}( valueParsers, dataValues, jQuery ) );
