<?php

class SpecialMobileOptions extends UnlistedSpecialPage {
	/**
	 * @var Title
	 */
	private $returnToTitle;
	private $subpage;
	private $options = array(
		'BetaOptIn' => array( 'get' => 'betaOptInGet', 'post' => 'betaOptInPost' ),
		'BetaOptOut' => array( 'get' => 'betaOptOutGet', 'post' => 'betaOptOutPost' ),
		'Language' => array( 'get' => 'chooseLanguage' ),
		'EnableImages' => array( 'get' => 'enableImages' ),
		'DisableImages' => array( 'get' => 'disableImages' )
	);

	public function __construct() {
		parent::__construct( 'MobileOptions' );
	}

	public function execute( $par = '' ) {
		$context = MobileContext::singleton();
		$out = $this->getOutput();

		$this->returnToTitle = Title::newFromText( $this->getRequest()->getText( 'returnto' ) );
		if ( !$this->returnToTitle ) {
			$this->returnToTitle = Title::newMainPage();
		}

		$this->setHeaders();
		$context->setForceMobileView( true );
		$context->setContentTransformations( false );
		if( $par == '' ) {
			if ( $this->getRequest()->wasPosted() ) {
				$this->submitSettingsForm();
			} else {
				$this->getSettingsForm();
			}
			return;
		} elseif ( !isset( $this->options[$par] ) ) {
			$out->showErrorPage( 'error', 'mobile-frontend-unknown-option', array( $par ) );
			return;
		}

		$this->subpage = $par;
		$option = $this->options[$par];

		if ( $this->getRequest()->wasPosted() && isset( $option['post'] ) ) {
			$func = $option['post'];
		} else {
			$func = $option['get'];
		}
		$this->$func();
	}

	private function getSettingsForm() {
		$out = $this->getOutput();
		$context = MobileContext::singleton();

		$out->setPageTitle( $this->msg( 'mobile-frontend-main-menu-settings-heading' ) );

		if ( $this->getRequest()->getCheck( 'success' ) ) {
			$out->wrapWikiMsg(
				"<div class=\"successbox\"><strong>\n$1\n</strong></div><div id=\"mw-pref-clear\"></div>",
				'savedprefs'
			);
		}

		$imagesChecked = $context->imagesDisabled() ? '' : 'checked'; // images are off when disabled
		$imagesBeta = $context->isBetaGroupMember() ? 'checked' : '';
		$disableMsg = $this->msg( 'mobile-frontend-images-status' )->parse();
		$betaEnableMsg = $this->msg( 'mobile-frontend-settings-beta' )->parse();
		$betaDescriptionMsg = $this->msg( 'mobile-frontend-opt-in-explain' )->parse();

		$saveSettings = $this->msg( 'mobile-frontend-save-settings' )->escaped();
		$onoff = '<span class="mw-mf-settings-on">' . $this->msg( 'mobile-frontend-on' )->escaped() . '</span><span class="mw-mf-settings-off">' .
			$this->msg( 'mobile-frontend-off' )->escaped() .'</span>';
		$action = $this->getTitle()->getLocalURL();
		$html = Html::openElement( 'form',
			array( 'class' => 'mw-mf-settings', 'method' => 'POST', 'action' => $action )
		);
		$aboutMessage = $this->msg( 'mobile-frontend-settings-description' )->parse();
		$token = Html::hidden( 'token', $context->getMobileToken() );
		$returnto = Html::hidden( 'returnto', $this->returnToTitle->getFullText() );

		$html .= <<<HTML
	<p>
		{$aboutMessage}
	</p>
	<ul>
		<li>
			{$disableMsg}
			<span class="mw-mf-checkbox-css3">
				<input type="checkbox" name="enableImages"
				{$imagesChecked}>{$onoff}
			</span>
		</li>
		<li>
			{$betaEnableMsg}
			<div class="mw-mf-checkbox-css3">
				<input type="checkbox" name="enableBeta"
				{$imagesBeta}>{$onoff}
			</div>
		</li>
		<li class="mw-mf-settings-description">
				{$betaDescriptionMsg}
		</li>
		<li>
			<input type="submit" id="mw-mf-settings-save" value="{$saveSettings}">
		</li>
	</ul>
	$token
	$returnto
</form>
HTML;
		$out->addHTML( $html );
	}

	private function getSiteSelector() {
		global $wgLanguageCode;

		wfProfileIn( __METHOD__ );
		$selector = '';
		$count = 0;
		$language = $this->getLanguage();
		foreach ( Interwiki::getAllPrefixes( true ) as $interwiki ) {
			$code = $interwiki['iw_prefix'];
			$name = $language->fetchLanguageName( $code );
			if ( !$name ) {
				continue;
			}
			$title = Title::newFromText( "$code:" );
			if ( $title ) {
				$url = $title->getFullURL();
			} else {
				$url = '';
			}
			$attrs = array( 'href' => $url );
			$count++;
			if( $code == $wgLanguageCode ) {
				$attrs['class'] = 'selected';
			}
			$selector .= Html::openElement( 'li' );
			$selector .= Html::element( 'a', $attrs, $name );
			$selector .= Html::closeElement( 'li' );
		}

		if ( $selector && $count > 1 ) {
			$selector = <<<HTML
			<p>{$this->msg( 'mobile-frontend-settings-site-description', $count )->parse()}</p>
			<ul id='mw-mf-language-list'>
				{$selector}
			</ul>
HTML;
		}
		wfProfileOut( __METHOD__ );
		return $selector;
	}

