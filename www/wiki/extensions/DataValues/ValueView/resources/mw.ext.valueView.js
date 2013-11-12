/**
 * Entrypoint for MediaWiki "ValueView" extension JavaScript code. Adds an extension object to the
 * global MediaWiki object and does some configuration on the "valueview" jQuery module.
 *
 * @file
 * @ingroup ValueView
 * @licence GNU GPL v2+
 * @author Daniel Werner < daniel.werner@wikimedia.de >
 */
( function( mw, dv, dt, $, vv ) {
	"use strict";

	mw.ext = mw.ext || {};

	var expertProvider = new vv.ExpertFactory();

	// Register valueview experts available by default:
	expertProvider.registerExpert(
		dv.StringValue,
		vv.experts.StringValue
	);

	expertProvider.registerExpert(
		dt.getDataType( 'commonsMedia' ),
		vv.experts.CommonsMediaType
	);

	/**
	 * Object representing the MeidaWiki "ValueView" extension.
	 *
	 * @since 0.1
	 */
	mw.ext.valueView = new ( function MwExtValueView() {
		/**
		 * Expert provider holding all jQuery.valueview experts globally available in MediaWiki
		 * context.
		 *
		 * @since 0.1
		 *
		 * @type jQuery.valueview.ExpertFactory
		 */
		this.expertProvider = expertProvider;
	} )();

	// 'expertProvider' is a required option in the original jQuery.valueview widget implementation.
	// If valueview is used in MediaWiki context, then the option should not be required anymore and
	// defaults to the ExpertFactory object set in mw.ext.valueview.expertProvider.
	vv.prototype.options.expertProvider = expertProvider;

}( mediaWiki, dataValues, dataTypes, jQuery, jQuery.valueview ) );
