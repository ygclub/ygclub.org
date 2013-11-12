/**
 * @file
 * @ingroup DataValues
 *
 * @licence GNU GPL v2+
 *
 * @author Daniel Werner
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
( function( dv, $ ) {
'use strict';

var PARENT = dv.DataValue,
	constructor = function( value ) {
		// TODO: validate
		this._value = value;
	};

/**
 * Constructor for creating a data value representing a string.
 *
 * @constructor
 * @extends dv.DataValue
 * @since 0.1
 *
 * @param {String} value
 */
dv.StringValue = dv.util.inherit( 'DvStringValue', PARENT, constructor, {

	/**
	 * @see dv.DataValue.getSortKey
	 *
	 * @since 0.1
	 *
	 * @return String
	 */
	getSortKey: function() {
		return this._value;
	},

	/**
	 * @see dv.DataValue.getValue
	 *
	 * @since 0.1
	 *
	 * @return String
	 */
	getValue: function() {
		return this._value;
	},

	/**
	 * @see dv.DataValue.equals
	 *
	 * @since 0.1
	 */
	equals: function( value ) {
		if ( !( value instanceof dv.StringValue ) ) {
			return false;
		}

		return this.getValue() === value.getValue();
	},

	/**
	 * @see dv.DataValue.toJSON
	 *
	 * @since 0.1
	 */
	toJSON: function() {
		return this._value;
	}

} );

/**
 * @see dv.DataValue.newFromJSON
 */
dv.StringValue.newFromJSON = function( json ) {
	return new dv.StringValue( json );
};

/**
 * @see dv.DataValue.TYPE
 */
dv.StringValue.TYPE = 'string';

dv.registerDataValue( dv.StringValue );

}( dataValues, jQuery ) );
