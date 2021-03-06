<?php

namespace ValueParsers\Test;

use ValueParsers\ParserOptions;

/**
 * Unit tests for the ValueParsers\ParserOptions class.
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
class ParserOptionsTest extends \MediaWikiTestCase {

	public function testConstructor() {
		$options = array(
			'foo' => 42,
			'bar' => 4.2,
			'baz' => array( 'o_O', false, null, '42' => 42, array() )
		);

		$parserOptions = new ParserOptions( $options );

		foreach ( $options as $option => $value ) {
			$this->assertEquals(
				serialize( $value ),
				serialize( $parserOptions->getOption( $option ) ),
				'Option should be set properly'
			);
		}

		$this->assertFalse( $parserOptions->hasOption( 'ohi' ) );
	}

	public function testConstructorFail() {
		$this->assertException(
			function() {
				$options = array(
					'foo' => 42,
					'bar' => 4.2,
					42 => array( 'o_O', false, null, '42' => 42, array() )
				);

				new ParserOptions( $options );
			}
		);
	}

	public function setOptionProvider() {
		$argLists = array();

		$parserOptions = new ParserOptions();

		$argLists[] = array( $parserOptions, 'foo', 42 );
		$argLists[] = array( $parserOptions, 'bar', 42 );
		$argLists[] = array( $parserOptions, 'foo', 'foo' );
		$argLists[] = array( $parserOptions, 'foo', null );

		return $argLists;
	}

	/**
	 * @dataProvider setOptionProvider
	 *
	 * @param ParserOptions $options
	 * @param $option
	 * @param $value
	 */
	public function testSetAndGetOption( ParserOptions $options, $option, $value ) {
		$options->setOption( $option, $value );

		$this->assertEquals(
			$value,
			$options->getOption( $option ),
			'Setting an option should work'
		);
	}

	public function testHashOption() {
		$options = array(
			'foo' => 42,
			'bar' => 4.2,
			'baz' => array( 'o_O', false, null, '42' => 42, array() )
		);

		$parserOptions = new ParserOptions( $options );

		foreach ( array_keys( $options ) as $option ) {
			$this->assertTrue( $parserOptions->hasOption( $option ) );
		}

		$this->assertFalse( $parserOptions->hasOption( 'ohi' ) );
		$this->assertFalse( $parserOptions->hasOption( 'Foo' ) );
	}

	public function testSetOption() {
		$parserOptions = new ParserOptions( array( 'foo' => 'bar' ) );

		$values = array(
			array( 'foo', 'baz' ),
			array( 'foo', 'bar' ),
			array( 'onoez', '' ),
			array( 'hax', 'zor' ),
			array( 'nyan', 9001 ),
			array( 'cat', 4.2 ),
			array( 'spam', array( '~=[,,_,,]:3' ) ),
		);

		foreach ( $values as $value ) {
			$parserOptions->setOption( $value[0], $value[1] );
			$this->assertEquals( $value[1], $parserOptions->getOption( $value[0] ) );
		}
	}

	public function testForSomeReasonPhpSegfaultsIfThereIsOneMethodLess() {
		$this->assertTrue( (bool)'This is fucking weird' );
	}

	public function testGetOption() {
		$this->assertTrue( true );
		$parserOptions = new ParserOptions( array( 'foo' => 'bar' ) );

		foreach ( array( 'bar', 'Foo', 'FOO', 'spam', 'onoez' ) as $nonExistingOption ) {
			$this->assertException(
				function() use ( $parserOptions, $nonExistingOption ) {
					$parserOptions->getOption( $nonExistingOption );
				},
				'InvalidArgumentException'
			);
		}
	}

	public function testRequireOption() {
		$options = array(
			'foo' => 42,
			'bar' => 4.2,
			'baz' => array( 'o_O', false, null, '42' => 42, array() )
		);

		$parserOptions = new ParserOptions( $options );

		foreach ( array_keys( $options ) as $option ) {
			$parserOptions->requireOption( $option );
		}

		$notSet = array( 'ohi', 'Foo' );

		foreach ( $notSet as $option ) {
			$this->assertException(
				function() use ( $parserOptions, $option ) {
					$parserOptions->requireOption( $option );
				}
			);
		}
	}

	public function testDefaultOption() {
		$options = array(
			'foo' => 42,
			'bar' => 4.2,
			'baz' => array( 'o_O', false, null, '42' => 42, array() )
		);

		$parserOptions = new ParserOptions( $options );

		foreach ( $options as $option => $value ) {
			$parserOptions->defaultOption( $option, 9001 );

			$this->assertEquals(
				serialize( $value ),
				serialize( $parserOptions->getOption( $option ) ),
				'Defaulting a set option should not affect its value'
			);
		}

		$defaults = array(
			'N' => 42,
			'y' => 4.2,
			'a' => false,
			'n' => array( '42' => 42, array( '' ) )
		);

		foreach ( $defaults as $option => $value ) {
			$parserOptions->defaultOption( $option, $value );

			$this->assertEquals(
				serialize( $value ),
				serialize( $parserOptions->getOption( $option ) ),
				'Defaulting a not set option should affect its value'
			);
		}
	}

}
