<?php

namespace ValueFormatters\Test;

use ValueFormatters\FormatterOptions;
use ValueFormatters\ValueFormatter;

/**
 * Base for unit tests for ValueFormatter implementing classes.
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
 * @file
 * @since 0.1
 *
 * @ingroup ValueFormattersTest
 *
 * @group ValueFormatters
 * @group DataValueExtensions
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
abstract class ValueFormatterTestBase extends \MediaWikiTestCase {

	/**
	 * Returns a list with valid inputs and their associated formatting output.
	 *
	 * @since 0.1
	 *
	 * @return array
	 */
	public abstract function validProvider();

	/**
	 * Returns the name of the ValueFormatter implementing class.
	 *
	 * @since 0.1
	 *
	 * @return string
	 */
	protected abstract function getFormatterClass();

	/**
	 * @since 0.1
	 *
	 * @param FormatterOptions $options
	 *
	 * @return ValueFormatter
	 */
	protected function getInstance( FormatterOptions $options ) {
		$class = $this->getFormatterClass();
		return new $class( $options );
	}

	/**
	 * @dataProvider validProvider
	 *
	 * @since 0.1
	 *
	 * @param mixed $value
	 * @param mixed $expected
	 * @param FormatterOptions|null $options
	 * @param ValueFormatter|null $formatter
	 */
	public function testValidFormat( $value, $expected, FormatterOptions $options = null, ValueFormatter $formatter = null ) {
		if ( $options === null ) {
			$options = new FormatterOptions();
		}

		if ( is_null( $formatter ) ) {
			$formatter = $this->getInstance( $options );
		}

		$this->assertEquals( $expected, $formatter->format( $value ) );
	}

}