	private function chooseLanguage() {
		$out = $this->getOutput();
		$out->setPageTitle( $this->msg( 'mobile-frontend-settings-site-header' )->escaped() );
		$out->addHTML( $this->getSiteSelector() );
	}

	private function submitSettingsForm() {
		$context = MobileContext::singleton();
		$request = $this->getRequest();

		if ( $request->getVal( 'token' ) != $context->getMobileToken() ) {
			wfDebug( __METHOD__ . "(): token mismatch\n" );
			//return; // Display something here?
		}
		$inBeta = $request->getBool( 'enableBeta' );
		$imagesDisabled = !$request->getBool( 'enableImages' );
		$context->setDisableImagesCookie( $imagesDisabled );
		$context->setOptInOutCookie( $inBeta ? '1' : '' );
		$context->setBetaGroupMember( $inBeta );

		$returnToTitle = Title::newFromText( $request->getText( 'returnto' ) );
		if ( $returnToTitle ) {
			$url = $returnToTitle->getFullURL();
		} else {
			$url = $this->getTitle()->getFullURL( 'success' );
		}
		$context->getOutput()->redirect( MobileContext::singleton()->getMobileUrl( $url ) );
	}

	public static function getURL( $option, Title $returnTo = null, $fullUrl = false ) {
		$t = SpecialPage::getTitleFor( 'MobileOptions', $option );
		$params = array();
		if ( $returnTo ) {
			$params['returnto'] = $returnTo->getPrefixedText();
		}
		if ( $fullUrl ) {
			return MobileContext::singleton()->getMobileUrl( $t->getFullURL( $params ) );
		} else {
			return $t->getLocalURL( $params );
		}
	}

	private function showEnquiryForm( $headingMsg, $textMsg, $yesButtonMsg, $noButtonMsg ) {
		$out = $this->getOutput();
		$out->setPageTitle( $this->msg( $headingMsg ) );

		$out->addHTML( '
			<p>' . $this->msg( $textMsg )->parse() . '</p>
			<div id="disableButtons">'
				. Html::openElement( 'form',
					array(
							'method' => 'POST',
							'action' => $this->getTitle( $this->subpage )->getLocalURL(),
						)
					)
				. Html::element( 'input',
					array(
						'name' => 'returnto',
						'type' => 'hidden',
						'value' => $this->returnToTitle->getFullText(),
					)
				)
				. Html::element( 'button',
					array(
							'id' => 'disableButton',
							'type' => 'submit',
						),
					$this->msg( $yesButtonMsg )->text()
					)
				. Html::closeElement( 'form' )
				. Html::openElement( 'form',
					array(
							'method' => 'GET',
							'action' => $this->returnToTitle->getLocalURL(),
						)
					)
				. Html::element( 'button',
					array(
							'id' => 'backButton',
							'type' => 'submit',
						),
					$this->msg( $noButtonMsg )->text()
					)
				. Html::closeElement( 'form' )
			. '</div>'
		);
	}

	private function doReturnTo() {
		$params = array();
		$params['t'] = md5( time() ); // this is a hack to get around the 304 response and local browser cache
		$this->getOutput()->sendCacheControl(); // cache should already be private
		$this->getRequest()->response()->header( 'Location: '
			. MobileContext::singleton()->getMobileUrl( wfExpandUrl( $this->returnToTitle->getFullURL( $params ) ) ) );
		exit;
	}

	private function betaOptInGet() {
		$this->showEnquiryForm(
			'mobile-frontend-opt-in-message',
			'mobile-frontend-opt-in-explain',
			'mobile-frontend-opt-in-yes-button',
			'mobile-frontend-opt-in-no-button'
		);
	}

	private function betaOptInPost() {
		MobileContext::singleton()->setOptInOutCookie( true );
		$this->doReturnTo();
	}

	private function betaOptOutGet() {
		$this->showEnquiryForm(
			'mobile-frontend-opt-out-message',
			'mobile-frontend-opt-out-explain',
			'mobile-frontend-opt-out-yes-button',
			'mobile-frontend-opt-out-no-button'
		);
	}

	private function betaOptOutPost() {
		MobileContext::singleton()->setOptInOutCookie( false );
		$this->doReturnTo();
	}

	private function enableImages( $enable = true ) {
		MobileContext::singleton()->setDisableImagesCookie( !$enable );
		$this->doReturnTo();
	}

	private function disableImages() {
		$this->enableImages( false );
	}
}
