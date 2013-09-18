mw.mobileFrontend = {
	_modules: {},

	assertMode: function( modes ) {
		var mode = mw.config.get( 'wgMFMode' );
		if ( modes.indexOf( mode ) === -1 ) {
			throw new Error( 'Attempt to run module outside declared environment mode ' + mode  );
		}
	},

	/**
	 * Require (import) a module previously defined using define().
	 *
	 * @param {string} id Required module id.
	 * @return {Object} Required module, can be any JavaScript object.
	 */
	require: function( id ) {
		if ( !this._modules.hasOwnProperty( id ) ) {
			throw new Error( 'Module not found: ' + id );
		}
		return this._modules[ id ];
	},
	testMode: mw.config.get( 'wgCanonicalSpecialPageName' ) === 'JavaScriptTest',

	/**
	 * Define a module which can be later required (imported) using require().
	 *
	 * @param {string} id Defined module id.
	 * @param {Object} obj Defined module body, can be any JavaScript object.
	 */
	define: function( id, obj ) {
		if ( this._modules.hasOwnProperty( id ) ) {
			throw new Error( 'Module already exists: ' + id );
		}
		this._modules[ id ] = obj;
		// FIXME: modules should not self initialise
		if ( obj.init && !this.testMode ) {
			obj.init();
		}
	}
};
