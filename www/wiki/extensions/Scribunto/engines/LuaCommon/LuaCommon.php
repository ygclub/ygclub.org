<?php

abstract class Scribunto_LuaEngine extends ScribuntoEngineBase {
	/**
	 * Libraries to load. See also the 'ScribuntoExternalLibraries' hook.
	 * @var array Maps module names to PHP classes
	 */
	protected static $libraryClasses = array(
		'mw.site' => 'Scribunto_LuaSiteLibrary',
		'mw.uri' => 'Scribunto_LuaUriLibrary',
		'mw.ustring' => 'Scribunto_LuaUstringLibrary',
		'mw.language' => 'Scribunto_LuaLanguageLibrary',
		'mw.message' => 'Scribunto_LuaMessageLibrary',
		'mw.title' => 'Scribunto_LuaTitleLibrary',
		'mw.text' => 'Scribunto_LuaTextLibrary',
	);

	/**
	 * Paths for modules that may be loaded from Lua. See also the
	 * 'ScribuntoExternalLibraryPaths' hook.
	 * @var array Paths
	 */
	protected static $libraryPaths = array(
		'.',
		'luabit',
		'ustring',
	);

	protected $loaded = false;
	protected $interpreter;
	protected $mw;
	protected $currentFrames = array();
	protected $expandCache = array();
	protected $loadedLibraries = array();

	const MAX_EXPAND_CACHE_SIZE = 100;

	/**
	 * Create a new interpreter object
	 * @return Scribunto_LuaInterpreter
	 */
	abstract function newInterpreter();

	protected function newModule( $text, $chunkName ) {
		return new Scribunto_LuaModule( $this, $text, $chunkName );
	}

	public function newLuaError( $message, $params = array() ) {
		return new Scribunto_LuaError( $message, $this->getDefaultExceptionParams() + $params );
	}

	public function destroy() {
		// Break reference cycles
		$this->interpreter = null;
		$this->mw = null;
		$this->expandCache = null;
		$this->loadedLibraries = null;
		parent::destroy();
	}

	/**
	 * Initialise the interpreter and the base environment
	 */
	public function load() {
		if( $this->loaded ) {
			return;
		}
		$this->loaded = true;

		try {
			$this->interpreter = $this->newInterpreter();

			$funcs = array(
				'loadPackage',
				'frameExists',
				'newChildFrame',
				'getExpandedArgument',
				'getAllExpandedArguments',
				'expandTemplate',
				'callParserFunction',
				'preprocess',
				'incrementExpensiveFunctionCount',
			);

			$lib = array();
			foreach ( $funcs as $name ) {
				$lib[$name] = array( $this, $name );
			}

			$this->mw = $this->registerInterface( 'mw.lua', $lib,
				array( 'allowEnvFuncs' => $this->options['allowEnvFuncs'] ) );

			$libraries = $this->getLibraries( 'lua', self::$libraryClasses );
			foreach ( $libraries as $name => $class ) {
				$this->loadedLibraries[$name] = new $class( $this );
				$this->loadedLibraries[$name]->register();
			}
		} catch ( Exception $ex ) {
			$this->loaded = false;
			$this->interpreter = null;
			throw $ex;
		}
	}

	/**
	 * Register a Lua Library
	 *
	 * This should be called from the library's PHP module's register() method.
	 *
	 * The value for $interfaceFuncs is used to populate the mw_interface
	 * global that is defined when the library's Lua module is loaded. Values
	 * must be PHP callables, which will be seen in Lua as functions.
	 *
	 * @param $moduleFileName string The path to the Lua portion of the library
	 *         (absolute, or relative to $this->getLuaLibDir())
	 * @param $interfaceFuncs array Populates mw_interface
	 * @param $setupOptions array Passed to the modules setupInterface() method.
	 * @return Lua package
	 */
	public function registerInterface( $moduleFileName, $interfaceFuncs, $setupOptions = array() ) {
		$this->interpreter->registerLibrary( 'mw_interface', $interfaceFuncs );
		$moduleFileName = $this->normalizeModuleFileName( $moduleFileName );
		$package = $this->loadLibraryFromFile( $moduleFileName );
		if ( $package['setupInterface'] ) {
			$this->interpreter->callFunction( $package['setupInterface'], $setupOptions );
		}
		return $package;
	}

