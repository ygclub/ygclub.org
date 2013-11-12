<?php

namespace DataValues;

/**
 * Class for performing basic arithmetic and other transformations
 * on DecimalValues.
 *
 * @see DecimalValue
 *
 * @since 0.1
 *
 * @licence GNU GPL v2+
 * @author Daniel Kinzler
 */
class DecimalMath {

	/**
	 * Returns the product of the two values.
	 *
	 * @param DecimalValue $a
	 * @param DecimalValue $b
	 *
	 * @return DecimalValue
	 */
	public function product( DecimalValue $a, DecimalValue $b ) {
		//TODO: use bcmath if available
		$product = $a->getValueFloat() * $b->getValueFloat();

		return new DecimalValue( $product );
	}

	/**
	 * Returns the sum of the two values.
	 *
	 * @param DecimalValue $a
	 * @param DecimalValue $b
	 *
	 * @return DecimalValue
	 */
	public function sum( DecimalValue $a, DecimalValue $b ) {
		//TODO: use bcmath if available
		$product = $a->getValueFloat() + $b->getValueFloat();

		return new DecimalValue( $product );
	}

	/**
	 * Returns the given value, with any insignificant digits removed or zeroed.
	 * Rounding is applied  using the "round half away from zero" rule (that is, +0.5 is
	 * rounded to +1 and -0.5 is rounded to -1).
	 *
	 * @since 0.1
	 *
	 * @todo: change this (or provide an alternative) to work based on the exponent
	 * of the least significant digit, instead of its position. E.g. -1 would
	 * mean "the first digit after the decimal point", 0 would mean "the first
	 * digit before the decimal point", and so on.
	 *
	 * @param DecimalValue $decimal
	 * @param int $significantDigits
	 *
	 * @throws \InvalidArgumentException
	 * @return DecimalValue
	 */
	public function round( DecimalValue $decimal, $significantDigits ) {
		$value = $decimal->getValue();
		$rounded = $this->roundDigits( $value, $significantDigits );
		return new DecimalValue( $rounded );
	}

	/**
	 * Returns the given value, with any insignificant digits removed or zeroed.
	 * Rounding is applied using the "round half away from zero" rule (that is, +0.5 is
	 * rounded to +1 and -0.5 is rounded to -1).
	 *
	 * @see round()
	 *
	 * @param string $value
	 * @param int $significantDigits
	 *
	 * @throws \InvalidArgumentException
	 * @return string
	 */
	protected function roundDigits( $value, $significantDigits ) {
		if ( !is_int( $significantDigits ) ) {
			throw new \InvalidArgumentException( '$significantDigits must be an integer' );
		}

		if ( $significantDigits <= 0 ) {
			throw new \InvalidArgumentException( '$significantDigits must be larger than zero.' );
		}

		// whether the last character is already part of the integer part of the decimal value
		$inIntPart = ( strpos( $value, '.' ) === false );

		$rounded = '';

		// Iterate over characters from right to left and build the result back to front.
		for ( $i = strlen( $value ) -1; $i > 0 && $i > $significantDigits; $i-- ) {

			list( $value, $i, $inIntPart, $next ) = $this->roundNextDigit( $value, $i, $inIntPart );

			$rounded = $next . $rounded;
		}

		// just keep the remainder of the value as is (this includes the sign)
		$rounded = substr( $value, 0, $i +1 ) . $rounded;

		// strip trailing decimal point
		$rounded = rtrim( $rounded, '.' );
		return $rounded;
	}

	/**
	 * Extracts the next character to add to the result of a rounding run:
	 * $value[$] will be examined and processed in order to determine the next
	 * character to prepend to the result (returned in the $nextCharacter field).
	 *
	 * Updated values for the parameters are returned as well as the next
	 * character.
	 *
	 * @param string $value
	 * @param int $i
	 * @param bool $inIntPart
	 *
	 * @return array ( $value, $i, $inIntPart, $nextCharacter )
	 */
	private function roundNextDigit( $value, $i, $inIntPart ) {
		// next digit
		$ch = $value[$i];

		if ( $ch === '.' ) {
			// just transition from the fractional to the integer part
			$inIntPart = true;
			$nextCharacter = '.';
		} else {
			if ( $inIntPart ) {
				// in the integer part, zero out insignificant digits
				$nextCharacter = '0';
			} else {
				// in the fractional part, strip insignificant digits
				$nextCharacter = '';
			}

			if ( ord( $ch ) >= ord( '5' ) ) {
				// when stripping a character >= 5, bump up the next digit to the left.
				list( $value, $i, $inIntPart ) = $this->bumpDigitsForRounding( $value, $i, $inIntPart );
			}
		}

		return array( $value, $i, $inIntPart, $nextCharacter );
	}

