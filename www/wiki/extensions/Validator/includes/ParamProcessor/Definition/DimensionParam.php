<?php

namespace ParamProcessor\Definition;

use MWException;
use ParamProcessor\ParamDefinition;
use ParamProcessor\IParam;
use ParamProcessor\IParamDefinition;

/**
 * Defines the dimension parameter type.
 * This parameter describes the size of a dimension (ie width) in some unit (ie px) or a percentage.
 * Specifies the type specific validation and formatting logic.
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
 * TODO: this class is silly, should be handled by a dedicated formatting object/function.
 *
 * @since 1.0
 *
 * @file
 * @ingroup ParamProcessor
 * @ingroup ParamDefinition
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class DimensionParam extends ParamDefinition {

	/**
	 * Formats the parameter value to it's final result.
	 * @see ParamDefinition::formatValue
	 *
	 * @since 1.0
	 *
	 * @param mixed $value
	 * @param IParam $param
	 * @param IParamDefinition[] $definitions
	 * @param IParam[] $params
	 *
	 * @return mixed
	 * @throws MWException
	 */
	protected function formatValue( $value, IParam $param, array &$definitions, array $params ) {
		if ( $value === 'auto' ) {
			return $value;
		}

		/**
		 * @var \ValueValidators\DimensionValidator $validator
		 */
		$validator = $this->getValueValidator();

		if ( get_class( $validator ) === 'ValueValidators\DimensionValidator' ) {
			foreach ( $validator->getAllowedUnits() as $unit ) {
				if ( $unit !== '' && strpos( $value, $unit ) !== false ) {
					return $value;
				}
			}

			return $value . $validator->getDefaultUnit();
		}
		else {
			throw new MWException(
				'ValueValidator of a DimensionParam should be a ValueValidators\DimensionValidator and not a '
					. get_class( $validator )
			);
		}
	}

}
