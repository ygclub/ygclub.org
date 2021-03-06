/**
 * @file
 * @ingroup ValueParsers
 *
 * @licence GNU GPL v2+
 *
 * @author H. Snater < mediawiki@snater.com >
 */
( function( vp, dv ) {
	'use strict';

	var PARENT = vp.ApiBasedValueParser;

	/**
	 * Constructor for globe coordinate parsers.
	 *
	 * @constructor
	 * @extends vp.ApiBasedValueParser
	 * @since 0.1
	 */
	vp.GlobeCoordinateParser = dv.util.inherit( PARENT, {
		/**
		 * @see vp.ApiBasedValueParser.API_VALUE_PARSER_ID
		 */
		API_VALUE_PARSER_ID: 'globecoordinate'
	} );

}( valueParsers, dataValues ) );