	/**
	 * Return the base path for Lua modules.
	 * @return string
	 */
	public function getLuaLibDir() {
		return dirname( __FILE__ ) .'/lualib';
	}

	/**
	 * Normalize a lua module to its full path. If path does not look like an
	 * absolute path (i.e. begins with DIRECTORY_SEPARATOR or "X:"), prepend
	 * getLuaLibDir()
	 *
	 * @param $file String name of the lua module file
	 * @return string
	 */
	protected function normalizeModuleFileName( $fileName ) {
		if ( !preg_match( '<^(?:[a-zA-Z]:)?' . preg_quote( DIRECTORY_SEPARATOR ) . '>', $fileName ) ) {
			$fileName = "{$this->getLuaLibDir()}/{$fileName}";
		}
		return $fileName;
	}

	/**
	 * Get performance characteristics of the Lua engine/interpreter
	 *
	 * phpCallsRequireSerialization: boolean
	 *   whether calls between PHP and Lua functions require (slow)
	 *   serialization of parameters and return values
	 *
	 * @return array
	 */
	public abstract function getPerformanceCharacteristics();

	/**
	 * Get the current interpreter object
	 * @return Scribunto_LuaInterpreter
	 */
	public function getInterpreter() {
		$this->load();
		return $this->interpreter;
	}

	/**
	 * Execute a module chunk in a new isolated environment
	 */
	public function executeModule( $chunk ) {
		return $this->getInterpreter()->callFunction( $this->mw['executeModule'], $chunk );
	}

	/**
	 * Execute a module function chunk
	 */
	public function executeFunctionChunk( $chunk, $frame ) {
		$oldFrames = $this->currentFrames;
		$this->currentFrames = array(
			'current' => $frame,
			'parent' => isset( $frame->parent ) ? $frame->parent : null,
		);
		try {
			$result = $this->getInterpreter()->callFunction(
				$this->mw['executeFunction'],
				$chunk );
		} catch ( Exception $ex ) {
			$this->currentFrames = $oldFrames;
			throw $ex;
		}
		$this->currentFrames = $oldFrames;
		return $result;
	}

	/**
	 * Load a library from the given file and execute it in the base environment.
	 * @param string File name/path to load
	 * @return mixed the export list, or null if there isn't one.
	 */
	protected function loadLibraryFromFile( $fileName ) {
		$code = file_get_contents( $fileName );
		if ( $code === false ) {
			throw new MWException( 'Lua file does not exist: ' . $fileName );
		}
		# Prepending an "@" to the chunk name makes Lua think it is a filename
		$module = $this->getInterpreter()->loadString( $code, '@' . basename( $fileName ) );
		$ret = $this->getInterpreter()->callFunction( $module );
		return isset( $ret[0] ) ? $ret[0] : null;
	}

	public function getGeSHiLanguage() {
		return 'lua';
	}

	public function getCodeEditorLanguage() {
		return 'lua';
	}

