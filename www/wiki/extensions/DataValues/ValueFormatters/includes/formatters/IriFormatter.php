<?php

namespace ValueFormatters;

use DataValues\IriValue;
use InvalidArgumentException;

/**
 * Formatter for string values
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
 * @ingroup ValueFormatters
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class IriFormatter extends ValueFormatterBase {

	/**
	 * Formats a StringValue data value
	 *
	 * @since 0.1
	 *
	 * @param mixed $dataValue value to format
	 *
	 * @return string
	 * @throws InvalidArgumentException
	 */
	public function format( $dataValue ) {
		if ( !( $dataValue instanceof IriValue ) ) {
			throw new InvalidArgumentException( 'DataValue is not a IriValue.' );
		}

		$formatted = $dataValue->getScheme() . ':'
			. $dataValue->getHierarchicalPart()
			. ( $dataValue->getQuery() === '' ? '' : '?' . $dataValue->getQuery() )
			. ( $dataValue->getFragment() === '' ? '' : '#' . $dataValue->getFragment() );

		return $formatted;
	}

}
