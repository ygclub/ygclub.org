<?php

/**
 * Class registration file for the ValueParser library.
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
return array(
	'ValueParsers\Error' => 'includes/Error.php',
	'ValueParsers\ParseException' => 'includes/ParseException.php',
	'ValueParsers\ErrorObject' => 'includes/Error.php',
	'ValueParsers\ParserOptions' => 'includes/ParserOptions.php',
	'ValueParsers\ValueParser' => 'includes/ValueParser.php',
	'ValueParsers\ValueParserFactory' => 'includes/ValueParserFactory.php',

	'ValueParsers\ApiParseValue' => 'includes/api/ApiParseValue.php',

	'ValueParsers\BoolParser' => 'includes/parsers/BoolParser.php',
	'ValueParsers\GeoCoordinateParser' => 'includes/parsers/GeoCoordinateParser.php',
	'ValueParsers\FloatParser' => 'includes/parsers/FloatParser.php',
	'ValueParsers\IntParser' => 'includes/parsers/IntParser.php',
	'ValueParsers\NullParser' => 'includes/parsers/NullParser.php',
	'ValueParsers\StringValueParser' => 'includes/parsers/StringValueParser.php',
	'ValueParsers\TitleParser' => 'includes/parsers/TitleParser.php',

	'ValueParsers\Test\StringValueParserTest' => 'tests/includes/parsers/StringValueParserTest.php',
	'ValueParsers\Test\ValueParserTestBase' => 'tests/includes/parsers/ValueParserTestBase.php',
);
