/*global mw, jQuery */
/*jslint sloppy: true, white:true, maxerr: 50, indent: 4, plusplus: true */
( function( M, $ ) {

var m = ( function() {
	function makeSection( $editArea, sectionId ) {
		var $section = $( '<div class="section">' ).insertBefore( $editArea ),
			$heading = $( '<h2 class="section_heading">' ).attr( 'id', 'section_edit' + sectionId ),
			$content = $( '<div class="content_block">' );
		
		if ( sectionId > 0 ) {
			$section.append( $heading );
			$content.attr( { 'id': 'content_edit' + sectionId } );
		} else {
			$content.addClass( 'openSection' );
		}

		$section.append( $content );
		return $section;
	}

	function concatTextAreas() {
		var newVal = [],
			$segments = $( 'form#editform .segment' ),
			last = $segments.length - 1;

		$segments.each( function( i ) {
			newVal.push( $( this ).val() );

			if ( i === last ) {
				return;
			} else if ( this.nodeName === 'INPUT' ) {
				newVal.push( '\n' );
			} else {
				newVal.push( '\n\n' );
			}
		} );
		return newVal.join( '' );
	}

	function splitTextArea( $editArea ) {
		var wikitext = $editArea.val(),
			$editSummary = $( '#wpSummary' ),
			$loader,
			parts = wikitext.split( '\n\n' ),
			headingLocation, section_id = 0,
			i, val, heading, $el,
			$section = makeSection( $editArea, section_id );

		for ( i = 0; i < parts.length; i++ ) {
			val = parts[ i ];
			if ( val.indexOf( '=' ) === 0 ) {
				headingLocation = $section.find( '.content_block' );
				val = val.split( '\n' );
				heading = val[ 0 ];
				val = val.slice( 1 ).join( '\n' );
				$el = $( '<input class="segment">' ).
					val( heading );
				if ( heading.indexOf( '====' ) > -1 ) {
					$el.addClass( 'h4' );
				} else if ( heading.indexOf( '===' ) > -1 ) {
					$el.addClass( 'h3' );
				} else if ( heading.indexOf( '==' ) > -1 ) {
					section_id += 1;
					$section = makeSection( $editArea, section_id );
					headingLocation = $section.find( '.section_heading' );
					$el.addClass( 'h2' );
				}
				$el.on( 'click', function( ev ) {
					ev.stopPropagation();
				} ).appendTo( headingLocation )
			}

			if ( val ) {
				$el = $( '<textarea class="segment">' );
			} else { // a heading for followed by 2 new lines - ensure the blank line is kept
				$el = $( '<input class="segment">' );
			}
			$el.val( val ).appendTo( $section.find( '.content_block' ) );
		}

		$loader = $( '<div class="loader">' ).text( M.message( 'mobile-frontend-page-saving', M.setting( 'title' ) ) ).
			hide().insertBefore( '#content_0' );
		$( 'form#editform' ).on( 'submit', function() {
			$( '#content_0' ).hide();
			$loader.show();
			var val = concatTextAreas();
			$editArea.val( val );
			$editSummary.val( $editSummary.val() + ' [Via Mobile]' );
		} );

		mw.mobileFrontend.getModule( 'toggle' ).enableToggling(); // FIXME: adds dependency to toggle module
	}

	function init() {
		var $editArea = $( 'form#editform textarea' ).hide();
		// only register if we found an edit area
		if ( $editArea[ 0 ] ) {
			splitTextArea( $editArea );
		}
	}

	return {
		concatTextAreas: concatTextAreas,
		init: init
	};

} () );

M.registerModule( 'edit', m );
}( mw.mobileFrontend, jQuery ) );