	public function runConsole( $params ) {
		$oldFrames = $this->currentFrames;
		$this->currentFrames = array(
			'current' => $this->getParser()->getPreprocessor()->newFrame(),
		);

		try {
			/**
			 * TODO: provide some means for giving correct line numbers for errors
			 * in console input, and for producing an informative error message
			 * if there is an error in prevQuestions.
			 *
			 * Maybe each console line could be evaluated as a different chunk, 
			 * apparently that's what lua.c does.
			 */
			$code = "return function (__init, exe)\n" .
				"local p = exe(__init)\n" .
				"__init, exe = nil, nil\n" .
				"local print = mw.log\n";
			foreach ( $params['prevQuestions'] as $q ) {
				if ( substr( $q, 0, 1 ) === '=' ) {
					$code .= "print(" . substr( $q, 1 ) . ")";
				} else {
					$code .= $q;
				}
				$code .= "\n";
			}
			$code .= "mw.clearLogBuffer()\n";
			if ( substr( $params['question'], 0, 1 ) === '=' ) {
				// Treat a statement starting with "=" as a return statement, like in lua.c
				$code .= "local ret = mw.allToString(" . substr( $params['question'], 1 ) . ")\n" .
					"return ret, mw.getLogBuffer()\n";
			} else {
				$code .= $params['question'] . "\n" .
					"return nil, mw.getLogBuffer()\n";
			}
			$code .= "end\n";

			$contentModule = $this->newModule( 
				$params['content'], $params['title']->getPrefixedDBkey() );
			$contentInit = $contentModule->getInitChunk();

			$consoleModule = $this->newModule(
				$code,
				wfMessage( 'scribunto-console-current-src' )->text()
			);
			$consoleInit = $consoleModule->getInitChunk();
			$ret = $this->getInterpreter()->callFunction( $this->mw['executeModule'], $consoleInit, true );
			$func = $ret[0];
			$ret = $this->getInterpreter()->callFunction( $func, $contentInit, $this->mw['executeModule'] );
		} catch ( Exception $ex ) {
			$this->currentFrames = $oldFrames;
			throw $ex;
		}

		$this->currentFrames = $oldFrames;
		return array(
			'return' => isset( $ret[0] ) ? $ret[0] : null,
			'print' => isset( $ret[1] ) ? $ret[1] : '',
		);
	}
		
	/**
	 * Workalike for luaL_checktype()
	 *
	 * @param $funcName The Lua function name, for use in error messages
	 * @param $args The argument array
	 * @param $index0 The zero-based argument index
	 * @param $type The type name as given by gettype()
	 * @param $msgType The type name used in the error message
	 */
	public function checkType( $funcName, $args, $index0, $type, $msgType ) {
		if ( !isset( $args[$index0] ) || gettype( $args[$index0] ) !== $type ) {
			$index1 = $index0 + 1;
			throw new Scribunto_LuaError( "bad argument #$index1 to '$funcName' ($msgType expected)" );
		}
	}

	/**
	 * Workalike for luaL_checkstring()
	 *
	 * @param $funcName The Lua function name, for use in error messages
	 * @param $args The argument array
	 * @param $index0 The zero-based argument index
	 */
	public function checkString( $funcName, $args, $index0 ) {
		$this->checkType( $funcName, $args, $index0, 'string', 'string' );
	}

	/**
	 * Workalike for luaL_checknumber()
	 *
	 * @param $funcName The Lua function name, for use in error messages
	 * @param $args The argument array
	 * @param $index0 The zero-based argument index
	 */
	public function checkNumber( $funcName, $args, $index0 ) {
		$this->checkType( $funcName, $args, $index0, 'double', 'number' );
	}

	/**
	 * Handler for the loadPackage() callback. Load the specified
	 * module and return its chunk. It's not necessary to cache the resulting
	 * chunk in the object instance, since there is caching in a wrapper on the
	 * Lua side.
	 */
	function loadPackage( $name ) {
		$args = func_get_args();
		$this->checkString( 'loadPackage', $args, 0 );

		# This is what Lua does for its built-in loaders
		$luaName = str_replace( '.', '/', $name ) . '.lua';
		$paths = $this->getLibraryPaths( 'lua', self::$libraryPaths );
		foreach ( $paths as $path ) {
			$fileName = $this->normalizeModuleFileName( "$path/$luaName" );
			if ( !file_exists( $fileName ) ) {
				continue;
			}
			$code = file_get_contents( $fileName );
			$init = $this->interpreter->loadString( $code, "@$luaName" );
			return array( $init );
		}

		$title = Title::newFromText( $name );
		if ( !$title || $title->getNamespace() != NS_MODULE ) {
			return array();
		}

		$module = $this->fetchModuleFromParser( $title );
		if ( $module ) {
			return array( $module->getInitChunk() );
		} else {
			return array();
		}
	}

