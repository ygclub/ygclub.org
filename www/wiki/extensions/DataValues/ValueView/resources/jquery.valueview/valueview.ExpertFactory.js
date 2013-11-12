/**
 * @file
 * @ingroup ValueView
 * @licence GNU GPL v2+
 * @author Daniel Werner < daniel.werner@wikimedia.de >
 */
( function( dv, dt, $, vv ) {
	'use strict';

	var SELF = vv.ExpertFactory = function ValueviewExpertFactory() {
		this._expertsForDataValueTypes = {};
		this._expertsForDataTypes = {};
	};

	SELF.prototype = {
		/**
		 * Map from DataValue types to widgets responsible for the type.
		 * @type Object
		 */
		_expertsForDataValueTypes: null,

		/**
		 * Map from DataType IDs to widgets responsible for the type. This overrules the valueViews
		 * if $.valueview receives a DataType in its 'on' option and the type can be found in here.
		 * If there is no Expert for the given DataType, the Expert for the DataType's data value
		 * type will be used instead.
		 * @type Object
		 */
		_expertsForDataTypes: null,

		/**
		 * Registers a valueview expert for displaying values suitable for a certain data type or
		 * of a certain data value type.
		 *
		 * @param {dataTypes.DataType|dataValues.DataValue|Function} expertPurpose
		 * @param {Function} expert Constructor of the expert
		 */
		registerExpert: function( expertPurpose, expert ) {
			if( expertPurpose instanceof dt.DataType ) {
				this.registerDataTypeExpert( expertPurpose, expert );
			}
			else if (
				$.isFunction( expertPurpose ) // DataValue constructor
				&& expertPurpose.prototype instanceof dv.DataValue
				&& expertPurpose.TYPE
			) {
				this.registerDataValueExpert( expertPurpose.TYPE, expert );
			}
			else {
				throw new Error( 'No sufficient indicator provided what to register the expert for. ' );
			}
		},

		/**
		 * Registers a valueview expert for displaying values of a certain data value type.
		 *
		 * @since 0.1
		 *
		 * @param {string} dataValueType
		 * @param {Function} expert Constructor of the expert
		 */
		registerDataValueExpert: function( dataValueType, expert ) {
			if( typeof dataValueType !== 'string' ) {
				throw new Error( 'the data value type is expected to be a string' );
			}
			if( !$.isFunction( expert ) ) {
				throw new Error( 'No expert constructor given for data value type "'
					+ dataValueType + '"' );
			}
			this._expertsForDataValueTypes[ dataValueType ] = expert;
		},

		/**
		 * Registers a valueview expert for displaying data values suitable for a certain data type.
		 *
		 * @since 0.1
		 *
		 * @param {dt.DataType|string} dataType
		 * @param {Function} expert Constructor of the expert
		 */
		registerDataTypeExpert: function( dataType, expert ) {
			var dataTypeId;

			if( dataType instanceof dt.DataType ) {
				dataTypeId = dataType.getId();
			}
			else if( typeof dataType === 'string' ) {
				dataTypeId = dataType;
			} else {
				throw new Error( 'data type ID (as string) or dataTypes.DataType instance expected' );
			}
			if( !$.isFunction( expert ) ) {
				throw new Error( 'No expert constructor given for data type "' + dataTypeId + '"' );
			}
			this._expertsForDataTypes[ dataTypeId ] = expert;
		},

		/**
		 * Returns all data value types which can be represented by the valueview widget since there
		 * is an Expert constructor for presenting them.
		 *
		 * @since 0.1
		 *
		 * @return string[]
		 */
		getCoveredDataValueTypes: function() {
			var dvType,
				types = [];

			for( dvType in this._expertsForDataValueTypes ) {
				types.push( dvType );
			}
			return types;
		},

		/**
		 * Returns all data types which can be represented by the valueview widget since there is an
		 * Expert constructor for representing a value suitable for the specific data type or there
		 * is an Expert constructor which can handle values of the the data type's data value type.
		 *
		 * @since 0.1
		 *
		 * @return string[]
		 */
		getCoveredDataTypes: function() {
			var types = [];

			$.each( dt.getDataTypeIds(), function( i, dtType ) {
				if( this._expertsForDataTypes.hasOwnProperty( dtType )
					|| this._expertsForDataValueTypes.hasOwnProperty(
							dt.getDataType( dtType ).getDataValueType()
						)
				) {
					types.push( dtType );
				}
			} );
			return types;
		},

		/**
		 * Returns whether there is a suitable expert constructor for representing a certain
		 * kind of value within a jQuery.valueview.
		 *
		 * @since 0.1
		 *
		 * @param {dataTypes.DataType|dataValues.DataValue|Function} onTheBasisOf
		 * @return boolean
		 */
		hasExpertFor: function( onTheBasisOf ) {
			return !!this.getExpert( onTheBasisOf );
		},

		/**
		 * Will return the most suitable valueview Expert based on a given hint.
		 *
		 * @param {dataTypes.DataType|dataValues.DataValue|Function} onTheBasisOf
		 * @return {jQuery.valueview.Expert|null} null if there is no Expert available
		 *
		 * @throws {Error} if no sufficient first parameter is given.
		 */
		getExpert: function( onTheBasisOf ) {
			var valueType,
				dataTypeId,
				expert;

			if( onTheBasisOf instanceof dv.DataValue ) {
				valueType = onTheBasisOf.getType();
			}
			else if( onTheBasisOf instanceof dt.DataType ) {
				valueType = onTheBasisOf.getDataValueType();
				dataTypeId = onTheBasisOf.getId();
			}
			else if (
				$.isFunction( onTheBasisOf ) // DataValue constructor
				&& onTheBasisOf.prototype instanceof dv.DataValue
				&& onTheBasisOf.TYPE
			) {
				valueType = onTheBasisOf.TYPE;
			}
			else {
				throw new Error( 'No sufficient indicator provided for choosing a valueview view widget' );
			}

			if( dataTypeId ) {
				// try to get a view designed for this specific DataType
				expert = this._expertsForDataTypes[ dataTypeId ];
			}
			if( !expert ) {
				// no view for specific data type or only DataValue given, so get the view based on
				// given DataValue's type or on given DataType's data value type:
				expert = this._expertsForDataValueTypes[ valueType ];
			}

			return expert || null;
		},

		/**
		 * Returns the expert required by a valueview for representing a certain kind of data value
		 * or data type. Expects a data value or data type for choosing the relevant expert.
		 *
		 * @since 0.1
		 *
		 * @param {dataTypes.DataType|dataValues.DataValue|Function} onTheBasisOf
		 * @param {jQuery} $expertViewPort
		 * @param {jQuery.valueview.ViewState} viewState
		 * @return jQuery.valueview.Expert|null
		 */
		newExpert: function( onTheBasisOf, $expertViewPort, viewState ) {
			var Expert = this.getExpert( onTheBasisOf );
			return Expert ? new Expert( $expertViewPort, viewState ) : null;
		}
	};

}( dataValues, dataTypes, jQuery, jQuery.valueview ) );
