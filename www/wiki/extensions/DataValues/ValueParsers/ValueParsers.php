<?php

/**
 * Entry point for the ValueParser extension.
 *
 * Documentation:	 		https://www.mediawiki.org/wiki/Extension:ValueParsers
 * Support					https://www.mediawiki.org/wiki/Extension_talk:ValueParsers
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
 * @ingroup ValueParsers
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

/**
 * Files belonging to the ValueParsers extension.
 *
 * @defgroup ValueParsers ValueParsers
 */

/**
 * Tests part of the ValueParsers extension.
 *
 * @defgroup ValueParserTests ValueParserTests
 * @ingroup ValueParsers
 * @ingroup Test
 */

if ( !defined( 'DATAVALUES' ) && !defined( 'MEDIAWIKI' ) ) {
	die( 'Not an entry point.' );
}

if ( !defined( 'DATAVALUES' ) ) {
	define( 'DATAVALUES', true );
}

define( 'ValueParsers_VERSION', '0.1 alpha' );

global $wgValueParsers;

$wgValueParsers = array();

$wgValueParsers['bool'] = 'ValueParsers\BoolParser';
$wgValueParsers['float'] = 'ValueParsers\FloatParser';
$wgValueParsers['geocoordinate'] = 'ValueParsers\GeoCoordinateParser';
$wgValueParsers['int'] = 'ValueParsers\IntParser';
$wgValueParsers['null'] = 'ValueParsers\NullParser';
$wgValueParsers['title'] = 'ValueParsers\TitleParser';

if ( defined( 'MEDIAWIKI' ) ) {
	include __DIR__ . '/ValueParsers.mw.php';
}
else {
	spl_autoload_register( function ( $className ) {
		// @codeCoverageIgnoreStart
		static $classes = false;

		if ( $classes === false ) {
			$classes = include( __DIR__ . '/' . 'ValueParsers.classes.php' );
		}

		if ( array_key_exists( $className, $classes ) ) {
			include_once __DIR__ . '/' . $classes[$className];
		}
		// @codeCoverageIgnoreEnd
	} );
}
