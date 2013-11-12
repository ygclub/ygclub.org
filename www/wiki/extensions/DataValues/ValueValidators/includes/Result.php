<?php

namespace ValueValidators;

/**
 * Interface for value validator results.
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
 * @ingroup ValueValidators
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class Result implements \Immutable {

	/**
	 * @since 0.1
	 *
	 * @var boolean
	 */
	protected $isValid;

	/**
	 * @since 0.1
	 *
	 * @var Error[]
	 */
	protected $errors = array();

	/**
	 * @since 0.1
	 *
	 * @return Result
	 */
	public static function newSuccess() {
		return new static( true );
	}

	/**
	 * @since 0.1
	 *
	 * @param Error[] $errors
	 *
	 * @return Result
	 */
	public static function newError( array $errors ) {
		return new static( false, $errors );
	}

	/**
	 * @since 0.1
	 *
	 * @param boolean $isValid
	 * @param Error[] $errors
	 */
	protected function __construct( $isValid, array $errors = array() ) {
		$this->isValid = $isValid;
		$this->errors = $errors;
	}

	/**
	 * Returns if the value was found to be valid or not.
	 *
	 * @since 0.1
	 *
	 * @return boolean
	 */
	public function isValid() {
		return $this->isValid;
	}

	/**
	 * Returns an array with the errors that occurred during validation.
	 *
	 * @since 0.1
	 *
	 * @return Error[]
	 */
	public function getErrors() {
		return $this->errors;
	}

}

/**
 * @deprecated
 */
class ResultObject extends Result {}
