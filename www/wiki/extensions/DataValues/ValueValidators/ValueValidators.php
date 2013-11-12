<?php

/**
 * Entry point for the ValueValidators extension.
 *
 * Documentation:	 		https://www.mediawiki.org/wiki/Extension:ValueValidators
 * Support					https://www.mediawiki.org/wiki/Extension_talk:ValueValidators
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
 * @ingroup ValueValidators
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

/**
 * Files belonging to the ValueValidators extension.
 *
 * @defgroup ValueValidators ValueValidators
 */

/**
 * Tests part of the ValueValidators extension.
 *
 * @defgroup ValueValidatorTests ValueValidatorTests
 * @ingroup ValueValidators
 * @ingroup Test
 */

if ( !defined( 'DATAVALUES' ) && !defined( 'MEDIAWIKI' ) ) {
	die( 'Not an entry point.' );
}

if ( !defined( 'DATAVALUES' ) ) {
	define( 'DATAVALUES', true );
}

define( 'ValueValidators_VERSION', '0.1 alpha' );

global $wgValueValidators;

$wgValueValidators = array();

$wgValueValidators['range'] = 'ValueValidators\RangeValidator';

if ( defined( 'MEDIAWIKI' ) ) {
	include __DIR__ . '/ValueValidators.mw.php';
}
else {
	spl_autoload_register( function ( $className ) {
		// @codeCoverageIgnoreStart
		static $classes = false;

		if ( $classes === false ) {
			$classes = include( __DIR__ . '/' . 'ValueValidators.classes.php' );
		}

		if ( array_key_exists( $className, $classes ) ) {
			include_once __DIR__ . '/' . $classes[$className];
		}
		// @codeCoverageIgnoreEnd
	} );
}
