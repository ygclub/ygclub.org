/*!
 * VisualEditor UserInterface LinkInspector class.
 *
 * @copyright 2011-2013 VisualEditor Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */

/**
 * Link inspector.
 *
 * @class
 * @extends ve.ui.Inspector
 *
 * @constructor
 * @param {ve.ui.Surface} surface
 * @param {Object} [config] Config options
 */
ve.ui.LinkInspector = function VeUiLinkInspector( surface, config ) {
	// Parent constructor
	ve.ui.Inspector.call( this, surface, config );

	// Properties
	this.initialAnnotationHash = null;
	this.isNewAnnotation = false;
};

/* Inheritance */

ve.inheritClass( ve.ui.LinkInspector, ve.ui.Inspector );

/* Static properties */

ve.ui.LinkInspector.static.icon = 'link';

ve.ui.LinkInspector.static.titleMessage = 'visualeditor-linkinspector-title';

ve.ui.LinkInspector.static.linkTargetInputWidget = ve.ui.LinkTargetInputWidget;

/**
 * Annotation models this inspector can edit.
 *
 * @static
 * @property {Function[]}
 */
ve.ui.LinkInspector.static.modelClasses = [ ve.dm.LinkAnnotation ];

/* Methods */

/**
 * Handle frame ready events.
 *
 * @method
 */
ve.ui.LinkInspector.prototype.initialize = function () {
	// Parent method
	ve.ui.Inspector.prototype.initialize.call( this );

	// Properties
	this.targetInput = new this.constructor.static.linkTargetInputWidget( {
		'$$': this.frame.$$, '$overlay': this.surface.$localOverlayMenus
	} );

	// Initialization
	this.$form.append( this.targetInput.$ );
};

/**
 * Handle the inspector being setup.
 *
 * There are 4 scenarios:
 *     * Zero-length selection not near a word -> no change, text will be inserted on close
 *     * Zero-length selection inside or adjacent to a word -> expand selection to cover word
 *     * Selection covering non-link text -> trim selection to remove leading/trailing whitespace
 *     * Selection covering link text -> expand selection to cover link
 *
 * @method
 */
ve.ui.LinkInspector.prototype.onSetup = function () {
	var expandedFragment, trimmedFragment, truncatedFragment,
		fragment = this.surface.getModel().getFragment( null, true ),
		annotation = this.getMatchingAnnotations( fragment, true ).get( 0 );

	// Parent method
	ve.ui.Inspector.prototype.onSetup.call( this );

	// Initialize range
	if ( !annotation ) {
		if ( fragment.getRange().isCollapsed() ) {
			// Expand to nearest word
			expandedFragment = fragment.expandRange( 'word' );
			fragment = expandedFragment;
		} else {
			// Trim whitespace
			trimmedFragment = fragment.trimRange();
			fragment = trimmedFragment;
		}
		if ( !fragment.getRange().isCollapsed() ) {
			// Create annotation from selection
			truncatedFragment = fragment.truncateRange( 255 );
			fragment = truncatedFragment;
			annotation = this.getAnnotationFromTarget( fragment.getText() );
			fragment.annotateContent( 'set', annotation );
			this.isNewAnnotation = true;
		}
	} else {
		// Expand range to cover annotation
		expandedFragment = fragment.expandRange( 'annotation', annotation );
		fragment = expandedFragment;
	}

	// Update selection
	fragment.select();
};

/**
 * Handle the inspector being opened.
 */
ve.ui.LinkInspector.prototype.onOpen = function () {
	var fragment = this.surface.getModel().getFragment( null, true ),
		annotation = this.getMatchingAnnotations( fragment, true ).get( 0 ),
		// Note that we don't set the 'all' flag here so that any
		// non-annotated content is annotated on close
		initialAnnotation = this.getMatchingAnnotations( fragment ).get( 0 );


	// Parent method
	ve.ui.Inspector.prototype.onOpen.call( this );

	// Wait for animation to complete
	this.surface.disable();
	setTimeout( ve.bind( function () {
		// Note: Focus input prior to setting target annotation
		this.targetInput.$input.focus();
		// Setup annotation
		this.initialAnnotationHash = initialAnnotation && ve.getHash( initialAnnotation );
		this.targetInput.setAnnotation( annotation );
		this.targetInput.$input.select();
		this.surface.enable();
	}, this ), 200 );
};

/**
 * Handle the inspector being closed.
 *
 * @param {string} action Action that caused the window to be closed
 */
ve.ui.LinkInspector.prototype.onClose = function ( action ) {
	// Parent method
	ve.ui.Inspector.prototype.onClose.call( this, action );

	var i, len, annotations, selection,
		insert = false,
		undo = false,
		clear = false,
		set = false,
		remove = action === 'remove',
		target = this.targetInput.getValue(),
		annotation = this.targetInput.getAnnotation(),
		fragment = this.surface.getModel().getFragment( this.initialSelection, false );
	// Undefined annotation causes removal
	if ( !annotation ) {
		remove = true;
	}
	if ( remove ) {
		clear = true;
	} else {
		if ( this.initialSelection.isCollapsed() ) {
			insert = true;
		}
		if ( ve.getHash( annotation ) !== this.initialAnnotationHash ) {
			if ( this.isNewAnnotation ) {
				undo = true;
			} else {
				clear = true;
			}
			set = true;
		}
	}
	if ( insert ) {
		fragment.insertContent( target, false );

		// Move cursor to the end of the inserted content, even if back button is used
		this.previousSelection = new ve.Range( this.initialSelection.start + target.length );
	}
	if ( undo ) {
		// Go back to before we added an annotation
		this.surface.execute( 'history', 'undo' );
	}
	if ( clear ) {
		// Clear all existing annotations
		annotations = this.getMatchingAnnotations( fragment, true ).get();
		for ( i = 0, len = annotations.length; i < len; i++ ) {
			fragment.annotateContent( 'clear', annotations[i] );
		}
	}
	if ( set ) {
		// Apply new annotation
		fragment.annotateContent( 'set', annotation );
	}
	if ( action === 'back' ) {
		selection = this.previousSelection;
	}
	// Selection changes may have occured in the insertion and annotation hullabaloo - restore it
	this.surface.execute(
		'content', 'select', selection || new ve.Range( fragment.getRange().end )
	);
	// Reset state
	this.isNewAnnotation = false;
};

/**
 * Get an annotation object from a target.
 *
 * @method
 * @param {string} target Link target
 * @returns {ve.dm.LinkAnnotation}
 */
ve.ui.LinkInspector.prototype.getAnnotationFromTarget = function ( target ) {
	return new ve.dm.LinkAnnotation( {
		'type': 'link',
		'attributes': {
			'href': target
		}
	 } );
};

/**
 * Get matching annotations within a fragment.
 *
 * @method
 * @param {ve.dm.SurfaceFragment} fragment Fragment to get matching annotations within
 * @param {boolean} [all] Get annotations which only cover some of the fragment
 * @returns {ve.dm.AnnotationSet} Matching annotations
 */
ve.ui.LinkInspector.prototype.getMatchingAnnotations = function ( fragment, all ) {
	var modelClasses = this.constructor.static.modelClasses;

	return fragment.getAnnotations( all ).filter( function ( annnotation ) {
		return ve.isInstanceOfAny( annnotation, modelClasses );
	} );
};

/* Registration */

ve.ui.inspectorFactory.register( 'link', ve.ui.LinkInspector );