	/**
	 * Helper function for the implementation of frame methods
	 */
	protected function getFrameById( $frameId ) {
		if ( isset( $this->currentFrames[$frameId] ) ) {
			return $this->currentFrames[$frameId];
		} else {
			throw new Scribunto_LuaError( 'invalid frame ID' );
		}
	}

	/**
	 * Handler for frameExists()
	 */
	function frameExists( $frameId ) {
		return array( isset( $this->currentFrames[$frameId] ) );
	}

	/**
	 * Handler for newChildFrame()
	 */
	function newChildFrame( $frameId, $title, $args ) {
		if ( count( $this->currentFrames ) > 100 ) {
			throw new Scribunto_LuaError( 'newChild: too many frames' );
		}

		$frame = $this->getFrameById( $frameId );
		if ( $title === false ) {
			$title = $frame->getTitle();
		} else {
			$title = Title::newFromText( $title );
			if ( !$title ) {
				throw new Scribunto_LuaError( 'newChild: invalid title' );
			}
		}
		$args = $this->getParser()->getPreprocessor()->newPartNodeArray( $args );
		$newFrame = $frame->newChild( $args, $title );
		$newFrameId = 'frame' . count( $this->currentFrames );
		$this->currentFrames[$newFrameId] = $newFrame;
		return array( $newFrameId );
	}

	/**
	 * Handler for getExpandedArgument()
	 */
	function getExpandedArgument( $frameId, $name ) {
		$args = func_get_args();
		$this->checkString( 'getExpandedArgument', $args, 0 );

		$frame = $this->getFrameById( $frameId );
		$this->getInterpreter()->pauseUsageTimer();
		$result = $frame->getArgument( $name );
		if ( $result === false ) {
			return array();
		} else {
			return array( $result );
		}
	}

	/**
	 * Handler for getAllExpandedArguments()
	 */
	function getAllExpandedArguments( $frameId ) {
		$frame = $this->getFrameById( $frameId );
		$this->getInterpreter()->pauseUsageTimer();
		return array( $frame->getArguments() );
	}

	/**
	 * Handler for expandTemplate()
	 */
	function expandTemplate( $frameId, $titleText, $args ) {
		$frame = $this->getFrameById( $frameId );
		$title = Title::newFromText( $titleText, NS_TEMPLATE );
		if ( !$title ) {
			throw new Scribunto_LuaError( 'expandTemplate: invalid title' );
		}

		if ( $frame->depth >= $this->parser->mOptions->getMaxTemplateDepth() ) {
			throw new Scribunto_LuaError( 'expandTemplate: template depth limit exceeded' );
		}
		if ( MWNamespace::isNonincludable( $title->getNamespace() ) ) {
			throw new Scribunto_LuaError( 'expandTemplate: template inclusion denied' );
		}

		list( $dom, $finalTitle ) = $this->parser->getTemplateDom( $title );
		if ( $dom === false ) {
			throw new Scribunto_LuaError( "expandTemplate: template \"$titleText\" does not exist" );
		}
		if ( !$frame->loopCheck( $finalTitle ) ) {
			throw new Scribunto_LuaError( 'expandTemplate: template loop detected' );
		}

		$newFrame = $this->parser->getPreprocessor()->newCustomFrame( $args );
		$text = $this->doCachedExpansion( $newFrame, $dom, 
			array(
				'template' => $finalTitle->getPrefixedDBkey(),
				'args' => $args
			) );
		return array( $text );
	}

