<?php
/**
 * Definition of ResourceLoader modules of the ValueParsers extension.
 * When included this returns an array with all the modules introduced by ValueParsers.
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
 *
 * @codeCoverageIgnoreStart
 */
return call_user_func( function() {

	$moduleTemplate = array(
		'localBasePath' => __DIR__ . '/resources',
		'remoteExtPath' =>  'DataValues/ValueParsers/resources',
	);

	return array(
		'valueParsers' => $moduleTemplate + array(
			'scripts' => array(
				'valueParsers.js',
			),
		),

		'valueParsers.ValueParser' => $moduleTemplate + array(
			'scripts' => array(
				'ValueParser.js',
				'ApiBasedValueParser.js',
			),
			'dependencies' => array(
				'valueParsers',
				'valueParsers.util',
			),
		),

		'valueParsers.factory' => $moduleTemplate + array(
			'scripts' => array(
				'ValueParserFactory.js',
			),
			'dependencies' => array(
				'valueParsers.ValueParser',
			),
		),

		'valueParsers.parsers' => $moduleTemplate + array(
			'scripts' => array(
				'parsers/BoolParser.js',
				'parsers/FloatParser.js',
				'parsers/IntParser.js',
				'parsers/StringParser.js',
				'parsers/NullParser.js',
			),
			'dependencies' => array(
				'valueParsers.ValueParser',
				'valueParsers.api',
			),
		),

		'valueParsers.util' => $moduleTemplate + array(
			'scripts' => array(
				'valueParsers.util.js',
			),
			'dependencies' => array(
				'dataValues.util',
				'valueParsers',
			),
		),

		'valueParsers.api' => $moduleTemplate + array(
			'scripts' => array(
				'Api.js',
			),
			'dependencies' => array(
				'valueParsers',
				'dataValues.values',
				'jquery.json',
			),
		),
	);

} );
// @codeCoverageIgnoreEnd
