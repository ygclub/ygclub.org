<?php

namespace ValueParsers;

use InvalidArgumentException;

/**
 * Factory for creating ValueParser objects.
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
class ValueParserFactory {

	/**
	 * Maps parser id to ValueParser class or instance.
	 *
	 * @since 0.1
	 *
	 * @var array
	 */
	protected $parsers = array();

	/**
	 * @since 0.1
	 *
	 * @param array $valueParsers
	 */
	public function __construct( array $valueParsers ) {
		foreach ( $valueParsers as $parserId => $parserClass ) {
			if ( !is_string( $parserId ) ) {
				throw new InvalidArgumentException( 'Parser id needs to be a string' );
			}

			if ( !is_string( $parserClass ) ) {
				throw new InvalidArgumentException( 'Parser class needs to be a string' );
			}

			$this->parsers[$parserId] = $parserClass;
		}
	}

	/**
	 * Returns the ValueParser identifiers.
	 *
	 * @since 0.1
	 *
	 * @return string[]
	 */
	public function getParserIds() {
		return array_keys( $this->parsers );
	}

	/**
	 * Returns class of the ValueParser with the provided id or null if there is no such ValueParser.
	 *
	 * @since 0.1
	 *
	 * @param string $parserId
	 *
	 * @return string|null
	 */
	public function getParserClass( $parserId ) {
		if ( array_key_exists( $parserId, $this->parsers ) ) {
			return is_string( $this->parsers[$parserId] ) ? $this->parsers[$parserId] : get_class( $this->parsers[$parserId] );
		}

		return null;
	}

	/**
	 * Returns an instance of the ValueParser with the provided id or null if there is no such ValueParser.
	 *
	 * @since 0.1
	 *
	 * @param string $parserId
	 * @param ParserOptions $parserOptions
	 *
	 * @return ValueParser|null
	 */
	public function newParser( $parserId, ParserOptions $parserOptions ) {
		if ( !array_key_exists( $parserId, $this->parsers ) ) {
			return null;
		}

		$parser = new $this->parsers[$parserId]( $parserOptions );

		assert( $parser instanceof ValueParser );

		return $parser;
	}

}
