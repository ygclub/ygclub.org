<?php

namespace ValueParsers\Test;

/**
 * Unit tests for the ValueParsers\ApiParseValue class.
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
 * @group ApiParseValue
 *
 * @group medium
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ApiParseValueTest extends \ApiTestCase {

	public function testStuff() {
		$params = array(
			'action' => 'parsevalue',
			'parser' => 'geocoordinate',
			'values' => '4,2|0,0',
		);

		list( $resultArray, ) = $this->doApiRequest( $params );

		$this->assertInternalType( 'array', $resultArray, 'top level element is an array' );
		$this->assertArrayHasKey( 'results', $resultArray, 'top level element has a results key' );

		foreach ( $resultArray['results'] as $result ) {
			$this->assertInternalType( 'array', $result, 'result is an array' );

			$this->assertArrayHasKey( 'value', $result, 'result has a value key' );
			$this->assertArrayHasKey( 'raw', $result, 'result has a raw key' );
			$this->assertArrayHasKey( 'type', $result, 'result has a type key' );

			$value = $result['value'];

			$this->assertInternalType( 'array', $value, 'value key points to an array' );

			$this->assertArrayHasKey( 'latitude', $value, 'value has latitude key' );
			$this->assertArrayHasKey( 'longitude', $value, 'value has longitude key' );
			$this->assertArrayHasKey( 'altitude', $value, 'value has altitude key' );
			$this->assertArrayHasKey( 'globe', $value, 'value has globe key' );
		}
	}

}
