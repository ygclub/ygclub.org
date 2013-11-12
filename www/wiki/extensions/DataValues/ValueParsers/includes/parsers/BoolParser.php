<?php

namespace ValueParsers;

use DataValues\BooleanValue;

/**
 * ValueParser that parses the string representation of a boolean.
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
class BoolParser extends StringValueParser {

	protected $values = array(
		'yes' => true,
		'on' => true,
		'1' => true,
		'true' => true,
		'no' => false,
		'off' => false,
		'0' => false,
		'false' => false,
	);

	/**
	 * @see StringValueParser::stringParse
	 *
	 * @since 0.1
	 *
	 * @param string $value
	 *
	 * @return BooleanValue
	 * @throws ParseException
	 */
	protected function stringParse( $value ) {
		$value = strtolower( $value );

		if ( array_key_exists( $value, $this->values ) ) {
			return new BooleanValue( $this->values[$value] );
		}

		throw new ParseException( 'Not a boolean' );
	}

}
