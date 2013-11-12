<?php

namespace DataValues\Tests;

use DataValues\DecimalValue;
use DataValues\QuantityValue;

/**
 * @covers DataValues\QuantityValue
 *
 * @since 0.1
 *
 * @group DataValue
 * @group DataValueExtensions
 *
 * @licence GNU GPL v2+
 * @author Daniel Kinzler
 */
class QuantityValueTest extends DataValueTest {

	/**
	 * @see DataValueTest::getClass
	 *
	 * @since 0.1
	 *
	 * @return string
	 */
	public function getClass() {
		return 'DataValues\QuantityValue';
	}

	public function validConstructorArgumentsProvider() {
		$argLists = array();

		$argLists[] = array( new DecimalValue( '+42' ), '1', new DecimalValue( '+42' ), new DecimalValue( '+42' ) );
		$argLists[] = array( new DecimalValue( '+0.01' ), '1', new DecimalValue( '+0.02' ), new DecimalValue( '+0.0001' ) );
		$argLists[] = array( new DecimalValue( '-0.5' ), '1', new DecimalValue( '+0.02' ), new DecimalValue( '-0.7' ) );

		return $argLists;
	}

	public function invalidConstructorArgumentsProvider() {
		$argLists = array();

		$argLists[] = array();
		$argLists[] = array( new DecimalValue( '+0' ), '', new DecimalValue( '+0' ), new DecimalValue( '+0' ) );
		$argLists[] = array( new DecimalValue( '+0' ), 1, new DecimalValue( '+0' ), new DecimalValue( '+0' ) );

		$argLists[] = array( new DecimalValue( '+0' ), '1', new DecimalValue( '-0.001' ), new DecimalValue( '-1' ) );
		$argLists[] = array( new DecimalValue( '+0' ), '1', new DecimalValue( '+1' ), new DecimalValue( '+0.001' ) );

		return $argLists;
	}

	/**
	 * @dataProvider instanceProvider
	 * @param QuantityValue $quantity
	 * @param array $arguments
	 */
	public function testGetValue( QuantityValue $quantity, array $arguments ) {
		$this->assertInstanceOf( $this->getClass(), $quantity->getValue() );
	}

	/**
	 * @dataProvider instanceProvider
	 * @param QuantityValue $quantity
	 * @param array $arguments
	 */
	public function testGetAmount( QuantityValue $quantity, array $arguments ) {
		$this->assertEquals( $arguments[0], $quantity->getAmount() );
	}

	/**
	 * @dataProvider instanceProvider
	 * @param QuantityValue $quantity
	 * @param array $arguments
	 */
	public function testGetUnit( QuantityValue $quantity, array $arguments ) {
		$this->assertEquals( $arguments[1], $quantity->getUnit() );
	}

	/**
	 * @dataProvider instanceProvider
	 * @param QuantityValue $quantity
	 * @param array $arguments
	 */
	public function testGetUpperBound( QuantityValue $quantity, array $arguments ) {
		$this->assertEquals( $arguments[2], $quantity->getUpperBound() );
	}

	/**
	 * @dataProvider instanceProvider
	 * @param QuantityValue $quantity
	 * @param array $arguments
	 */
	public function testGetLowerBound( QuantityValue $quantity, array $arguments ) {
		$this->assertEquals( $arguments[3], $quantity->getLowerBound() );
	}

	/**
	 * @dataProvider newFromNumberProvider
	 *
	 * @param $amount
	 * @param $unit
	 * @param $upperBound
	 * @param $lowerBound
	 * @param QuantityValue $expected
	 */
	public function testNewFromNumber( $amount, $unit, $upperBound, $lowerBound, QuantityValue $expected ) {
		$quantity = QuantityValue::newFromNumber( $amount, $unit, $upperBound, $lowerBound );

		$this->assertEquals( $expected->getAmount()->getValue(), $quantity->getAmount()->getValue() );
		$this->assertEquals( $expected->getUpperBound()->getValue(), $quantity->getUpperBound()->getValue() );
		$this->assertEquals( $expected->getLowerBound()->getValue(), $quantity->getLowerBound()->getValue() );
	}

	public function newFromNumberProvider() {
		return array(
			array(
				42, '1', null, null,
				new QuantityValue( new DecimalValue( '+42' ), '1', new DecimalValue( '+42' ), new DecimalValue( '+42' ) )
			),
			array(
				-0.05, '1', null, null,
				new QuantityValue( new DecimalValue( '-0.05' ), '1', new DecimalValue( '-0.05' ), new DecimalValue( '-0.05' ) )
			),
			array(
				0, 'm', 0.5, -0.5,
				new QuantityValue( new DecimalValue( '+0' ), 'm', new DecimalValue( '+0.5' ), new DecimalValue( '-0.5' ) )
			),
			array(
				'+23', '1', null, null,
				new QuantityValue( new DecimalValue( '+23' ), '1', new DecimalValue( '+23' ), new DecimalValue( '+23' ) )
			),
			array(
				'+42', '1', '+43', '+41',
				new QuantityValue( new DecimalValue( '+42' ), '1', new DecimalValue( '+43' ), new DecimalValue( '+41' ) )
			),
			array(
				'-0.05', 'm', '-0.04', '-0.06',
				new QuantityValue( new DecimalValue( '-0.05' ), 'm', new DecimalValue( '-0.04' ), new DecimalValue( '-0.06' ) )
			),
			array(
				new DecimalValue( '+42' ), '1', new DecimalValue( 43 ), new DecimalValue( 41.0 ),
				new QuantityValue( new DecimalValue( '+42' ), '1', new DecimalValue( 43 ), new DecimalValue( 41.0 ) )
			),
		);
	}

