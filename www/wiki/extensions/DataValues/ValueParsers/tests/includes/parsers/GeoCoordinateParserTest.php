<?php

namespace ValueParsers\Test;

use DataValues\GeoCoordinateValue;
use ValueParsers\GeoCoordinateParser;

/**
 * Unit tests for the GeoCoordinateValue class.
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
 * @group GeoCoordinateParserTest
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class GeoCoordinateParserTest extends StringValueParserTest {

	/**
	 * @see ValueParserTestBase::validInputProvider
	 *
	 * @since 0.1
	 *
	 * @return array
	 */
	public function validInputProvider() {
		$argLists = array();

		// TODO: test with different parser options

		$valid = array(
			// Float
			'55.7557860 N, 37.6176330 W' => array( 55.7557860, -37.6176330 ),
			'55.7557860, -37.6176330' => array( 55.7557860, -37.6176330 ),
			'55 S, 37.6176330 W' => array( -55, -37.6176330 ),
			'-55, -37.6176330' => array( -55, -37.6176330 ),
			'5.5S,37W ' => array( -5.5, -37 ),
			'-5.5,-37 ' => array( -5.5, -37 ),
			'4,2' => array( 4, 2 ),

			// DD
			'55.7557860° N, 37.6176330° W' => array( 55.7557860, -37.6176330 ),
			'55.7557860°, -37.6176330°' => array( 55.7557860, -37.6176330 ),
			'55° S, 37.6176330 ° W' => array( -55, -37.6176330 ),
			'-55°, -37.6176330 °' => array( -55, -37.6176330 ),
			'5.5°S,37°W ' => array( -5.5, -37 ),
			'-5.5°,-37° ' => array( -5.5, -37 ),

			// DMS
			'55° 45\' 20.8296", 37° 37\' 3.4788"' => array( 55.755786, 37.617633 ),
			'55° 45\' 20.8296", -37° 37\' 3.4788"' => array( 55.755786, -37.617633 ),
			'-55° 45\' 20.8296", -37° 37\' 3.4788"' => array( -55.755786, -37.617633 ),
			'-55° 45\' 20.8296", 37° 37\' 3.4788"' => array( -55.755786, 37.617633 ),
			'55° 0\' 0", 37° 0\' 0"' => array( 55, 37 ),
			'55° 30\' 0", 37° 30\' 0"' => array( 55.5, 37.5 ),
			'55° 0\' 18", 37° 0\' 18"' => array( 55.005, 37.005 ),
			'0° 0\' 0", 0° 0\' 0"' => array( 0, 0 ),
			'0° 0\' 18" N, 0° 0\' 18" E' => array( 0.005, 0.005 ),
			' 0° 0\' 18" S  , 0°  0\' 18"  W ' => array( -0.005, -0.005 ),

			// DM
			'55° 0\', 37° 0\'' => array( 55, 37 ),
			'55° 30\', 37° 30\'' => array( 55.5, 37.5 ),
			'0° 0\', 0° 0\'' => array( 0, 0 ),
			'-55° 30\', -37° 30\'' => array( -55.5, -37.5 ),
			'0° 0.3\' S, 0° 0.3\' W' => array( -0.005, -0.005 ),
		);

		foreach ( $valid as $value => $expected ) {
			$expected = new GeoCoordinateValue( $expected[0], $expected[1] );
			$argLists[] = array( (string)$value, $expected );
		}

		return $argLists;
	}

	public function invalidInputProvider() {
		$argLists = parent::invalidInputProvider();

		$invalid = array(
			'~=[,,_,,]:3',
			'ohi there',
		);

		foreach ( $invalid as $value ) {
			$argLists[] = array( $value );
		}

		return $argLists;
	}

	/**
	 * @see ValueParserTestBase::getParserClass
	 *
	 * @since 0.1
	 *
	 * @return string
	 */
	protected function getParserClass() {
		return 'ValueParsers\GeoCoordinateParser';
	}

}
