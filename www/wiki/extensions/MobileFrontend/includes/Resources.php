<?php
/**
 * Definition of MobileFrontend's ResourceLoader modules.
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
 * @file
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'Not an entry point.' );
}

$localBasePath = dirname( __DIR__ );
$remoteExtPath = 'MobileFrontend';

/**
 * A boilerplate for the MFResourceLoaderModule that supports templates
 */
$wgMFMobileResourceBoilerplate = array(
	'localBasePath' => $localBasePath,
	'remoteExtPath' => $remoteExtPath,
	'localTemplateBasePath' => $localBasePath . '/templates',
	'class' => 'MFResourceLoaderModule',
);

/**
 * A boilerplate containing common properties for all RL modules served to mobile site special pages
 */
$wgMFMobileSpecialPageResourceBoilerplate = array(
	'localBasePath' => $localBasePath,
	'remoteExtPath' => $remoteExtPath,
	'targets' => 'mobile',
	'group' => 'other',
);

/**
 * A boilerplate for RL script modules
*/
$wgMFMobileSpecialPageResourceScriptBoilerplate = $wgMFMobileSpecialPageResourceBoilerplate + array(
	'dependencies' => array( 'mobile.stable' ),
);
/**
 * A boilerplate for RL style modules for special pages
*/
$wgMFMobileSpecialPageResourceStyleBoilerplate = $wgMFMobileSpecialPageResourceBoilerplate + array(
	// ensure special css is always loaded after mobile.styles for cascading purposes (keep jgonera happy)
	'dependencies' => array( 'mobile.styles' ),
);