	/**
	 * Bumps the last digit of a value that is being processed for rounding while taking
	 * care of edge cases and updating the state of the rounding process.
	 *
	 * - $value is truncated to $i digits, so we can safely increment (bump) the last digit.
	 * - if the last character of $value is '.', it's trimmed (and $inIntPart is set to true)
	 *   to handle the transition from the fractional to the integer part of $value.
	 * - the last digit of $value is bumped using bumpDigits() - this is where the magic happens.
	 * - $i is set to strln( $value ) to make the index consistent in case a trailing decimal
	 *   point got removed.
	 *
	 * Updated values for the parameters are returned.
	 * Note: when returning, $i is always one greater than the greatest valid index in $value.
	 *
	 * @param string $value
	 * @param int $i
	 * @param bool $inIntPart
	 *
	 * @return array ( $value, $i, $inIntPart, $next )
	 */
	private function bumpDigitsForRounding( $value, $i, $inIntPart ) {
		$remaining = substr( $value, 0, $i );

		// If there's a '.' at the end, strip it and note that we are in the
		// integer part of $value now.
		if ( $remaining[ strlen( $remaining ) -1 ] === '.' ) {
			$remaining = rtrim( $remaining, '.' );
			$inIntPart = true;
		}

		// Rounding may add digits, adjust $i for that.
		$value = $this->bumpDigits( $remaining );
		$i = strlen( $value );

		return array( $value, $i, $inIntPart );
	}

	/**
	 * Increment the least significant digit by one if it is less than 9, and
	 * set it to zero and continue to the next more significant digit if it is 9.
	 * Exception: bump( 0 ) == 1;
	 *
	 * E.g.: bump( 0.2 ) == 0.3, bump( -0.09 ) == -0.10, bump( 9.99 ) == 10.00
	 *
	 * This is the inverse of @see slump()
	 *
	 * @since 0.1
	 *
	 * @param DecimalValue $decimal
	 *
	 * @return DecimalValue
	 */
	public function bump( DecimalValue $decimal ) {
		$value = $decimal->getValue();
		$bumped = $this->bumpDigits( $value );
		return new DecimalValue( $bumped );
	}

	/**
	 * Increment the least significant digit by one if it is less than 9, and
	 * set it to zero and continue to the next more significant digit if it is 9.
	 *
	 * @see bump()
	 *
	 * @param string $value
	 * @return string
	 */
	protected function bumpDigits( $value ) {
		if ( $value === '+0' ) {
			return '+1';
		}

		$bumped = '';

		for ( $i = strlen( $value ) -1; $i >= 0; $i-- ) {
			$ch = $value[$i];

			if ( $ch === '.' ) {
				$bumped = '.' . $bumped;
				continue;
			} elseif ( $ch === '9' ) {
				$bumped = '0' . $bumped;
				continue;
			} elseif ( $ch === '+' || $ch === '-' ) {
				$bumped = $ch . '1' . $bumped;
				break;
			} else {
				$bumped =  chr( ord( $ch ) + 1 ) . $bumped;
				break;
			}
		}

		$bumped = substr( $value, 0, $i ) . $bumped;
		return $bumped;
	}

	/**
	 * Decrement the least significant digit by one if it is more than 0, and
	 * set it to 9 and continue to the next more significant digit if it is 0.
	 * Exception: slump( 0 ) == -1;
	 *
	 * E.g.: slump( 0.2 ) == 0.1, slump( -0.10 ) == -0.01, slump( 0.0 ) == -1.0
	 *
	 * This is the inverse of @see bump()
	 *
	 * @since 0.1
	 *
	 * @param DecimalValue $decimal
	 *
	 * @return DecimalValue
	 */
	public function slump( DecimalValue $decimal ) {
		$value = $decimal->getValue();
		$slumped = $this->slumpDigits( $value );
		return new DecimalValue( $slumped );
	}

	/**
	 * Decrement the least significant digit by one if it is more than 0, and
	 * set it to 9 and continue to the next more significant digit if it is 0.
	 *
	 * @see slump()
	 *
	 * @param string $value
	 * @return string
	 */
	protected function slumpDigits( $value ) {
		if ( $value === '+0' ) {
			return '-1';
		}

		// a "precise zero" will become negative
		if ( preg_match( '/^\+0\.(0*)0$/', $value, $m ) ) {
			return '-0.' . $m[1] . '1';
		}

		$slumped = '';

		for ( $i = strlen( $value ) -1; $i >= 0; $i-- ) {
			$ch = substr( $value, $i, 1 );

			if ( $ch === '.' ) {
				$slumped = '.' . $slumped;
				continue;
			} elseif ( $ch === '0' ) {
				$slumped = '9' . $slumped;
				continue;
			} elseif ( $ch === '+' || $ch === '-' ) {
				$slumped = '0';
				break;
			} else {
				$slumped =  chr( ord( $ch ) - 1 ) . $slumped;
				break;
			}
		}

		// preserve prefix
		$slumped = substr( $value, 0, $i ) . $slumped;

		// strip leading zeros
		$slumped = preg_replace( '/^([-+])(0+)([0-9]+(\.|$))/', '\1\3', $slumped );

		if ( $slumped === '-0' ) {
			$slumped = '+0';
		}

		return $slumped;
	}

}