/**
 * @file
 * @ingroup ValueView
 * @licence GNU GPL v2+
 * @author Daniel Werner < daniel.werner@wikimedia.de >
 */
jQuery.valueview.MockViewState = ( function( $, inherit, ViewState ) {
	'use strict';

	/**
	 * Mock ViewState for usage in tests. Allows to inject the state as a plain object.
	 *
	 * @constructor
	 * @extends jQuery.valueview.ViewState
	 * @since 0.1
	 *
	 * @param {Object} [definition={}] A plain object with the fields "isInEditMode", "isDisabled",
	 *        "value" and "options". This will just keep a reference to the object, so changing the
	 *        object from the outside will also update the ViewState's functions return values.
	 */
	return inherit( 'ValueviewMockViewState', ViewState, function ( definition ) {
		if( definition !== undefined && !$.isPlainObject( definition ) ) {
			throw new Error( 'Given definition needs to be a plain object' );
		}
		this._view = definition || {};
	}, {
		/**
		 * @see jQuery.valueview.ViewState.isInEditMode
		 */
		isInEditMode: function() {
			return !!this._view.isInEditMode;
		},

		/**
		 * @see jQuery.valueview.ViewState.isDisabled
		 */
		isDisabled: function() {
			return !!this._view.isDisabled;
		},

		/**
		 * @see jQuery.valueview.ViewState.value
		 */
		value: function() {
			return this._view.value;
		},

		/**
		 * @see jQuery.valueview.ViewState.option
		 */
		option: function( key ) {
			return ( this._view.options || {} )[ key ];
		}
	} );

}( jQuery, dataValues.util.inherit, jQuery.valueview.ViewState ) );
