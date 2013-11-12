<?php

namespace ValueParsers\Test;

use ValueParsers\Error;

/**
 * Unit tests for the ValueParsers\Error class.
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
 * @ingroup ValueParsersTest
 *
 * @group ValueParsers
 * @group DataValueExtensions
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ErrorTest extends \MediaWikiTestCase {

	public function newErrorProvider() {
		$argLists = array();

		$argLists[] = array();

		$argLists[] = array( '' );
		$argLists[] = array( 'foo' );
		$argLists[] = array( ' foo bar baz.' );

		$argLists[] = array( ' foo bar ', null );
		$argLists[] = array( ' foo bar ', 'length' );

		return $argLists;
	}

	/**
	 * @dataProvider newErrorProvider
	 */
	public function testNewError() {
		$args = func_get_args();

		$error = call_user_func_array( 'ValueParsers\Error::newError', $args );

		/**
		 * @var Error $error
		 */
		$this->assertInstanceOf( 'ValueParsers\Error', $error );

		$this->assertInternalType( 'string', $error->getText() );
		$this->assertInternalType( 'integer', $error->getSeverity() );
		$this->assertTypeOrValue( 'string', $error->getProperty(), null );

		if ( count( $args ) > 0 ) {
			$this->assertEquals( $args[0], $error->getText() );
		}

		if ( count( $args ) > 1 ) {
			$this->assertEquals( $args[1], $error->getProperty() );
		}
	}

}
