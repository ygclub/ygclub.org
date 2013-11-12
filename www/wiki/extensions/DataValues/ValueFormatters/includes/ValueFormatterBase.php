<?php

namespace ValueFormatters;

/**
 * Base class for ValueFormatters.
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
 * @since 0.1
 *
 * @file
 * @ingroup ValueFormatters
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
abstract class ValueFormatterBase implements ValueFormatter {

	/**
	 * @since 0.1
	 *
	 * @var FormatterOptions
	 */
	protected $options;

	/**
	 * @since 0.1
	 *
	 * @param FormatterOptions $options
	 */
	public function __construct( FormatterOptions $options ) {
		$this->options = $options;

		$this->options->defaultOption( ValueFormatter::OPT_LANG, 'en' );
	}

	/**
	 * Shortcut to $this->options->getOption.
	 *
	 * @since 0.1
	 *
	 * @param string $option
	 */
	protected final function getOption( $option ) {
		return $this->options->getOption( $option );
	}

	/**
	 * Shortcut to $this->options->requireOption.
	 *
	 * @param string $option
	 */
	protected final function requireOption( $option ) {
		$this->options->requireOption( $option );
	}

	/**
	 * Shortcut to $this->options->defaultOption.
	 *
	 * @since 0.1
	 *
	 * @param string $option
	 * @param mixed $default
	 */
	protected final function defaultOption( $option, $default ) {
		$this->options->defaultOption( $option, $default );
	}

}
