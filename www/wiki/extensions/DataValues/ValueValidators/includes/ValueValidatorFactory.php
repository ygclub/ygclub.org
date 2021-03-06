<?php

namespace ValueValidators;

/**
 * Factory for creating ValueValidator objects.
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
class ValueValidatorFactory {

	/**
	 * Maps validator id to ValueValidator class.
	 *
	 * @since 0.1
	 *
	 * @var ValueValidator[]
	 */
	protected $validators = array();

	/**
	 * @since 0.1
	 *
	 * @param ValueValidator[] $valueValidators
	 */
	public function __construct( array $valueValidators ) {
		foreach ( $valueValidators as $validatorId => $validatorClass ) {
			assert( is_string( $validatorId ) );
			assert( is_string( $validatorClass ) );

			$this->parsers[$validatorId] = $validatorClass;
		}
	}

	/**
	 * @deprecated
	 *
	 * @return ValueValidatorFactory
	 */
	public function singleton() {
		global $wgValueValidators;
		return new static( $wgValueValidators );
	}

	/**
	 * Returns the ValueValidator identifiers.
	 *
	 * @since 0.1
	 *
	 * @return string[]
	 */
	public function getValidatorIds() {
		return array_keys( $this->validators );
	}

	/**
	 * Returns class of the ValueValidator with the provided id or null if there is no such ValueValidator.
	 *
	 * @since 0.1
	 *
	 * @param string $validatorId
	 *
	 * @return string|null
	 */
	public function getValidatorClass( $validatorId ) {
		return array_key_exists( $validatorId, $this->validators ) ? $this->validators[$validatorId] : null;
	}

	/**
	 * Returns an instance of the ValueValidator with the provided id or null if there is no such ValueValidator.
	 *
	 * @since 0.1
	 *
	 * @param string $validatorId
	 *
	 * @return ValueValidator|null
	 */
	public function newValidator( $validatorId ) {
		return array_key_exists( $validatorId, $this->validators ) ? new $this->validators[$validatorId]() : null;
	}

}
