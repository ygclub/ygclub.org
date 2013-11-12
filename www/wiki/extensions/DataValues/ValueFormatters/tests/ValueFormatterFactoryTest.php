<?php

namespace ValueFormatters\Test;

use ValueFormatters\ValueFormatterFactory;

/**
 * Unit tests for the ValueFormatterFactory class.
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
class ValueFormatterFactoryTest extends \MediaWikiTestCase {

	/**
	 * @var null|ValueFormatterFactory
	 */
	protected $factory = null;

	/**
	 * @since 0.1
	 *
	 * @return ValueFormatterFactory
	 */
	protected function getFactoryFromGlobals() {
		if ( $this->factory === null ) {
			$this->factory = new ValueFormatterFactory( $GLOBALS['wgValueFormatters'] );
		}

		return $this->factory;
	}

	public function testGetFormatterIds() {
		$ids = $this->getFactoryFromGlobals()->getFormatterIds();
		$this->assertInternalType( 'array', $ids );

		foreach ( $ids as $id ) {
			$this->assertInternalType( 'string', $id );
		}

		$this->assertArrayEquals( array_unique( $ids ), $ids );
	}

	public function testGetFormatter() {
		$factory = $this->getFactoryFromGlobals();

		$options = new \ValueFormatters\FormatterOptions();

		foreach ( $factory->getFormatterIds() as $id ) {
			$this->assertInstanceOf( 'ValueFormatters\ValueFormatter', $factory->newFormatter( $id, $options ) );
		}

		$this->assertInternalType( 'null', $factory->newFormatter( "I'm in your tests, being rather silly ~=[,,_,,]:3", $options ) );
	}

	public function testGetFormatterClass() {
		$factory = $this->getFactoryFromGlobals();

		foreach ( $factory->getFormatterIds() as $id ) {
			$this->assertInternalType( 'string', $factory->getFormatterClass( $id ) );
		}

		$this->assertInternalType( 'null', $factory->getFormatterClass( "I'm in your tests, being rather silly ~=[,,_,,]:3" ) );
	}

}
