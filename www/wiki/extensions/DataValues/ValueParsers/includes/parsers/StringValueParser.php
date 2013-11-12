<?php

namespace ValueParsers;

use RuntimeException;

/**
 * ValueParser that parses the string representation of something.
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
abstract class StringValueParser implements ValueParser {

	/**
	 * @since 0.1
	 *
	 * @var ParserOptions
	 */
	protected $options;

	/**
	 * @since 0.1
	 *
	 * @param ParserOptions|null $options
	 */
	public function __construct( ParserOptions $options = null ) {
		if ( $options === null ) {
			$options = new ParserOptions();
		}

		$this->options = $options;

		$this->defaultOption( ValueParser::OPT_LANG, 'en' );
	}

	/**
	 * @see ValueParser::parse
	 *
	 * @since 0.1
	 *
	 * @param mixed $value
	 *
	 * @return mixed
	 * @throws ParseException
	 */
	public function parse( $value ) {
		if ( is_string( $value ) ) {
			return $this->stringParse( $value );
		}

		throw new ParseException( 'Not a string' );
	}

	/**
	 * Parses the provided string and returns the result.
	 *
	 * @since 0.1
	 *
	 * @param string $value
	 *
	 * @return mixed
	 */
	protected abstract function stringParse( $value );

	/**
	 * @see ValueParser::setOptions
	 *
	 * @since 0.1
	 *
	 * @param ParserOptions $options
	 */
	public function setOptions( ParserOptions $options ) {
		$this->options = $options;
	}

	/**
	 * @see ValueParser::getOptions
	 *
	 * @since 0.1
	 *
	 * @return ParserOptions
	 */
	public function getOptions() {
		return $this->options;
	}

	/**
	 * Shortcut to $this->options->getOption.
	 *
	 * @since 0.1
	 *
	 * @param string $option
	 */
	protected final function getOption( $option ) {
		return $this->options->getOption( $option );
	}

	/**
	 * Shortcut to $this->options->requireOption.
	 *
	 * @param string $option
	 *
	 * @throws RuntimeException
	 */
	protected final function requireOption( $option ) {
		$this->options->requireOption( $option );
	}

	/**
	 * Shortcut to $this->options->defaultOption.
	 *
	 * @since 0.1
	 *
	 * @param string $option
	 * @param mixed $default
	 */
	protected final function defaultOption( $option, $default ) {
		$this->options->defaultOption( $option, $default );
	}

}
