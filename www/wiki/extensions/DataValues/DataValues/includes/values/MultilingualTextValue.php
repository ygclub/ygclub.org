<?php

namespace DataValues;

use InvalidArgumentException;

/**
 * Class representing a multilingual text value.
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
 * @ingroup DataValue
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class MultilingualTextValue extends DataValueObject {

	/**
	 * Array with language codes pointing to their associated texts.
	 *
	 * @since 0.1
	 *
	 * @var MonolingualTextValue[]
	 */
	protected $texts = array();

	/**
	 * @since 0.1
	 *
	 * @param MonolingualTextValue[] $monolingualValues
	 *
	 * @throws InvalidArgumentException
	 */
	public function __construct( array $monolingualValues ) {
		foreach ( $monolingualValues as $monolingualValue ) {
			if ( !( $monolingualValue instanceof MonolingualTextValue ) ) {
				throw new InvalidArgumentException( 'Can only construct MultilingualTextValue from MonolingualTextValue objects' );
			}

			$langCode = $monolingualValue->getLanguageCode();

			if ( array_key_exists( $langCode, $this->texts ) ) {
				throw new InvalidArgumentException( 'Can only add a single MonolingualTextValue per language to a MultilingualTextValue' );
			}

			$this->texts[$langCode] = $monolingualValue;
		}
	}

	/**
	 * @see Serializable::serialize
	 *
	 * @since 0.1
	 *
	 * @return string
	 */
	public function serialize() {
		return serialize( $this->texts );
	}

	/**
	 * @see Serializable::unserialize
	 *
	 * @since 0.1
	 *
	 * @param string $value
	 *
	 * @return MultilingualTextValue
	 */
	public function unserialize( $value ) {
		$this->__construct( unserialize( $value ) );
	}

	/**
	 * @see DataValue::getType
	 *
	 * @since 0.1
	 *
	 * @return string
	 */
	public function getType() {
		return 'multilingualtext';
	}

	/**
	 * @see DataValue::getSortKey
	 *
	 * @since 0.1
	 *
	 * @return string|float|int
	 */
	public function getSortKey() {
		return empty( $this->texts ) ? '' : reset( $this->texts )->getSortKey();
	}

	/**
	 * Returns the texts as an array of monolingual text values.
	 *
	 * @since 0.1
	 *
	 * @return MonolingualTextValue[]
	 */
	public function getTexts() {
		return $this->texts;
	}

	/**
	 * Returns the multilingual text value
	 * @see DataValue::getValue
	 *
	 * @since 0.1
	 *
	 * @return MultilingualTextValue
	 */
	public function getValue() {
		return $this;
	}

	/**
	 * @see DataValue::getArrayValue
	 *
	 * @since 0.1
	 *
	 * @return mixed
	 */
	public function getArrayValue() {
		$values = array();

		/**
		 * @var MonolingualTextValue $text
		 */
		foreach ( $this->texts as $text ) {
			$values[] = $text->getArrayValue();
		}

		return $values;
	}

	/**
	 * Constructs a new instance of the DataValue from the provided data.
	 * This can round-trip with @see getArrayValue
	 *
	 * @since 0.1
	 *
	 * @param array $data
	 *
	 * @return MultilingualTextValue
	 */
	public static function newFromArray( array $data ) {
		$values = array();

		foreach ( $data as $monolingualValue ) {
			$values[] = MonolingualTextValue::newFromArray( $monolingualValue );
		}

		return new static( $values );
	}

}
