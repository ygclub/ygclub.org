/**
 * @file
 * @ingroup ValueView
 * @licence GNU GPL v2+
 * @author Daniel Werner < daniel.werner@wikimedia.de >
 */
( function( dv, vp, $, vv, wikiUrlencode ) {
	'use strict';

	/**
	 * Returns an url to a certain file on commons.
	 *
	 * @param {string} file
	 * @returns string
	 */
	function commonsUrl( file ) {
		return location.protocol + '//commons.wikimedia.org/wiki/File:' + wikiUrlencode( file );
	}

	var PARENT = vv.BifidExpert,
		editableExpert = vv.experts.SuggestedStringValue;

	/**
	 * Valueview expert for adding specialized handling for CommonsMedia data type. Without this
	 * more specialized expert, the StringValue expert would be used since the CommonsMedia data
	 * type is using the String data value type.
	 * This expert is based on the StringValue expert but will add a dropdown for choosing commons
	 * media sources. It will also display the value as a link to commons.
	 *
	 * @since 0.1
	 *
	 * @constructor
	 * @extends jQuery.valueview.experts.StringValue
	 */
	vv.experts.CommonsMediaType = vv.expert( 'commonsmediatype', PARENT, {
		/**
		 * @see jQuery.valueview.BifidExpert._editableExpert
		 */
		_editableExpert: editableExpert,

		/**
		 * @see jQuery.valueview.BifidExpert._editableExpertOptions
		 */
		_editableExpertOptions: {
			suggesterOptions: {
				ajax: {
					url: location.protocol + '//commons.wikimedia.org/w/api.php',
					params: {
						action: 'opensearch',
						namespace: 6
					}
				},
				replace: [/^File:/, '']
			}
		},

		/**
		 * @see jQuery.valueview.BifidExpert._staticExpert
		 */
		_staticExpert: vv.experts.StaticDom,

		/**
		 * @see jQuery.valueview.BifidExpert._staticExpertOptions
		 */
		_staticExpertOptions: {
			domBuilder: function( currentRawValue, viewState ) {
				return $( '<a/>', {
					text: currentRawValue,
					href: commonsUrl( currentRawValue )
				} );
			},
			baseExpert: editableExpert
		}
	} );

}( dataValues, valueParsers, jQuery, jQuery.valueview, mw.util.wikiUrlencode ) );
