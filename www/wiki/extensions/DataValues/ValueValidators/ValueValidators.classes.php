<?php

/**
 * Class registration file for the ValueValidator library.
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
return array(
	'ValueValidators\Error' => 'includes/Error.php',
	'ValueValidators\ErrorObject' => 'includes/Error.php',
	'ValueValidators\Result' => 'includes/Result.php',
	'ValueValidators\ResultObject' => 'includes/Result.php',
	'ValueValidators\ValueValidator' => 'includes/ValueValidator.php',
	'ValueValidators\ValueValidatorFactory' => 'includes/ValueValidatorFactory.php',
	'ValueValidators\ValueValidatorObject' => 'includes/ValueValidatorObject.php',

	'ValueValidators\DimensionValidator' => 'includes/validators/DimensionValidator.php',
	'ValueValidators\ListValidator' => 'includes/validators/ListValidator.php',
	'ValueValidators\NullValidator' => 'includes/validators/NullValidator.php',
	'ValueValidators\RangeValidator' => 'includes/validators/RangeValidator.php',
	'ValueValidators\StringValidator' => 'includes/validators/StringValidator.php',
	'ValueValidators\TitleValidator' => 'includes/validators/TitleValidator.php',
);
