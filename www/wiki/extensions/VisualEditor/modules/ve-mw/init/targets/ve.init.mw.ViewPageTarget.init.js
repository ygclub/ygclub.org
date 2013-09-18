/*!
 * VisualEditor MediaWiki ViewPageTarget init.
 *
 * This file must remain as widely compatible as the base compatibility
 * for MediaWiki itself (see mediawiki/core:/resources/startup.js).
 * Avoid use of: ES5, SVG, HTML5 DOM, ContentEditable etc.
 *
 * @copyright 2011-2013 VisualEditor Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */

/*global mw */

/**
 * Platform preparation for the MediaWiki view page. This loads (when user needs it) the
 * actual MediaWiki integration and VisualEditor library.
 *
 * @class ve.init.mw.ViewPageTarget.init
 * @singleton
 */
( function () {
	var conf, uri, pageExists, viewUri, veEditUri, isViewPage,
		init, isBlacklisted, getTargetDeferred;

	/**
	 * Use deferreds to avoid loading and instantiating Target multiple times.
	 * @return {jQuery.Promise}
	 */
	function getTarget() {
		var loadTargetDeferred;
		if ( !getTargetDeferred ) {
			getTargetDeferred = $.Deferred();
			loadTargetDeferred = $.Deferred()
				.done( function () {
					var target = new ve.init.mw.ViewPageTarget();
					ve.init.mw.targets.push( target );

					// Transfer methods
					ve.init.mw.ViewPageTarget.prototype.setupSectionEditLinks = init.setupSectionEditLinks;

					getTargetDeferred.resolve( target );
				} )
				.fail( getTargetDeferred.reject );

			mw.loader.using( 'ext.visualEditor.viewPageTarget', loadTargetDeferred.resolve, loadTargetDeferred.reject );
		}
		return getTargetDeferred.promise();
	}

	conf = mw.config.get( 'wgVisualEditorConfig' );
	uri = new mw.Uri();
	pageExists = !!mw.config.get( 'wgArticleId' );
	viewUri = new mw.Uri( mw.util.wikiGetlink( mw.config.get( 'wgRelevantPageName' ) ) );
	veEditUri = viewUri.clone().extend( { 'veaction': 'edit' } );
	isViewPage = (
		mw.config.get( 'wgAction' ) === 'view' &&
		!( 'diff' in uri.query )
	);


	init = {

		support: {
			es5: (
				// It would be much easier to do a quick inline function that asserts "use strict"
				// works, but since IE9 doesn't support strict mode (and we don't use strict mode) we
				// have to instead list all the ES5 features we do use.
				Array.isArray &&
				Array.prototype.filter &&
				Array.prototype.indexOf &&
				Array.prototype.map &&
				Date.prototype.toJSON &&
				Function.prototype.bind &&
				Object.create &&
				Object.keys &&
				String.prototype.trim &&
				window.JSON &&
				JSON.parse &&
				JSON.stringify
			),
			contentEditable: 'contentEditable' in document.createElement( 'div' )
		},

		blacklist: {
			// IE <= 8 has various incompatibilities in layout and feature support
			// IE9 and IE10 generally work but fail in ajax handling when making POST
			// requests to the VisualEditor/Parsoid API which is causing silent failures
			// when trying to save a page (bug 49187)
			'msie': [['<=', 10]],
			// Android 2.x and below "support" CE but don't trigger keyboard input
			'android': [['<', 3]],
			// Firefox issues in versions 12 and below (bug 50780)
			// Wikilink [[./]] bug in Firefox 14 and below (bug 50720)
			'firefox': [['<=', 14]],
			// Blacklist all versions:
			'opera': null,
			'blackberry': null
		},

		skinSetup: function () {
			init.setupTabLayout();
			init.setupSectionEditLinks();
		},

		setupTabLayout: function () {
			var caVeEdit, caVeEditSource,
				action = pageExists ? 'edit' : 'create',
				pTabsId = $( '#p-views' ).length ? 'p-views' : 'p-cactions',
				$caSource = $( '#ca-viewsource' ),
				$caEdit = $( '#ca-edit' ),
				$caEditLink = $caEdit.find( 'a' ),
				reverseTabOrder = $( 'body' ).hasClass( 'rtl' ) && pTabsId === 'p-views',
				caVeEditNextnode = reverseTabOrder ? $caEdit.get( 0 ) : $caEdit.next().get( 0 );

			if ( !$caEdit.length || $caSource.length ) {
				// If there is no edit tab or a view-source tab,
				// the user doesn't have permission to edit.
				return;
			}

			// Add independent "VisualEditor" tab (#ca-ve-edit).
			if ( conf.tabLayout === 'add' ) {

				caVeEdit = mw.util.addPortletLink(
					pTabsId,
					// Use url instead of '#'.
					// So that 1) one can always open it in a new tab, even when
					// onEditTabClick is bound.
					// 2) when onEditTabClick is not bound (!isViewPage) it will
					// just work.
					veEditUri,
					// visualeditor-ca-ve-create
					// visualeditor-ca-ve-edit
					mw.msg( 'visualeditor-ca-ve-' + action ),
					'ca-ve-edit',
					mw.msg( 'tooltip-ca-ve-edit' ),
					mw.msg( 'accesskey-ca-ve-edit' ),
					caVeEditNextnode
				);

			// Replace "Edit" tab with a veEditUri version, add "Edit source" tab.
			} else {
				// Create "Edit source" link.
				// Re-create instead of convert ca-edit since we don't want to copy over accesskey etc.
				caVeEditSource = mw.util.addPortletLink(
					pTabsId,
					// Use original href to preserve oldid etc. (bug 38125)
					$caEditLink.attr( 'href' ),
					// visualeditor-ca-createsource
					// visualeditor-ca-editsource
					mw.msg( 'visualeditor-ca-' + action + 'source' ),
					'ca-editsource',
					// tooltip-ca-editsource
					// tooltip-ca-createsource
					mw.msg( 'tooltip-ca-' + action + 'source' ),
					mw.msg( 'accesskey-ca-editsource' ),
					caVeEditNextnode
				);
				// Copy over classes (e.g. 'selected')
				$( caVeEditSource ).addClass( $caEdit.attr( 'class' ) );

				// Create "Edit" tab.
				$caEdit.remove();
				caVeEdit = mw.util.addPortletLink(
					pTabsId,
					// Use url instead of '#'.
					// So that 1) one can always open it in a new tab, even when
					// onEditTabClick is bound.
					// 2) when onEditTabClick is not bound (!isViewPage) it will
					// just work.
					veEditUri,
					$caEditLink.text(),
					$caEdit.attr( 'id' ),
					$caEditLink.attr( 'title' ),
					mw.msg( 'accesskey-ca-ve-edit' ),
					reverseTabOrder ? caVeEditSource.nextSibling : caVeEditSource
				);
			}

			if ( isViewPage ) {
				// Allow instant switching to edit mode, without refresh
				$( caVeEdit ).click( init.onEditTabClick );
			}
		},

		setupSectionEditLinks: function () {
			var $editsections = $( '#mw-content-text .mw-editsection' );

			// match direction to the user interface
			$editsections.css( 'direction', $( 'body' ).css( 'direction' ) );
			// The "visibility" css construct ensures we always occupy the same space in the layout.
			// This prevents the heading from changing its wrap when the user toggles editSourceLink.
			$editsections.each( function () {
				var $closingBracket, $expandedOnly, $hiddenBracket, $outerClosingBracket,
					expandTimeout, shrinkTimeout,
					$editsection = $( this ),
					$heading = $editsection.closest( 'h1, h2, h3, h4, h5, h6' ),
					$editLink = $editsection.find( 'a' ).eq( 0 ),
					$editSourceLink = $editLink.clone(),
					$links = $editLink.add( $editSourceLink ),
					$divider = $( '<span>' ),
					dividerText = $.trim( mw.msg( 'pipe-separator' ) ),
					$brackets = $( [ this.firstChild, this.lastChild ] );

				function expandSoon() {
					// Cancel pending shrink, schedule expansion instead
					clearTimeout( shrinkTimeout );
					expandTimeout = setTimeout( expand, 100 );
				}

				function expand() {
					clearTimeout( shrinkTimeout );
					$closingBracket.css( 'visibility', 'hidden' );
					$expandedOnly.css( 'visibility', 'visible' );
					$heading.addClass( 'mw-editsection-expanded' );
				}

				function shrinkSoon() {
					// Cancel pending expansion, schedule shrink instead
					clearTimeout( expandTimeout );
					shrinkTimeout = setTimeout( shrink, 100 );
				}

				function shrink() {
					clearTimeout( expandTimeout );
					if ( !$links.is( ':focus' ) ) {
						$closingBracket.css( 'visibility', 'visible' );
						$expandedOnly.css( 'visibility', 'hidden' );
						$heading.removeClass( 'mw-editsection-expanded' );
					}
				}

				// TODO: Remove this (see Id27555c6 in mediawiki/core)
				if ( !$brackets.hasClass( 'mw-editsection-bracket' ) ) {
					$brackets = $brackets
						.wrap( $( '<span>' ).addClass( 'mw-editsection-bracket' ) )
						.parent();
				}

				$closingBracket = $brackets.last();
				$outerClosingBracket = $closingBracket.clone();
				$expandedOnly = $divider.add( $editSourceLink ).add( $outerClosingBracket )
					.css( 'visibility', 'hidden' );
				// The hidden bracket after the devider ensures we have balanced space before and after
				// divider. The space before the devider is provided by the original closing bracket.
				$hiddenBracket = $closingBracket.clone().css( 'visibility', 'hidden' );

				// Events
				$heading.on( { 'mouseenter': expandSoon, 'mouseleave': shrinkSoon } );
				$links.on( { 'focus': expand, 'blur': shrinkSoon } );
				if ( isViewPage ) {
					// Only init without refresh if we're on a view page. Though section edit links
					// are rarely shown on non-view pages, they appear in one other case, namely
					// when on a diff against the latest version of a page. In that case we mustn't
					// init without refresh as that'd initialise for the wrong rev id (bug 50925)
					// and would preserve the wrong DOM with a diff on top.
					$editLink.click( init.onEditSectionLinkClick );
				}

				// Initialization
				$editSourceLink
					.addClass( 'mw-editsection-link-secondary' )
					.text( mw.msg( 'visualeditor-ca-editsource-section' ) );
				$divider
					.addClass( 'mw-editsection-divider' )
					.text( dividerText );
				$editLink
					.attr( 'href', function ( i, val ) {
						return new mw.Uri( veEditUri ).extend( {
							'vesection': new mw.Uri( val ).query.section
						} );
					} )
					.addClass( 'mw-editsection-link-primary' );
				$closingBracket
					.after( $divider, $hiddenBracket, $editSourceLink, $outerClosingBracket );
			} );
		},

		onEditTabClick: function ( e ) {
			// Default mouse button is normalised by jQuery to key code 1.
			// Only do our handling if no keys are pressed, mouse button is 1
			// (e.g. not middle click or right click) and no modifier keys
			// (e.g. cmd-click to open in new tab).
			if ( ( e.which && e.which !== 1 ) || e.shiftKey || e.altKey || e.ctrlKey || e.metaKey ) {
				return;
			}

			e.preventDefault();

			getTarget().done( function ( target ) {
				target.logEvent( 'Edit', { action: 'edit-link-click' } );
				target.activate();
			} );
		},

		onEditSectionLinkClick: function ( e ) {
			if ( ( e.which && e.which !== 1 ) || e.shiftKey || e.altKey || e.ctrlKey || e.metaKey ) {
				return;
			}

			e.preventDefault();

			getTarget().done( function ( target ) {
				target.logEvent( 'Edit', { action: 'section-edit-link-click' } );
				target.saveEditSection( $( e.target ).closest( 'h1, h2, h3, h4, h5, h6' ).get( 0 ) );
				target.activate();
			} );
		}
	};

	// Note: Though VisualEditor itself only needs this exposure for a very small reason
	// (namely to access init.blacklist from the unit tests...) this has become one of the nicest
	// ways to easily detect whether VisualEditor is present on this page. The VE global was once
	// available always, but now that platform integration initialisation is propertly separated,
	// it doesn't exist until the platform loads VisualEditor core. Though mw.libs.ve shouldn't be
	// considered an API (the methods are subject to change and considered private), the presence
	// of this property should be reliable.
	mw.libs.ve = init;

	isBlacklisted = !( 'vewhitelist' in uri.query ) && $.client.test( init.blacklist, null, true );

	if ( !init.support.es5 || !init.support.contentEditable || isBlacklisted ) {
		mw.log( 'Browser does not support VisualEditor' );
		return;
	}

	$( function () {
		if ( isViewPage ) {
			if ( uri.query.veaction === 'edit' ) {
				getTarget().done( function ( target ) {
					target.activate();
				} );
			}
		}
		init.skinSetup();
	} );

}() );
