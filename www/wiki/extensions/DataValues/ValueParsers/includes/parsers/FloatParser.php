<?php

namespace ValueParsers;

use DataValues\NumberValue;

/**
 * ValueParser that parses the string representation of a float.
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
class FloatParser extends StringValueParser {

	/**
	 * @see StringValueParser::stringParse
	 *
	 * TODO: add options for different group and decimal separators.
	 *
	 * @since 0.1
	 *
	 * @param string $value
	 *
	 * @return NumberValue
	 * @throws ParseException
	 */
	protected function stringParse( $value ) {
		if ( preg_match( '/^(-)?\d+((\.|,)\d+)?$/', $value ) ) {
			return new NumberValue( (float)$value );
		}

		throw new ParseException( 'Not a float' );
	}

}
