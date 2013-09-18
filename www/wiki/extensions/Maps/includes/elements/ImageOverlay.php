<?php

namespace Maps;

use DataValues\GeoCoordinateValue;

/**
 * Class representing an image overlay.
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
 * @since 3.0
 *
 * @ingroup Maps
 *
 * @licence GNU GPL v2+
 * @author Kim Eik < kim@heldig.org >
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ImageOverlay extends Rectangle {

	/**
	 * @since 3.0
	 *
	 * @var string
	 */
	protected $image;

	/**
	 * Constructor.
	 *
	 * @since 3.0
	 *
	 * @param GeoCoordinateValue $boundsNorthEast
	 * @param GeoCoordinateValue $boundsSouthWest
	 * @param string $image
	 */
	public function __construct( GeoCoordinateValue $boundsNorthEast, GeoCoordinateValue $boundsSouthWest, $image ) {
		parent::__construct( $boundsNorthEast, $boundsSouthWest );
		$this->image = $image;
	}

	/**
	 * @since 3.0
	 *
	 * @return string
	 */
	public function getImage() {
		return $this->image;
	}

}
