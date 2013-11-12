<?php

/**
 * Entry point for the DataTypes extension.
 *
 * Documentation:	 		https://www.mediawiki.org/wiki/Extension:DataTypes
 * Support					https://www.mediawiki.org/wiki/Extension_talk:DataTypes
 * Source code:				https://gerrit.wikimedia.org/r/gitweb?p=mediawiki/extensions/DataValues.git
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @since 0.1
 *
 * @file
 * @ingroup DataTypes
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

/**
 * Files belonging to the DataTypes extension.
 *
 * @defgroup DataTypes DataTypes
 */

/**
 * Tests part of the DataTypes extension.
 *
 * @defgroup DataTypesTests DataTypesTests
 * @ingroup DataTypes
 */

namespace DataTypes;

if ( !defined( 'DATAVALUES' ) && !defined( 'MEDIAWIKI' ) ) {
	die( 'Not an entry point.' );
}

if ( !defined( 'DATAVALUES' ) ) {
	define( 'DATAVALUES', true );
}

define( 'DataTypes_VERSION', '0.1 alpha' );

global $wgDataTypes;
$wgDataTypes = array(
	'commonsMedia' => array(
		'datavalue' => 'string',
	),
	'string' => array(
		'datavalue' => 'string',
	),
	'geo-coordinate' => array(
		'datavalue' => 'geocoordinate',
	),
	'quantity' => array(
		'datavalue' => 'quantity',
	),
	'monolingual-text' => array(
		'datavalue' => 'monolingualtext',
	),
	'multilingual-text' => array(
		'datavalue' => 'multilingualtext',
	),
	'time' => array(
		'datavalue' => 'time',
	),

//	'geo' => array(
//		'datavalue' => 'geo-dv',
//		'parser' => 'geo-parser',
//		'formatter' => 'geo-formatter',
//	),
//	'positive-number' => array(
//		'datavalue' => 'numeric-dv',
//		'parser' => 'numeric-parser',
//		'formatter' => 'numeric-formatter',
//		'validators' => array( $rangeValidator ),
//	),
);

// @codeCoverageIgnoreStart

if ( defined( 'MEDIAWIKI' ) ) {
	include __DIR__ . '/DataTypes.mw.php';
}
else {
	spl_autoload_register( function ( $className ) {
		static $classes = false;

		if ( $classes === false ) {
			$classes = include( __DIR__ . '/' . 'DataTypes.classes.php' );
		}

		if ( array_key_exists( $className, $classes ) ) {
			include_once __DIR__ . '/' . $classes[$className];
		}
	} );
}

class Message {

	protected static $textFunction = null;

	/**
	 * Sets the function to call from @see text.
	 *
	 * @since 0.1
	 *
	 * @param callable $textFunction
	 * This function should take a message key, a language code, and an optional list of arguments.
	 */
	public static function registerTextFunction( $textFunction ) {
		self::$textFunction = $textFunction;
	}

	public static function text() {
		if ( is_null( self::$textFunction ) ) {
			throw new \Exception( 'No text function set in DataTypes\Message' );
		}
		else {
			return call_user_func_array( self::$textFunction, func_get_args() );
		}
	}

}

// @codeCoverageIgnoreEnd