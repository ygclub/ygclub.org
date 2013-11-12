<?php

namespace ValueParsers\Test;

use ValueParsers\ParserOptions;
use ValueParsers\ValueParser;

/**
 * Base for unit tests for ValueParser implementing classes.
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
abstract class ValueParserTestBase extends \MediaWikiTestCase {

	/**
	 * @since 0.1
	 * @return string
	 */
	protected abstract function getParserClass();

	/**
	 * @since 0.1
	 */
	public abstract function validInputProvider();

	/**
	 * @since 0.1
	 */
	public function invalidInputProvider() {
		return array();
	}

	/**
	 * @since 0.1
	 * @return ValueParser
	 */
	protected function getInstance() {
		$class = $this->getParserClass();
		return new $class( $this->newParserOptions() );
	}

	/**
	 * @dataProvider validInputProvider
	 * @since 0.1
	 * @param $value
	 * @param mixed $expected
	 * @param ValueParser|null $parser
	 */
	public function testParseWithValidInputs( $value, $expected, ValueParser $parser = null ) {
		if ( is_null( $parser ) ) {
			$parser = $this->getInstance();
		}

		$this->assertSmartEquals( $expected, $parser->parse( $value ) );
	}

	private function assertSmartEquals( $expected, $actual ) {
		if ( $this->requireDataValue() || $expected instanceof \Comparable ) {
			$this->assertTrue( $expected->equals( $actual ) );
		}
		else {
			$this->assertEquals( $expected, $actual );
		}
	}

	/**
	 * @dataProvider invalidInputProvider
	 * @since 0.1
	 * @param $value
	 * @param ValueParser|null $parser
	 */
	public function testParseWithInvalidInputs( $value, ValueParser $parser = null ) {
		if ( is_null( $parser ) ) {
			$parser = $this->getInstance();
		}

		$this->setExpectedException( 'ValueParsers\ParseException' );

		$parser->parse( $value );
	}

	/**
	 * Returns if the result of the parsing process should be checked to be a DataValue.
	 *
	 * @since 0.1
	 *
	 * @return boolean
	 */
	protected function requireDataValue() {
		return true;
	}

	/**
	 * Returns some parser options object with all required options for the parser under test set.
	 *
	 * @since 0.1
	 *
	 * @return ParserOptions
	 */
	protected function newParserOptions() {
		return new ParserOptions();
	}

}
