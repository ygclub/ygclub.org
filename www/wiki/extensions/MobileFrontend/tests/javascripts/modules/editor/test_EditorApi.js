( function ( M, $ ) {

	var EditorApi = M.require( 'modules/editor/EditorApi' );

	QUnit.module( 'MobileFrontend modules/editor/EditorApi', {
		setup: function() {
			this.spy = sinon.stub( EditorApi.prototype, 'get' ).returns( $.Deferred().resolve( {
				"query": {
					"pages": {
						"1": {
							"revisions": [
								{
								"timestamp": "2013-05-15T00:30:26Z",
								"*": "section"
							}
							]
						}
					}
				}
			} ) );
			sinon.stub( EditorApi.prototype, 'getToken' ).returns( $.Deferred().resolve( 'fake token' ) );
		},
		teardown: function() {
			EditorApi.prototype.get.restore();
			EditorApi.prototype.getToken.restore();
		}
	} );

	QUnit.test( '#getContent (no section)', 1, function( assert ) {
		var editorApi = new EditorApi( { title: 'MediaWiki:Test.css' } );

		editorApi.getContent();
		assert.ok( this.spy.calledWith( {
				action: 'query',
				prop: 'revisions',
				rvprop: [ 'content', 'timestamp' ],
				titles: 'MediaWiki:Test.css'
			} ), 'rvsection not passed to api request' );
	} );

	QUnit.test( '#getContent', 2, function( assert ) {
		var editorApi = new EditorApi( { title: 'test', sectionId: 1 } );

		editorApi.getContent().done( function( resp ) {
			assert.strictEqual( resp, 'section', 'return section content' );
		} );
		editorApi.getContent();
		assert.ok( editorApi.get.calledOnce, 'cache content' );
	} );

	QUnit.test( '#getContent, new page', 2, function( assert ) {
		var editorApi = new EditorApi( { title: 'test', isNew: true } );

		editorApi.getContent().done( function( resp ) {
			assert.strictEqual( resp, '', 'return empty section' );
		} );
		assert.ok( !editorApi.get.called, "don't try to retrieve content using API" );
	} );

	QUnit.test( '#getContent, missing section', 2, function( assert ) {
		var editorApi = new EditorApi( { title: 'test', sectionId: 1 } ), doneSpy = sinon.spy();

		EditorApi.prototype.get.restore();
		sinon.stub( EditorApi.prototype, 'get' ).returns( $.Deferred().resolve( {
			"error": { "code": "rvnosuchsection" }
		} ) );

		editorApi.getContent().done( doneSpy ).fail( function( error ) {
			assert.strictEqual( error, 'rvnosuchsection', "return error code" );
		} );
		assert.ok( !doneSpy.called, "don't call done" );
	} );

	QUnit.test( '#save, success', 2, function( assert ) {
		var editorApi = new EditorApi( { title: 'test', sectionId: 1 } );

		sinon.stub( editorApi, 'post' ).returns( $.Deferred().resolve(
			{ edit: { result: 'Success' } }
		) );

		editorApi.getContent();
		editorApi.setContent( 'section 1' );
		editorApi.save( { summary: 'summary' } ).done( function() {
			assert.ok( editorApi.post.calledWith( {
				action: 'edit',
				title: 'test',
				section: 1,
				text: 'section 1',
				summary: 'summary',
				captchaid: undefined,
				captchaword: undefined,
				token: 'fake token',
				basetimestamp: '2013-05-15T00:30:26Z',
				starttimestamp: '2013-05-15T00:30:26Z'
			} ), 'save first section' );
		} );
		assert.strictEqual( editorApi.hasChanged, false, 'reset hasChanged' );
	} );

	QUnit.test( '#save, new page', 2, function( assert ) {
		var editorApi = new EditorApi( { title: 'Talk:test', isNew: true } );

		sinon.stub( editorApi, 'post' ).returns( $.Deferred().resolve(
			{ edit: { result: 'Success' } }
		) );

		editorApi.getContent();
		editorApi.setContent( 'section 0' );
		editorApi.save( { summary: 'summary' } ).done( function() {
			assert.ok( editorApi.post.calledWith( {
				action: 'edit',
				title: 'Talk:test',
				text: 'section 0',
				summary: 'summary',
				captchaid: undefined,
				captchaword: undefined,
				token: 'fake token',
				basetimestamp: undefined,
				starttimestamp: undefined
			} ), 'save lead section' );
		} );
		assert.strictEqual( editorApi.hasChanged, false, 'reset hasChanged' );
	} );

	QUnit.test( '#save, submit CAPTCHA', 2, function( assert ) {
		var editorApi = new EditorApi( { title: 'test', sectionId: 1 } );

		sinon.stub( editorApi, 'post' ).returns( $.Deferred().resolve(
			{ edit: { result: 'Success' } }
		) );

		editorApi.getContent();
		editorApi.setContent( 'section 1' );
		editorApi.save( { summary: 'summary', captchaId: 123, captchaWord: 'abc' } ).done( function() {
			assert.ok( editorApi.post.calledWith( {
				action: 'edit',
				title: 'test',
				section: 1,
				text: 'section 1',
				summary: 'summary',
				captchaid: 123,
				captchaword: 'abc',
				token: 'fake token',
				basetimestamp: '2013-05-15T00:30:26Z',
				starttimestamp: '2013-05-15T00:30:26Z'
			} ), 'save first section' );
		} );
		assert.strictEqual( editorApi.hasChanged, false, 'reset hasChanged' );
	} );

	QUnit.test( '#save, request failure', 2, function( assert ) {
		var editorApi = new EditorApi( { title: 'test', sectionId: 1 } ),
			doneSpy = sinon.spy(), failSpy = sinon.spy();

		sinon.stub( editorApi, 'post' ).returns( $.Deferred().reject() );

		editorApi.getContent();
		editorApi.setContent( 'section 1' );

		editorApi.save().done( doneSpy ).fail( failSpy );

		assert.ok( failSpy.calledWith( { type: 'error', details: 'HTTP error' } ), "call fail" );
		assert.ok( !doneSpy.called, "don't call done" );
	} );

	QUnit.test( '#save, API failure', 2, function( assert ) {
		var editorApi = new EditorApi( { title: 'test', sectionId: 1 } ),
			doneSpy = sinon.spy(), failSpy = sinon.spy();

		sinon.stub( editorApi, 'post' ).returns( $.Deferred().resolve(
			{ error: { code: 'error code' } }
		) );

		editorApi.getContent();
		editorApi.setContent( 'section 1' );

		editorApi.save().done( doneSpy ).fail( failSpy );

		assert.ok( failSpy.calledWith( { type: 'error', details: 'error code' } ), "call fail" );
		assert.ok( !doneSpy.called, "don't call done" );
	} );

	QUnit.test( '#save, CAPTCHA response with image URL', 2, function( assert ) {
		var editorApi = new EditorApi( { title: 'test', sectionId: 1 } ),
			captcha = {
				type: "image",
				mime: "image/png",
				id: "1852528679",
				url: "/w/index.php?title=Especial:Captcha/image&wpCaptchaId=1852528679"
			},
			doneSpy = sinon.spy(), failSpy = sinon.spy();

		sinon.stub( editorApi, 'post' ).returns( $.Deferred().resolve( {
			edit: {
				result: 'Failure',
				captcha: captcha
			}
		} ) );

		editorApi.getContent();
		editorApi.setContent( 'section 1' );

		editorApi.save().done( doneSpy ).fail( failSpy );

		assert.ok( failSpy.calledWith( { type: 'captcha', details: captcha } ), "call fail" );
		assert.ok( !doneSpy.called, "don't call done" );
	} );

	QUnit.test( '#save, AbuseFilter warning', 2, function( assert ) {
		var editorApi = new EditorApi( { title: 'test', sectionId: 1 } ),
			doneSpy = sinon.spy(), failSpy = sinon.spy();

		sinon.stub( editorApi, 'post' ).returns( $.Deferred().resolve( {
			edit: {
				code: "abusefilter-warning-usuwanie-tekstu",
				info: "Hit AbuseFilter: Usuwanie du\u017cej ilo\u015bci tekstu",
				warning: "horrible desktop-formatted message",
				result: "Failure"
			}
		} ) );

		editorApi.getContent();
		editorApi.setContent( 'section 1' );

		editorApi.save().done( doneSpy ).fail( failSpy );

		assert.ok( failSpy.calledWith( {
			type: 'abusefilter',
			details: {
				type: 'warning',
				message: "horrible desktop-formatted message"
			}
		} ), "call fail with type and message" );
		assert.ok( !doneSpy.called, "don't call done" );
	} );

	QUnit.test( '#save, AbuseFilter disallow', 2, function( assert ) {
		var editorApi = new EditorApi( { title: 'test', sectionId: 1 } ),
			doneSpy = sinon.spy(), failSpy = sinon.spy();

		sinon.stub( editorApi, 'post' ).returns( $.Deferred().resolve( {
			edit: {
				code: "abusefilter-disallow",
				info: "Scary filter",
				warning: "horrible desktop-formatted message",
				result: "Failure"
			}
		} ) );

		editorApi.getContent();
		editorApi.setContent( 'section 1' );

		editorApi.save().done( doneSpy ).fail( failSpy );

		assert.ok( failSpy.calledWith( {
			type: 'abusefilter',
			details: {
				type: 'disallow',
				message: "horrible desktop-formatted message"
			}
		} ), "call fail with type and message" );
		assert.ok( !doneSpy.called, "don't call done" );
	} );

	QUnit.test( '#save, AbuseFilter other', 2, function( assert ) {
		var editorApi = new EditorApi( { title: 'test', sectionId: 1 } ),
			doneSpy = sinon.spy(), failSpy = sinon.spy();

		sinon.stub( editorApi, 'post' ).returns( $.Deferred().resolve( {
			edit: {
				code: "abusefilter-something",
				info: "Scary filter",
				warning: "horrible desktop-formatted message",
				result: "Failure"
			}
		} ) );

		editorApi.getContent();
		editorApi.setContent( 'section 1' );

		editorApi.save().done( doneSpy ).fail( failSpy );

		assert.ok( failSpy.calledWith( {
			type: 'abusefilter',
			details: {
				type: 'other',
				message: "horrible desktop-formatted message"
			}
		} ), "call fail with type and message" );
		assert.ok( !doneSpy.called, "don't call done" );
	} );

	QUnit.test( '#save, extension errors', 2, function( assert ) {
		var editorApi = new EditorApi( { title: 'test', sectionId: 1 } ),
			doneSpy = sinon.spy(), failSpy = sinon.spy();

		sinon.stub( editorApi, 'post' ).returns( $.Deferred().resolve( {
			edit: {
				code: "testerror",
				result: "Failure"
			}
		} ) );

		editorApi.getContent();
		editorApi.setContent( 'section 1' );

		editorApi.save().done( doneSpy ).fail( failSpy );

		assert.ok( failSpy.calledWith( { type: 'error', details: 'testerror' } ), "call fail with code" );
		assert.ok( !doneSpy.called, "don't call done" );
	} );

	QUnit.test( '#save, unknown errors', 2, function( assert ) {
		var editorApi = new EditorApi( { title: 'test', sectionId: 1 } ),
			doneSpy = sinon.spy(), failSpy = sinon.spy();

		sinon.stub( editorApi, 'post' ).returns( $.Deferred().resolve( {} ) );

		editorApi.getContent();
		editorApi.setContent( 'section 1' );

		editorApi.save().done( doneSpy ).fail( failSpy );

		assert.ok( failSpy.calledWith( { type: 'error', details: 'unknown' } ), "call fail with unknown" );
		assert.ok( !doneSpy.called, "don't call done" );
	} );

	QUnit.test( '#save, without changes', 2, function( assert ) {
		var editorApi = new EditorApi( { title: 'test', sectionId: 1 } );

		assert.throws(
			function() {
				editorApi.save();
			},
			/no changes/i,
			'throw an error'
		);
		assert.ok( !editorApi.getToken.called, "don't get the token" );
	} );

}( mw.mobileFrontend, jQuery ) );