	/**
	 * @dataProvider instanceProvider
	 */
	public function testGetSortKey( QuantityValue $quantity ) {
		$this->assertEquals( $quantity->getAmount()->getValueFloat(), $quantity->getSortKey() );
	}

	/**
	 * @dataProvider getUncertaintyProvider
	 */
	public function testGetUncertainty( QuantityValue $quantity, $expected ) {
		$actual = $quantity->getUncertainty();

		// floats are wonkey, accept small differences here
		$this->assertTrue( abs( $actual - $expected ) < 0.000000001, "expected $expected, got $actual" );
	}

	public function getUncertaintyProvider() {
		return array(
			array( QuantityValue::newFromNumber( '+0', '1', '+0', '+0' ), 0 ),

			array( QuantityValue::newFromNumber( '+0', '1', '+1', '-1' ), 2 ),
			array( QuantityValue::newFromNumber( '+0.00', '1', '+0.01', '-0.01' ), 0.02 ),
			array( QuantityValue::newFromNumber( '+100', '1', '+101', '+99' ), 2 ),
			array( QuantityValue::newFromNumber( '+100.0', '1', '+100.1', '+99.9' ), 0.2 ),
			array( QuantityValue::newFromNumber( '+12.34', '1', '+12.35', '+12.33' ), 0.02 ),

			array( QuantityValue::newFromNumber( '+0', '1', '+0.2', '-0.6' ), 0.8 ),
			array( QuantityValue::newFromNumber( '+7.3', '1', '+7.7', '+5.2' ), 2.5 ),
		);
	}

	/**
	 * @dataProvider getUncertaintyMarginProvider
	 */
	public function testGetUncertaintyMargin( QuantityValue $quantity, $expected ) {
		$actual = $quantity->getUncertaintyMargin();

		$this->assertEquals( $expected, $actual->getValue() );
	}

	public function getUncertaintyMarginProvider() {
		return array(
			array( QuantityValue::newFromNumber( '+0', '1', '+1', '-1' ), '+1' ),
			array( QuantityValue::newFromNumber( '+0.00', '1', '+0.01', '-0.01' ), '+0.01' ),

			array( QuantityValue::newFromNumber( '-1', '1', '-1', '-1' ), '+0' ),

			array( QuantityValue::newFromNumber( '+0', '1', '+0.2', '-0.6' ), '+0.6' ),
			array( QuantityValue::newFromNumber( '+7.5', '1', '+7.5', '+5.5' ), '+2' ),
			array( QuantityValue::newFromNumber( '+11.5', '1', '+15', '+10.5' ), '+3.5' ),
		);
	}


	/**
	 * @dataProvider getSignificantDigitsProvider
	 */
	public function testGetSignificantDigits( QuantityValue $quantity, $expected ) {
		$actual = $quantity->getSignificantDigits();

		$this->assertEquals( $expected, $actual );
	}

	public function getSignificantDigitsProvider() {
		return array(
			0 => array( QuantityValue::newFromNumber( '+0' ), 1 ),
			1 => array( QuantityValue::newFromNumber( '-123' ), 3 ),
			2 => array( QuantityValue::newFromNumber( '-1.23' ), 4 ),

			10 => array( QuantityValue::newFromNumber( '-100', '1', '-99', '-101' ), 3 ),
			11 => array( QuantityValue::newFromNumber( '+0.00', '1', '+0.01', '-0.01' ), 4 ),
			12 => array( QuantityValue::newFromNumber( '-117.3', '1', '-117.2', '-117.4' ), 5 ),

			20 => array( QuantityValue::newFromNumber( '+100', '1', '+100.01', '+99.97' ), 6 ),
			21 => array( QuantityValue::newFromNumber( '-0.002', '1', '-0.001', '-0.004' ), 5 ),
			22 => array( QuantityValue::newFromNumber( '-0.002', '1', '+0.001', '-0.06' ), 5 ),
			23 => array( QuantityValue::newFromNumber( '-21', '1', '+1.1', '-120' ), 1 ),
			24 => array( QuantityValue::newFromNumber( '-2', '1', '+1.1', '-120' ), 1 ),
			25 => array( QuantityValue::newFromNumber( '+1000', '1', '+1100', '+900.03' ), 3 ),
			26 => array( QuantityValue::newFromNumber( '+1000', '1', '+1100', '+900' ), 2 ),
		);
	}

