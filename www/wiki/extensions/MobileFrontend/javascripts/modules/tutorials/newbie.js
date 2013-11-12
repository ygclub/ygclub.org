( function( M, $ ) {
	var LeadPhotoTutorialOverlay = M.require( 'tutorials/LeadPhotoTutorialOverlay' ),
		PageActionOverlay = M.require( 'tutorials/PageActionOverlay' ),
		action = M.query.article_action,
		escapeHash = M.require( 'toggle' ).escapeHash;

	function shouldShowUploadTutorial() {
		// FIXME: Limit audience to only users with low edit count
		return $( '#ca-upload' ).hasClass( 'enabled' ) &&
			action === 'photo-upload';
	}

	function shouldShowEditTutorial() {
		// FIXME: Limit audience to only users with low edit count
		return $( '#ca-edit' ).hasClass( 'enabled' ) &&
			action === 'edit';
	}

	$( function() {
		var photoOverlay, editOverlay, target;

		if ( !M.isLoggedIn() ) {
			return;
		} else if ( shouldShowEditTutorial() ) {
			if ( window.location.hash ) {
				target = escapeHash( window.location.hash ) + ' ~ .edit-page';
			} else {
				target = '#ca-edit .edit-page';
			}

			if ( mw.config.get( 'wgMFMode' ) !== 'stable' && window.location.hash && M.isTestA ) {
				// go straight to the editor if in beta/alpha, editing a section and in bucket A
				window.location.href = $( target ).attr( 'href' );
			} else {
				editOverlay = new PageActionOverlay( {
					target: target,
					className: 'slide active editing',
					summary: mw.msg( 'mobile-frontend-editor-tutorial-summary', mw.config.get( 'wgTitle' ) ),
					confirmMsg: mw.msg( 'mobile-frontend-editor-tutorial-confirm' )
				} );
				editOverlay.show();
				$( '#ca-edit' ).on( 'mousedown', $.proxy( editOverlay, 'hide' ) );
				editOverlay.$( '.actionable' ).on( M.tapEvent( 'click' ), function() {
					// Hide the tutorial
					editOverlay.hide();
					// Load the editing interface
					window.location.href = $( target ).attr( 'href' );
				} );
			}
		} else if ( shouldShowUploadTutorial() ) {
			photoOverlay = new LeadPhotoTutorialOverlay( {
				target: $( '#ca-upload input' ),
				funnel: 'newbie'
			} );
			photoOverlay.show();
			$( '#ca-upload' ).on( 'mousedown', $.proxy( photoOverlay, 'hide' ) );
		}
	} );

}( mw.mobileFrontend, jQuery ) );
