<?php

namespace ValueFormatters;

/**
 * Factory for creating ValueFormatter objects.
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
class ValueFormatterFactory {

	/**
	 * Maps parser id to ValueFormatter class.
	 *
	 * @since 0.1
	 *
	 * @var ValueFormatter[]
	 */
	protected $formatters = array();

	/**
	 * @since 0.1
	 *
	 * @param string[] $valueFormatters
	 */
	public function __construct( array $valueFormatters ) {
		foreach ( $valueFormatters as $formatterId => $formatterClass ) {
			assert( is_string( $formatterId ) );
			assert( is_string( $formatterClass ) );

			$this->formatters[$formatterId] = $formatterClass;
		}
	}

	/**
	 * Returns the ValueFormatter identifiers.
	 *
	 * @since 0.1
	 *
	 * @return string[]
	 */
	public function getFormatterIds() {
		return array_keys( $this->formatters );
	}

	/**
	 * Returns class of the ValueFormatter with the provided id or null if there is no such ValueFormatter.
	 *
	 * @since 0.1
	 *
	 * @param string $formatterId
	 *
	 * @return string|null
	 */
	public function getFormatterClass( $formatterId ) {
		return array_key_exists( $formatterId, $this->formatters ) ? $this->formatters[$formatterId] : null;
	}

	/**
	 * Returns an instance of the ValueFormatter with the provided id or null if there is no such ValueFormatter.
	 *
	 * @since 0.1
	 *
	 * @param string $formatterId
	 * @param FormatterOptions $options
	 *
	 * @return ValueFormatter|null
	 */
	public function newFormatter( $formatterId, FormatterOptions $options ) {
		if ( !array_key_exists( $formatterId, $this->formatters ) ) {
			return null;
		}

		$instance = new $this->formatters[$formatterId]( $options );

		assert( $instance instanceof ValueFormatter );

		return $instance;
	}

}
