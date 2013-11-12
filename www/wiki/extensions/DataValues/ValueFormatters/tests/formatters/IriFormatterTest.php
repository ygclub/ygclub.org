<?php

namespace ValueFormatters\Test;

use DataValues\IriValue;
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
class IriFormatterTest extends ValueFormatterTestBase {

	/**
	 * @see ValueFormatterTestBase::validProvider
	 *
	 * @since 0.1
	 *
	 * @return array
	 */
	public function validProvider() {
		$argLists = array();

		$argLists[] = array(
			new IriValue(
				'http',
				'//www.wikidata.org'
			),
			'http://www.wikidata.org'
		);

		$argLists[] = array(
			new IriValue(
				'http',
				'//www.wikidata.org',
				'type=animal&name=narwhal'
			),
			'http://www.wikidata.org?type=animal&name=narwhal'
		);

		$argLists[] = array(
			new IriValue(
				'http',
				'//www.wikidata.org',
				'type=animal&name=narwhal',
				'headerSection'
			),
			'http://www.wikidata.org?type=animal&name=narwhal#headerSection'
		);

		$argLists[] = array(
			new IriValue(
				'http',
				'//www.wikidata.org',
				'',
				'headerSection'
			),
			'http://www.wikidata.org#headerSection'
		);

		$argLists[] = array(
			new IriValue(
				'irc',
				'//en.wikipedia.org',
				'',
				''
			),
			'irc://en.wikipedia.org'
		);

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
		return 'ValueFormatters\IriFormatter';
	}

}
