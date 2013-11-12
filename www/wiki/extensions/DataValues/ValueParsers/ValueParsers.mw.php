<?php

/**
 * MediaWiki setup for the ValueParser extension.
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

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'Not an entry point.' );
}

global $wgExtensionCredits, $wgExtensionMessagesFiles, $wgAutoloadClasses, $wgHooks, $wgAPIModules, $wgResourceModules;

$wgExtensionCredits['datavalues'][] = array(
	'path' => __DIR__,
	'name' => 'ValueParsers',
	'version' => ValueParsers_VERSION,
	'author' => array( '[https://www.mediawiki.org/wiki/User:Jeroen_De_Dauw Jeroen De Dauw]' ),
	'url' => 'https://www.mediawiki.org/wiki/Extension:ValueParsers',
	'descriptionmsg' => 'valueparsers-desc',
);

$wgExtensionMessagesFiles['ValueParsers'] = __DIR__ . '/ValueParsers.i18n.php';

foreach ( include( __DIR__ . '/ValueParsers.classes.php' ) as $class => $file ) {
	if ( !array_key_exists( $class, $GLOBALS['wgAutoloadLocalClasses'] ) ) {
		$wgAutoloadClasses[$class] = __DIR__ . '/' . $file;
	}
}

// API module registration
$wgAPIModules['parsevalue'] = 'ValueParsers\ApiParseValue';

/**
 * Hook to add PHPUnit test cases.
 * @see https://www.mediawiki.org/wiki/Manual:Hooks/UnitTestsList
 *
 * @since 0.1
 *
 * @param array $files
 *
 * @return boolean
 */
$wgHooks['UnitTestsList'][] = function( array &$files ) {
	// @codeCoverageIgnoreStart
	$testFiles = array(
		'includes/api/ApiParseValue',

		'includes/parsers/BoolParser',
		'includes/parsers/GeoCoordinateParser',
		'includes/parsers/FloatParser',
		'includes/parsers/IntParser',
		'includes/parsers/NullParser',
		'includes/parsers/TitleParser',

		'includes/Error',
		'includes/ParserOptions',
		'includes/ValueParserFactory',
	);

	foreach ( $testFiles as $file ) {
		$files[] = __DIR__ . '/tests/' . $file . 'Test.php';
	}

	return true;
	// @codeCoverageIgnoreEnd
};

/**
 * Hook to add QUnit test cases.
 * @see https://www.mediawiki.org/wiki/Manual:Hooks/ResourceLoaderTestModules
 * @since 0.1
 *
 * @param array &$testModules
 * @param \ResourceLoader &$resourceLoader
 * @return boolean
 */
$wgHooks['ResourceLoaderTestModules'][] = function ( array &$testModules, \ResourceLoader &$resourceLoader ) {
	// @codeCoverageIgnoreStart
	$moduleTemplate = array(
		'localBasePath' => __DIR__,
		'remoteExtPath' => 'DataValues/ValueParsers',
	);

	$testModules['qunit']['ext.valueParsers.tests'] = $moduleTemplate + array(
		'scripts' => array(
			'tests/qunit/ValueParser.tests.js',
		),
		'dependencies' => array(
			'valueParsers.parsers',
		),
	);

	$testModules['qunit']['ext.valueParsers.factory'] = $moduleTemplate + array(
		'scripts' => array(
			'tests/qunit/ValueParserFactory.tests.js',
		),
		'dependencies' => array(
			'valueParsers.factory',
			'valueParsers.parsers',
		),
	);

	$testModules['qunit']['ext.valueParsers.parsers'] = $moduleTemplate + array(
		'scripts' => array(
			'tests/qunit/parsers/BoolParser.tests.js',
			'tests/qunit/parsers/FloatParser.tests.js',
			'tests/qunit/parsers/IntParser.tests.js',
			'tests/qunit/parsers/StringParser.tests.js',
			'tests/qunit/parsers/NullParser.tests.js',
		),
		'dependencies' => array(
			'ext.valueParsers.tests',
		),
	);

	return true;
	// @codeCoverageIgnoreEnd
};

// Resource Loader module registration
$wgResourceModules = array_merge(
	$wgResourceModules,
	include( __DIR__ . '/Resources.php' )
);