	/**
	 * @dataProvider getSignificantDigitsProviderOf
	 */
	public function testGetSignificantDigitsOf( QuantityValue $quantity, DecimalValue $value, $expected ) {
		$actual = $quantity->getSignificantDigitsOf( $value );

		$this->assertEquals( $expected, $actual );
	}

	public function getSignificantDigitsProviderOf() {
		return array(
			array( QuantityValue::newFromNumber( '+0' ), new DecimalValue( '+0' ), 1 ),
			array( QuantityValue::newFromNumber( '-123', '1', '-123', '-123' ), new DecimalValue( '+10' ), 2 ),
			array( QuantityValue::newFromNumber( '-123', '1', '-123', '-123' ), new DecimalValue( '+10.03' ), 2 ),
			array( QuantityValue::newFromNumber( '+123', '1', '+143', '+103' ), new DecimalValue( '+13.44' ), 1 ),
			array( QuantityValue::newFromNumber( '+12.31', '1', '+12.32', '+12.30' ), new DecimalValue( '+13.03' ), 5 ),
			array( QuantityValue::newFromNumber( '+12.31', '1', '+12.32', '+12.30' ), new DecimalValue( '+13' ), 2 ),
			array( QuantityValue::newFromNumber( '+12.31', '1', '+12.32', '+12.30' ), new DecimalValue( '+2213' ), 4 ),
		);
	}

	/**
	 * @dataProvider transformProvider
	 */
	public function testTransform( QuantityValue $quantity, $transformation, QuantityValue $expected ) {
		$args = func_get_args();
		$extraArgs = array_slice( $args, 3 );

		$call = array( $quantity, 'transform' );
		$callArgs = array_merge( array( 'x', $transformation ), $extraArgs );
		$actual = call_user_func_array( $call, $callArgs );

		$this->assertEquals( 'x', $actual->getUnit() );
		$this->assertEquals( $expected->getAmount()->getValue(), $actual->getAmount()->getValue(), 'value' );
		$this->assertEquals( $expected->getUpperBound()->getValue(), $actual->getUpperBound()->getValue(), 'upper bound' );
		$this->assertEquals( $expected->getLowerBound()->getValue(), $actual->getLowerBound()->getValue(), 'lower bound' );
	}

	public function transformProvider() {
		$identity = function ( DecimalValue $value ) {
			return $value;
		};

		$square = function ( DecimalValue $value ) {
			$v = $value->getValueFloat();
			return new DecimalValue( $v * $v * $v );
		};

		$scale = function ( DecimalValue $value, $factor ) {
			return new DecimalValue( $value->getValueFloat() * $factor );
		};

		return array(
			 0 => array( QuantityValue::newFromNumber( '+10',   '1', '+11',  '+9' ),   $identity, QuantityValue::newFromNumber(   '+10',    '?',   '+11',    '+9' ) ),
			 1 => array( QuantityValue::newFromNumber(  '-0.5', '1', '-0.4', '-0.6' ), $identity, QuantityValue::newFromNumber(    '-0.5',  '?',    '-0.4',  '-0.6' ) ),
			 2 => array( QuantityValue::newFromNumber(  '+0',   '1', '+1',   '-1' ),   $square,   QuantityValue::newFromNumber(    '+0',    '?',    '+1',    '-1' ) ),
			 3 => array( QuantityValue::newFromNumber( '+10',   '1', '+11',  '+9' ),   $square,   QuantityValue::newFromNumber( '+1000',    '?', '+1300',  '+730' ) ), // note how rounding applies to bounds
			 4 => array( QuantityValue::newFromNumber(  '+0.5', '1', '+0.6', '+0.4' ), $scale,    QuantityValue::newFromNumber(    '+0.25', '?',    '+0.3',  '+0.2' ), 0.5 ),

			// note: absolutely exact values require conversion with infinite precision!
			10 => array( QuantityValue::newFromNumber( '+100', '1', '+100',   '+100' ),    $scale, QuantityValue::newFromNumber( '+12825.0', '?', '+12825.0', '+12825.0' ), 128.25 ),

			11 => array( QuantityValue::newFromNumber( '+100', '1', '+110',    '+90' ),    $scale, QuantityValue::newFromNumber( '+330',    '?', '+370',    '+300' ), 3.3333 ),
			12 => array( QuantityValue::newFromNumber( '+100', '1', '+100.1',  '+99.9' ),  $scale, QuantityValue::newFromNumber( '+333.3',  '?', '+333.7',  '+333.0' ), 3.3333 ),
			13 => array( QuantityValue::newFromNumber( '+100', '1', '+100.01', '+99.99' ), $scale, QuantityValue::newFromNumber( '+333.33', '?', '+333.36', '+333.30' ), 3.3333 ),
		);
	}

}
