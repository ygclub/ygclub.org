<?php

namespace ValueParsers;

use ApiBase;
use DataValues\DataValue;
use LogicException;
use MWException;

/**
 * API module for using value parsers.
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
 * @ingroup ValueParsers
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ApiParseValue extends ApiBase {

	/**
	 * @var null|ValueParserFactory
	 */
	protected $factory = null;

	/**
	 * @since 0.1
	 *
	 * @return ValueParserFactory
	 */
	protected function getFactory() {
		if ( $this->factory === null ) {
			$this->factory = new ValueParserFactory( $GLOBALS['wgValueParsers'] );
		}

		return $this->factory;
	}

	/**
	 * @see ApiBase::execute
	 *
	 * @since 0.1
	 */
	public function execute() {
		$parser = $this->getParser();

		$results = array();

		$params = $this->extractRequestParams();

		foreach ( $params['values'] as $value ) {
			$results[] = $this->parseValue( $parser, $value );
		}

		$this->outputResults( $results );
	}

	/**
	 * @return ValueParser
	 * @throws LogicException
	 */
	private function getParser() {
		$params = $this->extractRequestParams();

		$options = $this->getOptionsObject( $params['options'] );
		$parser = $this->getFactory()->newParser( $params['parser'], $options );

		// Paranoid check, should never fail as we only accept registered parsers for the parser parameter.
		if ( $parser === null ) {
			throw new LogicException( 'Could not obtain a ValueParser instance' );
		}

		return $parser;
	}

	private function parseValue( ValueParser $parser, $value ) {
		$result = array(
			'raw' => $value
		);

		try {
			$parseResult = $parser->parse( $value );
		}
		catch ( ParseException $parsingError ) {
			$result['error'] = $parsingError->getMessage();
			return $result;
		}

		if ( $parseResult instanceof DataValue ) {
			$result['value'] = $parseResult->getArrayValue();
			$result['type'] = $parseResult->getType();
		}
		else {
			$result['value'] = $parseResult;
		}

		return $result;
	}

	private function outputResults( array $results ) {
		$this->getResult()->setIndexedTagName( $results, 'result' );

		$this->getResult()->addValue(
			null,
			'results',
			$results
		);
	}

	/**
	 * @since 0.1
	 *
	 * @param string $optionsParam
	 *
	 * @return ParserOptions
	 */
	protected function getOptionsObject( $optionsParam ) {
		$parserOptions = new ParserOptions();
		$parserOptions->setOption( ValueParser::OPT_LANG, $this->getLanguage()->getCode() );

		$options = \FormatJson::decode( $optionsParam, true );

		if ( is_array( $options ) ) {
			foreach ( $options as $name => $value ) {
				$parserOptions->setOption( $name, $value );
			}
		}

		return $parserOptions;
	}

	/**
	 * @see ApiBase::getAllowedParams
	 *
	 * @since 0.1
	 *
	 * @return array
	 */
	public function getAllowedParams() {
		return array(
			'parser' => array(
				ApiBase::PARAM_TYPE => $this->getFactory()->getParserIds(),
				ApiBase::PARAM_REQUIRED => true,
			),
			'values' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true,
				ApiBase::PARAM_ISMULTI => true,
			),
			'options' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => false,
			),
		);
	}

	/**
	 * @see ApiBase::getParamDescription
	 *
	 * @since 0.1
	 *
	 * @return array
	 */
	public function getParamDescription() {
		return array(
			'parser' => 'Id of the ValueParser to use',
			'values' => 'The values to parse',
			'options' => 'The options the parser should use. Provided as a JSON object.',
		);
	}

	/**
	 * @see ApiBase::getDescription
	 *
	 * @since 0.1
	 *
	 * @return string
	 */
	public function getDescription() {
		return array(
			'API module for parsing values using a ValueParser.'
		);
	}

	/**
	 * @see ApiBase::getExamples
	 *
	 * @since 0.1
	 *
	 * @return array
	 */
	protected function getExamples() {
		return array(
			// 'ex' => 'desc' // TODO
		);
	}

	/**
	 * @see ApiBase::getHelpUrls
	 *
	 * @since 0.1
	 *
	 * @return string
	 */
	public function getHelpUrls() {
		return ''; // TODO
	}

	/**
	 * @see ApiBase::getVersion
	 *
	 * @since 0.1
	 *
	 * @return string
	 */
	public function getVersion() {
		return __CLASS__ . '-' . ValueParsers_VERSION;
	}

}
