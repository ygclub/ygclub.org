/*!
 * VisualEditor user interface MediaInsertDialog class.
 *
 * @copyright 2011-2013 VisualEditor Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */

/*global mw */

/**
 * @class
 * @abstract
 * @extends ve.ui.MWDialog
 *
 * @constructor
 * @param {ve.ui.Surface} surface
 * @param {Object} [config] Config options
 */
ve.ui.MWMediaInsertDialog = function VeUiMWMediaInsertDialog( surface, config ) {
	// Parent constructor
	ve.ui.MWDialog.call( this, surface, config );

	// Properties
	this.item = null;
};

/* Inheritance */

ve.inheritClass( ve.ui.MWMediaInsertDialog, ve.ui.MWDialog );

/* Static Properties */

ve.ui.MWMediaInsertDialog.static.titleMessage = 'visualeditor-dialog-media-insert-title';

ve.ui.MWMediaInsertDialog.static.icon = 'picture';

/* Methods */

ve.ui.MWMediaInsertDialog.prototype.onSelect = function ( item ) {
	this.item = item;
	this.applyButton.setDisabled( item === null );
};

ve.ui.MWMediaInsertDialog.prototype.onOpen = function () {
	// Parent method
	ve.ui.MWDialog.prototype.onOpen.call( this );

	// Initialization
	this.search.getQuery().$input.focus().select();
};

ve.ui.MWMediaInsertDialog.prototype.onClose = function ( action ) {
	var info;

	// Parent method
	ve.ui.MWDialog.prototype.onClose.call( this );

	if ( action === 'apply' ) {
		info = this.item.imageinfo[0];
		this.surface.getModel().getFragment().insertContent( [
			{
				'type': 'mwBlockImage',
				'attributes': {
					'type': 'thumb',
					'align': 'right',
					//'href': info.descriptionurl,
					'href': './' + this.item.title,
					'src': info.thumburl,
					'width': info.thumbwidth,
					'height': info.thumbheight,
					'resource': './' + this.item.title
				}
			},
			{ 'type': 'mwImageCaption' },
			{ 'type': '/mwImageCaption' },
			{ 'type': '/mwBlockImage' }
		] );
	}
};

ve.ui.MWMediaInsertDialog.prototype.initialize = function () {
	// Parent method
	ve.ui.MWDialog.prototype.initialize.call( this );

	// Properties
	this.search = new ve.ui.MWMediaSearchWidget( { '$$': this.frame.$$ } );

	// Events
	this.search.connect( this, { 'select': 'onSelect' } );

	// Initialization
	this.applyButton.setDisabled( true ).setLabel(
		mw.msg( 'visualeditor-dialog-media-insert-button' )
	);
	this.search.$.addClass( 've-ui-mwMediaInsertDialog-select' );
	this.$body.append( this.search.$ );
};

/* Registration */

ve.ui.dialogFactory.register( 'mwMediaInsert', ve.ui.MWMediaInsertDialog );
