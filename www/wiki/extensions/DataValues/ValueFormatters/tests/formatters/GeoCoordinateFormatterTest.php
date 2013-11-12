<?php

namespace ValueFormatters\Test;

use DataValues\GeoCoordinateValue;
use ValueFormatters\GeoCoordinateFormatter;

/**
 * Unit tests for the ValueFormatters\GeoCoordinateFormatter class.
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
class GeoCoordinateFormatterTest extends ValueFormatterTestBase {

	/**
	 * @see ValueFormatterTestBase::validProvider
	 *
	 * @since 0.1
	 *
	 * @return array
	 */
	public function validProvider() {
		$floats = array(
			'55.755786, -37.617633' => array( 55.755786, -37.617633 ),
			'-55.755786, 37.617633' => array( -55.755786, 37.617633 ),
			'-55, -37.617633' => array( -55, -37.617633 ),
			'5.5, 37' => array( 5.5, 37 ),
			'0, 0' => array( 0, 0 ),
		);

		$decimalDegrees = array(
			'55.755786°, 37.617633°' => array( 55.755786, 37.617633 ),
			'55.755786°, -37.617633°' => array( 55.755786, -37.617633 ),
			'-55°, -37.617633°' => array( -55, -37.617633 ),
			'-5.5°, -37°' => array( -5.5, -37 ),
			'0°, 0°' => array( 0, 0 ),
		);

		$dmsCoordinates = array(
			'55° 45\' 20.8296", 37° 37\' 3.4788"' => array( 55.755786, 37.617633 ),
			'55° 45\' 20.8296", -37° 37\' 3.4788"' => array( 55.755786, -37.617633 ),
			'-55° 45\' 20.8296", -37° 37\' 3.4788"' => array( -55.755786, -37.617633 ),
			'-55° 45\' 20.8296", 37° 37\' 3.4788"' => array( -55.755786, 37.617633 ),

			'55° 0\' 0", 37° 0\' 0"' => array( 55, 37 ),
			'55° 30\' 0", 37° 30\' 0"' => array( 55.5, 37.5 ),
			'55° 0\' 18", 37° 0\' 18"' => array( 55.005, 37.005 ),
			'0° 0\' 0", 0° 0\' 0"' => array( 0, 0 ),
			'0° 0\' 18", 0° 0\' 18"' => array( 0.005, 0.005 ),
			'-0° 0\' 18", -0° 0\' 18"' => array( -0.005, -0.005 ),
		);

		$dmCoordinates = array(
			'55° 0\', 37° 0\'' => array( 55, 37 ),
			'55° 30\', 37° 30\'' => array( 55.5, 37.5 ),
			'0° 0\', 0° 0\'' => array( 0, 0 ),
			'-55° 30\', -37° 30\'' => array( -55.5, -37.5 ),
			'-0° 0.3\', -0° 0.3\'' => array( -0.005, -0.005 ),
		);

		$argLists = array();

		$tests = array(
			GeoCoordinateFormatter::TYPE_FLOAT => $floats,
			GeoCoordinateFormatter::TYPE_DD => $decimalDegrees,
			GeoCoordinateFormatter::TYPE_DMS => $dmsCoordinates,
			GeoCoordinateFormatter::TYPE_DM => $dmCoordinates,
		);

		foreach ( $tests as $format => $coords ) {
			foreach ( $coords as $expected => $arguments ) {
				$options = new \ValueFormatters\FormatterOptions();
				$options->setOption( GeoCoordinateFormatter::OPT_FORMAT, $format );
				$argLists[] = array( new GeoCoordinateValue( $arguments[0], $arguments[1] ), $expected, $options );
			}
		}

		return $argLists;
	}

	/**
	 * @see ValueFormatterTestBase::getFormatterClass
	 *
	 * @since 0.1
	 *
	 * @return string
	 */
	protected function getFormatterClass() {
		return 'ValueFormatters\GeoCoordinateFormatter';
	}

}
