<?php
/**
 * Search index updater
 *
 * See deferred.txt
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
 * @ingroup Search
 */

/**
 * Database independant search index updater
 *
 * @ingroup Search
 */
class SearchUpdate implements DeferrableUpdate {

	private $mId = 0, $mNamespace, $mTitle, $mText;
	private $mTitleWords;

	/**
	 * Constructor
	 *
	 * @param int $id Page id to update
	 * @param Title|string $title Title of page to update
	 * @param Content|string|false $content Content of the page to update.
	 *  If a Content object, text will be gotten from it. String is for back-compat.
	 *  Passing false tells the backend to just update the title, not the content
	 */
	public function __construct( $id, $title, $content = false ) {
		if ( is_string( $title ) ) {
			$nt = Title::newFromText( $title );
		} else {
			$nt = $title;
		}

		if ( $nt ) {
			$this->mId = $id;
			// @todo This isn't ideal, we'd really like to have content-specific
			// handling here. See similar content in SearchEngine::initText().
			if( is_string( $content ) ) {
				// b/c for ApprovedRevs
				$this->mText = $content;
			} else {
				$this->mText = $content ? $content->getTextForSearchIndex() : false;
			}

			$this->mNamespace = $nt->getNamespace();
			$this->mTitle = $nt->getText(); # Discard namespace

			$this->mTitleWords = $this->mTextWords = array();
		} else {
			wfDebug( "SearchUpdate object created with invalid title '$title'\n" );
		}
	}

	public function doUpdate() {
		global $wgDisableSearchUpdate;

		if ( $wgDisableSearchUpdate || !$this->mId ) {
			return;
		}

		wfProfileIn( __METHOD__ );

		$search = SearchEngine::create();
		$normalTitle = $search->normalizeText( Title::indexTitle( $this->mNamespace, $this->mTitle ) );

		if ( WikiPage::newFromId( $this->mId ) === null ) {
			$search->delete( $this->mId, $normalTitle );
			wfProfileOut( __METHOD__ );
			return;
		} elseif ( $this->mText === false ) {
			$search->updateTitle( $this->mId, $normalTitle );
			wfProfileOut( __METHOD__ );
			return;
		}

		$text = self::updateText( $this->mText );

		wfRunHooks( 'SearchUpdate', array( $this->mId, $this->mNamespace, $this->mTitle, &$text ) );

		# Perform the actual update
		$search->update( $this->mId, $normalTitle, $search->normalizeText( $text ) );

		wfProfileOut( __METHOD__ );
	}

	public static function updateText( $text ) {
		global $wgContLang;

		# Language-specific strip/conversion
		$text = $wgContLang->normalizeForSearch( $text );
		$lc = SearchEngine::legalSearchChars() . '&#;';

		wfProfileIn( __METHOD__ . '-regexps' );
		$text = preg_replace( "/<\\/?\\s*[A-Za-z][^>]*?>/",
			' ', $wgContLang->lc( " " . $text . " " ) ); # Strip HTML markup
		$text = preg_replace( "/(^|\\n)==\\s*([^\\n]+)\\s*==(\\s)/sD",
			"\\1\\2 \\2 \\2\\3", $text ); # Emphasize headings

		# Strip external URLs
		$uc = "A-Za-z0-9_\\/:.,~%\\-+&;#?!=()@\\x80-\\xFF";
		$protos = "http|https|ftp|mailto|news|gopher";
		$pat = "/(^|[^\\[])({$protos}):[{$uc}]+([^{$uc}]|$)/";
		$text = preg_replace( $pat, "\\1 \\3", $text );

		$p1 = "/([^\\[])\\[({$protos}):[{$uc}]+]/";
		$p2 = "/([^\\[])\\[({$protos}):[{$uc}]+\\s+([^\\]]+)]/";
		$text = preg_replace( $p1, "\\1 ", $text );
		$text = preg_replace( $p2, "\\1 \\3 ", $text );

		# Internal image links
		$pat2 = "/\\[\\[image:([{$uc}]+)\\.(gif|png|jpg|jpeg)([^{$uc}])/i";
		$text = preg_replace( $pat2, " \\1 \\3", $text );

		$text = preg_replace( "/([^{$lc}])([{$lc}]+)]]([a-z]+)/",
			"\\1\\2 \\2\\3", $text ); # Handle [[game]]s

		# Strip all remaining non-search characters
		$text = preg_replace( "/[^{$lc}]+/", " ", $text );

		# Handle 's, s'
		#
		#   $text = preg_replace( "/([{$lc}]+)'s /", "\\1 \\1's ", $text );
		#   $text = preg_replace( "/([{$lc}]+)s' /", "\\1s ", $text );
		#
		# These tail-anchored regexps are insanely slow. The worst case comes
		# when Japanese or Chinese text (ie, no word spacing) is written on
		# a wiki configured for Western UTF-8 mode. The Unicode characters are
		# expanded to hex codes and the "words" are very long paragraph-length
		# monstrosities. On a large page the above regexps may take over 20
		# seconds *each* on a 1GHz-level processor.
		#
		# Following are reversed versions which are consistently fast
		# (about 3 milliseconds on 1GHz-level processor).
		#
		$text = strrev( preg_replace( "/ s'([{$lc}]+)/", " s'\\1 \\1", strrev( $text ) ) );
		$text = strrev( preg_replace( "/ 's([{$lc}]+)/", " s\\1", strrev( $text ) ) );

		# Strip wiki '' and '''
		$text = preg_replace( "/''[']*/", " ", $text );
		wfProfileOut( __METHOD__ . '-regexps' );
		return $text;
	}
}