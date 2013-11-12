<?php

class MobileFormatterHTML extends MobileFormatter {
	protected $pageTransformStart = '<div>';
	protected $pageTransformEnd = '</div>';
	protected $headingTransformStart = '</div>';
	protected $headingTransformEnd = '<div>';
	/**
	 * Constructor
	 *
	 * @param string $html: Text to process
	 * @param Title $title: Title to which $html belongs
	 */
	public function __construct( $html, $title ) {
		parent::__construct( $html, $title );
	}

	public function getFormat() {
		return 'HTML';
	}

	protected function onHtmlReady( $html ) {
		wfProfileIn( __METHOD__ );
		if ( $this->expandableSections ) {
			$tagName = strrpos( $html, '<h1' ) !== false ? 'h1' : 'h2';
			$html = $this->headingTransform( $html, $tagName );
		}
		wfProfileOut( __METHOD__ );
		return $html;
	}
}
