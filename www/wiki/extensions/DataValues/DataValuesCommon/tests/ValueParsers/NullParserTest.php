<?php

namespace ValueParsers\Test;

use DataValues\UnknownValue;
use ValueParsers\ValueParser;

/**
 * Unit test NullParser class.
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
class NullParserTest extends ValueParserTestBase {

	/**
	 * @see ValueParserTestBase::validInputProvider
	 *
	 * @since 0.1
	 *
	 * @return array
	 */
	public function validInputProvider() {
		$argLists = array();

		$values = array(
			'42',
			42,
			false,
			array(),
			'ohi there!',
			null,
			4.2,
		);

		foreach ( $values as $value ) {
			$argLists[] = array(
				$value,
				new UnknownValue( $value )
			);
		}

		return $argLists;
	}

	/**
	 * @since 0.1
	 */
	public function invalidInputProvider() {
		return array( array(
			'This sucks; this parser has no invalid inputs, so this test should be skipped.' .
			'Not clear how to do that in a way one does not get a "incomplete test" notice though'
		) );
	}

	/**
	 * @dataProvider invalidInputProvider
	 * @param $value
	 * @param ValueParser $parser
	 */
	public function testParseWithInvalidInputs( $value, ValueParser $parser = null ) {
		$this->assertTrue( true );
	}

	/**
	 * @see ValueParserTestBase::getParserClass
	 * @since 0.1
	 * @return string
	 */
	protected function getParserClass() {
		return 'ValueParsers\NullParser';
	}

}