$wgResourceModules = array_merge( $wgResourceModules, array(
	// FIXME: Upstream to core
	'mobile.templates' => array(
		'localBasePath' => $localBasePath,
		'remoteExtPath' => $remoteExtPath,
		'scripts' => array(
			'javascripts/externals/hogan.js',
			'javascripts/common/templates.js'
		),
		'targets' => array( 'mobile', 'desktop' ),
	),

	// EventLogging
	'mobile.loggingSchemas' => $wgMFMobileResourceBoilerplate + array(
		'dependencies' => array(
			'mobile.startup',
		),
		'scripts' => array(
			'javascripts/loggingSchemas/MobileWebClickTracking.js',
		),
	),

	'mobile.file.scripts' => $wgMFMobileResourceBoilerplate + array(
		'dependencies' => array( 'mobile.startup' ),
		'scripts' => array(
			'javascripts/file/filepage.js'
		),
	),

	'mobile.styles.page' => $wgMFMobileResourceBoilerplate + array(
		'dependencies' => array( 'mobile.startup' ),
		'styles' => array(
			'less/common/enwp.less'
		),
	),

	'mobile.pagelist.styles' => $wgMFMobileResourceBoilerplate + array(
		'styles' => array(
			'less/common/pagelist.less',
		),
		'position' => 'top',
	),

	'mobile.styles' => $wgMFMobileResourceBoilerplate + array(
		'styles' => array(
			'less/common/reset.less',
			'less/common/common.less',
			'less/common/ui.less',
			'less/common/typography.less',
			'less/common/footer.less',
			'less/modules/toggle.less',
			// FIXME: move to module mobile.stable.styles for some reason it breaks RTL when in that module
			'less/common/navigation.less',
			'less/common/overlays.less',
			'less/common/drawer.less',
			'less/common/hacks.less',
			'less/common/pageactions.less',
		),
		'position' => 'top',
	),

	'mobile.styles.beta' => $wgMFMobileResourceBoilerplate + array(
		'styles' => array(
			'less/common/secondaryPageActions.less',
		),
		'position' => 'top',
	),

	// Important: This module is loaded on both mobile and desktop skin
	'mobile.head' => $wgMFMobileResourceBoilerplate + array(
		'scripts' => array(
			'javascripts/common/polyfills.js',
			'javascripts/common/modules.js',
			'javascripts/common/Class.js',
			'javascripts/common/eventemitter.js',
			'javascripts/common/navigation.js',
		),
		'position' => 'top',
	),

	'mobile.startup' => $wgMFMobileResourceBoilerplate + array(
		'dependencies' => array(
			'mobile.head',
			'mobile.templates',
		),
		'scripts' => array(
			'javascripts/common/Router.js',
			'javascripts/common/api.js',
			'javascripts/common/PageApi.js',
			'javascripts/common/application.js',
			'javascripts/common/settings.js',
			'javascripts/modules/mf-stop-mobile-redirect.js',
		),
		'position' => 'bottom',
	),

	'mobile.editor' => $wgMFMobileResourceBoilerplate + array(
		'dependencies' => array(
			'mobile.stable',
			'mobile.templates',
			'jquery.cookie',
		),
		'scripts' => array(
			'javascripts/modules/editor/EditorApi.js',
			'javascripts/modules/editor/AbuseFilterOverlay.js',
			'javascripts/modules/editor/EditorOverlay.js',
		),
		'templates' => array(
			'modules/editor/EditorOverlay',
			'modules/editor/AbuseFilterOverlay',
		),
		'messages' => array(
			// modules/editor/EditorOverlay.js
			'mobile-frontend-editor-continue',
			'mobile-frontend-editor-cancel',
			'mobile-frontend-editor-keep-editing',
			'mobile-frontend-editor-license' => array( 'parse' ),
			'mobile-frontend-editor-placeholder',
			'mobile-frontend-editor-summary-placeholder',
			'mobile-frontend-editor-cancel-confirm',
			'mobile-frontend-editor-wait',
			'mobile-frontend-editor-guider',
			'mobile-frontend-editor-success',
			'mobile-frontend-editor-success-landmark-1' => array( 'parse' ),
			'mobile-frontend-editor-refresh',
			'mobile-frontend-editor-error',
			'mobile-frontend-editor-error-conflict',
			'mobile-frontend-editor-error-loading',
			'mobile-frontend-editor-preview-header',
			'mobile-frontend-editor-error-preview',
			'mobile-frontend-account-create-captcha-placeholder',
			'mobile-frontend-editor-captcha-try-again',
			'mobile-frontend-photo-ownership-confirm',
			'mobile-frontend-editor-abusefilter-warning',
			'mobile-frontend-editor-abusefilter-disallow',
			'mobile-frontend-editor-abusefilter-read-more',
		),
	),

	'mobile.uploads' => $wgMFMobileResourceBoilerplate + array(
		'dependencies' => array(
			'mobile.stable',
			'mobile.templates',
		),
		'scripts' => array(
			'javascripts/modules/uploads/LearnMoreOverlay.js',
			'javascripts/modules/uploads/PhotoApi.js',
			'javascripts/modules/uploads/NagOverlay.js',
			'javascripts/modules/uploads/PhotoUploadProgress.js',
			'javascripts/modules/uploads/PhotoUploaderPreview.js',
			'javascripts/modules/uploads/LeadPhoto.js',
			'javascripts/modules/uploads/PhotoUploader.js',
		),
		'templates' => array(
			'uploads/PhotoUploadPreview',
			'uploads/PhotoUploadProgress',
			'uploads/NagOverlay',
			'uploads/LearnMoreOverlay',
			'uploads/LeadPhoto',
		),
		'messages' => array(
			'mobile-frontend-photo-upload-success-article',
			'mobile-frontend-photo-upload-error',

			// LearnMoreOverlay.js
			'mobile-frontend-photo-ownership-confirm',

			// PhotoApi.js
			'mobile-frontend-photo-article-edit-comment',
			'mobile-frontend-photo-article-donate-comment',
			'mobile-frontend-photo-upload-error-filename',
			'mobile-frontend-photo-upload-comment',

			// PhotoUploaderPreview.js
			'mobile-frontend-photo-ownership',
			'mobile-frontend-photo-ownership-help',
			'mobile-frontend-photo-caption-placeholder',
			'mobile-frontend-image-loading',
			'mobile-frontend-photo-submit',
			'mobile-frontend-photo-cancel',
			'mobile-frontend-photo-ownership-bullet-one',
			'mobile-frontend-photo-ownership-bullet-two',
			'mobile-frontend-photo-ownership-bullet-three',
			'mobile-frontend-photo-upload-error-file-type',

			// PhotoUploadProgress.js
			'mobile-frontend-image-uploading-wait',
			'mobile-frontend-image-uploading-long',
			'mobile-frontend-image-uploading-cancel',

			// NagOverlay.js
			'mobile-frontend-photo-license' => array( 'parse' ),
			'mobile-frontend-photo-nag-1-bullet-1-heading',
			'mobile-frontend-photo-nag-1-bullet-1-text' => array( 'parse' ),
			'mobile-frontend-photo-nag-1-bullet-2-heading',
			'mobile-frontend-photo-nag-1-bullet-2-text',
			'mobile-frontend-photo-nag-2-bullet-1-heading',
			'mobile-frontend-photo-nag-3-bullet-1-heading',
			'parentheses',
			'mobile-frontend-learn-more',
			'mobile-frontend-photo-nag-learn-more-heading',
			'mobile-frontend-photo-nag-learn-more-1' => array( 'parse' ),
			'mobile-frontend-photo-nag-learn-more-2' => array( 'parse' ),
			'mobile-frontend-photo-nag-learn-more-3' => array( 'parse' ),
		),
	),

	'mobile.beta.common' => $wgMFMobileResourceBoilerplate + array(
		'templates' => array(
			// NotificationsOverlay.js
			'overlays/notifications',
			// page.js
			'pageActionTutorial',
		),
		'dependencies' => array(
			'mobile.stable.common',
			'mobile.loggingSchemas',
			'mobile.templates',
		),
		'scripts' => array(
			'javascripts/common/ContentOverlay.js',
		),
		'messages' => array(
			// LanguageOverlay.js
			'mobile-frontend-language-header',
			'mobile-frontend-language-site-choose',
			'mobile-frontend-language-footer',
		),
	),

	'mobile.keepgoing' => $wgMFMobileResourceBoilerplate + array(
		'dependencies' => array(
			'mobile.beta',
			'mobile.templates',
		),
		'templates' => array(
			'keepgoing/KeepGoingDrawer',
		),
		'messages' => array(
			'mobilefrontend-keepgoing-suggest',
			'mobilefrontend-keepgoing-suggest-again',
			'mobilefrontend-keepgoing-cancel',
			'mobilefrontend-keepgoing-ask',
			'mobilefrontend-keepgoing-ask-first',
			'mobilefrontend-keepgoing-explain',
		),
		'scripts' => array(
			'javascripts/loggingSchemas/mobileWebCta.js',
			'javascripts/modules/keepgoing/KeepGoingDrawer.js',
		),
	),

	'mobile.geonotahack' => $wgMFMobileResourceBoilerplate + array(
		'dependencies' => array(
			'mobile.startup',
		),
		'messages' => array(
			'mobile-frontend-geonotahack',
		),
		'scripts' => array(
			'javascripts/modules/nearbypages.js',
		)
	),

	'mobile.beta' => $wgMFMobileResourceBoilerplate + array(
		'dependencies' => array(
			'mobile.stable',
			'mobile.beta.common',
		),
		'styles' => array(
			'less/modules/talk.less',
		),
		'scripts' => array(
			'javascripts/modules/mf-toggle-dynamic.js',
			'javascripts/modules/talk/talk.js',
			'javascripts/modules/search/pageImages.js',
			'javascripts/modules/languages/preferred.js',
			'javascripts/modules/tutorials/PageActionOverlay.js',
			'javascripts/modules/tutorials/newbie.js',
			'javascripts/modules/lastModifiedBeta.js',
			'javascripts/modules/keepgoing/keepgoing.js',
		),
		'position' => 'bottom',
		'messages' => array(
			// for mf-toggle-dynamic.js
			'mobile-frontend-show-button',
			'mobile-frontend-hide-button',

			// newbie.js
			'mobile-frontend-lead-image-tutorial-summary',
			'mobile-frontend-lead-image-tutorial-confirm',
			'mobile-frontend-editor-tutorial-summary',
			'mobile-frontend-editor-tutorial-confirm',

			// for talk.js
			'mobile-frontend-talk-overlay-header',

			// notifications.js (defined in Echo)
			'echo-none',
			'notifications',
		),
	),

	'mobile.talk' => $wgMFMobileResourceBoilerplate + array(
		'dependencies' => array(
			'mobile.beta',
			'mobile.templates',
		),
		'scripts' => array(
			'javascripts/modules/talk/TalkSectionOverlay.js',
			'javascripts/modules/talk/TalkOverlay.js',
		),
		'templates' => array(
			// talk.js
			'overlays/talk',
			'overlays/talkSectionAdd',
			'talkSection',
		),
		'messages' => array(
			'mobile-frontend-talk-explained',
			'mobile-frontend-talk-explained-empty',
			'mobile-frontend-talk-overlay-lead-header',
			'mobile-frontend-talk-add-overlay-subject-placeholder',
			'mobile-frontend-talk-add-overlay-content-placeholder',
			'mobile-frontend-talk-edit-summary',
			'mobile-frontend-talk-add-overlay-submit',
			'mobile-frontend-talk-reply-success',
			'mobile-frontend-talk-reply',
			'mobile-frontend-talk-reply-info',
			// FIXME: Gets loaded twice if editor and talk both loaded.
			'mobile-frontend-editor-cancel',
		),
	),

	'mobile.alpha' => $wgMFMobileResourceBoilerplate + array(
		'templates' => array(
			'modules/ImageOverlay',
		),
		'dependencies' => array(
			'mobile.stable',
			'mobile.beta',
			'mobile.templates',
		),
		'messages' => array(

			// for mf-table.js
			'mobile-frontend-table',

			// mediaViewer.js
			'mobile-frontend-media-details',
		),
		'styles' => array(
			'less/modules/mediaViewer.less',
		),
		'scripts' => array(
			'javascripts/externals/micro.tap.js',
			'javascripts/externals/epoch.js',
			'javascripts/common/history-alpha.js',
			'javascripts/modules/mf-translator.js',
			'javascripts/modules/lazyload.js',
			'javascripts/modules/mediaViewer.js',
		),
	),

	'mobile.toast.styles' => $wgMFMobileResourceBoilerplate + array(
		'styles' => array(
			'less/common/notifications.less',
		),
		'position' => 'top',
	),

	'mobile.stable.styles' => $wgMFMobileResourceBoilerplate + array(
		'styles' => array(
			'less/common/common-js.less',
			'less/modules/languages.less',
			'less/modules/search.less',
			'less/modules/issues.less',
			'less/modules/watchstar.less',
			'less/modules/uploads.less',
			'less/modules/tutorials.less',
			'less/modules/editor.less',
		),
		'position' => 'top',
	),

	// Important: This module is loaded on both mobile and desktop skin
	'mobile.stable.common' => $wgMFMobileResourceBoilerplate + array(
		'dependencies' => array(
			'mobile.startup',
			'mobile.toast.styles',
			'mediawiki.jqueryMsg',
			'mediawiki.util',
			'mobile.templates',
		),
		'templates' => array(
			'LoadingOverlay',
			'section',
			'wikitext/commons-upload',
			// LanguageOverlay.js
			'overlays/languages',
			'overlay',
			'overlays/cleanup',
			// search-2.js
			'articleList',
			'overlays/search/search',
			// page.js
			'page',
			'languageSection',
			// PhotoUploaderButton.js
			// For new page action menu
			'uploads/LeadPhotoUploaderButton',
			// FIXME: this should be in special.uploads (need to split
			// code in PhotoUploaderButton.js into separate files too)
			'uploads/PhotoUploaderButton',

			'ctaDrawer',
			// mf-references.js
			'ReferencesDrawer',
		),
		'scripts' => array(
			'javascripts/common/View.js',
			'javascripts/common/Drawer.js',
			'javascripts/common/CtaDrawer.js',
			'javascripts/common/Overlay.js',
			'javascripts/common/LoadingOverlay.js',
			'javascripts/widgets/progress-bar.js',
			'javascripts/common/notification.js',
			'javascripts/common/Page.js',
			// Upload specific code
			'javascripts/modules/uploads/PhotoUploaderButton.js',
			// Language specific code
			'javascripts/common/languages/LanguageOverlay.js',
		),
		'messages' => array(
			// mf-navigation.js
			'mobile-frontend-watchlist-cta-button-signup',
			'mobile-frontend-watchlist-cta-button-login',
			'mobile-frontend-drawer-cancel',
			'mobile-frontend-overlay-escape',

			// PhotoUploaderButton.js
			'mobile-frontend-photo-upload-cta',

			// LearnMoreOverlay.js, newbie.js
			'cancel',

			// LanguageOverlay.js
			'mobile-frontend-language-header',
			'mobile-frontend-language-site-choose',
			'mobile-frontend-language-footer',

			// page.js
			'mobile-frontend-talk-overlay-header',
			'mobile-frontend-language-article-heading',
			// editor.js
			'mobile-frontend-editor-disabled',
			'mobile-frontend-editor-unavailable',
			'mobile-frontend-editor-cta',
			'mobile-frontend-editor-edit',
			// modules/editor/EditorOverlay.js and modules/talk.js
			'mobile-frontend-editor-save',
		),
	),

	'mobile.stable' => $wgMFMobileResourceBoilerplate + array(
		'dependencies' => array(
			'mobile.startup',
			'mobile.stable.common',
			'mediawiki.util',
			'mobile.stable.styles',
			'mobile.templates',
			'mediawiki.language',
		),
		'scripts' => array(
			'javascripts/modules/editor/editor.js',
			'javascripts/modules/mf-toggle.js',
			'javascripts/modules/issues/issues.js',
			'javascripts/modules/languages/languages.js',
			'javascripts/modules/mf-last-modified.js',
			'javascripts/modules/uploads/lead-photo-init.js',
			'javascripts/modules/mainmenutweaks.js',
			'javascripts/modules/search/search.js',
			'javascripts/modules/mf-watchstar.js',
			'javascripts/modules/mf-references.js',
		),
		'messages' => array(
			// for mf-toggle.js
			'mobile-frontend-close-section',
			'mobile-frontend-show-button',
			'mobile-frontend-hide-button',

			// issues.js
			'mobile-frontend-meta-data-issues',
			'mobile-frontend-meta-data-issues-header',

			// mf-last-modified.js
			'mobile-frontend-last-modified-seconds',
			'mobile-frontend-last-modified-hours',
			'mobile-frontend-last-modified-minutes',
			'mobile-frontend-last-modified-hours',
			'mobile-frontend-last-modified-days',
			'mobile-frontend-last-modified-months',
			'mobile-frontend-last-modified-years',

			// leadphoto.js
			'mobile-frontend-photo-upload-disabled',
			'mobile-frontend-photo-upload-protected',
			'mobile-frontend-photo-upload-anon',
			'mobile-frontend-photo-upload-unavailable',
			'mobile-frontend-photo-upload',

			// mf-watchstar.js
			'mobile-frontend-watchlist-add',
			'mobile-frontend-watchlist-removed',
			'mobile-frontend-watchlist-cta',

			// for search.js
			'mobile-frontend-search-help',
			'mobile-frontend-search-noresults',
		),
	),

	'mobile.site' => array(
		'dependencies' => array( 'mobile.startup' ),
		'class' => 'MobileSiteModule',
	),

	// Resources to be loaded on desktop version of site
	'mobile.desktop' => array(
		'scripts' => array( 'javascripts/desktop/unset_stopmobileredirect.js' ),
		'dependencies' => array( 'jquery.cookie' ),
		'localBasePath' => $localBasePath,
		'remoteExtPath' => $remoteExtPath,
		'targets' => 'desktop',
	),

	/**
		* Special page modules
		* FIXME: Remove the need for these by making more reusable CSS
		* FIXME: Rename these modules in the interim to clarify that they are modules for use on special pages
		*
		* Note: Use correct names to ensure modules load on pages
		* Name must be the name of the special page lowercased prefixed by 'mobile.'
		* suffixed by '.styles' or '.scripts'
		*/
	'mobile.mobilemenu.styles' => $wgMFMobileSpecialPageResourceStyleBoilerplate + array(
		'styles' => array(
			'less/specials/mobilemenu.less',
		),
	),
	'mobile.mobileoptions.styles' => $wgMFMobileSpecialPageResourceBoilerplate + array(
		'styles' => array(
			'less/specials/mobileoptions.less',
		),
	),
	'mobile.mobileoptions.scripts' => $wgMFMobileSpecialPageResourceBoilerplate + array(
		'position' => 'top',
		'scripts' => array(
			'javascripts/specials/mobileoptions.js',
		),
	),

	'mobile.nearby.styles' => $wgMFMobileResourceBoilerplate + array(
		'styles' => array(
			'less/specials/nearby.less',
		),
	),

	// FIXME: Merge with mobile.nearby when geonotahack moves to  stable
	'mobile.nearby.beta' => $wgMFMobileResourceBoilerplate + array(
		'messages' => array(
			// NearbyOverlay.js
			'mobile-frontend-nearby-to-page',

			// PagePreviewOverlay
			'mobile-frontend-nearby-directions',
			'mobile-frontend-nearby-link',
		),
		'templates' => array(
			'overlays/nearby',
		),
		'dependencies' => array(
			'mobile.stable.common',
			'mobile.nearby',
			'mobile.beta.common',
		),
		'scripts' => array(
			'javascripts/specials/overlays/PagePreviewOverlay.js',
			'javascripts/modules/nearby/NearbyOverlay.js',
		)
	),

	'mobile.nearby' => $wgMFMobileResourceBoilerplate + array(
		'templates' => array(
			'articleList',
			'overlays/pagePreview',
		),
		'dependencies' => array(
			'mobile.stable.common',
			'mobile.nearby.styles',
			'jquery.json',
			'mediawiki.language',
			'mobile.templates',
			'mobile.loggingSchemas',
		),
		'messages' => array(
			// NearbyApi.js
			'mobile-frontend-nearby-distance',
			'mobile-frontend-nearby-distance-meters',
			// Nearby.js
			'mobile-frontend-nearby-requirements',
			'mobile-frontend-nearby-requirements-guidance',
			'mobile-frontend-nearby-error',
			'mobile-frontend-nearby-error-guidance',
			'mobile-frontend-nearby-loading',
			'mobile-frontend-nearby-noresults',
			'mobile-frontend-nearby-noresults-guidance',
			'mobile-frontend-nearby-lookup-ui-error',
			'mobile-frontend-nearby-lookup-ui-error-guidance',
			'mobile-frontend-nearby-permission',
			'mobile-frontend-nearby-permission-guidance',
		),
		'scripts' => array(
			'javascripts/modules/nearby/NearbyApi.js',
			'javascripts/modules/nearby/Nearby.js',
		),
	),

	'mobile.nearby.scripts' => $wgMFMobileResourceBoilerplate + array(
		'dependencies' => array(
			'mobile.nearby',
		),
		'messages' => array(
			// specials/nearby.js
			'mobile-frontend-nearby-refresh',
		),
		'scripts' => array(
			'javascripts/specials/nearby.js',
		),
		// stop flash of unstyled content when loading from cache
		'position' => 'top',
	),
	'mobile.notifications.special.styles' => $wgMFMobileSpecialPageResourceBoilerplate + array(
		'styles' => array(
			'less/specials/notifications.less',
		),
		'position' => 'top',
	),
	'mobile.notifications.special.scripts' => $wgMFMobileSpecialPageResourceScriptBoilerplate + array(
		'scripts' => array(
			'javascripts/specials/notifications.js',
		),
		'messages' => array(
			// defined in Echo
			'echo-load-more-error',
		),
	),
	'mobile.notifications.overlay' => $wgMFMobileResourceBoilerplate + array(
		'dependencies' => array(
			'mobile.stable',
		),
		'scripts' => array(
			'javascripts/modules/NotificationsOverlay.js',
		),
		'styles' => array(
			'less/modules/NotificationsOverlay.less',
		),
		'messages' => array(
			// defined in Echo
			'echo-none',
			'notifications',
			'echo-overlay-link',
		),
	),
	'mobile.search.styles' => $wgMFMobileSpecialPageResourceBoilerplate + array(
		'styles' => array(
			'less/specials/search.less',
		),
	),
	'mobile.watchlist.scripts' => $wgMFMobileSpecialPageResourceBoilerplate + array(
		'dependencies' => array(
			'mobile.loggingSchemas',
			'mobile.stable',
		),
		'scripts' => array(
			'javascripts/specials/watchlist.js',
		),
	),
	'mobile.watchlist.styles' => $wgMFMobileSpecialPageResourceBoilerplate + array(
		'styles' => array(
			'less/specials/watchlist.less',
		),
	),
	'mobile.userlogin.styles' => $wgMFMobileSpecialPageResourceBoilerplate + array(
		'styles' => array(
			'less/specials/userlogin.less',
		),
	),
	// Special:UserProfile
	'mobile.userprofile.styles' => $wgMFMobileSpecialPageResourceBoilerplate + array(
		'styles' => array(
			'less/specials/userprofile.less',
		),
	),
	'mobile.uploads.scripts' => $wgMFMobileResourceBoilerplate + array(
		'dependencies' => array(
			'mobile.stable.styles',
			'mobile.stable.common',
			'mobile.uploads',
			'mobile.templates',
		),
		'templates' => array(
			'specials/uploads/carousel',
			'specials/uploads/photo',
			'specials/uploads/userGallery',
		),
		'messages' => array(
			'mobile-frontend-photo-upload-generic',
			'mobile-frontend-donate-photo-upload-success',
			'mobile-frontend-donate-photo-first-upload-success',
			'mobile-frontend-listed-image-no-description',
			'mobile-frontend-photo-upload-user-count',
			'mobile-frontend-first-upload-wizard-new-page-1-header',
			'mobile-frontend-first-upload-wizard-new-page-1',
			'mobile-frontend-first-upload-wizard-new-page-2-header',
			'mobile-frontend-first-upload-wizard-new-page-2',
			'mobile-frontend-first-upload-wizard-new-page-3-header',
			'mobile-frontend-first-upload-wizard-new-page-3',
			'mobile-frontend-first-upload-wizard-new-page-3-ok',
			'mobile-frontend-donate-image-nouploads',
		),
		'scripts' => array(
			'javascripts/widgets/carousel.js',
			'javascripts/specials/overlays/CarouselOverlay.js',
			'javascripts/specials/uploads.js',
		),
		'position' => 'top',
	),
	'mobile.uploads.styles' => $wgMFMobileSpecialPageResourceBoilerplate + array(
		'styles' => array(
			'less/specials/uploads.less',
		),
	),
	'mobile.mobilediff.styles' => $wgMFMobileSpecialPageResourceBoilerplate + array(
		'styles' => array(
			'less/specials/watchlist.less',
			'less/specials/mobilediff.less',
		),
	),

	// Note that this module is declared as a dependency in the Thanks extension (for the
	// mobile diff thanks button code). Keep the module name there in sync with this one.
	'mobile.mobilediff.scripts' => $wgMFMobileResourceBoilerplate + array(
		'dependencies' => array(
			'mobile.loggingSchemas',
			'mobile.stable.common',
		),
		'scripts' => array(
			'javascripts/specials/mobilediff.js',
		),
	),

	'mobile.mobilediff.scripts.beta.head' => $wgMFMobileResourceBoilerplate + array(
		// should be no dependencies except mobile.head and position to top to avoid flash of unstyled content
		'dependencies' => array(
			'mobile.head',
		),
		'position' => 'top',
		'scripts' => array(
			'javascripts/externals/jsdiff.js',
			'javascripts/specials/mobilediffBeta.js',
		),
	),
) );

unset( $localBasePath );
unset( $remoteExtPath );
