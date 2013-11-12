<?php

namespace ValueValidators;

/**
 * Interface for ValueValidator errors.
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
class Error implements \Immutable {

	const SEVERITY_ERROR = 9;
	const SEVERITY_WARNING = 4;

	protected $text;
	protected $severity;
	protected $property;

	/**
	 * Create a new error.
	 *
	 * @since 0.1
	 *
	 * @param string $text
	 * @param string|null $property
	 *
	 * @return Error
	 */
	public static function newError( $text = '', $property = null ) {
		return new static( $text, Error::SEVERITY_ERROR, $property );
	}

	/**
	 * @since 0.1
	 *
	 * @param string $text
	 * @param integer $severity
	 * @param string|null $property
	 */
	protected function __construct( $text, $severity, $property ) {
		$this->text = $text;
		$this->severity = $severity;
		$this->property = $property;
	}

	/**
	 * Returns the error text.
	 *
	 * @since 0.1
	 *
	 * @return string
	 */
	public function getText() {
		return $this->text;
	}

	/**
	 * Returns the severity of the error
	 *
	 * @since 0.1
	 *
	 * @return integer, element of the ValueValidatorError::SEVERITY_ enum
	 */
	public function getSeverity() {
		return $this->severity;
	}

	/**
	 * Returns the property of the value for which the error occurred, or null if it occurred for the value itself.
	 *
	 * @since 0.1
	 *
	 * @return string|null
	 */
	public function getProperty() {
		return $this->property;
	}

}

/**
 * @deprecated
 */
class ErrorObject extends Error {}