	/**
	 * Handler for callParserFunction()
	 */
	function callParserFunction( $frameId, $function, $args ) {
		$frame = $this->getFrameById( $frameId );

		# Make zero-based, without screwing up named args
		$args = array_merge( array(), $args );

		# Sort, since we can't rely on the order coming in from Lua
		uksort( $args, function ( $a, $b ) {
			if ( is_int( $a ) !== is_int( $b ) ) {
				return is_int( $a ) ? -1 : 1;
			}
			if ( is_int( $a ) ) {
				return $a - $b;
			}
			return strcmp( $a, $b );
		} );

		# Be user-friendly
		$colonPos = strpos( $function, ':' );
		if ( $colonPos !== false ) {
			array_unshift( $args, trim( substr( $function, $colonPos + 1 ) ) );
			$function = substr( $function, 0, $colonPos );
		}

		$result = $this->parser->callParserFunction( $frame, $function, $args );
		if ( !$result['found'] ) {
			throw new Scribunto_LuaError( "callParserFunction: function \"$function\" was not found" );
		}

		# Set defaults for various flags
		$result += array(
			'nowiki' => false,
			'isChildObj' => false,
			'isLocalObj' => false,
			'isHTML' => false,
			'title' => false,
		);

		$text = $result['text'];
		if ( $result['isChildObj'] ) {
			$fargs = $this->getParser()->getPreprocessor()->newPartNodeArray( $args );
			$newFrame = $frame->newChild( $fargs, $result['title'] );
			if ( $result['nowiki'] ) {
				$text = $newFrame->expand( $text, PPFrame::RECOVER_ORIG );
			} else {
				$text = $newFrame->expand( $text );
			}
		}
		if ( $result['isLocalObj'] && $result['nowiki'] ) {
			$text = $frame->expand( $text, PPFrame::RECOVER_ORIG );
			$result['isLocalObj'] = false;
		}

		# Replace raw HTML by a placeholder
		if ( $result['isHTML'] ) {
			$text = $this->parser->insertStripItem( $text );
		} elseif ( $result['nowiki'] ) {
			# Escape nowiki-style return values
			$text = wfEscapeWikiText( $text );
		}

		if ( $result['isLocalObj'] ) {
			$text = $frame->expand( $text );
		}

		return array( "$text" );
	}

	/**
	 * Handler for preprocess()
	 */
	function preprocess( $frameId, $text ) {
		$args = func_get_args();
		$this->checkString( 'preprocess', $args, 0 );

		$frame = $this->getFrameById( $frameId );

		if ( !$frame ) {
			throw new Scribunto_LuaError( 'attempt to call mw.preprocess with no frame' );
		}

		// Don't count the time for expanding all the frame arguments against
		// the Lua time limit.
		$this->getInterpreter()->pauseUsageTimer();
		$args = $frame->getArguments();
		$this->getInterpreter()->unpauseUsageTimer();

		$text = $this->doCachedExpansion( $frame, $text,
			array(
				'inputText' => $text,
				'args' => $args,
			) );
		return array( $text );
	}

	/**
	 * Increment the expensive function count, and throw if limit exceeded
	 *
	 * @return null
	 */
	public function incrementExpensiveFunctionCount() {
		if ( !$this->getParser()->incrementExpensiveFunctionCount() ) {
			throw new Scribunto_LuaError( "too many expensive function calls" );
		}
		return null;
	}

	function doCachedExpansion( $frame, $input, $cacheKey ) {
		$hash = md5( serialize( $cacheKey ) );
		if ( !isset( $this->expandCache[$hash] ) ) {
			if ( is_scalar( $input ) ) {
				$dom = $this->parser->getPreprocessor()->preprocessToObj( 
					$input, Parser::PTD_FOR_INCLUSION );
			} else {
				$dom = $input;
			}
			if ( count( $this->expandCache ) > self::MAX_EXPAND_CACHE_SIZE ) {
				reset( $this->expandCache );
				$oldHash = key( $this->expandCache );
				unset( $this->expandCache[$oldHash] );
			}
			$this->expandCache[$hash] = $frame->expand( $dom );
		}
		return $this->expandCache[$hash];
	}
}

class Scribunto_LuaModule extends ScribuntoModuleBase {
	protected $initChunk;

	/**
	 * @param $name string
	 * @return Scribunto_LuaFunction
	 */
	protected function newFunction( $name ) {
		return new Scribunto_LuaFunction( $this, $name, $contents ); // FIXME: $contents is undefined
	}

	public function validate() {
		try {
			$this->getInitChunk();
		} catch ( ScribuntoException $e ) {
			return $e->toStatus();
		}
		return Status::newGood();
	}

