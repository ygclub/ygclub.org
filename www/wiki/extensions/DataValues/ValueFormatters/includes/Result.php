<?php

namespace ValueFormatters;

use Exception;
use Immutable;

/**
 * Interface for value parser results.
 * Immutable.
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
 * @deprecated
 *
 * @file
 * @ingroup ValueFormatters
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class Result implements Immutable {

	/**
	 * Indicates if the parsing process was successful.
	 *
	 * @since 0.1
	 *
	 * @var boolean
	 */
	protected $isValid;

	/**
	 * A DataValue instance representing the parsed value,
	 * or null if the parsing process failed.
	 *
	 * @since 0.1
	 *
	 * @var mixed
	 */
	protected $value;

	/**
	 * @since 0.1
	 *
	 * @param mixed $value
	 *
	 * @return Result
	 */
	public static function newSuccess( $value ) {
		return new static( true, $value );
	}

	/**
	 * @since 0.1
	 *
	 * @param boolean $isValid
	 * @param mixed $value
	 */
	protected function __construct( $isValid, $value = null ) {
		$this->isValid = $isValid;
		$this->value = $value;
	}

	/**
	 * Returns the formatted value.
	 * If the formatting process failed, this method will throw an
	 * exception when called. You can check for failure using
	 * the @see isValid method first.
	 *
	 * @since 0.1
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function getValue() {
		if ( $this->isValid() ) {
			return $this->value;
		}
		else {
			throw new Exception( 'Cannot obtain the formatted value as the formatting process failed' );
		}
	}

	/**
	 * Returns if the formatting was successful.
	 * If it was, you can obtain the resulting value via @see getValue
	 *
	 * @since 0.1
	 *
	 * @return boolean
	 */
	public function isValid() {
		return $this->isValid;
	}

}

/**
 * @deprecated
 */
class ResultObject extends Result {}