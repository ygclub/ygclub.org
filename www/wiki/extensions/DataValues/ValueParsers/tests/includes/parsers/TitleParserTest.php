<?php

namespace ValueParsers\Test;

use DataValues\MediaWikiTitleValue;

/**
 * Unit test TitleParser class.
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
class TitleParserTest extends StringValueParserTest {

	/**
	 * @see ValueParserTestBase::validInputProvider
	 *
	 * @since 0.1
	 *
	 * @return array
	 */
	public function validInputProvider() {
		$argLists = array();

		$valid = array(
			'Foo bar',
			'Ohi there!',
		);

		foreach ( $valid as $value ) {
			$argLists[] = array( $value, new MediaWikiTitleValue( \Title::newFromText( $value ) ) );
		}

		return $argLists;
	}

	/**
	 * @see ValueParserTestBase::getParserClass
	 * @since 0.1
	 * @return string
	 */
	protected function getParserClass() {
		return 'ValueParsers\TitleParser';
	}

}