	/**
	 * Execute the module function and return the export table.
	 */
	public function execute() {
		$init = $this->getInitChunk();
		$ret = $this->engine->executeModule( $init );
		if( !$ret ) {
			throw $this->engine->newException( 'scribunto-lua-noreturn' );
		}
		if( !is_array( $ret[0] ) ) {
			throw $this->engine->newException( 'scribunto-lua-notarrayreturn' );
		}
		return $ret[0];
	}

	/**
	 * Get the chunk which, when called, will return the export table.
	 */
	public function getInitChunk() {
		if ( !$this->initChunk ) {
			$this->initChunk = $this->engine->getInterpreter()->loadString(
				$this->code, 
				// Prepending an "=" to the chunk name avoids truncation or a "[string" prefix
				'=' . $this->chunkName );
		}
		return $this->initChunk;
	}

	/**
	 * Invoke a function within the module. Return the expanded wikitext result.
	 */
	public function invoke( $name, $frame ) {
		$exports = $this->execute();
		if ( !isset( $exports[$name] ) ) {
			throw $this->engine->newException( 'scribunto-common-nosuchfunction' );
		}

		$result = $this->engine->executeFunctionChunk( $exports[$name], $frame );
		if ( isset( $result[0] ) ) {
			return $result[0];
		} else {
			return null;
		}
	}
}

class Scribunto_LuaError extends ScribuntoException {
	var $luaMessage, $lineMap = array();

	function __construct( $message, $options = array() ) {
		$this->luaMessage = $message;
		$options = $options + array( 'args' => array( $message ) );
		if ( isset( $options['module'] ) && isset( $options['line'] ) ) {
			$msg = 'scribunto-lua-error-location';
		} else {
			$msg = 'scribunto-lua-error';
		}

		parent::__construct( $msg, $options );
	}

	function getLuaMessage() {
		return $this->luaMessage;
	}

	function setLineMap( $map ) {
		$this->lineMap = $map;
	}

	/**
	 * @param array $options Options for message processing. Currently supports:
	 * $options['msgOptions']['content'] to use content language.
	 * @return bool|string
	 */
	function getScriptTraceHtml( $options = array() ) {
		if ( !isset( $this->params['trace'] ) ) {
			return false;
		}
		if ( isset( $options['msgOptions'] ) ){
			$msgOptions = $options['msgOptions'];
		} else {
			$msgOptions = array();
		}

		$s = '<ol class="scribunto-trace">';
		foreach ( $this->params['trace'] as $info ) {
			$short_src = $srcdefined = $info['short_src'];
			$currentline = $info['currentline'];

			$src = htmlspecialchars( $short_src );
			if ( $currentline > 0 ) {
				$src .= ':' . htmlspecialchars( $currentline );

				$title = Title::newFromText( $short_src );
				if ( $title && $title->getNamespace() === NS_MODULE ) {
					$title->setFragment( '#mw-ce-l' . $currentline );
					$src = Html::rawElement( 'a', 
						array( 'href' => $title->getFullURL( 'action=edit' ) ),
						$src );
				}
			}

			if ( strval( $info['namewhat'] ) !== '' ) {
				$function = wfMessage( 'scribunto-lua-in-function', $info['name'] );
				in_array( 'content', $msgOptions ) ?
					$function = $function->inContentLanguage()->text() :
					$function = $function->text();
			} elseif ( $info['what'] == 'main' ) {
				$function = wfMessage( 'scribunto-lua-in-main' );
				in_array( 'content', $msgOptions ) ?
					$function = $function->inContentLanguage()->text() :
					$function = $function->text();
			} elseif ( $info['what'] == 'C' || $info['what'] == 'tail' ) {
				$function = '?';
			}

			$backtraceLine = wfMessage( 'scribunto-lua-backtrace-line', "<strong>$src</strong>", $function );
			in_array( 'content', $msgOptions ) ?
				$backtraceLine = $backtraceLine->inContentLanguage()->text() :
				$backtraceLine = $backtraceLine->text();

			$s .= "<li>\n\t" . $backtraceLine  . "\n</li>\n";
		}
		$s .= '</ol>';
		return $s;
	}
}
